<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       www.waashero.com
 * @since      1.0.0
 *
 * @package    The_Wu_Integration
 * @subpackage The_Wu_Integration/includes
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
 * @package    The_Wu_Integration
 * @subpackage The_Wu_Integration/includes
 * @author     J Hanlon <info@waashero.com>
 */
namespace The_WP_Ultimo;
class The_Wu_Integration {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      The_Wu_Integration_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;
	
	private static $instance = null;

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
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'THE_WU_INTEGRATION_VERSION' ) ) {
			$this->version = THE_WU_INTEGRATION_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'the-wu-integration';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}
	// The object is created from within the class itself
	// only if the class has no instance.
	public static function getInstance() {
		if ( self::$instance == null ) {
			self::$instance = new The_Wu_Integration();
		}
	
		return self::$instance;
	}
	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - The_Wu_Integration_Loader. Orchestrates the hooks of the plugin.
	 * - The_Wu_Integration_i18n. Defines internationalization functionality.
	 * - The_Wu_Integration_Admin. Defines all hooks for the admin area.
	 * - The_Wu_Integration_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-the-wu-integration-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-the-wu-integration-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-the-wu-integration-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area options.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/settings/class-the-wu-integration-settings.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-the-wu-integration-public.php';

		/**
		 * The class responsible for defining updats
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-the-wu-integration-updater.php';
		$this->loader = new The_Wu_Integration\The_Wu_Integration_Loader();
		if( is_multisite() ) {
			$this->loader->add_filter( 'network_admin_plugin_action_links_'. THE_WU_INTEGRATION_BASE_DIR, $this, 'settings_link' );
		}
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the The_Wu_Integration_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new The_Wu_Integration\The_Wu_Integration_i18n();

		$this->loader->add_action( 
			'plugins_loaded', 
			$plugin_i18n, 
			'load_plugin_textdomain' 
		);

	}

		/**
	 * Add settings link on plugin page
	 *
	 * @return href
	 */
	public function settings_link( $links ) {
		$settings_link = '<a href="admin.php?page=the-wu-options">' . __( 'Settings',  'the-wu-integration' ) . '</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}


	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new The_Wu_Integration\The_Wu_Integration_Admin( $this->get_plugin_name(), $this->get_version() );
		$GLOBALS['THE_WU_OPTIONS'] = new The_Wu_Integration\Options();
		if ( is_multisite() ) {
			$this->loader->add_action( 
				'network_admin_menu', 
				$GLOBALS['THE_WU_OPTIONS'], 
				'the_wu_network_menu' ,
				10 
			);

			$this->loader->add_action( 
				'network_admin_notices', 
				$GLOBALS['THE_WU_OPTIONS'], 
				'the_wu_admin_notices'
			);
		}
		$this->loader->add_action( 
			'admin_notices', 
			$GLOBALS['THE_WU_OPTIONS'], 
			'the_wu_admin_notices'
		);
		
		$this->loader->add_action( 
			'admin_post_the_wu_admin_advance_settings', 
			$GLOBALS['THE_WU_OPTIONS'], 
			'the_wu_admin_advance_settings_save'
		);

		$this->loader->add_action( 
			'wp_ajax_the_wu_update_license_options', 
			$plugin_admin, 
			'the_wu_update_license_options'
		);

		$this->loader->add_filter ( 
			'admin_footer_text', 
			$GLOBALS['THE_WU_OPTIONS'], 
			'the_wu_remove_footer_admin' 
		);
		$this->loader->add_action( 
			'admin_enqueue_scripts', 
			$plugin_admin, 
			'enqueue_styles' 
		);

		$this->loader->add_action( 
			'admin_enqueue_scripts', 
			$plugin_admin, 
			'enqueue_scripts' 
		);

		$this->loader->add_action( 
			'load-plans_page_wu-edit-plan', 
			$plugin_admin, 
			'add_ultimo_libraries_metaboxes', 
			30 
		);

		$this->loader->add_action( 
			'load-toplevel_page_wp-ultimo-plans', 
			$plugin_admin, 
			'add_ultimo_libraries_metaboxes', 
			30 
		);

		$this->loader->add_filter( 
			'template_hero_elementor_change_activation_context', 
			$plugin_admin, 
			'the_wu_add_context', 
			10 
		);

		$this->loader->add_filter( 
			'template_hero_activate_library_button_content', 
			$plugin_admin, 
			'the_wu_add_button_content', 
			10, 
			5 
		);

		$this->loader->add_action( 
			'wp_ajax_the_wu_activateLibraryplan', 
			$plugin_admin, 
			'the_wu_activateLibraryplan' 
		);

		$this->loader->add_filter( 
			'the_activated_libraries', 
			$plugin_admin, 
			'the_wu_set_activated_libraries', 
			10, 
			2 
		);

		$this->loader->add_filter( 
			'the_libraries_ids_key', 
			$plugin_admin, 
			'the_wu_set_libraries_ids_key', 
			100, 
			1 
		);

		$this->loader->add_filter( 
			'the_libraries_names_key', 
			$plugin_admin, 
			'the_wu_set_libraries_names_key', 
			100, 
			1 
		);
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new The_Wu_Integration\The_Wu_Integration_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 
			'wp_enqueue_scripts', 
			$plugin_public, 
			'enqueue_styles' 
		);

		$this->loader->add_action( 
			'wp_enqueue_scripts', 
			$plugin_public, 
			'enqueue_scripts' 
		);

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
	 * @return    The_Wu_Integration_Loader    Orchestrates the hooks of the plugin.
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
