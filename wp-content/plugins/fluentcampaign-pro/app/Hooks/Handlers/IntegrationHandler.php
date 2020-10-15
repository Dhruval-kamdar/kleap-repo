<?php

namespace FluentCampaign\App\Hooks\Handlers;

use FluentCampaign\App\Services\Funnel\Actions\AddEmailSequenceAction;
use FluentCampaign\App\Services\Funnel\Actions\RemoveFromEmailSequenceAction;
use FluentCampaign\App\Services\Funnel\Actions\SendCampaignEmailAction;
use FluentCampaign\App\Services\Funnel\Benchmarks\LinkClickBenchmark;
use FluentCampaign\App\Services\Integrations\Integrations;

class IntegrationHandler
{
    public function init()
    {
        $this->initAddons();
        $this->initFunnelActions();
        $this->initBenchmarks();
    }

    private function initAddons()
    {
        (new Integrations())->init();;
    }

    private function initFunnelActions()
    {
        new AddEmailSequenceAction();
        new RemoveFromEmailSequenceAction();
        new SendCampaignEmailAction();
    }

    private function initBenchmarks()
    {
        new LinkClickBenchmark();
    }
}