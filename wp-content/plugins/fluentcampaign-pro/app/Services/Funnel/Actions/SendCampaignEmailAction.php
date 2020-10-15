<?php

namespace FluentCampaign\App\Services\Funnel\Actions;

use FluentCrm\App\Models\Campaign;
use FluentCrm\App\Services\Funnel\BaseAction;
use FluentCrm\App\Services\Funnel\FunnelHelper;

class SendCampaignEmailAction extends BaseAction
{
    public function __construct()
    {
        $this->actionName = 'send_campaign_email';
        $this->priority = 17;
        parent::__construct();
    }

    public function getBlock()
    {
        return [
            'title'       => 'Send Campaign Email',
            'description' => 'Send an Email from your existing campaign',
            'icon' => fluentCrmMix('images/funnel_icons/send_campaign.svg'),
            'settings'    => [
                'campaign_id' => ''
            ]
        ];
    }

    public function getBlockFields()
    {
        return [
            'title'     => 'Schedule Campaign Email',
            'sub_title' => 'Select which campaign email will be scheduled to this contact',
            'fields'    => [
                'campaign_id' => [
                    'type'        => 'option_selectors',
                    'option_key' => 'campaigns',
                    'is_multiple' => false,
                    'label' => 'Select Campaign',
                    'placeholder' => 'Select Campaign Email'
                ]
            ]
        ];
    }

    public function handle($subscriber, $sequence, $funnelSubscriberId, $funnelMetric)
    {
        if (empty($sequence->settings['campaign_id'])) {
            FunnelHelper::changeFunnelSubSequenceStatus($funnelSubscriberId, $sequence->id, 'skipped');
            return;
        }

        $campaignId = intval($sequence->settings['campaign_id']);
        $campaign = Campaign::find($campaignId);
        if(!$campaign) {
            return;
        }
        $campaign->subscribe([$subscriber->id], [
            'status' => 'scheduled',
            'scheduled_at' => current_time('mysql'),
            'note' => 'Email has been triggered by Automation Funnel ID: '.$sequence->funnel_id
        ]);
        FunnelHelper::changeFunnelSubSequenceStatus($funnelSubscriberId, $sequence->id);
    }
}
