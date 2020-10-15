<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              www.waashero.com
 * @since             1.0.0
 * @package           The_Wu_Integration
 *
 * @wordpress-plugin
 * Plugin Name:       Template Hero Elementor WP Ultimo Integration
 * Plugin URI:        www.waashero.com
 * Description:       This plugin provides ultimo supported elementor libraries.
 * Version:           1.0.0
 * Author:            J Hanlon
 * Author URI:        www.waashero.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       the-wu-integration
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'THE_WU_INTEGRATION_VERSION', '1.0.0' );
define( 'THE_WU_INTEGRATION_TEXT_DOMAIN', 'the-wu-integration' );
define( 'THE_WU_INTEGRATION_STORE_URL', 'https://waashero.com' );
define( 'THE_WU_INTEGRATION_ITEM_ID', 9998 ); 
define( 'THE_WU_INTEGRATION_ITEM_NAME', 'Template Hero Elementor WP Ultimo Integration' ); 
if ( !defined( "THE_WU_INTEGRATION_FILE" ) ) {
	define( 'THE_WU_INTEGRATION_FILE', __FILE__ );
}
/**
 * Define constant for plugin settings link
 */
if( !defined( 'THE_WU_INTEGRATION_BASE_DIR' ) ) {
	define( 'THE_WU_INTEGRATION_BASE_DIR', plugin_basename( __FILE__ ) );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-the-wu-integration-activator.php
 */
function activate_the_wu_integration() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-the-wu-integration-activator.php';
	The_Wu_Integration_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-the-wu-integration-deactivator.php
 */
function deactivate_the_wu_integration() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-the-wu-integration-deactivator.php';
	The_Wu_Integration_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_the_wu_integration' );
register_deactivation_hook( __FILE__, 'deactivate_the_wu_integration' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-the-wu-integration.php';
/**
 * Checks for plugin dependencies
 *
 * @return void
 */
function the_wu_check_for_dependencies() {
	if( !is_admin() || !is_network_admin() ) {
		return true;
	}
	// Check if WP Ultimo is installed and activated
	$notice = true;
	if( ! class_exists( 'WP_Ultimo' ) ) {
		$notice = false;
		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'the-wu-integration' ),
			'<strong>' . esc_html__( 'Template Hero Elementor WP Ultimo Integration', 'the-wu-integration' ) . '</strong>',
			'<strong>' . esc_html__( 'WP Ultimo', 'the-wu-integration' ) . '</strong>'
		);		

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
		deactivate_plugins( plugin_basename( __FILE__ ), true );
		
	}

	// Check if Elementor installed and activated
	if ( ! class_exists( '\\TemplateHero\\Plugin_Client' ) ) {
		$notice = false;
		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'the-wu-integration' ),
			'<strong>' . esc_html__( 'Template Hero Elementor WP Ultimo Integration', 'the-wu-integration' ) . '</strong>',
			'<strong>' . esc_html__( 'Template Hero Elementor Client', 'the-wu-integration' ) . '</strong>'
		);		

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
		deactivate_plugins( plugin_basename( __FILE__ ), true );

	}

	return $notice;
}
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_the_wu_integration() {
	$return = the_wu_check_for_dependencies();
	if( $return == false ) {
		return $return;
	}
	$plugin = The_WP_Ultimo\The_Wu_Integration::getInstance();
	$plugin->run();
}

add_action( 'plugins_loaded', 'run_the_wu_integration' );

/**
 * Translate the "Plugin activated." string
 *
 * @param [string] $translated_text
 * @param [string] $untranslated_text
 * @param [string] $domain
 *
 * @return string
 */

function the_wu_activation_message( $translated_text, $untranslated_text, $domain ) {
	$old = [
		"Plugin <strong>activated</strong>.",
		"Selected plugins <strong>activated</strong>.",
		"Plugin activated.",
		"Selected plugins activated.",
	];

	$new = "The Core is stable and the Plugin is <strong>deactivated</strong>";
	
	if ( ( ! class_exists( 'WP_Ultimo' )  || ( ! class_exists( '\\TemplateHero\\Plugin_Client' ) ) ) && in_array( $untranslated_text , $old, true ) ) {
		$translated_text = $new;
		remove_filter( current_filter(), __FUNCTION__, 99 );
	}

	return $translated_text;
}

add_filter( 'gettext', 'the_wu_activation_message' , 999, 3 );
