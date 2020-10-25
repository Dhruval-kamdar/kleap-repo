<?php
namespace ElementorExtensions\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Settings;
use ElementorExtensions\Includes\Modules_Manager;
use ElementorExtensions\Admin\EE_MB_Setting_Common;

class EE_MB_Admin extends EE_MB_Setting_Common{

	private static $widget_name_prefix = 'ee_mb_';
	private static $_instance;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function enqueue_styles() {
		
		wp_register_style(
			'es-mb-admin-setting',
			plugins_url( 'admin/assets/css/admin-setting.css', ELEMENTOR_EXTENSIONS__FILE__ ),
			[],
			ELEMENTOR_EXTENSIONS_VERSION
		);
		wp_enqueue_style( 'es-mb-admin-setting' );

		wp_register_style(
			'ee-mb-admin-metabox',
			plugins_url( 'admin/assets/css/admin-metabox.css', ELEMENTOR_EXTENSIONS__FILE__ ),
			[],
			ELEMENTOR_EXTENSIONS_VERSION
		);
		wp_enqueue_style( 'ee-mb-admin-metabox' );

		wp_register_style(
			'jquery-ui-style',
			plugins_url( 'admin/assets/css/lib/jquery-ui.min.css', ELEMENTOR_EXTENSIONS__FILE__ ),
			[],
			ELEMENTOR_EXTENSIONS_VERSION
		);
		wp_enqueue_style( 'jquery-ui-style' );

		wp_register_style(
			'jquery-timepicker-style',
			plugins_url( 'admin/assets/css/lib/jquery.timepicker.min.css', ELEMENTOR_EXTENSIONS__FILE__ ),
			[],
			ELEMENTOR_EXTENSIONS_VERSION
		);
		wp_enqueue_style( 'jquery-timepicker-style' );
	}

	public function enqueue_scripts() {

		wp_enqueue_style( 'wp-color-picker' );	
		wp_enqueue_script(
			'es-tab-script',
			plugins_url( 'admin/assets/js/settings.js', ELEMENTOR_EXTENSIONS__FILE__ ),
			array( 'wp-color-picker' ),
			ELEMENTOR_EXTENSIONS_VERSION,
			true
		);

		wp_enqueue_script('jquery-ui-datepicker');
	
		wp_enqueue_script(
			'jquery-timepicker-js',
			plugins_url( 'admin/assets/js/lib/jquery.timepicker.min.js', ELEMENTOR_EXTENSIONS__FILE__ ),
			array( 'jquery' ),
			ELEMENTOR_EXTENSIONS_VERSION,
			true
		);
	}

	public function register_page() {

		$slug = 'elementor-extensions';
		$capability = 'manage_options';

		add_submenu_page(
			Settings::PAGE_ID,
			__( 'Extensions', 'elementor-extensions' ),
			__( 'Extensions', 'elementor-extensions' ),
			$capability,
			$slug,
			[ $this, 'ee_mb_setting_view' ]
		);
	}

	public function ee_mb_setting_view() {

		$manager = new Modules_Manager();
		$prefix = self::$widget_name_prefix;

		$integration = self::get_settings_key('ee_mb_integration_setting');
		$checked_widget = get_option('ee_mb_hide_show_widgets');
		$cookie = stripslashes_deep(get_option('ee_mb_cookie_message'));
    	$modules = $manager->get_modules();

		require_once(ELEMENTOR_EXTENSIONS_PATH . 'admin/views/settings.php');
	}

	public function ee_mb_action_all_settings_tab(){
		$this->integration_setting_tab_saving();
		$this->sections_setting_tab_saving();
		$this->widget_setting_tab_saving();	
		$this->cookie_setting_tab_saving();	
	}

	public function integration_setting_tab_saving(){

		if(isset($_POST['btn_update_integration']) && $_SERVER['REQUEST_METHOD'] === 'POST' && wp_verify_nonce( $_POST['integration_settings_nounce'], 'integration_settings' )):

			$request = $_POST['ee_mb_integration_setting'];

			/*@ Sanitizing request */
			$new_req = [];
			if( !empty($request) ):
				foreach ($request as $key => $value) :

					if( $key === 'ee_mb_import_template_url' ):
						$new_req[$key] = esc_url($value);
					else:
						$new_req[$key] = sanitize_text_field($value);
					endif;
				endforeach;
			endif;

			$integration = $new_req;
			update_option( 'ee_mb_integration_setting', $integration );

			$integration_tab_url = $_POST['_wp_http_referer']."&saved=1#integration";
			wp_safe_redirect($integration_tab_url);
			die();
		endif;
	}

	public function sections_setting_tab_saving(){

		if(isset($_POST['btn_update_section_settings']) && $_SERVER['REQUEST_METHOD'] === 'POST' && wp_verify_nonce( $_POST['wp_section_nounce'], 'update_section_settings' )):
        
			$ee_mb_cpt_single = (!empty($_POST['ee_mb_cpt_single'])) ? EE_MB_Setting_Common::sanitize($_POST['ee_mb_cpt_single']) : '';

			$ee_mb_enable_post_types = $ee_mb_cpt_single;
			update_option('ee_mb_cpt_single',$ee_mb_enable_post_types);
	
			$section_tab_url = $_POST['_wp_http_referer']."&saved=1#section_settings";
			wp_safe_redirect($section_tab_url);
			die();
		endif;
	}

	public function widget_setting_tab_saving(){

		if(isset($_POST['btn_update_widget_settings']) && $_SERVER['REQUEST_METHOD'] === 'POST' && wp_verify_nonce( $_POST['wp_widget_nounce'], 'update_widget_settings' )):
        
			$ee_mb_hide_show_widgets = (!empty($_POST['ee_mb_hide_show_widgets'])) ? EE_MB_Setting_Common::sanitize($_POST['ee_mb_hide_show_widgets']) : '';
			$post_types = $ee_mb_hide_show_widgets;
			update_option('ee_mb_hide_show_widgets',$post_types);
		
			$widget_tab_url = $_POST['_wp_http_referer']."&saved=1#widget_settings";
			wp_safe_redirect($widget_tab_url);
			die();
		endif;
	}


	public function cookie_setting_tab_saving(){

		if(isset($_POST['btn_update_cookie_settings']) && $_SERVER['REQUEST_METHOD'] === 'POST' && wp_verify_nonce( $_POST['elementor_extensions_cookie_notice_nounce'], 'elementor_extensions_cookie_notice' )):
        
			$cookie_post = (!empty($_POST['cookie'])) ? EE_MB_Setting_Common::sanitize($_POST['cookie']) : '';
			
			$cookie = $cookie_post;
			update_option('ee_mb_cookie_message',$cookie);
		
			$widget_tab_url = $_POST['_wp_http_referer']."&saved=1#cookie_notice";
			wp_safe_redirect($widget_tab_url);
			die();
		endif;
	}

	public function __construct(){
		add_action( 'admin_post_elementor_extensions_settings', [ $this, 'ee_mb_action_all_settings_tab' ] );		
		add_action( 'admin_menu', [ $this, 'register_page' ], 800 );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}
}
