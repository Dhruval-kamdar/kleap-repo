<?php

namespace FluentCampaign\App\Services\Funnel\Actions;

use FluentCampaign\App\Models\Sequence;
use FluentCrm\App\Services\Funnel\BaseAction;
use FluentCrm\App\Services\Funnel\FunnelHelper;

class RemoveFromEmailSequenceAction extends BaseAction
{
    public function __construct()
    {
        $this->actionName = 'remove_from_email_sequence';
        $this->priority = 17;
        parent::__construct();
    }

    public function getBlock()
    {
        return [
            'title'       => 'Cancel Sequence Emails',
            'description' => 'Cancel Sequence Emails for the contact',
            'icon' => fluentCrmMix('images/funnel_icons/cancel_sequence.svg'),
            'settings'    => [
                'sequence_ids' => []
            ]
        ];
    }

    public function getBlockFields()
    {
        return [
            'title'     => 'Remove Email Sequences',
            'sub_title' => 'Select which sequences will be removed from this contact',
            'fields'    => [
                'sequence_ids' => [
                    'type'        => 'option_selectors',
                    'option_key' => 'email_sequences',
                    'is_multiple' => true,
                    'placeholder' => 'Select Sequences'
                ]
            ]
        ];
    }

    public function handle($subscriber, $sequence, $funnelSubscriberId, $funnelMetric)
    {
        if (empty($sequence->settings['sequence_ids'])) {
            FunnelHelper::changeFunnelSubSequenceStatus($funnelSubscriberId, $sequence->id, 'skipped');
            return;
        }

        $sequenceIds = $sequence->settings['sequence_ids'];
        foreach ($sequenceIds as $sequenceId) {
            $sequenceModel = Sequence::find($sequenceId);
            if($sequenceModel) {
                $sequenceModel->unsubscribe([$subscriber->id], 'Cancelled by Automation Funnel ID: '.$sequence->funnel_id);
            }
        }
        FunnelHelper::changeFunnelSubSequenceStatus($funnelSubscriberId, $sequence->id);
    }
}
