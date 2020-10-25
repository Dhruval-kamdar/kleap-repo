<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              info@waashero.com
 * @since             1.0.0
 * @package           Wh_Ultimo_Widgets_Elementor
 *
 * @wordpress-plugin
 * Plugin Name:       WP-Ultimo Widgets for Elementor
 * Plugin URI:        https://waashero.com
 * Description:       Display WP Ultimo widgets in a custom admin area & front end pages using Elementor.
 * Version:           1.0.4
 * Author:            J. Hanlon | WaaS Hero
 * Author URI:        info@waashero.com
 * Text Domain:       wh-ultimo-widgets-elementor
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) || ! defined( 'ABSPATH' ) ) {
	die;
}
require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

/**
 * Plugin Define CONSTANTS.
 * 
 */
define( 'WH_ULTIMO_WIDGETS_VERSION', '1.0.4' );
define( 'WH_ULTIMO_TEXT_DOMAIN', 'wh-ultimo-widgets-elementor' );
define( 'WH_ULTIMO_WIDGETS_SLUG', 'wh-ultimo-widgets-elementor' );
define( 'WH_ULTIMO_WIDGETS_DIR', plugin_dir_path( __FILE__ ) );
define( 'WH_ULTIMO_WIDGETS_URL', plugins_url( '/', __FILE__ ) );
define( 'WH_ULTIMO_WIDGETS_FILE' , __FILE__ );
define( 'WH_ULTIMO_WIDGETS_STORE_URL', 'https://waashero.com' );
define( 'WH_ULTIMO_WIDGETS_ITEM_ID', 8687 ); 
define( 'WH_ULTIMO_WIDGETS_ITEM_NAME', 'WP Ultimo Widgets for Elementor' ); 
define( 'MINIMUM_PHP_VERSION', '6.0' ); 
define( 'MINIMUM_ELEMENTOR_VERSION', '2.5.11' ); 

register_activation_hook( __FILE__, [ 'Wh_Ultimo_Widgets_Elementor', 'activate_wh_ultimo_widgets_elementor'] );
register_deactivation_hook( __FILE__, [ 'Wh_Ultimo_Widgets_Elementor', 'deactivate_wh_ultimo_widgets_elementor'] );
final class Wh_Ultimo_Widgets_Elementor {
	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '2.5.11';

	/**
	 * Minimum PHP Version
	 *
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '6.0';

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @var Wh_Ultimo_Widget_Elementor The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return Wh_Ultimo_Widget_Elementor An instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {	
		add_action( 
			'init', 
			[ $this, 'i18n' ] 
		);

		add_action( 
			'template_redirect', 
			[ $this, "render_custom_domain_messages" ], 
			10 
		);
		$this->init();
	}

	/**
	 * Front end save custom domain
	 *
	 * @return void
	 */
	public function render_custom_domain_messages() {
		if ( isset( $_POST['wu-action-save-custom-domain'] ) ) {
			$domain_mapping = new WU_Domain_Mapping();
			$domain_mapping->save_domain_mapping();
		}
	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 *
	 * Fired by `init` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function i18n() {}

	/**
	 * Initialize the plugin
	 *
	 * Load the plugin only after Elementor (and other plugins) are loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed load the files required to run the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init() {
		// Add Plugin actions
		$this->init_plugin();
	}

	/**
	 * Fires on activation
	 *
	 * @return void
	 */
	public static function activate_wh_ultimo_widgets_elementor() {
	
		require WH_ULTIMO_WIDGETS_DIR . 'includes/class-wh-ultimo-widgets-elementor-activator.php';
	}

	/**
	 * Fires on deactivation
	 *
	 * @return void
	 */
	public static function deactivate_wh_ultimo_widgets_elementor() {

		require WH_ULTIMO_WIDGETS_DIR . 'includes/class-wh-ultimo-widgets-elementor-deactivator.php';
	}

	/**
	 * Init Widgets
	 *
	 * Include widgets files and register them
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init_plugin() {

		/**
		 * The core plugin class that is used to define internationalization,
		 * admin-specific hooks, and public-facing site hooks.
		 */
		require WH_ULTIMO_WIDGETS_DIR . 'includes/class-wh-ultimo-widgets-elementor.php';
		// Instantiate the Inner Plugin Class
		Wh_Elementor_Modules\Plugin::instance();

	}
}

/**
 * Checks for plugin dependencies
 *
 * @return void
 */
function check_for_dependencies() {
	if( !is_admin() || !is_network_admin() ) {
		return true;
	}
	// Check if WP Ultimo is installed and activated
	$notice = true;
	if( ! class_exists( 'WP_Ultimo' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ), true );
		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', WH_ULTIMO_TEXT_DOMAIN ),
			'<strong>' . esc_html__( 'WP Ultimo Widgets for Elementor', WH_ULTIMO_TEXT_DOMAIN ) . '</strong>',
			'<strong>' . esc_html__( 'WP Ultimo', WH_ULTIMO_TEXT_DOMAIN ) . '</strong>'
		);		

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
		
		$notice = false;
	}

	// Check if Elementor installed and activated
	if ( ! did_action( 'elementor/loaded' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ), true );
		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', WH_ULTIMO_TEXT_DOMAIN ),
			'<strong>' . esc_html__( 'WP Ultimo Widgets for Elementor', WH_ULTIMO_TEXT_DOMAIN ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', WH_ULTIMO_TEXT_DOMAIN ) . '</strong>'
		);		

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

		$notice = false;
	}

	// Check for required Elementor version			
	if ( did_action( 'elementor/loaded' ) && ! version_compare( ELEMENTOR_VERSION, MINIMUM_ELEMENTOR_VERSION, '>=' )  ) {
		deactivate_plugins( plugin_basename( __FILE__ ), true );
		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', WH_ULTIMO_TEXT_DOMAIN ),
			'<strong>' . esc_html__( 'WP Ultimo Widgets for Elementor', WH_ULTIMO_TEXT_DOMAIN ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', WH_ULTIMO_TEXT_DOMAIN ) . '</strong>',
			MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

		$notice = false;
	}

	// Check for required PHP version
	if ( version_compare( PHP_VERSION, MINIMUM_PHP_VERSION, '<' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ), true );
		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', WH_ULTIMO_TEXT_DOMAIN ),
			'<strong>' . esc_html__( 'WP Ultimo Widgets for Elementor', WH_ULTIMO_TEXT_DOMAIN ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', WH_ULTIMO_TEXT_DOMAIN ) . '</strong>',
			MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

		$notice = false;
	}

	return $notice;
}

/**
 * instantiate our starting class after checking for dependencies
 *
 * @return bool/instance
 */
function instantiate_main_class() {
	$return = check_for_dependencies();
	if( $return == false ) {
		return $return;
	}
	Wh_Ultimo_Widgets_Elementor::instance();
}
add_action( 'plugins_loaded', 'instantiate_main_class' );

/**
 * Translate the "Plugin activated." string
 *
 * @param [type] $translated_text
 * @param [type] $untranslated_text
 * @param [type] $domain
 *
 * @return string
 */

function activation_message( $translated_text, $untranslated_text, $domain ) {
	$old = [
		"Plugin <strong>activated</strong>.",
		"Selected plugins <strong>activated</strong>.",
		"Plugin activated.",
		"Selected plugins activated.",
	];

	$new = "The Core is stable and the Plugin is <strong>deactivated</strong>";
	
	if ( ( ! class_exists( 'WP_Ultimo' ) || version_compare( PHP_VERSION, MINIMUM_PHP_VERSION, '<' ) || ( ! did_action( 'elementor/loaded' ) ) ) && in_array( $untranslated_text , $old, true ) ) {
		$translated_text = $new;
		remove_filter( current_filter(), __FUNCTION__, 99 );
	}

	return $translated_text;
}

add_filter( 'gettext', 'activation_message' , 999, 3 );
