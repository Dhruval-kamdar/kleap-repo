<?php

namespace FluentCampaign\App\Services\Integrations\LifterLms;

use FluentCrm\App\Services\Funnel\BaseTrigger;
use FluentCrm\App\Services\Funnel\FunnelHelper;
use FluentCrm\App\Services\Funnel\FunnelProcessor;
use FluentCrm\Includes\Helpers\Arr;

class CourseEnrollTrigger extends BaseTrigger
{
    public function __construct()
    {
        if (!defined('LLMS_PLUGIN_FILE')) {
            return;
        }

        $this->triggerName = 'llms_user_enrolled_in_course';
        $this->priority = 11;
        $this->actionArgNum = 2;
        parent::__construct();
    }

    public function getTrigger()
    {
        return [
            'category'    => 'LifterLMS',
            'label'       => 'Enrollment in a course',
            'description' => 'This funnel will start when a student is enrolled in a course'
        ];
    }

    public function getFunnelSettingsDefaults()
    {
        return [
            'subscription_status' => 'subscribed'
        ];
    }

    public function getSettingsFields($funnel)
    {
        return [
            'title'     => 'Enrollment in a course in LifterLMS',
            'sub_title' => 'This Funnel will start when a student is enrolled in a course',
            'fields'    => [
                'subscription_status' => [
                    'type'        => 'option_selectors',
                    'option_key'  => 'editable_statuses',
                    'is_multiple' => false,
                    'label'       => 'Subscription Status',
                    'placeholder' => 'Select Status'
                ],
                'subscription_status_info' => [
                    'type' => 'html',
                    'info' => '<b>An Automated double-optin email will be sent for new subscribers</b>',
                    'dependency'  => [
                        'depends_on'    => 'subscription_status',
                        'operator' => '=',
                        'value'    => 'pending'
                    ]
                ]
            ]
        ];
    }

    public function getFunnelConditionDefaults($funnel)
    {
        return [
            'update_type'   => 'update', // skip_all_actions, skip_update_if_exist
            'course_ids'    => []
        ];
    }

    public function getConditionFields($funnel)
    {
        return [
            'update_type'   => [
                'type'    => 'radio',
                'label'   => 'If Contact Already Exist?',
                'help'    => 'Please specify what will happen if the subscriber already exist in the database',
                'options' => FunnelHelper::getUpdateOptions()
            ],
            'course_ids'    => [
                'type'        => 'multi-select',
                'label'       => 'Target Courses',
                'help'        => 'Select for which Courses this automation will run',
                'options'     => Helper::getCourses(),
                'inline_help' => 'Keep it blank to run to any Course Enrollment'
            ]
        ];
    }

    public function handle($funnel, $originalArgs)
    {
        $userId = $originalArgs[0];
        $courseId = $originalArgs[1];

        $subscriberData = FunnelHelper::prepareUserData($userId);

        $subscriberData['source'] = 'LifterLMS';

        $subscriberData = array_merge($subscriberData, Helper::getStudentAddress($userId));

        if (empty($subscriberData['email'])) {
            return;
        }

        $willProcess = $this->isProcessable($funnel, $courseId, $subscriberData);

        $willProcess = apply_filters('fluentcrm_funnel_will_process_' . $this->triggerName, $willProcess, $funnel, $subscriberData, $originalArgs);
        if (!$willProcess) {
            return;
        }

        $subscriberData = wp_parse_args($subscriberData, $funnel->settings);

        $subscriberData['status'] = $subscriberData['subscription_status'];
        unset($subscriberData['subscription_status']);

        (new FunnelProcessor())->startFunnelSequence($funnel, $subscriberData, [
            'source_trigger_name' => $this->triggerName,
            'source_ref_id' => $courseId
        ]);
    }

    private function isProcessable($funnel, $courseId, $subscriberData)
    {
        $conditions = $funnel->conditions;
        // check update_type
        $updateType = Arr::get($conditions, 'update_type');

        $subscriber = FunnelHelper::getSubscriber($subscriberData['email']);
        if ($subscriber && $updateType == 'skip_all_if_exist') {
            return false;
        }

        // check run_only_one
        if ($subscriber && FunnelHelper::ifAlreadyInFunnel($funnel->id, $subscriber->id)) {
            return false;
        }

        // check the products ids
        if ($conditions['course_ids']) {
            return in_array($courseId, $conditions['course_ids']);
        }

        return true;
    }
}
