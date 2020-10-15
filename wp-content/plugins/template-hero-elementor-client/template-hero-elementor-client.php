<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://waashero.com
 * @since             1.0.0
 * @package           Template_Hero_Elementor_Client
 *
 * @wordpress-plugin
 * Plugin Name:       Library
 * Plugin URI:        https://waashero.com
 * Description:       Easily Loads Elementor Template Libraries using any wordpress single or multisite server.
 * Version:           1.2.4
 * Author:            J Hanlon | Waas Hero
 * Author URI:        https://waashero.com
 * Text Domain:       template-hero-elementor
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define constant for plugin settings link
 */
if( !defined( 'TEMPLATE_HERO_ELEMENTOR_CLIENT_BASE_DIR' ) ) {
	define( 'TEMPLATE_HERO_ELEMENTOR_CLIENT_BASE_DIR', plugin_basename( __FILE__ ) );
}


/**
 * Define constant for plugin settings link
 */
if( !defined( 'TEMPLATE_HERO_ELEMENTOR_CLIENT_DIR' ) ) {
	define( 'TEMPLATE_HERO_ELEMENTOR_CLIENT_DIR', plugin_dir_path( __FILE__ ) );
}
define( 'TEMPLATE_HERO_ELEMENTOR_FILE', __FILE__ );
define( 'th_elementor_token', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvd29yZHByZXNzLTQyMjg0NS0xNDk5MDAxLmNsb3Vkd2F5c2FwcHMuY29tIiwiZGF0YSI6eyJjbGllbnQiOnsicHVibGljX2lkIjoiaE5GclV3eGhKeSY5I1N4XiJ9fX0.LY4kvtJ2-Io8DFvCPf-yDMmv6EHKY7lfBJyq1n8umSE' );
define( 'th_elementor_pk', 'r9V7w0*TK0IATdctUnRAK!ko' );
/**
 * The code that runs during plugin activation.
 * @since 1.0.0
 */
function activate_template_hero_elementor_client() {
	if( file_exists( TEMPLATE_HERO_ELEMENTOR_CLIENT_DIR . 'includes/class-template-hero-elementor-activator.php' ) ) {
		require_once TEMPLATE_HERO_ELEMENTOR_CLIENT_DIR . 'includes/class-template-hero-elementor-activator.php';
		Template_Hero_Elementor_Client_Activator::activate();
	}
}

/**
 * The code that runs during plugin deactivation.
 * @since 1.0.0
 */
function deactivate_template_hero_elementor_client() {
	if( file_exists( TEMPLATE_HERO_ELEMENTOR_CLIENT_DIR . 'includes/class-template-hero-elementor-deactivator.php' ) ) {
		require_once TEMPLATE_HERO_ELEMENTOR_CLIENT_DIR . 'includes/class-template-hero-elementor-deactivator.php';
		Template_Hero_Elementor_Client_Deactivator::deactivate();
	}
}

register_activation_hook( __FILE__, 'activate_template_hero_elementor_client' );
register_deactivation_hook( __FILE__, 'deactivate_template_hero_elementor_client' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
if( file_exists( TEMPLATE_HERO_ELEMENTOR_CLIENT_DIR . 'includes/class-template-hero-elementor.php' ) ) {
	require TEMPLATE_HERO_ELEMENTOR_CLIENT_DIR . 'includes/class-template-hero-elementor.php';
}


/**
 * Display admin notifications if dependency not found.
 * @since 1.0.0
 */
function template_hero_elementor_client_ready() {
	if ( ! is_admin() && ! is_network_admin() ) {
		return;
	}

	if ( ! class_exists( '\\Elementor\\Plugin' ) ) {
		$class   = 'notice is-dismissible error';
		$message = __( 'Template Hero Elementor Client add-on requires the <a href="https://wordpress.org/plugins/elementor/" target="_BLANK">Elementor or Elementor Pro</a> plugin to be activated.', 'template-hero-elementor' );
		printf( '<div id="message" class="%s"> <p>%s</p></div>', $class, $message );
		deactivate_plugins( plugin_basename( __FILE__ ) );
	}
	
	if ( class_exists( '\\TemplateHero\\Plugin' ) ) {
		$class   = 'notice is-dismissible error';
		$message = __( 'You cannot have both the TH Client plugin and the TH Server plugin active on the same site. Please remove one, and install on connecting site, or install just the server plugin and activate the library function in advanced settings. Please see documentation for more info. <a https://docs.waashero.com> Click Here </a>', 'template-hero-elementor' );
		printf( '<div id="message" class="%s"> <p>%s</p></div>', $class, $message );
		deactivate_plugins( plugin_basename( __FILE__ ) );
	}

	return true;
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
function template_hero_elementor_client() {
	if ( !class_exists( '\\Elementor\\Plugin'  ) || class_exists( '\\TemplateHero\\Plugin'  ) ) {
		add_action( 'admin_notices', 'template_hero_elementor_client_ready' );
		if( is_network_admin() ) {
			add_action( 'network_admin_notices', 'template_hero_elementor_client_ready' );
		}

		return false;
	}
	
	$plugin = TemplateHero\Plugin_Client::getInstance();
	$plugin->run();
	$GLOBALS['Template_Hero_Elementor_Client'] = $plugin;
}

add_action( 
	'plugins_loaded', 
	'template_hero_elementor_client' 
);

/**
 * Modifies Deactivation message
 * @since 1.0.0
 * @param [string] $translated_text
 * @param [string] $untranslated_text
 * @param [string] $domain
 * @return void
 */
function client_deactivation_message( $translated_text, $untranslated_text, $domain ) {
    $old = [
        "Plugin <strong>activated</strong>.",
        "Selected plugins <strong>activated</strong>.",
        "Plugin activated.",
        "Selected plugins activated.",
    ];
    $new = "The Core is stable and the Plugin is <strong>deactivated</strong>";
    
    if ( ( ! class_exists( '\\Elementor\\Plugin' ) || class_exists( '\\TemplateHero\\Plugin'  ) )  && in_array( $untranslated_text , $old, true ) ) {
        $translated_text = $new;
        remove_filter( current_filter(), __FUNCTION__, 99 );
    }
    return $translated_text;
}
add_filter( 
    'gettext', 
    'client_deactivation_message' , 
    999, 
    3 
);
