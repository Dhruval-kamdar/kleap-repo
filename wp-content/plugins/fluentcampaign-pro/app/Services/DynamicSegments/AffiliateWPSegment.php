<?php

namespace FluentCampaign\App\Services\DynamicSegments;

use FluentCrm\App\Models\Subscriber;
use FluentCrm\Includes\Helpers\Arr;

class AffiliateWPSegment extends BaseSegment
{
    private $model = null;

    public $slug = 'affiliate_wp';

    public function getInfo()
    {
        return [
            'id'          => 0,
            'slug'        => $this->slug,
            'is_system'   => true,
            'title'       => 'Active Affiliates (AffiliateWP)',
            'subtitle' => 'Active Affiliates who are also in the contact list as subscribed',
            'description' => 'This segment contains all your Subscribed contacts which are also your active Affiliates',
            'settings'    => [],
            'contact_count' => $this->getCount()
        ];
    }

    public function getCount()
    {
        return $this->getModel()->count();
    }

    public function getModel($segment = [])
    {
        if($this->model) {
            return $this->model;
        }

        $affiliates = wpFluent()->table('affiliate_wp_affiliates')
            ->where('status', 'active')
            ->select('user_id')
            ->get();

        $affiliateIds = [];
        foreach ($affiliates as $affiliate) {
            $affiliateIds[] = $affiliate->user_id;
        }

        $this->model = Subscriber::whereIn('user_id', $affiliateIds)
                    ->where('status', 'subscribed');
        return $this->model;
    }

    public function getSegmentDetails($segment, $id, $config)
    {
        $segment = $this->getInfo();
        if(Arr::get($config, 'subscribers')) {
            $segment['subscribers'] = $this->getSubscribers($config);
        }
        if(Arr::get($config, 'model')) {
            $segment['model'] = $this->getModel($segment);
        }
        return $segment;
    }
}