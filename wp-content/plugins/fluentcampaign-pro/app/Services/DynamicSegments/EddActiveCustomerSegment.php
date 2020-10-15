<?php

namespace FluentCampaign\App\Services\DynamicSegments;

use FluentCrm\App\Models\Subscriber;
use FluentCrm\Includes\Helpers\Arr;

class EddActiveCustomerSegment extends BaseSegment
{
    private $model = null;

    public $slug = 'edd_customers';

    public function getInfo()
    {
        return [
            'id'          => 0,
            'slug'        => $this->slug,
            'is_system'   => true,
            'title'       => 'Easy Digital Downloads Customers',
            'subtitle' => 'EDD customers who are also in the contact list as subscribed',
            'description' => 'This segment contains all your Subscribed contacts which are also your EDD Customers with atleast one purchase',
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

        $customers = wpFluent()->table('edd_customers')
            ->where('purchase_count', '>', 0)
            ->select('user_id')
            ->get();

        $customerIds = [];
        foreach ($customers as $customer) {
            $customerIds[] = $customer->user_id;
        }

        $this->model = Subscriber::whereIn('user_id', $customerIds)
                    ->where('status', 'subscribed');

        return $this->model;
    }

    public function getSegmentDetails($segment, $id, $config)
    {
        $segment = $this->getInfo();

        if(Arr::get($config, 'model')) {
            $segment['model'] = $this->getModel($segment);
        }

        if(Arr::get($config, 'subscribers')) {
            $segment['subscribers'] = $this->getSubscribers($config);
        }
        return $segment;
    }
}