<?php

namespace FluentCampaign\App\Services\Integrations\WooCommerce;

use FluentCrm\App\Services\Funnel\FunnelHelper;
use FluentCrm\Includes\Helpers\Arr;

class Helper
{
    public static function getProducts()
    {
        $products = \wc_get_products([]);
        $formattedProducts = [];
        foreach ($products as $product) {
            $formattedProducts[] = [
                'id'    => strval($product->get_id()),
                'title' => $product->get_title()
            ];
        }

        return $formattedProducts;
    }

    public static function getCategories()
    {
        $categories = get_terms('product_cat', array(
            'orderby'    => 'name',
            'order'      => 'asc',
            'hide_empty' => true,
        ));

        $formattedOptions = [];
        foreach ($categories as $category) {
            $formattedOptions[] = [
                'id'    => strval($category->term_id),
                'title' => $category->name
            ];
        }

        return $formattedOptions;
    }

    public static function purchaseTypeOptions()
    {
        return [
            [
                'id'    => 'all',
                'title' => 'Any type of purchase'
            ],
            [
                'id'    => 'first_purchase',
                'title' => 'Only for first purchase'
            ],
            [
                'id'    => 'from_second',
                'title' => 'From 2nd Purchase'
            ]
        ];
    }

    /**
     * @param $order \WC_Order
     * @return array
     * @throws \Exception
     */
    public static function prepareSubscriberData($order)
    {
        $customer_id = $order->get_customer_id();

        if( $customer_id !== 0 ) {
            $userId = $order->get_user_id();
            $customer = new \WC_Customer($customer_id);

            if ($userId) {
                $subscriberData = FunnelHelper::prepareUserData($userId);
            } else {
                $subscriberData = [
                    'first_name' => $customer->get_first_name(),
                    'last_name'  => $customer->get_last_name(),
                    'email'      => $customer->get_email()
                ];
            }
            $billingAddress = $customer->get_billing();
        } else {
            // this was a guest checkout
            $subscriberData = [
                'first_name' => $order->get_billing_first_name(),
                'last_name'  => $order->get_billing_last_name(),
                'email'      => $order->get_billing_email()
            ];
            $billingAddress = $order->get_address('billing');
        }

        $subscriberData = array_merge($subscriberData, [
            'address_line_1' => Arr::get($billingAddress, 'address_1'),
            'address_line_2' => Arr::get($billingAddress, 'address_2'),
            'postal_code'    => Arr::get($billingAddress, 'postcode'),
            'city'           => Arr::get($billingAddress, 'city'),
            'state'          => Arr::get($billingAddress, 'state'),
            'country'        => Arr::get($billingAddress, 'country'),
            'source'         => 'woocommerce',
        ]);


        if ($ipAddress = $order->get_customer_ip_address()) {
            $subscriberData['ip'] = $ipAddress;
        }

        return array_filter($subscriberData);
    }

    public static function isProductIdCategoryMatched($order, $conditions)
    {
        $items = $order->get_items();
        $purchaseProductIds = [];
        foreach ($items as $item) {
            $purchaseProductIds[] = $item->get_product_id();
        }

        // check the products ids
        if ($conditions['product_ids']) {
            if (array_intersect($purchaseProductIds, $conditions['product_ids'])) {
                return true;
            }
        }

        if ($targetCategories = $conditions['product_categories']) {
            $categoryMatch = wpFluent()->table('term_relationships')
                ->whereIn('object_id', $targetCategories)
                ->whereIn('term_taxonomy_id', $purchaseProductIds)
                ->count();

            if (!$categoryMatch) {
                return false;
            }
        }
        return true;
    }

    public static function isPurchaseTypeMatch($order, $purchaseType)
    {
        if(!$purchaseType) {
            return true;
        }
        if($purchaseType == 'from_second') {
            $orders = wc_get_orders([
                'limit' => 2,
                'status' => ['wc-processing', 'wc-completed'],
                'customer_id' => $order->customer_id
            ]);
            if(count($orders) < 2) {
                return false;
            }
        } else if($purchaseType == 'first_purchase') {
            $orders = wc_get_orders([
                'limit' => 2,
                'status' => ['wc-processing', 'wc-completed'],
                'customer_id' => $order->customer_id
            ]);
            if(count($orders) > 1) {
                return false;
            }
        }

        return true;
    }

}