<?php
/*
 * Plugin Name: Extensions For Elementor
 * Description: Extend your elementor capability by adding elementor extension.
 * Version: 1.0.8
 * Text Domain: elementor-extensions
 * Author: mayanksdudakiya
 * Author URI: https://www.linkedin.com/in/mayanksdudakiya/
 * Plugin URI: 
 * License: GPL 3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'ELEMENTOR_EXTENSIONS_VERSION', '1.0.8' );

define( 'ELEMENTOR_EXTENSIONS__FILE__', __FILE__ );
define( 'ELEMENTOR_EXTENSIONS_PLUGIN_BASE', plugin_basename( ELEMENTOR_EXTENSIONS__FILE__ ) );
define( 'ELEMENTOR_EXTENSIONS_PATH', plugin_dir_path( ELEMENTOR_EXTENSIONS__FILE__ ) );
define( 'ELEMENTOR_EXTENSIONS_MODULES_PATH', ELEMENTOR_EXTENSIONS_PATH . 'modules/' );
define( 'ELEMENTOR_EXTENSIONS_URL', plugins_url( '/', ELEMENTOR_EXTENSIONS__FILE__ ) );
define( 'ELEMENTOR_EXTENSIONS_ASSETS_URL', ELEMENTOR_EXTENSIONS_URL . 'assets/' );
define( 'ELEMENTOR_EXTENSIONS_MODULES_URL', ELEMENTOR_EXTENSIONS_URL . 'modules/' );
define( 'EE_MB_CUSTOM_FIELD_PLUG_PATH', ELEMENTOR_EXTENSIONS_PATH.'includes/custom-fields/' );
define( 'EE_MB_CUSTOM_FIELD_PLUG_URL', ELEMENTOR_EXTENSIONS_URL.'/includes/custom-fields/' );


/*@ Check ACF pro is active or not */
if ( ! function_exists( 'is_acf_pro_active' ) ) {
	function is_acf_pro_active() {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		$plugin = 'advanced-custom-fields-pro/acf.php';

		return is_plugin_active( $plugin );
	}
}

include_once( EE_MB_CUSTOM_FIELD_PLUG_PATH.'includes/custom-field-photo-gallery/custom-field-photo-gallery.php' );
include_once( ELEMENTOR_EXTENSIONS_PATH.'includes/ee-mb-default-values.php' );


if (!is_acf_pro_active()) :
	include_once( EE_MB_CUSTOM_FIELD_PLUG_PATH . 'custom-fields.php' );


	if(!function_exists('ee_mb_customfield_settings_url')){
		add_filter('acf/settings/url', 'ee_mb_customfield_settings_url');
		function ee_mb_customfield_settings_url( $url ) {
		    return EE_MB_CUSTOM_FIELD_PLUG_URL;
		}
	}

endif;

//if(!function_exists('ee_mb_custom_field_settings_show_admin')){
// add_filter('acf/settings/show_admin', 'ee_mb_custom_field_settings_show_admin');
// function ee_mb_custom_field_settings_show_admin( $show_admin ) {
//     return false;
// }
//}

if(!function_exists('ee_mb_customfield_json_save_point')){
	add_filter('acf/settings/save_json', 'ee_mb_customfield_json_save_point');
	function ee_mb_customfield_json_save_point( $path ) {
	    $path = EE_MB_CUSTOM_FIELD_PLUG_PATH.'includes/custom-fields';
	    return $path;
	}
}

if(!function_exists('ee_mb_customfield_json_load_point')){
	add_filter('acf/settings/load_json', 'ee_mb_customfield_json_load_point');
	function ee_mb_customfield_json_load_point( $paths ) {
		unset($paths[0]);
	    $paths[] = EE_MB_CUSTOM_FIELD_PLUG_PATH.'includes/custom-fields';
	    return $paths;
	}
}

/**
 * Load gettext translate for our text domain.
 *
 */
if(!function_exists('elementor_extensions_mb_load_plugin')){
	function elementor_extensions_mb_load_plugin() {
		load_plugin_textdomain( 'elementor-extensions' );

		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', 'elementor_extensions_fail_load' );
			return;
		}

		$elementor_version_required = '1.0.8';
		if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_required, '>=' ) ) {
			add_action( 'admin_notices', 'elementor_extensions_fail_load_out_of_date' );
			return;
		}

		require( ELEMENTOR_EXTENSIONS_PATH . 'plugin.php' );
	}
	add_action( 'plugins_loaded', 'elementor_extensions_mb_load_plugin' );
}

if(!function_exists('ee_mb_plugin_activation')){
	function ee_mb_plugin_activation(){
	    do_action( 'ee_mb_default_plugin_values' );
	}
	register_activation_hook( __FILE__, 'ee_mb_plugin_activation' );
}


/**
 * Show in WP Dashboard notice about the plugin is not activated.
 */
if(!function_exists('elementor_extensions_fail_load')){
	function elementor_extensions_fail_load() {
		$screen = get_current_screen();
		if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
			return;
		}

		$plugin = 'elementor/elementor.php';

		if ( _is_elementor_installed() ) {
			if ( ! current_user_can( 'activate_plugins' ) ) {
				return;
			}

			$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );

			$message = '<p>' . __( 'Elementor Extensions is not working because you need to activate the Elementor plugin.', 'elementor-extensions' ) . '</p>';
			$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, __( 'Activate Elementor Now', 'elementor-extensions' ) ) . '</p>';
		} else {
			if ( ! current_user_can( 'install_plugins' ) ) {
				return;
			}

			$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );

			$message = '<p>' . __( 'Elementor Extensions is not working because you need to install the Elemenor plugin', 'elementor-extensions' ) . '</p>';
			$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, __( 'Install Elementor Now', 'elementor-extensions' ) ) . '</p>';
		}

		echo '<div class="error"><p>' . $message . '</p></div>';
	}
}

if(!function_exists('elementor_extensions_fail_load_out_of_date')){
	function elementor_extensions_fail_load_out_of_date() {
		if ( ! current_user_can( 'update_plugins' ) ) {
			return;
		}

		$file_path = 'elementor/elementor.php';

		$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
		$message = '<p>' . __( 'Elementor Extensions is not working because you are using an old version of Elementor.', 'elementor-extensions' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, __( 'Update Elementor Now', 'elementor-extensions' ) ) . '</p>';

		echo '<div class="error">' . $message . '</div>';
	}
}

if ( ! function_exists( '_is_elementor_installed' ) ) {
	function _is_elementor_installed() {
		$file_path = 'elementor/elementor.php';
		$installed_plugins = get_plugins();

		return isset( $installed_plugins[ $file_path ] );
	}
}

if ( ! function_exists( 'is_elementor_pro_active' ) ) {
	function is_elementor_pro_active() {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		$plugin = 'elementor-pro/elementor-pro.php';

		return is_plugin_active( $plugin ) || function_exists( 'elementor_pro_load_plugin' );
	}
}
