<?php
namespace ElementorExtensions\Modules\ImportTemplates;

if ( ! defined( 'ABSPATH' ) ) exit;

use ElementorExtensions\Base\Module_Base;
use ElementorExtensions\Admin\EE_MB_Setting_Common;
use ElementorExtensions\Classes\Utils;

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		$this->add_actions();
	}

	protected function add_actions() {
		add_action( 'wp_enqueue_scripts', [ $this, 'ee_mb_enqueue_scripts' ], 11 );

		$api_site_uri = EE_MB_Setting_Common::get_settings_key('ee_mb_integration_setting', 'ee_mb_import_template_url');

		add_action( 'rest_api_init', [ $this, 'ee_mb_get_template_apis' ] );
	}

	public function ee_mb_get_template_apis(){

		register_rest_route( 'templates', '/all-templates', array(
		    'methods' => 'GET',
		    'callback' => [ $this, 'ee_mb_get_template_dropdown' ]
		));

		register_rest_route( 'templates', '/get', array(
		    'methods' => 'POST',
		    'callback' => [ $this, 'ee_mb_get_templates' ]
		));
	}

	public function ee_mb_get_templates($request_data){

		$parameters = $request_data->get_params();
		$template_id = $parameters['template_id'];

		return $res = Utils::elementor()->frontend->get_builder_content_for_display( $template_id );
	}

	public function ee_mb_get_elementor_library_post(){

		$template_args = [
			'post_type' => 'elementor_library',
			'post_status' => 'publish'
		];
		return get_posts($template_args);
	}

	public function ee_mb_get_template_dropdown(){

		$ee_mb_templates = $this->ee_mb_get_elementor_library_post();

		$res_templates = [];
		foreach($ee_mb_templates as $key => $template):
			$template_id = $template->ID; 
			$template_title = $template->post_title; 

			$templates[ $template_id ] = $template_title;		
		endforeach;

		$template_pass = EE_MB_Setting_Common::get_settings_key('ee_mb_integration_setting', 'ee_mb_template_password');

		$res_templates['templates'] = $templates;
		$res_templates['template_password'] = $template_pass;
		$res_templates['template_paths'] = wp_upload_dir();
		
		return $res_templates;
	}

	public function ee_mb_enqueue_scripts() {	
		// wp_enqueue_style( 'font-awesome');
		// wp_enqueue_style( 'elementor-icons');
		// wp_enqueue_style( 'elementor-animations');
		// wp_enqueue_style( 'flatpickr');

		wp_enqueue_script(
			'font-awesome-4-shim',
			self::get_fa_asset_url( 'v4-shims', 'js' ),
			[],
			ELEMENTOR_VERSION
		);
		wp_enqueue_style(
			'font-awesome-5-all',
			self::get_fa_asset_url( 'all' ),
			[],
			ELEMENTOR_VERSION
		);
		wp_enqueue_style(
			'font-awesome-4-shim',
			self::get_fa_asset_url( 'v4-shims' ),
			[],
			ELEMENTOR_VERSION
		);
	}

	private static function get_fa_asset_url( $filename, $ext_type = 'css', $add_suffix = true ) {
		static $is_test_mode = null;
		if ( null === $is_test_mode ) {
			$is_test_mode = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || defined( 'ELEMENTOR_TESTS' ) && ELEMENTOR_TESTS;
		}

		$url = ELEMENTOR_ASSETS_URL . 'lib/font-awesome/' . $ext_type . '/' . $filename;
		if ( ! $is_test_mode && $add_suffix ) {
			$url .= '.min';
		}
		return $url . '.' . $ext_type;
	}
	
	public function get_name() {
		return 'ee-mb-import-templates';
	}

	public function get_widgets() {
		return [
			'Import_Templates'
		];
	}
}
