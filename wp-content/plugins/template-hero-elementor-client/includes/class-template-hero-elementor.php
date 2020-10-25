<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://waashero.com
 * @since      1.0.0
 *
 * @package    Template_Hero_Elementor_Client
 * @subpackage Template_Hero_Elementor/includes
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
 * @package    Template_Hero_Elementor_Client
 * @subpackage Template_Hero_Elementor_Client/includes
 * @author     J Hanlon | Waas Hero <info@waashero.com>
 */

namespace TemplateHero;
use Elementor\Controls_Stack;
use Elementor\Element_Base;
use Elementor\Plugin as plugin;
use Elementor\Core\Files\CSS\Post;
use TemplateHero\Plugin_Client\TemplatesType as Documents;
class Plugin_Client {

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
	
	private static $instance = null;
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
		if ( defined( 'TEMPLATE_HERO_ELEMENTOR_VERSION' ) ) {
			$this->version = TEMPLATE_HERO_ELEMENTOR_VERSION;
		} else {
			$this->version = '1.2.4';
		}
		$this->plugin_name = 'template-hero-elementor-client';

		$this->setup_constants();
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_api_hooks();
		
	}

	// The object is created from within the class itself
	// only if the class has no instance.
	public static function getInstance() {
		if ( self::$instance == null ) {
			self::$instance = new Plugin_Client();
		}
	
		return self::$instance;
	}		

	/**
	 * Setup constants for the plugin
	 * @since 1.0.0
	 * @return void
	 */
	private function setup_constants() {
		/**
		 * Plugin Text Domain
		 */
		define( 'TEMPLATE_HERO_ELEMENTOR_CLIENT_TEXT_DOMAIN', 'template-hero-elementor');
		define( 'TEMPLATE_HERO_ELEMENTOR_STORE_URL', 'https://waashero.com' );
		define( 'TEMPLATE_HERO_ELEMENTOR_ITEM_ID', 9044 ); 
		define( 'TEMPLATE_HERO_ELEMENTOR_ITEM_NAME', 'Template Hero Client For Elementor' ); 
		/**
		 * Plugin Version
		 */
		define( 'TEMPLATE_HERO_ELEMENTOR_VERSION', '1.2.4' );
		/**
		 * Template Hero DB Version
		 */
		define( 'TEMPLATE_HERO_ELEMENTOR_DATABASE_VERSION', '1.0.1' );
		/**
		 * Template Library Version
		 */
		define( 'TEMPLATE_HERO_ELEMENTOR_LIBRARY_VERSION', '1.0.1' );

		$lv_option_name = '_transient_waashero_remote_library_version';

		//save as option
        $library_version = get_option( $lv_option_name );
        if ( !isset( $library_version ) || empty( $library_version ) ) {
           update_option( $lv_option_name, TEMPLATE_HERO_ELEMENTOR_LIBRARY_VERSION );
		}

		/**
		 * Plugin API Install Timestamp
		 */
		define( 'TEMPLATE_HERO_ELEMENTOR_INSTALL_TIMESTAMP', get_option( '_waashero_elementor_installed_time', time()) );
		
		/**
		 * Plugin Directory
		 */
		if( !defined( 'TEMPLATE_HERO_ELEMENTOR_CLIENT_DIR' ) ) {
			
			define( 'TEMPLATE_HERO_ELEMENTOR_CLIENT_DIR', plugin_dir_path( __FILE__ ) );
		}
		// define( 'TEMPLATE_HERO_ELEMENTOR_FILE', __FILE__ );
		define( 'TEMPLATE_HERO_ELEMENTOR_INCLUDES_DIR', trailingslashit( TEMPLATE_HERO_ELEMENTOR_CLIENT_DIR . 'includes' ) );
		define( 'TEMPLATE_HERO_ELEMENTOR_INCLUDES_DIR_ADMIN', trailingslashit( TEMPLATE_HERO_ELEMENTOR_CLIENT_DIR . 'admin' ) );
		define( 'TEMPLATE_HERO_ELEMENTOR_INCLUDES_DIR_PUBLIC', trailingslashit( TEMPLATE_HERO_ELEMENTOR_CLIENT_DIR . 'public' ) );
		define( 'TEMPLATE_HERO_ELEMENTOR_TEMPLATES_DIR', trailingslashit( TEMPLATE_HERO_ELEMENTOR_CLIENT_DIR . 'templates' ) );
		if( ! defined( 'TEMPLATE_HERO_ELEMENTOR_BASE_DIR' ) ) {
			define( 'TEMPLATE_HERO_ELEMENTOR_BASE_DIR', plugin_basename( __FILE__ ) );
		}

		/**
		 * Plugin URLS
		 */
		define( 'TEMPLATE_HERO_ELEMENTOR_SITE_URL', trailingslashit( get_site_url() ) );
		define( 'TEMPLATE_HERO_ELEMENTOR_URL', trailingslashit( plugins_url( '', __DIR__ ) ) );
		define( 'TEMPLATE_HERO_ELEMENTOR_LIB_TEMP_URL', trailingslashit( trailingslashit( TEMPLATE_HERO_ELEMENTOR_URL . 'library/templates' ) ) );
		define( 'TEMPLATE_HERO_ELEMENTOR_ASSETS_URL', trailingslashit( TEMPLATE_HERO_ELEMENTOR_URL . 'assets' ) );
		define( 'TEMPLATE_HERO_ELEMENTOR_ASSETS_URL_ADMIN', trailingslashit( TEMPLATE_HERO_ELEMENTOR_URL . 'admin/assets' ) );
		define( 'TEMPLATE_HERO_ELEMENTOR_ASSETS_URL_PUBLIC', trailingslashit( TEMPLATE_HERO_ELEMENTOR_URL . 'public/assets' ) );

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Template_Hero_Elementor_Loader. Orchestrates the hooks of the plugin.
	 * - Template_Hero_Elementor_i18n. Defines internationalization functionality.
	 * - Template_Hero_Elementor_Admin. Defines all hooks for the admin area.
	 * - Template_Hero_Elementor_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
         * Load dependecies managed by composer.
         */
		if ( file_exists(  TEMPLATE_HERO_ELEMENTOR_INCLUDES_DIR . 'vendor/autoload.php'  ) && !class_exists( 'ComposerAutoloaderInitbeffdb0f9850edce14b645fff1d09f21' ) ) {
			require_once TEMPLATE_HERO_ELEMENTOR_INCLUDES_DIR . 'vendor/autoload.php';
		}

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		if ( file_exists(  TEMPLATE_HERO_ELEMENTOR_INCLUDES_DIR . 'class-template-hero-elementor-loader.php' ) ) {
			require_once TEMPLATE_HERO_ELEMENTOR_INCLUDES_DIR . 'class-template-hero-elementor-loader.php';
		}

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		if ( file_exists(  TEMPLATE_HERO_ELEMENTOR_INCLUDES_DIR . 'class-template-hero-elementor-i18n.php' ) ) {
			require_once TEMPLATE_HERO_ELEMENTOR_INCLUDES_DIR . 'class-template-hero-elementor-i18n.php';
		}

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		if ( file_exists(  TEMPLATE_HERO_ELEMENTOR_INCLUDES_DIR_ADMIN . 'class-template-hero-elementor-admin.php' ) ) {
			require_once TEMPLATE_HERO_ELEMENTOR_INCLUDES_DIR_ADMIN . 'class-template-hero-elementor-admin.php';
		}


		/**
		 * The class responsible for defining all api jwt token actions.
		 */
		if ( file_exists(  TEMPLATE_HERO_ELEMENTOR_INCLUDES_DIR . 'api/class-template-hero-tokens.php' ) ) {
			require_once TEMPLATE_HERO_ELEMENTOR_INCLUDES_DIR . 'api/class-template-hero-tokens.php';
		}

		/**
		 * The class responsible for plugin settings tabs and options .
		 * 
		 */
		if ( file_exists( TEMPLATE_HERO_ELEMENTOR_INCLUDES_DIR_ADMIN . 'settings/class-template-hero-elementor-options.php' ) ) {

			require_once TEMPLATE_HERO_ELEMENTOR_INCLUDES_DIR_ADMIN . 'settings/class-template-hero-elementor-options.php';
		}

		/**
		 * The functions responsible for loading the admin menu and definging the update actions.
		 */
		if ( file_exists( TEMPLATE_HERO_ELEMENTOR_INCLUDES_DIR . 'template-hero-for-elementor-updater.php' ) ) {

			require_once TEMPLATE_HERO_ELEMENTOR_INCLUDES_DIR . 'template-hero-for-elementor-updater.php' ;
		}

		/**
		 * The functions responsible for loading the admin menu and definging the update actions.
		 */
		if ( file_exists( TEMPLATE_HERO_ELEMENTOR_INCLUDES_DIR . 'template-hero-functions.php' ) ) {

			require_once TEMPLATE_HERO_ELEMENTOR_INCLUDES_DIR . 'template-hero-functions.php' ;
		}

		/**
		 * The class responsible for defining all api client actions.
		 */
		if ( file_exists(  TEMPLATE_HERO_ELEMENTOR_INCLUDES_DIR . 'api/class-template-hero-client.php' ) ) {
			require_once TEMPLATE_HERO_ELEMENTOR_INCLUDES_DIR . 'api/class-template-hero-client.php';
		}

		/**
		 * The functions responsible for maintaining logs.
		 */
		if ( file_exists( TEMPLATE_HERO_ELEMENTOR_INCLUDES_DIR . 'logs/class-template-hero-logs.php' ) ) {

			require_once TEMPLATE_HERO_ELEMENTOR_INCLUDES_DIR . 'logs/class-template-hero-logs.php'  ;
		}

		$this->loader = new Plugin_Client\Loader();
		if( !is_network_admin() ) {
			$this->loader->add_filter( 'plugin_action_links_'. TEMPLATE_HERO_ELEMENTOR_CLIENT_BASE_DIR, $this, 'settings_link' );
		} else {
			$this->loader->add_filter( 'network_admin_plugin_action_links_'. TEMPLATE_HERO_ELEMENTOR_CLIENT_BASE_DIR, $this, 'settings_link' );
		}
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Template_Hero_Elementor_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Plugin_Client\Elementor_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		
		$GLOBALS['Template_Hero_Elementor_Options'] = new Plugin_Client\Options();
		$this->loader->add_filter ( 
			'admin_footer_text', 
			$GLOBALS['Template_Hero_Elementor_Options'], 
			'template_hero_elementor_remove_footer_admin' 
		);
		
        if ( is_multisite() ) {
			$this->loader->add_action( 
				'network_admin_menu', 
				$GLOBALS['Template_Hero_Elementor_Options'], 
				'template_hero_elementor_mu_menu' ,
				220 
			);

			$this->loader->add_action( 
				'network_admin_notices', 
				$GLOBALS['Template_Hero_Elementor_Options'], 
				'template_hero_elementor_admin_notices'
			);
        } 
		$this->loader->add_action( 
			'admin_menu', 
			$GLOBALS['Template_Hero_Elementor_Options'], 
			'template_hero_elementor_menu', 
			220 
		);

		$this->loader->add_action( 
			'admin_notices', 
			$GLOBALS['Template_Hero_Elementor_Options'], 
			'template_hero_elementor_admin_notices'
		);

		$this->loader->add_action( 
			'elementor/init', 
			$this, 
			'register_template_hero_rest_source_class', 
			10000
		);

		
		$this->loader->add_action( 
			'elementor/editor/init', 
			$this, 
			'add_template', 
			10
		);

		
		
		$plugin_admin = new Plugin_Client\Admin( $this->get_plugin_name(), $this->get_version() );

		if( is_admin() || is_network_admin() ):
			//load backend and any frontend ajax hooks

			$this->loader->add_action( 
				'wp_ajax_th_update_license_options', 
				$plugin_admin, 
				'th_update_license_options' 
			);

			$this->loader->add_action( 
				'wp_print_scripts', 
				$plugin_admin, 
				'th_docs_dequeue_script',
				100 
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
				'elementor/editor/after_enqueue_scripts', 
				$plugin_admin, 
				'th_docs_dequeue_script' 
			);

			$this->loader->add_filter( 
				'elementor/editor/localize_settings', 
				$plugin_admin, 
				'th_localize_setting',
				1000,
				1
			);
		
		else:

		endif;
		
	}

	/**
	 * Pushes our custom template
	 * @since 1.2.1
	 * @return void
	 */
	public function add_template() {
		
		$template_name = 'templates';
		Plugin::$instance->common->add_template( TEMPLATE_HERO_ELEMENTOR_INCLUDES_DIR_ADMIN. "settings/templates/$template_name.php" );
	}

	/**
	 * Register all of the hooks related to the public-facing rest api functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_api_hooks() {

		$plugin_apitokens = new Plugin_Client\Api\Tokens( $this->get_plugin_name(), $this->get_version() );
		$plugin_logs      = new Plugin_Client\Logs\Template_Hero_Logger();
		$settings         = new Plugin_Client\Options();
		$this->loader->add_action( 
			'admin_post_template_hero_elementor_admin_settings', 
			$plugin_apitokens, 
			'template_hero_elementor_admin_settings_save' 
		);
		if( is_admin() || is_network_admin() ):
		//load backend and frontend hooks
		$this->loader->add_action( 
			'admin_enqueue_scripts', 
			$plugin_apitokens, 
			'enqueue_token_scripts' 
		);

		$this->loader->add_action( 
			'wp_ajax_refreshJwtToken', 
			$plugin_apitokens, 
			'refreshJwtToken' 
		);

		$this->loader->add_action( 
			'wp_ajax_the_activate_library', 
			$plugin_apitokens, 
			'the_activate_library' 
		);

		$this->loader->add_action( 
			'wp_ajax_removeTokenTransient', 
			$plugin_apitokens, 
			'removeTokenTransient'
		);
		
		$this->loader->add_action( 
			'admin_post_template_hero_elementor_admin_logs_settings', 
			$plugin_logs, 
			'template_hero_elementor_admin_logs_settings_save'
		);

		$this->loader->add_action( 
			'admin_post_template_hero_elementor_admin_library_settings', 
			$plugin_apitokens, 
			'template_hero_elementor_admin_library_settings_save'
		);

		$this->loader->add_action( 
			'admin_post_template_hero_elementor_admin_advance_settings', 
			$settings, 
			'template_hero_elementor_admin_advance_settings_save'
		);
		else:

		endif;
		$plugin_apiclient = new Plugin_Client\Api\Client( $this->get_plugin_name(), $this->get_version() );

		if( is_admin() || is_network_admin() ):
		//load backend and frontend hooks

		$this->loader->add_action( 
			'wp_ajax_thDeleteClientLibrary', 
			$plugin_apiclient, 
			'thDeleteClientLibrary' 
		);

		$this->loader->add_action( 
			'wp_ajax_thGetClientLibrarySecret', 
			$plugin_apiclient, 
			'thGetClientLibrarySecret' 
		);

		$this->loader->add_action( 
			'wp_ajax_template_hero_sync_library', 
			$settings, 
			'template_hero_sync_library' 
		);

		else:

		endif;
	}

	/**
	 * register_template_hero_rest_source_class with elementor
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function register_template_hero_rest_source_class() {
			
		if( file_exists( TEMPLATE_HERO_ELEMENTOR_INCLUDES_DIR . 'class-template-hero-elementor-remote-source.php' ) && is_readable( TEMPLATE_HERO_ELEMENTOR_INCLUDES_DIR . 'class-template-hero-elementor-remote-source.php' ) && include( "class-template-hero-elementor-remote-source.php" ) ) {	
			//Unregister source with closure binding
			$unregister_source = function( $id ) {
				unset( $this->_registered_sources[ $id ] );
			};

            if( ELEMENTOR_VERSION >= 3 ) {
                $unregister_source->call( \Elementor\Plugin::instance()->templates_manager, 'remote');
            } else {
                \Elementor\Plugin::instance()->templates_manager->register_source( 'Elementor\TemplateLibrary\Template_Hero_Remote_Source' );
            }
			// \Elementor\Plugin::instance()->templates_manager->register_source( 'Elementor\TemplateLibrary\Template_Hero_Remote_Source' );
			$elementor = plugin::instance();
			$elementor->templates_manager->register_source( 'Elementor\TemplateLibrary\Template_Hero_Remote_Source' );
		}
	
	}

	/**
	 * Add settings link on plugin page
	 * @since  1.0.0
	 *
	 * @return href
	 */
	public function settings_link( $links ) {
		$settings_link = '<a href="admin.php?page=template-hero-elementor-options">' . __( 'Settings', 'template-hero-elementor') . '</a>';
		array_unshift( $links, $settings_link );
		return $links;
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
