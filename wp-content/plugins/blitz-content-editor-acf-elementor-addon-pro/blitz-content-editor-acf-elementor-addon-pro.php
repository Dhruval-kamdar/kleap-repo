<?php
/**
 * Plugin Name: 	BLITZ - Content Editor/ACF Add-on for Elementor
 * Plugin URI: 	    https://waaspro.com
 * Description: 	Content Editor/ACF Add-on for Elementor gives elementor widgets which supports ACF shortcodes in place where we use media,icons or numbers etc.
 * Version:     	1.0.6
 * Author:      	WaaS.PRO
 * Author URI:  	https://waaspro.com
 * License:     	GPL2 etc
*/

if (!defined('ABSPATH')) { exit; }

register_activation_hook( __FILE__, array( 'Blitz_ACF_Elementor_Widgets', 'plugin_activation' ) );
update_option('software_license_key_BZ-ACF-ELE-PRO', 'activated');
if (!class_exists('Blitz_ACF_Elementor_Widgets'))
{
	class Blitz_ACF_Elementor_Widgets {
		
		public function bz_acf_elementor_load()
		{
			global $bz_acf_elementor_load;

			if ( !isset($bz_acf_elementor_load))
			{
			  include_once(__DIR__ . '/blitz-acf-elementor-widgets-settings.php');
			  $Plugin = new BZ_ACF_WZ\BZ_ACFWidgetSettings;
			  $Plugin->init();
			}
			return $bz_acf_elementor_load;
		}
		
		public static function plugin_activation() {
			
				if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
					include_once( ABSPATH . '/wp-admin/includes/plugin.php' );
				}
				
				if (current_user_can( 'activate_plugins' )	&& is_plugin_active( 'elementor/elementor.php' ) ) {
				} else {
					
					deactivate_plugins( plugin_basename(__FILE__) );
					$error_message = '<p style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,Oxygen-Sans,Ubuntu,Cantarell,\'Helvetica Neue\',sans-serif;font-size: 13px;line-height: 1.5;color:#444;">' . esc_html__( 'This plugin requires Elementor plugin to be installed and Activated', 'simplewlv' ). '</p>';
					 die($error_message); 
				}
		}
		

	}

}

$PluginAcfElementorWidgets = new Blitz_ACF_Elementor_Widgets;
$PluginAcfElementorWidgets->bz_acf_elementor_load();
