<?php

namespace FluentCampaign\App\Services\Integrations\WooCommerce;

use FluentCrm\App\Services\Funnel\BaseBenchMark;
use FluentCrm\App\Services\Funnel\FunnelHelper;
use FluentCrm\App\Services\Funnel\FunnelProcessor;
use FluentCrm\Includes\Helpers\Arr;

class WooOrderSuccessBenchmark extends BaseBenchMark
{
    public function __construct()
    {
        $this->triggerName = 'woocommerce_order_status_processing';
        $this->actionArgNum = 2;
        $this->priority = 20;

        parent::__construct();
    }

    public function getBlock()
    {
        return [
            'title'       => 'Order Received in WooCommerce',
            'description' => 'This will run once new order will be placed as processing',
            'icon' => fluentCrmMix('images/funnel_icons/new_order_woo.svg'),
            'settings'    => [
                'product_ids'        => [],
                'product_categories' => [],
                'purchase_type'      => 'all',
                'type'               => 'required'
            ]
        ];
    }

    public function getDefaultSettings()
    {
        return [
            'product_ids'        => [],
            'product_categories' => [],
            'purchase_type'      => '',
            'type'               => 'required'
        ];
    }

    public function getBlockFields($funnel)
    {
        return [
            'title'     => 'Order Received in WooCommerce',
            'sub_title' => 'This will run once new order will be placed as processing',
            'fields'    => [
                'product_ids'        => [
                    'type'        => 'multi-select',
                    'label'       => 'Target Products',
                    'help'        => 'Select for which products this benchmark will run',
                    'options'     => Helper::getProducts(),
                    'inline_help' => 'Keep it blank to run to any product purchase'
                ],
                'product_categories' => [
                    'type'        => 'multi-select',
                    'label'       => 'Target Product Categories',
                    'help'        => 'Select for which product category the benchmark will run',
                    'options'     => Helper::getCategories(),
                    'inline_help' => 'Keep it blank to run to any category products'
                ],
                'purchase_type'      => [
                    'type'        => 'radio',
                    'label'       => 'Purchase Type',
                    'help'        => 'Select the purchase type',
                    'options'     => Helper::purchaseTypeOptions(),
                    'inline_help' => 'For what type of purchase you want to run this benchmark'
                ],
                'type'               => $this->benchmarkTypeField()
            ]
        ];
    }

    public function handle($benchMark, $originalArgs)
    {
        $order = $originalArgs[1];
        $conditions = $benchMark->settings;


        if (!$this->isMatched($conditions, $order)) {
            return; // It's not a match
        }

        $subscriberData = Helper::prepareSubscriberData($order);

        $subscriberData = FunnelHelper::maybeExplodeFullName($subscriberData);

        if (!is_email($subscriberData['email'])) {
            return;
        }

        $subscriberData['status'] = 'subscribed';

        $subscriber = FunnelHelper::createOrUpdateContact($subscriberData);

        $funnelProcessor = new FunnelProcessor();
        $funnelProcessor->startFunnelFromSequencePoint($benchMark, $subscriber, [], [
            'benchmark_value'    => intval($order->get_total() * 100), // converted to cents
            'benchmark_currency' => $order->get_currency(),
        ]);
    }

    private function isMatched($conditions, $order)
    {
        $purchaseType = Arr::get($conditions, 'purchase_type');
        return Helper::isPurchaseTypeMatch($order, $purchaseType) && Helper::isPurchaseTypeMatch($order, $purchaseType);
    }
}