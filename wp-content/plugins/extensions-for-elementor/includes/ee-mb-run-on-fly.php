<?php
namespace ElementorExtensions\Includes;

if ( ! defined( 'ABSPATH' ) ) exit;

use ElementorExtensions\Admin\EE_MB_Setting_Common;

class EE_MB_Run_On_Fly{

	private static $_instance;
	private $map_key;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct(){
		add_action('init', [ $this, 'ee_mb_add_action' ] );
		$this->map_key = EE_MB_Setting_Common::get_settings_key('ee_mb_integration_setting', 'ee_mb_google_map_key');
	}

	public function ee_mb_add_action(){

		$checked_widget = get_option('ee_mb_hide_show_widgets');
			
		if(is_admin() && is_array($checked_widget) && !empty($checked_widget) && in_array('property-search',$checked_widget)):
			$this->ee_mb_add_cookies_default_values();

			if(empty(get_page_by_title('property search'))):
				$this->ee_mb_add_page('Property Search');
			endif;
			
			add_filter('acf/fields/google_map/api', [ $this, 'ee_mb_acf_free_google_map_api' ], 10, 1 );
			add_action('acf/init', [ $this, 'ee_mb_pro_acf_init' ] );
		else:

			$page = get_page_by_path( 'property-search' );

			if (!empty($page) && isset($page->ID)):
				wp_delete_post($page->ID, true); 
			endif;
		endif;
	}

	private function ee_mb_add_cookies_default_values(){

		if(!get_option('ee_mb_cookie_message')):
			$cookie_args = [
				'enable' => '',
				'message' => 'We use cookies to ensure that we give you the best experience on our website. If you continue to use this site we will assume that you are happy with it.',
				'button_text' => 'Ok',
				'box_color' => '#2b2b2b',
				'font_color' => '#FFF',
				'close_btn_color' => '#FFF',
				'overlay' => '',
				'vertical_position' => 'top',
				'horizontal_position' => 'none'
			];

			$cookie = json_encode($cookie_args);
			update_option( 'ee_mb_cookie_message', $cookie );
		endif;
	}

	private function ee_mb_add_page($page_name){

        add_action('init', function() use ($page_name) {
   	
	   		 $page_args = [
	            'post_type' => 'page',
	            'post_title' => wp_strip_all_tags($page_name),
	            'post_name' => $page_name,
	            'post_status' => 'publish',
	            'post_author' => 1,
	            'post_content' => ''
	        ];

	        wp_insert_post($page_args);

		}, 100);
    }

    public function ee_mb_acf_free_google_map_api( $api ){

        $api['key'] = $this->map_key;
        return $api;	
    }
    
    public function ee_mb_pro_acf_init() {	
        $gmapkey = $this->map_key;
        acf_update_setting('google_api_key', $gmapkey);
    }
}
