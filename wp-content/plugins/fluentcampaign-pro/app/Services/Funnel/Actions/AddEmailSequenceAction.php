<?php

namespace FluentCampaign\App\Services\Funnel\Actions;

use FluentCampaign\App\Models\Sequence;
use FluentCrm\App\Services\Funnel\BaseAction;
use FluentCrm\App\Services\Funnel\FunnelHelper;

class AddEmailSequenceAction extends BaseAction
{
    public function __construct()
    {
        $this->actionName = 'add_to_email_sequence';
        $this->priority = 15;
        parent::__construct();
    }

    public function getBlock()
    {
        return [
            'title'       => 'Set Sequence Emails',
            'description' => 'Send Automated Emails based on your Sequence settings',
            'icon' => fluentCrmMix('images/funnel_icons/set_sequence.svg'),
            'settings'    => [
                'sequence_id' => ''
            ]
        ];
    }

    public function getBlockFields()
    {
        return [
            'title'     => 'Set Sequence Emails',
            'sub_title' => 'Select which sequence will be assigned to this contact',
            'fields'    => [
                'sequence_id' => [
                    'type'        => 'option_selectors',
                    'option_key'  => 'email_sequences',
                    'is_multiple' => false,
                    'label'       => 'Select Email Sequence',
                    'placeholder' => 'Select Sequence Email'
                ]
            ]
        ];
    }

    public function handle($subscriber, $sequence, $funnelSubscriberId, $funnelMetric)
    {
        if (empty($sequence->settings['sequence_id'])) {
            FunnelHelper::changeFunnelSubSequenceStatus($funnelSubscriberId, $sequence->id, 'skipped');
            return;
        }

        $sequenceId = intval($sequence->settings['sequence_id']);

        $sequenceModel = Sequence::find($sequenceId);
        if($sequenceModel) {
            $sequenceModel->subscribe([$subscriber]);
        }

        FunnelHelper::changeFunnelSubSequenceStatus($funnelSubscriberId, $sequence->id);
    }
}
