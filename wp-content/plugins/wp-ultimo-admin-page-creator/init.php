<?php
/**
 * Add-on Init File
 *
 * Initializes the file for the add-on version of the plugin.
 *
 * @author      WP_Ultimo
 * @category    Admin
 * @package     WP_Ultimo/Addon/Admin_Pages
 * @version     0.0.1
 */

if (!function_exists('wu_apc_init')) :

	/**
	 * Initialize the Plugin
	 */
	add_action('plugins_loaded', 'wu_apc_init', 1);

	/**
	 * Initializes the plugin
	 *
	 * @return void
	 */
	function wu_apc_init() {

		if (!function_exists('WP_Ultimo')) {
			return;
		} // end if;

		if (!version_compare(WP_Ultimo()->version, '1.6.0', '>=')) {

			WP_Ultimo()->add_message(__('WP Ultimo: Admin Page Creator requires WP Ultimo version 1.6.0. ', 'wu-apc'), 'warning', true);

			return;

		} // end if;

		// Set global
		$GLOBALS['WP_Ultimo_APC'] = WP_Ultimo_APC();

		require_once plugin_dir_path(__FILE__) . 'inc/class-wapp-admin-notices.php';

		// Updater
		require_once WP_Ultimo_APC()->path('inc/class-wu-addon-updater.php');

		/**
		 * @since 1.2.0 Creates the updater
		 * @var WU_Addon_Updater
		 */
		$updater = new WU_Addon_Updater('wp-ultimo-admin-page-creator', __('WP Ultimo: Admin Page Creator', 'wp-wc'), WP_Ultimo_APC()->file);

	} // end wu_apc_init;

endif;
