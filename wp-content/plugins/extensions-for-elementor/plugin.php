<?php
namespace ElementorExtensions;

if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Controls_Manager;
use ElementorExtensions\Admin\EE_MB_Admin;
use ElementorExtensions\Classes\EE_MB_Front;
use ElementorExtensions\Classes\Cpt_Generator;
use ElementorExtensions\Classes\EE_MB_Wordpress_Metabox;
use ElementorExtensions\Classes\EE_MB_Event_Metabox;
use ElementorExtensions\Admin\EE_MB_Property_Single;
use ElementorExtensions\Admin\EE_MB_Setting_Common;
use ElementorExtensions\Includes\Modules_Manager;
use ElementorExtensions\Includes\EE_MB_Controls_Manager;
use ElementorExtensions\Includes\Templates\EE_MB_Templates;
use ElementorExtensions\Includes\EE_MB_Run_On_Fly;

class Plugin {

	private static $_instance;
	private $_modules_manager;
	private $_control_manager;
	private $_localize_settings = [];
	private $prefix = 'ee-mb-';

	public function get_version() {
		return ELEMENTOR_EXTENSIONS_VERSION;
	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __clone() {
		/* Cloning instances of the class is forbidden */
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'elementor-extensions' ), '1.0.0' );
	}

	public function __wakeup() {
		/* Unserializing instances of the class is forbidden - Disable unserializing of the class */
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'elementor-extensions' ), '1.0.0' );
	}

	public static function elementor() {
		return \Elementor\Plugin::$instance;
	}

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	private function _includes() {
		EE_MB_Controls_Manager::instance();
		if ( is_admin() ) {
			EE_MB_Admin::instance();
			Cpt_Generator::get_instance();
			include_once ELEMENTOR_EXTENSIONS_PATH.'classes/ee-mb-wordpress-metabox.php';
		}		
		EE_MB_Run_On_Fly::instance();
		EE_MB_Front::instance();
		EE_MB_Templates::instance();
		EE_MB_Property_Single::instance();
	}

	public function autoload( $class ) {
		if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
			return;
		}

		$filename = strtolower(
			preg_replace(
				[ '/^' . __NAMESPACE__ . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
				[ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
				$class
			)
		);
		$filename = ELEMENTOR_EXTENSIONS_PATH . $filename . '.php';

		if ( is_readable( $filename ) ) {
			include( $filename );
		}
	}

	public function get_localize_settings() {
		return $this->_localize_settings;
	}

	public function add_localize_settings( $setting_key, $setting_value = null ) {
		if ( is_array( $setting_key ) ) {
			$this->_localize_settings = array_replace_recursive( $this->_localize_settings, $setting_key );

			return;
		}

		if ( ! is_array( $setting_value ) || ! isset( $this->_localize_settings[ $setting_key ] ) || ! is_array( $this->_localize_settings[ $setting_key ] ) ) {
			$this->_localize_settings[ $setting_key ] = $setting_value;

			return;
		}

		$this->_localize_settings[ $setting_key ] = array_replace_recursive( $this->_localize_settings[ $setting_key ], $setting_value );
	}

	public function enqueue_styles() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		$direction_suffix = is_rtl() ? '-rtl' : '';

		$prefix = $this->prefix;

		wp_enqueue_style( 'font-awesome' );

		wp_enqueue_style(
			$prefix.'fullcalendar',
			ELEMENTOR_EXTENSIONS_URL . 'assets/lib/fullcalendar/fullcalendar' . $direction_suffix . $suffix . '.css',
			[],
			Plugin::instance()->get_version()
		);

		wp_enqueue_style(
			$prefix.'daygrid',
			ELEMENTOR_EXTENSIONS_URL . 'assets/lib/fullcalendar/daygrid/main' . $direction_suffix . $suffix . '.css',
			[],
			Plugin::instance()->get_version()
		);

		wp_enqueue_style(
			$prefix.'list',
			ELEMENTOR_EXTENSIONS_URL . 'assets/lib/fullcalendar/list/main' . $direction_suffix . $suffix . '.css',
			[],
			Plugin::instance()->get_version()
		);

		wp_enqueue_style(
			$prefix.'timegrid',
			ELEMENTOR_EXTENSIONS_URL . 'assets/lib/fullcalendar/timegrid/main' . $direction_suffix . $suffix . '.css',
			[],
			Plugin::instance()->get_version()
		);

		wp_enqueue_style(
			$prefix.'hamburgers',
			ELEMENTOR_EXTENSIONS_URL . 'assets/lib/hamburger/hamburgers' . $direction_suffix . $suffix . '.css',
			[],
			Plugin::instance()->get_version()
		);

		wp_enqueue_style(
            $prefix.'property-page',
            ELEMENTOR_EXTENSIONS_URL . 'assets/css/property-page.css',
            [],
            Plugin::instance()->get_version()
        );

		wp_enqueue_style(
			'elementor-extensions',
			ELEMENTOR_EXTENSIONS_URL . 'assets/css/frontend' . $direction_suffix . $suffix . '.css',
			[],
			Plugin::instance()->get_version()
		);
	}

	public function enqueue_editor_styles() {

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$prefix = $this->prefix;

		wp_enqueue_style( 'font-awesome' );
		
		wp_enqueue_style(
			$prefix.'editor-css',
			ELEMENTOR_EXTENSIONS_URL . 'assets/css/editor' . $suffix . '.css',
			[],
			Plugin::instance()->get_version()
		);
	}

	public function enqueue_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$prefix = $this->prefix;

		wp_enqueue_script(
			$prefix.'anchor-scroll',
			ELEMENTOR_EXTENSIONS_URL . 'assets/lib/anchor-scroll/anchor-scroll' . $suffix . '.js',
			[
				'jquery',
			],
			Plugin::instance()->get_version(),
			true
		);

		$gmap_key = EE_MB_Setting_Common::get_settings_key('ee_mb_integration_setting', 'ee_mb_google_map_key');

		$map_url = 'https://maps.googleapis.com/maps/api/js?sensor=false&libraries=places';
		if(!empty((array)$gmap_key)):
			$map_url = 'https://maps.googleapis.com/maps/api/js?key='.$gmap_key.'&libraries=places';
		endif;
		
		wp_enqueue_script(
			$prefix.'googlemap-api',
			$map_url,
			[ 
				'jquery', 
			],
			Plugin::instance()->get_version(),
			true 
		);
		
		wp_enqueue_script(
			$prefix.'gmap3',
			ELEMENTOR_EXTENSIONS_URL . 'assets/lib/gmap3/gmap3' . $suffix . '.js',
			[ 
				'jquery',
			],
			Plugin::instance()->get_version(),
			true 
		);

		wp_enqueue_script(
			$prefix.'jquery-resize',
			ELEMENTOR_EXTENSIONS_URL . 'assets/lib/jquery-resize/jquery.resize' . $suffix . '.js',
			[
				'jquery',
			],
			Plugin::instance()->get_version(),
			true
		);

		wp_enqueue_script(
			$prefix.'jquery-visible',
			ELEMENTOR_EXTENSIONS_URL . 'assets/lib/jquery-visible/jquery.visible' . $suffix . '.js',
			[
				'jquery',
			],
			Plugin::instance()->get_version(),
			true
		);

		wp_enqueue_script(
			$prefix.'table-sorter',
			ELEMENTOR_EXTENSIONS_URL . 'assets/lib/tablesorter/jquery.tablesorter' . $suffix . '.js',
			[
				'jquery',
			],
			Plugin::instance()->get_version(),
			true
		);

		wp_enqueue_script('moment');

		wp_enqueue_script(
			$prefix.'fullcalendar',
			ELEMENTOR_EXTENSIONS_URL . 'assets/lib/fullcalendar/fullcalendar' . $suffix . '.js',
			[
				'jquery',
			],
			Plugin::instance()->get_version(),
			true
		);

		wp_enqueue_script(
			$prefix.'daygrid',
			ELEMENTOR_EXTENSIONS_URL . 'assets/lib/fullcalendar/daygrid/main' . $suffix . '.js',
			[
				'jquery',
			],
			Plugin::instance()->get_version(),
			true
		);

		wp_enqueue_script(
			$prefix.'list',
			ELEMENTOR_EXTENSIONS_URL . 'assets/lib/fullcalendar/list/main' . $suffix . '.js',
			[
				'jquery',
			],
			Plugin::instance()->get_version(),
			true
		);

		wp_enqueue_script(
			$prefix.'interaction',
			ELEMENTOR_EXTENSIONS_URL . 'assets/lib/fullcalendar/interaction/main' . $suffix . '.js',
			[
				'jquery',
			],
			Plugin::instance()->get_version(),
			true
		);

		wp_enqueue_script(
			$prefix.'timegrid',
			ELEMENTOR_EXTENSIONS_URL . 'assets/lib/fullcalendar/timegrid/main' . $suffix . '.js',
			[
				'jquery',
			],
			Plugin::instance()->get_version(),
			true
		);

		wp_enqueue_script(
			$prefix.'gcal',
			ELEMENTOR_EXTENSIONS_URL . 'assets/lib/fullcalendar/google-calendar/main' . $suffix . '.js',
			[
				'jquery',
			],
			Plugin::instance()->get_version(),
			true
		);

		wp_enqueue_script(
			'elementor-extensions-js',
			ELEMENTOR_EXTENSIONS_URL . 'assets/js/frontend' . $suffix . '.js',
			[
				'jquery',
			],
			Plugin::instance()->get_version(),
			true
		);

		wp_localize_script(
			'elementor-extensions-js',
			'ElementorExtensionsFrontendConfig',
			[
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce( 'elementor-extensions-js' ),
				'ee_mb_path' => ELEMENTOR_EXTENSIONS_URL,
			]
		);	
	}

	public function enqueue_editor_scripts() {

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$prefix = $this->prefix;

		wp_enqueue_script(
			$prefix.'editor-js',
			ELEMENTOR_EXTENSIONS_URL . 'assets/js/editor' . $suffix . '.js',
			[
				'jquery',
			],
			Plugin::instance()->get_version(),
			true
		);

		wp_localize_script(
			'ee-mb-editor-js',
			'ElementorExtensionsFrontendConfig',
			[
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce( 'elementor-extensions-js' ),
				'ee_mb_path' => ELEMENTOR_EXTENSIONS_URL,
			]
		);	
	}

	public function enqueue_panel_scripts() {}

	public function enqueue_panel_styles() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	}

	public function elementor_init() {
		$this->_modules_manager = new Modules_Manager();

		\Elementor\Plugin::instance()->elements_manager->add_category(
			'elementor-extensions', /* This is the name of your addon's category and will be used to group your widgets/elements in the Edit sidebar pane! */
			[
				'title' => __( 'Elementor Extensions', 'elementor-extensions' ),
				'icon' => 'font',
			],
			1
		);
	}

	protected function add_actions() {
		add_action( 'elementor/init', [ $this, 'elementor_init' ] );

		add_action( 'elementor/frontend/before_enqueue_scripts', [ $this, 'enqueue_scripts' ], 998 );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ], 998 );
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'enqueue_editor_scripts' ], 998 );
		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'enqueue_editor_styles' ], 998 );
	}

	private function __construct() {
		spl_autoload_register( [ $this, 'autoload' ] );
		$this->_includes();
		$this->add_actions();
	}	
}

if ( ! defined( 'ELEMENTOR_EXTENSIONS_TESTS' ) ) {
	Plugin::instance();
}
