<?php

namespace FluentCampaign\App\Services\Funnel\Benchmarks;

use FluentCrm\App\Services\Funnel\BaseBenchMark;
use FluentCrm\App\Services\Funnel\FunnelProcessor;
use FluentCrm\Includes\Helpers\Arr;

class LinkClickBenchmark extends BaseBenchMark
{
    public function __construct()
    {
        $this->triggerName = 'fluencrm_benchmark_link_clicked';
        $this->actionArgNum = 2;
        $this->priority = 42;

        parent::__construct();

        add_filter('fluentcrm_funnel_sequence_filtered_'.$this->triggerName, function ($benchMark) {
            if(isset($benchMark['id'])) {
                $benchMark['settings']['mask_url'] = add_query_arg([
                    'fluentcrm' => 1,
                    'route' => 'bnu',
                    'aid' => $benchMark['id']
                ], site_url('/'));
            }
            return $benchMark;
        }, 10, 1);

    }

    public function getBlock()
    {
        return [
            'title'       => 'Link Click',
            'description' => 'This will run once a subscriber click on this provided link',
            'icon' => fluentCrmMix('images/funnel_icons/link_clicked.svg'),
            'settings'    => [
                'redirect_to' => site_url('/'),
                'mask_url'    => '',
                'type'        => 'optional'
            ],
            'reload_on_insert' => true
        ];
    }

    public function getBlockFields($funnel)
    {
        return [
            'title'     => 'Link Click',
            'sub_title' => 'This will run once a subscriber click on this provided link',
            'fields'    => [
                'mask_url'           => [
                    'type'        => 'input-text',
                    'placeholder' => 'Please save to get the sharable link',
                    'label'       => 'Copy This Link',
                    'readonly'    => true,
                    'help'        => 'Select for which products this benchmark will run',
                    'inline_help' => 'Paste this link in any email or page. When a contact click this link then it will be recorded and redirect to the url as provided bellow.'
                ],
                'redirect_to' => [
                    'type'        => 'url_selector',
                    'label'       => 'Redirect To',
                    'placeholder' => 'Your Target URL',
                    'help'        => 'Contacts will be redirected to this link.',
                    'inline_help' => 'Please provide the url to where the contact will be redirected'
                ],
                'type'               => $this->benchmarkTypeField()
            ]
        ];
    }

    public function handle($benchMark, $originalArgs)
    {
        $benchmarkId = $originalArgs[0];
        if($benchMark->id != $benchmarkId) {
            return;
        }

        $subscriber = $originalArgs[1];
        if($subscriber) {
            $funnelProcessor = new FunnelProcessor();
            $funnelProcessor->startFunnelFromSequencePoint($benchMark, $subscriber);
        }

        wp_redirect(Arr::get($benchMark->settings, 'redirect_to', site_url('/')));
        exit();
    }
}