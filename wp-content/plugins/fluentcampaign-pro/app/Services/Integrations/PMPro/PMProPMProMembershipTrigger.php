<?php

namespace FluentCampaign\App\Services\Integrations\PMPro;

use FluentCrm\App\Services\Funnel\BaseTrigger;
use FluentCrm\App\Services\Funnel\FunnelHelper;
use FluentCrm\App\Services\Funnel\FunnelProcessor;
use FluentCrm\Includes\Helpers\Arr;

class PMProPMProMembershipTrigger extends BaseTrigger
{
    public function __construct()
    {
        $this->triggerName = 'pmpro_after_change_membership_level';
        $this->priority = 11;
        $this->actionArgNum = 3;
        parent::__construct();
    }

    public function getTrigger()
    {
        return [
            'category'    => 'Paid Membership Pro',
            'label'       => 'Membership Level assignment of a User',
            'description' => 'This funnel will start when a user is assigned to specified membership levels'
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
            'title'     => 'Enrollment in a Membership Level in PMPro',
            'sub_title' => 'This funnel will start when an user is enrolled in Membership Levels',
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
            'membership_ids'    => []
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
            'membership_ids'    => [
                'type'        => 'multi-select',
                'label'       => 'Target Membership Levels',
                'help'        => 'Select for which Membership Levels this automation will run',
                'options'     => $this->getMembershipLevels(),
                'inline_help' => 'Keep it blank to run to any Level Enrollment'
            ]
        ];
    }

    public function handle($funnel, $originalArgs)
    {
        $levelId = intval($originalArgs[0]);
        $userId = $originalArgs[1];

        if(empty($levelId)) {
            return;
        }

        $subscriberData = FunnelHelper::prepareUserData($userId);

        $subscriberData['source'] = 'PMPro';

        if (empty($subscriberData['email'])) {
            return;
        }

        $willProcess = $this->isProcessable($funnel, $levelId, $subscriberData);


        $willProcess = apply_filters('fluentcrm_funnel_will_process_' . $this->triggerName, $willProcess, $funnel, $subscriberData, $originalArgs);
        if (!$willProcess) {
            return;
        }

        $subscriberData = wp_parse_args($subscriberData, $funnel->settings);

        $subscriberData['status'] = $subscriberData['subscription_status'];
        unset($subscriberData['subscription_status']);

        (new FunnelProcessor())->startFunnelSequence($funnel, $subscriberData, [
            'source_trigger_name' => $this->triggerName,
            'source_ref_id' => $levelId
        ]);

    }

    private function isProcessable($funnel, $membershipId, $subscriberData)
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
        if ($conditions['membership_ids']) {
            return in_array($membershipId, $conditions['membership_ids']);
        }

        return true;
    }

    private function getMembershipLevels()
    {
        $levels = \pmpro_getAllLevels(false, false);
        $formattedLevels = [];
        foreach ($levels as $level) {
            $formattedLevels[] = [
                'id' => strval($level->id),
                'title' => $level->name
            ];
        }

        return $formattedLevels;
    }
}
