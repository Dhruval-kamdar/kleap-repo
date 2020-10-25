<?php

namespace FluentCampaign\App\Services\Integrations\Edd;

use FluentCrm\App\Services\Funnel\BaseTrigger;
use FluentCrm\App\Services\Funnel\FunnelHelper;
use FluentCrm\App\Services\Funnel\FunnelProcessor;
use FluentCrm\Includes\Helpers\Arr;

class EddPaymentSuccessTrigger extends BaseTrigger
{
    public function __construct()
    {
        $this->triggerName = 'edd_update_payment_status';
        $this->priority = 10;
        $this->actionArgNum = 3;
        parent::__construct();
    }

    public function getTrigger()
    {
        return [
            'category'    => 'Easy Digital Downloads',
            'label'       => 'Edd - New Order Success',
            'description' => 'This Funnel will start once new order will be added as successful payment'
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
            'title'     => 'New Edd Order (paid) has been places',
            'sub_title' => 'This Funnel will start once new order will be added as successful payment',
            'fields'    => [
                'subscription_status'      => [
                    'type'        => 'option_selectors',
                    'option_key'  => 'editable_statuses',
                    'is_multiple' => false,
                    'label'       => 'Subscription Status',
                    'placeholder' => 'Select Status'
                ],
                'subscription_status_info' => [
                    'type'       => 'html',
                    'info'       => '<b>An Automated double-optin email will be sent for new subscribers</b>',
                    'dependency' => [
                        'depends_on' => 'subscription_status',
                        'operator'   => '=',
                        'value'      => 'pending'
                    ]
                ]
            ]
        ];
    }

    public function getFunnelConditionDefaults($funnel)
    {
        return [
            'update_type'        => 'update', // skip_all_actions, skip_update_if_exist
            'product_ids'        => [],
            'product_categories' => [],
            'purchase_type'      => 'all'
        ];
    }

    public function getConditionFields($funnel)
    {
        return [
            'update_type'        => [
                'type'    => 'radio',
                'label'   => 'If Contact Exist?',
                'help'    => 'Please specify what will happen if the subscriber already exist in the database',
                'options' => FunnelHelper::getUpdateOptions()
            ],
            'product_ids'        => [
                'type'        => 'multi-select',
                'label'       => 'Target Products',
                'help'        => 'Select for which products this automation will run',
                'options'     => Helper::getProducts(),
                'inline_help' => 'Keep it blank to run to any product purchase'
            ],
            'product_categories' => [
                'type'        => 'multi-select',
                'label'       => 'Target Product Categories',
                'help'        => 'Select for which product category the automation will run',
                'options'     => Helper::getCategories(),
                'inline_help' => 'Keep it blank to run to any category products'
            ],
            'purchase_type'      => [
                'type'        => 'radio',
                'label'       => 'Purchase Type',
                'help'        => 'Select the purchase type',
                'options'     => Helper::purchaseTypeOptions(),
                'inline_help' => 'For what type of purchase you want to run this funnel'
            ]
        ];
    }

    public function handle($funnel, $originalArgs)
    {
        $paymentId = $originalArgs[0];
        $newStatus = $originalArgs[1];
        $oldStatus = $originalArgs[2];
        if ($newStatus != 'publish' || $newStatus == $oldStatus) {
            return;
        }

        $payment = edd_get_payment($paymentId);

        $subscriberData = Helper::prepareSubscriberData($payment);
        $subscriberData['source'] = 'edd';

        if (empty($subscriberData['email'])) {
            return;
        }

        $willProcess = $this->isProcessable($funnel, $payment, $subscriberData);

        $willProcess = apply_filters('fluentcrm_funnel_will_process_' . $this->triggerName, $willProcess, $funnel, $subscriberData, $originalArgs);

        if (!$willProcess) {
            return;
        }

        $subscriberData = wp_parse_args($subscriberData, $funnel->settings);

        $subscriberData['status'] = $subscriberData['subscription_status'];
        unset($subscriberData['subscription_status']);

        (new FunnelProcessor())->startFunnelSequence($funnel, $subscriberData, [
            'source_trigger_name' => $this->triggerName,
            'source_ref_id'       => $paymentId,
        ]);

    }

    private function isProcessable($funnel, $payment, $subscriberData)
    {
        $conditions = (array) $funnel->conditions;

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

        $purchaseType = Arr::get($conditions, 'purchase_type');
        return Helper::isPurchaseTypeMatch($payment, $purchaseType) && Helper::isProductIdCategoryMatched($payment, $conditions);
    }
}
