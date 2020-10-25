<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       info@waashero.com
 * @since      1.0.0
 *
 * @package    Wh_Ultimo_Widgets_Elementor
 * @subpackage Wh_Ultimo_Widgets_Elementor/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wh_Ultimo_Widgets_Elementor
 * @subpackage Wh_Ultimo_Widgets_Elementor/includes
 * @author     J. Hanlon | WaaS Hero <info@waashero.com>
 */
namespace Wh_Elementor_Modules;

use \Elementor\Plugin as Widget_Manager;

class Plugin {

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 *
	 * @var Plugin The single instance of the class.
	 */
	private static $_instance = null;
	
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * The current ultimo user subscription.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $version    The current users Ultimo Sub.
	 */
	static private $subscription;


	/**
	 * The current ultimo user subscription active or not.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      boolean    $version    The current users Ultimo Sub plan active boolean.
	 */
	static private $is_active_subscription;


   /**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		
		return self::$_instance;
	}

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->version     = WH_ULTIMO_WIDGETS_VERSION;
		$this->plugin_name = WH_ULTIMO_WIDGETS_SLUG;
		$this->load_dependencies();
		$this->set_locale();
		
		if( is_user_logged_in() ):
		//register new elementor catagory
		add_action( 'elementor/elements/categories_registered', [ $this, 'add_elementor_widget_categories'] );
		//load Elementor Widgets
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'load_widgets' ] );
		endif;

	}
	

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Loader. Orchestrates the hooks of the plugin.
	 * - Wh_Ultimo_Widgets_Elementor_i18n. Defines internationalization functionality.
	 * - Updater. Defines all hooks for the plugins update methods.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once WH_ULTIMO_WIDGETS_DIR . 'includes/class-wh-ultimo-widgets-elementor-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once WH_ULTIMO_WIDGETS_DIR . 'includes/class-wh-ultimo-widgets-elementor-i18n.php';


		/**
		 * The functions responsible for loading the admin menu and definging the update actions.
		 */
		require_once WH_ULTIMO_WIDGETS_DIR . 'includes/wh-ultimo-widgets-elementor-updater.php';


		$this->loader = new Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wh_Ultimo_Widgets_Elementor_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wh_Ultimo_Widgets_Elementor_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Include Widgets files
	 *
	 * Load widgets files
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function include_widget_files() {
		
		require_once WH_ULTIMO_WIDGETS_DIR . 'widgets/class-widget-choose-plan.php';
		require_once WH_ULTIMO_WIDGETS_DIR . 'widgets/class-widget-plan-actions.php';
		require_once WH_ULTIMO_WIDGETS_DIR . 'widgets/class-widget-account-statistics.php';
		require_once WH_ULTIMO_WIDGETS_DIR . 'widgets/class-widget-account-status.php';
		require_once WH_ULTIMO_WIDGETS_DIR . 'widgets/class-widget-custom-domain.php';
		require_once WH_ULTIMO_WIDGETS_DIR . 'widgets/class-widget-limits-quotas.php';
	}

	/**
	 * Load the widgets and dependancies.
	 *
	 * @since    1.0.0
	 */
	public function load_widgets() { 

		// Include Widget Files
		$this->include_widget_files();
		
		// Register Global Widget Styles
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'widget_styles' ] );
		add_action( 'elementor/preview/enqueue_styles', [ $this, 'widget_styles' ] );
		
		//Register Popup script
		add_action( 'wp_head', [ $this, 'popup_script' ] );
		
		// Register Widgets
		Widget_Manager::instance()->widgets_manager->register_widget_type( new Ultimo_Widgets\Choose_Plan( ) );
		Widget_Manager::instance()->widgets_manager->register_widget_type( new Ultimo_Widgets\Plan_Actions( ) );
		Widget_Manager::instance()->widgets_manager->register_widget_type( new Ultimo_Widgets\Account_Stats( ) );
		Widget_Manager::instance()->widgets_manager->register_widget_type( new Ultimo_Widgets\Account_Status( ) );
		Widget_Manager::instance()->widgets_manager->register_widget_type( new Ultimo_Widgets\Custom_Domain( ) );
		Widget_Manager::instance()->widgets_manager->register_widget_type( new Ultimo_Widgets\Limits_Quotas( ) );

	}

	/**
	 * Include Widgets style file
	 *
	 * Load widgets styles
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function widget_styles() {
		wp_enqueue_style( 'waashero-widget-style', WH_ULTIMO_WIDGETS_URL . 'assets/css/widget-style.css',array(), WH_ULTIMO_WIDGETS_VERSION, 'all' );
		wp_enqueue_script( 'waashero-widget-script', 'https://cdn.jsdelivr.net/npm/sweetalert2@9', array('jquery'), WH_ULTIMO_WIDGETS_VERSION, false );
	}
	
	/**
	 * Register popup scripts
	 *
	 * Load  sweetalert2 script
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function popup_script() {
		wp_enqueue_script( 'waashero-widget-script', 'https://cdn.jsdelivr.net/npm/sweetalert2@9', array('jquery'), WH_ULTIMO_WIDGETS_VERSION, false );
	}

	public function add_elementor_widget_categories( $elements_manager ) {

		$elements_manager->add_category(
			'wp-ultimo',
			[
				'title' => __( 'WP Ultimo', $this->get_plugin_name() ),
				'icon' => 'fa fa-plug',
			]
		);
	
	}

	/**
	 * Get the logged in users WP Ultimo sub and temp cache if not in cache.
	 *
	 * @since     1.0.0
	 * @return    string    The current users WP Ultimo subscription.
	 */
	public static function get_subscription() {

		if ( false === ( $subscription = wp_cache_get( 'wh_wu_subscription' ) ) ) {
			$subscription = wu_get_current_site()->get_subscription();
			wp_cache_set( 'wh_wu_subscription', $subscription );
		}
		return $subscription;

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
