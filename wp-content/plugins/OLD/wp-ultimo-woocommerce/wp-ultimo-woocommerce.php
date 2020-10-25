<?php
/**
 * Plugin Name: WP Ultimo: WooCommerce Integration
 * Description: Extend your WP Ultimo payment options to allow your subscribers to use all the many available methods on your WooCommerce install!
 * Plugin URI: http://wpultimo.com/addons
 * Text Domain: wu-wc
 * Version: 1.2.6
 * Author: Arindo Duque - NextPress
 * Author URI: http://nextpress.co/
 * Copyright: Arindo Duque, NextPress
 * Network: true
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * WP Ultimo: WooCommerce Integration is distributed under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * WP Ultimo: WooCommerce Integration is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with WP Ultimo: WooCommerce Integration. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author   Arindo Duque
 * @category Core
 * @package  Addons
 * @version  1.2.6
 */

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

if (!class_exists('WP_Ultimo_WC')) :

/**
 * Here starts our plugin.
 */
class WP_Ultimo_WC {
  
  /**
   * Version of the Plugin
   * 
   * @var string
   */
  public $version = '1.2.6';
  
  /**
   * Makes sure we are only using one instance of the plugin
   * @var object WP_Ultimo_WC
   */
  public static $instance;

  /**
   * Returns the instance of WP_Ultimo_WC
   * @return object A WP_Ultimo_WC instance
   */
  public static function get_instance() {

    if (null === self::$instance) self::$instance = new self();

    return self::$instance;
    
  } // end get_instance;

  /**
   * Initializes the plugin
   */
  public function __construct() {

    // Set the plugins_path
    $this->plugins_path = plugin_dir_path(__DIR__);

    // Load the text domain
    load_plugin_textdomain('wp-ultimo-woocommerce', false, dirname(plugin_basename(__FILE__)) . '/lang');

    // Updater
    require_once $this->path('inc/class-wu-addon-updater.php');

    /**
     * @since 1.2.0 Creates the updater
     * @var WU_Addon_Updater
     */
    $updater = new WU_Addon_Updater('wp-ultimo-woocommerce', __('WP Ultimo: WooCommerce Integration', 'wp-wc'), __FILE__);

    /**
     * Adds the WooCommerce Subscription Functions
     * @since 1.2.0 
     */
    require_once $this->path('inc/woocommerce-subscriptions-functions.php');

    // Run Rorest, run!
    $this->hooks();

  } // end construct;

  /**
   * Return url to some plugin subdirectory
   * @return string Url to passed path
   */
  public function path($dir) {

    return plugin_dir_path(__FILE__).'/'.$dir;

  } // end path;

  /**
   * Return url to some plugin subdirectory
   * @return string Url to passed path
   */
  public function url($dir) {

    return plugin_dir_url(__FILE__).'/'.$dir;

  } // end url;
  
  /**
   * Return full URL relative to some file in assets
   * @return string Full URL to path
   */
  public function get_asset($asset, $assetsDir = 'img') {

    return $this->url("assets/$assetsDir/$asset");

  } // end get_asset;

  /**
   * Render Views
   * @param string $view View to be rendered.
   * @param Array $vars Variables to be made available on the view escope, via extract().
   */
  public function render($view, $vars = false) {

    // Make passed variables available
    if (is_array($vars)) extract($vars);

    // Load our view
    include $this->path("views/$view.php");

  } // end render;

  /**
   * Install our default settings after activation
   * @return
   */
  public function on_activation() {

    if (class_exists('WU_Settings')) { WU_Settings::save_settings(false, true); }

  } // end on_activation;

  /** 
   * Add the hooks we need to make this work
   */
  public function hooks() {

    register_activation_hook(__FILE__, array($this, 'on_activation'));

    add_action('wu_settings_sections', array($this, 'add_settings'));

    add_action('init', array($this, 'load_gateway'), 10);

  } // end hooks;

  /**
   * Loads the new gateway file, integrating with WooCommerce
   *
   * @return void
   */
  public function load_gateway() {

    // Gateway
    require_once $this->path('inc/class-wu-gateway-woocommerce.php');

  } // end load_gateway;

  /**
   * Adds the custom settings to the add-on section of our settings page on WP Ultimo
   * @param array $sections Sections;
   */
  function add_settings($sections) {

    return $sections;

  } // end add_settings;

} // end WP_Ultimo_WC;

/**
 * Initialize the Plugin
 */
add_action('plugins_loaded', 'wu_wc_init', 10);

/**
 * Returns the active instance of the plugin
 *
 * @return void
 */
function WP_Ultimo_WC() {

  return WP_Ultimo_WC::get_instance();

} // end WP_Ultimo_WC;

/**
 * Initializes the plugin
 *
 * @return void
 */
function wu_wc_init() {

  if (!class_exists('WP_Ultimo')) return; // We require WP Ultimo, baby

  if (!version_compare(WP_Ultimo()->version, '1.5.0', '>=')) {

    WP_Ultimo()->add_message(__('WP Ultimo: WooCommerce Integration requires WP Ultimo version 1.5.0. ', 'wp-ultimo-woocommerce'), 'warning', true);

    return;

  } // end if;

  // Get the main plugins
  $active_plugins = apply_filters('active_plugins', get_blog_option(get_current_site()->blog_id, 'active_plugins', array()));

  if (!function_exists('WC') && !in_array('woocommerce/woocommerce.php', $active_plugins)) {

    WP_Ultimo()->add_message(__('WP Ultimo: WooCommerce Integration requires WooCommerce to be activated at least on your main site.', 'wp-ultimo-woocommerce'), 'warning', true);

    return;

  } // end if;

  // Set global
  $GLOBALS['WP_Ultimo_WC'] = WP_Ultimo_WC();

} // end wu_wc_init;

/**
 * Checks wether or not we have WooCommerce Subscriptions activated on the site
 *
 * @since 1.2.0
 * @return boolean
 */
function wu_is_woocommerce_subscriptions_active() {

  // Get the main plugins
  $active_plugins = apply_filters('active_plugins', get_blog_option(get_current_site()->blog_id, 'active_plugins', array()));

  return class_exists('WC_Subscriptions') || in_array('woocommerce-subscriptions/woocommerce-subscriptions.php', $active_plugins);

} // end is_woocommerce_subscriptions_active;

endif;