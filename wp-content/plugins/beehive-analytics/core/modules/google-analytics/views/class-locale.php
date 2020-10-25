<?php
/**
 * The locale view class for the Analytics module.
 *
 * This class will handle all the strings required in analytics module.
 *
 * @link    http://premium.wpmudev.org
 * @since   3.2.4
 *
 * @author  Joel James <joel@incsub.com>
 * @package Beehive\Core\Modules\Google_Analytics\Views
 */

namespace Beehive\Core\Modules\Google_Analytics\Views;

// If this file is called directly, abort.
defined( 'WPINC' ) || die;

use Beehive\Core\Utils\Abstracts\Base;

/**
 * Class Locale
 *
 * @package Beehive\Core\Modules\Google_Analytics\Views
 */
class Locale extends Base {

	/**
	 * Get the localise vars for the post stats box.
	 *
	 * This data will be available only in post stats scripts.
	 *
	 * @since 3.2.4
	 *
	 * @return array
	 */
	public function post() {
		return array(
			'labels'       => array(
				'users'            => __( 'Users', 'ga_trans' ),
				'pageviews'        => __( 'Pageviews', 'ga_trans' ),
				'sessions'         => __( 'Sessions', 'ga_trans' ),
				'page_sessions'    => __( 'Pages/Sessions', 'ga_trans' ),
				'average_sessions' => __( 'Avg. Time', 'ga_trans' ),
				'bounce_rates'     => __( 'Bounce Rate', 'ga_trans' ),
			),
			'descriptions' => array(
				'users'                   => __( 'Users who have initiated at least one session during the date range.', 'ga_trans' ),
				/* translators: %1$s: No. of users. %2$s: Date period. */
				'screen_users'            => __( '%1$d users who have initiated at least one session between %2$s.', 'ga_trans' ),
				'pageviews'               => __( 'Pageviews is the total number of pages viewed. Repeated views of a single page are counted.', 'ga_trans' ),
				/* translators: %1$s: No. of views. %2$s: Date period. */
				'screen_pageviews'        => __( '%1$d views of this page between %2$s.', 'ga_trans' ),
				'sessions'                => __( 'Total number of Sessions within the date range. A session is the period of time user is actively engaged with your website, app, etc.', 'ga_trans' ),
				/* translators: %1$s: No. of sessions. %2$s: Date period. */
				'screen_sessions'         => __( '%1$d number of sessions within %2$s.', 'ga_trans' ),
				'page_sessions'           => __( 'Pages/Sessions (Average Page Depth) is the average number of pages viewed during a session. Repeated views of a single page are counted.', 'ga_trans' ),
				/* translators: %1$d: No. of page sessions. %2$s: Date period. */
				'screen_page_sessions'    => __( '%1$d page sessions between %2$s.', 'ga_trans' ),
				'average_sessions'        => __( 'The average length of a session.', 'ga_trans' ),
				/* translators: %1$s: Length of sessions. %2$s: Date period. */
				'screen_average_sessions' => __( 'This page has an average session length of %1$s between %2$s.', 'ga_trans' ),
				'bounce_rates'            => __( 'The percentage of single-page sessions in which there was no interaction with the page. A bounced session has a duration of 0 seconds.', 'ga_trans' ),
				/* translators: %1$s: No. of single-page sessions. %2$s: Date period. */
				'screen_bounce_rates'     => __( '%1$s of single-page sessions without interaction between %2$s.', 'ga_trans' ),
			),
		);
	}

	/**
	 * Get the localise vars specific to the dashboard widget.
	 *
	 * This data will be available only in dashboard widget script.
	 *
	 * @since 3.2.4
	 *
	 * @return array
	 */
	public function dashboard_widget() {
		return array(
			'titles'       => array(),
			'menus'        => array(),
			'notices'      => array(
				/* translators: %s: Link to login. */
				'auth_required' => __( 'A Google Analytics account hasn\'t been linked yet. To see statistics, <a href="%s" target="_blank">link an account</a>.', 'ga_trans' ),
				'empty_data'    => __( 'It may take up to 24 hours for data to begin feeding. Please check back soon.', 'ga_trans' ),
			),
			'placeholders' => array(),
			'errors'       => array(),
			'labels'       => array(
				'all_stats'              => __( 'See all stats', 'ga_trans' ),
				'refresh_data'           => __( 'Refresh data', 'ga_trans' ),
				'general_stats'          => __( 'General stats', 'ga_trans' ),
				'audience'               => __( 'Audience', 'ga_trans' ),
				'top_pages'              => __( 'Top Pages & Views', 'ga_trans' ),
				'sessions'               => __( 'Sessions', 'ga_trans' ),
				'pageviews'              => __( 'Pageviews', 'ga_trans' ),
				'page_sessions'          => __( 'Pages/Sessions', 'ga_trans' ),
				'average_sessions'       => __( 'Avg. Time', 'ga_trans' ),
				'bounce_rates'           => __( 'Bounce Rate', 'ga_trans' ),
				'users'                  => __( 'Users', 'ga_trans' ),
				'traffic'                => __( 'Traffic', 'ga_trans' ),
				'top_page'               => __( 'Top Page', 'ga_trans' ),
				'top_country'            => __( 'Top Country', 'ga_trans' ),
				'top_referral'           => __( 'Top Referral', 'ga_trans' ),
				'top_search_engine'      => __( 'Top Search Engine', 'ga_trans' ),
				'top_medium'             => __( 'Top Medium', 'ga_trans' ),
				'top_social_network'     => __( 'Top Social Network', 'ga_trans' ),
				'none'                   => __( 'None', 'ga_trans' ),
				'no_data_available'      => __( 'No data available.', 'ga_trans' ),
				'fetching_data'          => __( 'Fetching latest data...', 'ga_trans' ),
				'top_pages_most_visited' => __( 'Top Pages/most visited', 'ga_trans' ),
				'views'                  => __( 'Views', 'ga_trans' ),
				'top_countries'          => __( 'Top Countries', 'ga_trans' ),
				'returning_visitors'     => __( 'Returning visitors', 'ga_trans' ),
				'new_visitors'           => __( 'New visitors', 'ga_trans' ),
			),
			'buttons'      => array(),
			'tooltips'     => array(),
			'descriptions' => array(
				'no_donut_data'      => __( 'No data for this period of time', 'ga_trans' ),
				'returning_visitors' => __( 'returning visitors', 'ga_trans' ),
				'new_visitors'       => __( 'new visitors', 'ga_trans' ),
			),
		);
	}

	/**
	 * Get the localise vars specific to the all statistics page.
	 *
	 * This data will be available only in statistics page script.
	 *
	 * @since 3.2.4
	 *
	 * @return array
	 */
	public function statistics() {
		return array(
			'titles'       => array(
				'google_analytics' => __( 'Google Analytics', 'ga_trans' ),
			),
			'notices'      => array(
				/* translators: %s: Link to settings page. */
				'google_not_linked' => __( 'A Google Analytics account hasn\'t been linked yet. To see statistics, <a href="%s">link an account</a>.', 'ga_trans' ),
				'google_no_data'    => __( 'There\'s no analytics data to display yet. Either you haven\'t had traffic to this site or we don\'t have any data from API yet. Please check back in few hours.', 'ga_trans' ),
			),
			'placeholders' => array(),
			'errors'       => array(),
			'labels'       => array(
				'sessions'               => __( 'Sessions', 'ga_trans' ),
				'users'                  => __( 'Users', 'ga_trans' ),
				'pageviews'              => __( 'Pageviews', 'ga_trans' ),
				'page_sessions'          => __( 'Page/Sessions', 'ga_trans' ),
				'average_sessions'       => __( 'Avg. Sessions', 'ga_trans' ),
				'bounce_rates'           => __( 'Bounce Rates', 'ga_trans' ),
				'select_period'          => __( 'Select analytics time period to show', 'ga_trans' ),
				'compare_periods'        => __( 'Compare to last period', 'ga_trans' ),
				'top_countries'          => __( 'Top Countries', 'ga_trans' ),
				'no_information'         => __( 'No information', 'ga_trans' ),
				'mediums'                => __( 'Mediums', 'ga_trans' ),
				'top_pages'              => __( 'Top Pages', 'ga_trans' ),
				'search_engines'         => __( 'Search Engines', 'ga_trans' ),
				'social_networks'        => __( 'Social Networks', 'ga_trans' ),
				'visitors'               => __( 'Visitors', 'ga_trans' ),
				'fetching_data'          => __( 'Fetching latest data...', 'ga_trans' ),
				'top_pages_most_visited' => __( 'Top Pages/most visited', 'ga_trans' ),
				'trend'                  => __( 'Trend', 'ga_trans' ),
				'country_code'           => __( 'Country Code', 'ga_trans' ),
				'country_name'           => __( 'Country Name', 'ga_trans' ),
				'visits_percentage'      => __( 'Visits Percentage', 'ga_trans' ),
				'total_visits'           => __( 'Total Visits', 'ga_trans' ),
				'country'                => __( 'Country', 'ga_trans' ),
				'country_sessions'       => __( 'Country Sessions', 'ga_trans' ),
				'has'                    => __( 'has', 'ga_trans' ),
				'current_period'         => __( 'Current Period', 'ga_trans' ),
				'previous_period'        => __( 'Previous Period', 'ga_trans' ),
			),
			'buttons'      => array(),
			'tooltips'     => array(),
			'descriptions' => array(
				'empty_visitors_chart' => __( 'Visitors chart is empty. There\'s no data to display.', 'ga_trans' ),
				/* translators: %s: Percent no. of visits per country. */
				'percentage_visits'    => __( '%s visits.', 'ga_trans' ),
			),
		);
	}
}
