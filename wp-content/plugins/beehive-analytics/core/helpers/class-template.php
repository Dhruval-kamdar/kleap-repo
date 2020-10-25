<?php
/**
 * Defines template helper functionality of the plugin.
 *
 * @link    http://premium.wpmudev.org
 * @since   3.2.0
 *
 * @author  Joel James <joel@incsub.com>
 * @package Beehive\Core\Helpers
 */

namespace Beehive\Core\Helpers;

// If this file is called directly, abort.
defined( 'WPINC' ) || die;

/**
 * Class Template
 *
 * @package Beehive\Core\Helpers
 */
class Template {

	/**
	 * Get the settings page url.
	 *
	 * @param string   $tab     Tab.
	 * @param bool     $network Network flag.
	 * @param int|bool $blog_id Blog ID.
	 * @param string   $page    Admin page slug.
	 *
	 * @since 3.2.0
	 * @since 3.2.4 Added admin page slug param.
	 *
	 * @return string
	 */
	public static function settings_page( $tab = 'tracking', $network = false, $blog_id = false, $page = 'beehive-settings' ) {
		// Get current blog id if empty.
		if ( ! $blog_id ) {
			$blog_id = get_current_blog_id();
		}

		// Get base url.
		$url = $network ? network_admin_url( 'admin.php' ) : get_admin_url( $blog_id, 'admin.php' );

		/**
		 * Filter to modify main url used to build settings url
		 *
		 * @param bool $network Network flag.
		 *
		 * @since 3.2.2
		 */
		$url = apply_filters( 'beehive_settings_main_url', $url, $network, $blog_id );

		// Get page.
		$url = add_query_arg(
			array(
				'page' => $page,
			),
			$url
		);

		return $url . '#/' . $tab;
	}

	/**
	 * Get the all statistics page url.
	 *
	 * @param bool $network Network flag.
	 *
	 * @since 3.2.0
	 *
	 * @return string
	 */
	public static function statistics_page( $network = false ) {
		// Get base url.
		$url = $network ? network_admin_url( 'admin.php' ) : admin_url( 'admin.php' );

		return add_query_arg(
			array(
				'page' => 'beehive-google-analytics',
			),
			$url
		);
	}

	/**
	 * Get the dashbord page url.
	 *
	 * @param bool $network Network flag.
	 *
	 * @since 3.2.4
	 *
	 * @return string
	 */
	public static function dashboard_page( $network = false ) {
		// Get base url.
		$url = $network ? network_admin_url( 'admin.php' ) : admin_url( 'admin.php' );

		return add_query_arg(
			array(
				'page' => 'beehive',
			),
			$url
		);
	}

	/**
	 * Get assets url of Beehive plugin.
	 *
	 * @param string $url Relative url path.
	 *
	 * @since 3.2.0
	 *
	 * @return string
	 */
	public static function asset_url( $url = '' ) {
		return BEEHIVE_URL . 'app/assets/' . $url;
	}
}
