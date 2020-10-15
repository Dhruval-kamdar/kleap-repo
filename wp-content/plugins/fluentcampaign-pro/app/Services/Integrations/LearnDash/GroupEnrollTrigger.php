<?php

namespace FluentCampaign\App\Services\Integrations\LearnDash;

use FluentCrm\App\Services\Funnel\BaseTrigger;
use FluentCrm\App\Services\Funnel\FunnelHelper;
use FluentCrm\App\Services\Funnel\FunnelProcessor;
use FluentCrm\Includes\Helpers\Arr;

class GroupEnrollTrigger extends BaseTrigger
{
    public function __construct()
    {
        $this->triggerName = 'ld_added_group_access';
        $this->priority = 20;
        $this->actionArgNum = 2;
        parent::__construct();
    }

    public function getTrigger()
    {
        return [
            'category'    => 'LearnDash',
            'label'       => 'Enrolls in a Group',
            'description' => 'This funnel will start when a user is enrolled in a group'
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
            'title'     => 'Enrolls in a Group',
            'sub_title' => 'This Funnel will start when a student is enrolled in a group',
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
            'group_ids'    => []
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
            'group_ids'    => [
                'type'        => 'multi-select',
                'label'       => 'Target Groups',
                'help'        => 'Select for which groups this automation will run',
                'options'     => Helper::getGroups(),
                'inline_help' => 'Keep it blank to run to any group Enrollment'
            ]
        ];
    }

    public function handle($funnel, $originalArgs)
    {
        $userId = $originalArgs[0];
        $groupId = $originalArgs[1];

        $subscriberData = FunnelHelper::prepareUserData($userId);

        $subscriberData['source'] = 'LearnDash';

        if (empty($subscriberData['email']) || !is_email($subscriberData['email'])) {
            return;
        }

        $willProcess = $this->isProcessable($funnel, $groupId, $subscriberData);

        Helper::startProcessing($this->triggerName, $willProcess, $funnel, $subscriberData, $originalArgs, $groupId);
    }

    private function isProcessable($funnel, $groupId, $subscriberData)
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
        if ($conditions['group_ids']) {
            return in_array($groupId, $conditions['group_ids']);
        }

        return true;
    }
}
