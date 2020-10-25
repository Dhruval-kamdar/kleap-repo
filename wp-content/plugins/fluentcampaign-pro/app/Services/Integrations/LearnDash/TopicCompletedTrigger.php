<?php

namespace FluentCampaign\App\Services\Integrations\LearnDash;

use FluentCrm\App\Services\Funnel\BaseTrigger;
use FluentCrm\App\Services\Funnel\FunnelHelper;
use FluentCrm\App\Services\Funnel\FunnelProcessor;
use FluentCrm\Includes\Helpers\Arr;

class TopicCompletedTrigger extends BaseTrigger
{
    public function __construct()
    {
        $this->triggerName = 'learndash_topic_completed';
        $this->priority = 20;
        $this->actionArgNum = 2;
        parent::__construct();
    }

    public function getTrigger()
    {
        return [
            'category'    => 'LearnDash',
            'label'       => 'Completes a Topic',
            'description' => 'This funnel will start when a user is completes a lesson topic'
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
            'title'     => 'Completes a Topic',
            'sub_title' => 'This funnel will start when a user is completes a lesson topic',
            'fields'    => [
                'subscription_status'      => [
                    'type'        => 'option_selectors',
                    'option_key'  => 'editable_statuses',
                    'is_multiple' => false,
                    'label'       => 'Subscription Status',
                    'placeholder' => 'Select Status'
                ],
                'subscription_status_info' => [
                    'type'       => 'html',
                    'info'       => '<b>An Automated double-optin email will be sent for new subscribers</b>',
                    'dependency' => [
                        'depends_on' => 'subscription_status',
                        'operator'   => '=',
                        'value'      => 'pending'
                    ]
                ]
            ]
        ];
    }

    public function getFunnelConditionDefaults($funnel)
    {
        return [
            'update_type' => 'update', // skip_all_actions, skip_update_if_exist
            'course_id'   => '',
            'lesson_id'   => '',
            'topic_ids'   => []
        ];
    }

    public function getConditionFields($funnel)
    {
        return [
            'update_type' => [
                'type'    => 'radio',
                'label'   => 'If Contact Already Exist?',
                'help'    => 'Please specify what will happen if the subscriber already exist in the database',
                'options' => FunnelHelper::getUpdateOptions()
            ],
            'course_id'   => [
                'type'        => 'reload_field_selection',
                'label'       => 'Target Course',
                'help'        => 'Select Course to find out Lesson',
                'options'     => Helper::getCourses(),
                'inline_help' => 'You must select a course'
            ],
            'lesson_id'   => [
                'type'        => 'reload_field_selection',
                'label'       => 'Target Lesson',
                'help'        => 'Select Lesson to find out the available topics',
                'options'     => Helper::getLessonsByCourse($funnel->conditions['course_id']),
                'inline_help' => 'You must select a topic'
            ],
            'topic_ids'   => [
                'type'        => 'multi-select',
                'label'       => 'Target Topics',
                'help'        => 'Select for which Topics this automation will run',
                'options'     => Helper::getTopicsByCourseLesson($funnel->conditions['course_id'], $funnel->conditions['lesson_id']),
                'is_multiple' => true,
                'inline_help' => 'Keep it blank to run to any Topic for that lesson'
            ]
        ];
    }

    public function handle($funnel, $originalArgs)
    {
        $groupId = $originalArgs[1];

        $data = $originalArgs[0];

        $subscriberData = FunnelHelper::prepareUserData($data['user']);

        $subscriberData['source'] = 'LearnDash';

        if (empty($subscriberData['email']) || !is_email($subscriberData['email'])) {
            return;
        }

        $lessonId = $data['lesson']->ID;
        $courseId = $data['course']->ID;
        $topicId = $data['topic']->ID;

        $willProcess = $this->isProcessable($funnel, $courseId, $lessonId, $topicId, $subscriberData);

        Helper::startProcessing($this->triggerName, $willProcess, $funnel, $subscriberData, $originalArgs, $topicId);
    }

    private function isProcessable($funnel, $courseId, $lessonId, $topicId, $subscriberData)
    {
        $conditions = $funnel->conditions;

        if (Arr::get($conditions, 'course_id') != $courseId) {
            return false;
        }

        // check the products ids
        if ($conditions['lesson_id'] != $lessonId) {
            return false;
        }

        // check the products ids
        if ($conditions['topic_ids']) {
            if (!in_array($topicId, $conditions['topic_ids'])) {
                return false;
            }
        }

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

        return true;
    }
}
