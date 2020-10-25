<?php
/**
 * Plugin Name: WP Ultimo: VAT Support (Alpha)
 * Description: Adds VAT support to the WP Ultimo tax system for countries that are EU members.
 * Plugin URI: http://wpultimo.com/addons
 * Text Domain: wu-vat
 * Version: 0.0.1
 * Author: Arindo Duque - NextPress
 * Author URI: http://nextpress.co/
 * Copyright: Arindo Duque, NextPress
 * Network: true
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
} // end if;

if (!class_exists('WP_Ultimo_VAT')) :

	/**
	 * Here starts our plugin.
	 */
	class WP_Ultimo_VAT {

		/**
		 * Version of the Plugin
		 *
		 * @var string
		 */
		public $version = '0.0.1';

		/**
		 * Makes sure we are only using one instance of the plugin
         *
		 * @var object WP_Ultimo_VAT
		 */
		public static $instance;

		/**
		 * Returns the instance of WP_Ultimo_VAT
         *
		 * @return object A WP_Ultimo_VAT instance
		 */
		public static function get_instance() {

			if (null === self::$instance) {

				self::$instance = new self();

			} // end if;

			return self::$instance;

		} // end get_instance;

		/**
		 * Initializes the plugin
		 */
		public function __construct() {

			// Set the plugins_path
			$this->plugins_path = plugin_dir_path(__DIR__);

			// Load the text domain
			load_plugin_textdomain('wu-vat', false, dirname(plugin_basename(__FILE__)) . '/lang');

			// Updater
			require_once $this->path('inc/class-wu-addon-updater.php');

			/**
			 * @since 0.0.1 Creates the updater
			 * @var WU_Addon_Updater
			 */
			$updater = new WU_Addon_Updater('wp-ultimo-vat', __('WP Ultimo: VAT', 'wu-vat'), __FILE__);

			// Run Forest, run!
			add_action('init', array($this, 'hooks'));

		}  // end __construct;

		/**
		 * Return url to some plugin subdirectory
		 *
		 * @return string Url to passed path
		 */
		public function path($dir) {

			return plugin_dir_path(__FILE__) . '/' . $dir;

		} // end path;

		/**
		 * Return url to some plugin subdirectory
		 *
		 * @return string Url to passed path
		 */
		public function url($dir) {

			return plugin_dir_url(__FILE__) . '/' . $dir;

		} // end url;

		/**
		 * Return full URL relative to some file in assets
		 *
		 * @return string Full URL to path
		 */
		public function get_asset($asset, $assets_dir = 'img') {

			return $this->url("assets/$assets_dir/$asset");

		} // end get_asset;

		/**
		 * Render Views
		 *
		 * @param string $view View to be rendered.
		 * @param Array  $vars Variables to be made available on the view escope, via extract().
		 */
		public function render($view, $vars = false) {

			// Make passed variables available
			if (is_array($vars)) {

				extract($vars);

			} // end if;

			// Load our view
			include $this->path("views/$view.php");

		} // end render;

		/**
		 * Add the hooks we need to make this work
		 */
		public function hooks() {

      require_once $this->path('inc/class-wu-tax-eu-vat.php');
      
		} // end hooks;

	}  // end class WP_Ultimo_VAT;

	/**
	 * Returns the active instance of the plugin
	 *
	 * @return WU_Ultimo_VAT
	 */
	function WP_Ultimo_VAT() {

		return WP_Ultimo_VAT::get_instance();

	} // end WP_Ultimo_VAT;

	/**
	 * Initialize the Plugin
	 */
	add_action('plugins_loaded', 'wu_vat_init', 1);

	/**
	 * We require WP Ultimo, so we need it
	 *
	 * @since 0.0.1
	 * @return void
	 */
	function wu_vat_requires_ultimo() { ?>

    <div class="notice notice-warning"> 
    <p><?php _e('WP Ultimo: VAT requires WP Ultimo to run. Install and active WP Ultimo to use WP Ultimo: VAT.', 'wu-vat'); ?></p>
    </div>

		<?php
    }  // end wu_vat_requires_ultimo;

	/**
	 * Initializes the plugin
	 *
	 * @since 0.0.1
	 * @return mixed
	 */
	function wu_vat_init() {

		if (!class_exists('WP_Ultimo')) {

			return add_action('network_admin_notices', 'wu_vat_requires_ultimo');

		} // end if;

		if (!version_compare(WP_Ultimo()->version, '2.0', '>=')) {

			return WP_Ultimo()->add_message(__('WP Ultimo: VAT requires WP Ultimo version 2.0.0. ', 'wu-vat'), 'warning', true);

		} // end if;

		// Set global
		$GLOBALS['WP_Ultimo_VAT'] = WP_Ultimo_VAT();

	} // end wu_vat_init;

endif;
