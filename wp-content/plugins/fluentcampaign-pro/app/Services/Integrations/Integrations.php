<?php

namespace FluentCampaign\App\Services\Integrations;

class Integrations
{
    public function init()
    {
        // WooCommerce
        if (defined('WC_PLUGIN_FILE')) {
            new \FluentCampaign\App\Services\Integrations\WooCommerce\WooOrderSuccessTrigger();
            new \FluentCampaign\App\Services\Integrations\WooCommerce\WooOrderSuccessBenchmark();


            add_filter('fluentcrm_dashboard_stats', function ($stats) {
                if ( current_user_can( 'view_woocommerce_reports' ) || current_user_can( 'manage_woocommerce' ) || current_user_can( 'publish_shop_orders' ) ) {

                    if(!class_exists('\WC_Report_Sales_By_Date')) {
                        global $woocommerce;
                        include_once($woocommerce->plugin_path() . '/includes/admin/reports/class-wc-admin-report.php');
                        include_once($woocommerce->plugin_path() . '/includes/admin/reports/class-wc-report-sales-by-date.php');
                    }


                    $todaySalesQuery                 = new \WC_Report_Sales_By_Date();
                    $todaySalesQuery->start_date     = strtotime( date( 'Y-m-d', current_time( 'timestamp' ) ) );
                    $todaySalesQuery->end_date       = strtotime( date( 'Y-m-d', current_time( 'timestamp' ) ) );
                    $todaySalesQuery->chart_groupby  = 'month';
                    $todaySalesQuery->group_by_query = 'YEAR(posts.post_date), MONTH(posts.post_date), DAY(posts.post_date)';
                    $todayData = $todaySalesQuery->get_report_data();

                    $monthSalesQuery                 = new \WC_Report_Sales_By_Date();
                    $monthSalesQuery->start_date     = strtotime( date( 'Y-m-01', current_time( 'timestamp' ) ) );
                    $monthSalesQuery->end_date       = strtotime( date( 'Y-m-d', current_time( 'timestamp' ) ) );
                    $monthSalesQuery->chart_groupby  = 'month';
                    $monthSalesQuery->group_by_query = 'YEAR(posts.post_date), MONTH(posts.post_date), DAY(posts.post_date)';
                    $monthData = $monthSalesQuery->get_report_data();

                    $stats['woo_sales_today'] = [
                        'title' => 'Sales (Today)',
                        'count' => wc_price($todayData->net_sales)
                    ];
                    $stats['woo_sales_month'] = [
                        'title' => 'Sales (This Month)',
                        'count' => wc_price($monthData->net_sales)
                    ];
                }
                return $stats;
            });
        }

        // Easy Digital Downloads
        if (class_exists('\Easy_Digital_Downloads')) {
            new \FluentCampaign\App\Services\Integrations\Edd\EddPaymentSuccessTrigger();
            new \FluentCampaign\App\Services\Integrations\Edd\EddOrderSuccessBenchmark();
            // Push stats on dashboard
            add_filter('fluentcrm_dashboard_stats', function ($stats) {
                if ( current_user_can( apply_filters( 'edd_dashboard_stats_cap', 'view_shop_reports' ) ) ) {

                    $eddStat = new \EDD_Payment_Stats;

                    $stats['edd_sales_today'] = [
                        'title' => 'Earnings (Today)',
                        'count' => edd_currency_filter( edd_format_amount( $eddStat->get_earnings( 0, 'today' ) ) )
                    ];

                    $stats['edd_sales_month'] = [
                        'title' => 'Earnings (Current Month)',
                        'count' => edd_currency_filter( edd_format_amount( $eddStat->get_earnings( 0, 'this_month' ) ) )
                    ];

                    $stats['edd_sales_total'] = [
                        'title' => 'Earnings (All Time)',
                        'count' => edd_currency_filter( edd_format_amount( edd_get_total_earnings() ) )
                    ];

                }
                return $stats;
            });
        }

        // AffiliateWP
        if (class_exists('\Affiliate_WP')) {
            new \FluentCampaign\App\Services\Integrations\AffiliateWP\AffiliateWPAffActiveTrigger();
        }

        // LifterLMS
        if (defined('LLMS_PLUGIN_FILE')) {
            new \FluentCampaign\App\Services\Integrations\LifterLms\CourseEnrollTrigger();
            new \FluentCampaign\App\Services\Integrations\LifterLms\MembershipEnrollTrigger();
            new \FluentCampaign\App\Services\Integrations\LifterLms\LessonCompletedTrigger();
            new \FluentCampaign\App\Services\Integrations\LifterLms\CourseCompletedTrigger();
        }

        // LearnDash
        if (defined('LEARNDASH_VERSION')) {
            new \FluentCampaign\App\Services\Integrations\LearnDash\CourseEnrollTrigger();
            new \FluentCampaign\App\Services\Integrations\LearnDash\LessonCompletedTrigger();
            new \FluentCampaign\App\Services\Integrations\LearnDash\TopicCompletedTrigger();
            new \FluentCampaign\App\Services\Integrations\LearnDash\CourseCompletedTrigger();
            new \FluentCampaign\App\Services\Integrations\LearnDash\GroupEnrollTrigger();
        }

        // PaidMembership Pro
        if (defined('PMPRO_VERSION')) {
            new \FluentCampaign\App\Services\Integrations\PMPro\PMProPMProMembershipTrigger();
        }

        new \FluentCampaign\App\Services\Integrations\CRM\ListAppliedTrigger();
        new \FluentCampaign\App\Services\Integrations\CRM\RemoveFromListTrigger();
        new \FluentCampaign\App\Services\Integrations\CRM\TagAppliedTrigger();
        new \FluentCampaign\App\Services\Integrations\CRM\RemoveFromTagTrigger();
    }
}
