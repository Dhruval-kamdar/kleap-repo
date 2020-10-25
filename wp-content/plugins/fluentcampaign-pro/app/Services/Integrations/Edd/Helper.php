<?php

namespace FluentCampaign\App\Services\Integrations\Edd;

use FluentCrm\App\Services\Funnel\FunnelHelper;

class Helper
{
    public static function getProducts()
    {
        $args = array(
            'post_type' => 'download',
            'numberposts' => -1
        );

        $downloads = get_posts($args);

        $formattedProducts = [];
        foreach ($downloads as $download) {
            $formattedProducts[] = [
                'id'    => strval($download->ID),
                'title' => $download->post_title
            ];
        }

        return $formattedProducts;
    }

    public static function getCategories()
    {
        $categories = get_terms('download_category', array(
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
     * @param $payment \EDD_Payment
     * @param $conditions
     * @return bool
     */
    public static function isProductIdCategoryMatched($payment, $conditions)
    {
        $purchaseProductIds = [];
        foreach ($payment->cart_details as $item) {
            $purchaseProductIds[] = $item['id'];
        }

        if ($conditions['product_ids']) {
            if (!array_intersect($purchaseProductIds, $conditions['product_ids'])) {
                return false;
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

    /**
     * @param $payment \EDD_Payment
     * @param $purchaseType
     * @return bool
     */
    public static function isPurchaseTypeMatch($payment, $purchaseType)
    {
        if (!$purchaseType) {
            return true;
        }

        $customer = new \EDD_Customer($payment->customer_id);

        if ($purchaseType == 'from_second') {
            if ($customer->purchase_count < 2) {
                return false;
            }
        } else if ($purchaseType == 'first_purchase') {
            if ($customer->purchase_count > 1) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $payment \EDD_Payment
     * @return array
     */
    public static function prepareSubscriberData($payment)
    {
        if ($payment->user_id) {
            $subscriberData = FunnelHelper::prepareUserData($payment->user_id);
        } else {
            $subscriberData = [
                'first_name' => $payment->first_name,
                'last_name'  => $payment->last_name,
                'email'      => $payment->email,
                'ip'         => $payment->ip
            ];
        }

        return FunnelHelper::maybeExplodeFullName($subscriberData);
    }

}
