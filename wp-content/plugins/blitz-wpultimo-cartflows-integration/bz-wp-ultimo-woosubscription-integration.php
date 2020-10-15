<?php
/**
 * Plugin Name: BLITZ - WP Ultimo: CartFlows Integration
 * Plugin URI: 	https://waaspro.com
 * Description: Integrate your WP Ultimo network to your Woocommerce Subscriptions & Cartflows. All plans will automatically be linked to woocommerce subscriptions & payment handling using woocommerce.Also, signup process takes to cartflow funnels based on woo product assigned to funnel on checkout step.
 * Version:     1.0.10
 * Author:      WaaS.PRO
 * Author URI:  https://waaspro.com
 * License:     GPL2 etc
*/ 

if (!defined('ABSPATH')) {
  exit;
}
update_option('software_license_key_WPU-WCS-INT', 'activated');
function woosubs_plugin_activate() {
	
	
	if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
		include_once( ABSPATH . '/wp-admin/includes/plugin.php' );
	}
	
	if ( current_user_can( 'activate_plugins' )	&& !class_exists('WP_Ultimo')) {
		
		deactivate_plugins( plugin_basename(__FILE__) );
		$error_message = '<p style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,Oxygen-Sans,Ubuntu,Cantarell,\'Helvetica Neue\',sans-serif;font-size: 13px;line-height: 1.5;color:#444;">' . esc_html__( 'This plugin requires WP Ultimo to be installed and Activated', 'simplewlv' ). '</p>';
		 die($error_message); 
		 
	}  else if (current_user_can( 'activate_plugins' )	&& !class_exists('WooCommerce') ) {
		
		deactivate_plugins( plugin_basename(__FILE__) );
		$error_message = '<p style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,Oxygen-Sans,Ubuntu,Cantarell,\'Helvetica Neue\',sans-serif;font-size: 13px;line-height: 1.5;color:#444;">' . esc_html__( 'This plugin requires Woocommerce to be installed and Activated', 'simplewlv' ). '</p>';
		 die($error_message); 
		 
	}  else if (current_user_can( 'activate_plugins' )	&& !class_exists('WC_Subscriptions') ) {
		
		deactivate_plugins( plugin_basename(__FILE__) );
		$error_message = '<p style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,Oxygen-Sans,Ubuntu,Cantarell,\'Helvetica Neue\',sans-serif;font-size: 13px;line-height: 1.5;color:#444;">' . esc_html__( 'This plugin requires Woocommerce Subscriptions to be installed and Activated', 'simplewlv' ). '</p>';
		 die($error_message); 
		 
	}  else if (current_user_can( 'activate_plugins' )	&& !class_exists('Cartflows_Loader') ) {
		
		deactivate_plugins( plugin_basename(__FILE__) );
		$error_message = '<p style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,Oxygen-Sans,Ubuntu,Cantarell,\'Helvetica Neue\',sans-serif;font-size: 13px;line-height: 1.5;color:#444;">' . esc_html__( 'This plugin requires Cartflows to be installed and Activated', 'simplewlv' ). '</p>';
		 die($error_message); 
		 
	}
	
	
}
register_activation_hook( __FILE__, 'woosubs_plugin_activate' );


global $woosubsDir,$wppath,$wppath1;

$woosubsDir = plugin_dir_path( __DIR__ );
$wupath = $woosubsDir.'wp-ultimo/';
$woopath = $woosubsDir.'woocommerce/';
$wuwoosubspath = $woosubsDir.'blitz-wpultimo-cartflows-integration/';

require_once $wupath.'paradox/paradox.php';


final class WP_UltimoWooSubscriptions extends ParadoxFrameworkSafe {
	
	public static $instance;
	
	public static function wu_wcs_get_instance($config = array()) {
		if (null === self::$instance) self::$instance = new self($config);
		return self::$instance;
	}

	public function onPluginsLoaded() {
		
		global $woosubsDir,$wupath,$wuwoosubspath;
		
		if(class_exists('WP_Ultimo')){
			if(file_exists($wupath.'inc/wu-functions.php')){
				/**
				 * @since  1.3.0 Multi-Network Support
				 */
				require_once $wupath.'inc/class-wu-multi-network.php';

				/**
				 * @since  1.4.0 Customizer Options
				 */
				require_once $wupath.'inc/class-wu-customizer.php'; // @since 1.4.0

				/**
				 * Essential elements that need to get loaded first
				 * @since  1.2.0
				 */
				require_once $wupath.'inc/class-wu-util.php';
				require_once $wupath.'inc/class-wu-site-hooks.php';
				require_once $wupath.'inc/class-wu-admin-settings.php';

				// Models
				require_once $wupath.'inc/models/wu-plan.php';
				require_once $wupath.'inc/models/wu-site-owner.php';
				require_once $wupath.'inc/models/wu-subscription.php';
				require_once $wupath.'inc/models/wu-site.php';
				require_once $wupath.'inc/models/wu-broadcast.php';     // @since 1.1.5
				require_once $wupath.'inc/models/wu-site-template.php'; // @since 1.2.0
				require_once $wupath.'inc/models/wu-webhook.php';       // @since 1.6.0

				// Domain Mapping
				require_once $wupath.'inc/class-wu-domain-mapping.php';
				require_once $wupath.'inc/gateways/class-wu-gateway.php';
			}
		} 
	}
	
	/**
	 * Call the settings file here
	 */
	public function Plugin() {
		global $woosubsDir,$wupath,$wuwoosubspath;
		if(class_exists('WP_Ultimo')){
			require_once $wuwoosubspath.'AutoUpdater.php';
			require_once $wuwoosubspath.'bz-wu-woo-admin-settings.php';
			require_once $wuwoosubspath.'bz-wu-woo-subscriptions-functions.php';
		}
	}

	
}
WP_UltimoWooSubscriptions::wu_wcs_get_instance(array());
