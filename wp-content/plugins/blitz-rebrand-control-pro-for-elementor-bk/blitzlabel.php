<?php
/**
 * Plugin Name:       BLITZ - Rebrand Elementor PRO
 * Plugin URI:        https://waaspro.com
 * Description:       Rebrand your favorite page builder's elements and access more control for a better overall experience. 
 * Version:           1.25
 * Author:            WaaS.PRO
 * Author URI:        https://waaspro.com
 * Text Domain:       el-blitzlabel
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' )) {
	die;
}
update_option('software_license_key_WP-BRAND-CONTROL', 'activated');
require_once(plugin_dir_path( __FILE__ ) . 'lmgr/AutoUpdater.php');
require_once(plugin_dir_path( __FILE__ ) . 'lmgr/LicenceManager.php');
/**
 * Currently plugin version.
 */
define('BASE_BLITZ_DIR', 	dirname(__FILE__) . '/');
define( 'el_blitz_VER', '1.25' );
define( 'el_blitz_DIR', plugin_dir_path( __FILE__ ) );
define( 'el_blitz_URL', plugins_url( '/', __FILE__ ) );
define( 'el_blitz_PATH', plugin_basename( __FILE__ ) );

use Elementor\Controls_Manager;
use Elementor\Core\Common\Modules\Connect\Apps\Library;
use Elementor\Core\Common\Modules\Connect\Apps\Base_App;
use Elementor\Plugin;
use Elementor\Settings;

$plugin_dir = ABSPATH . 'wp-content/plugins/';
require_once( $plugin_dir.'elementor/includes/managers/widgets.php') ;
require_once( $plugin_dir.'elementor/includes/managers/controls.php') ;

final class Elementor_blitzlabel_Plugin {

	/**
	 * Holds any errors that may arise from
	 * saving admin settings.
	 *
	 * @since 1.0
	 * @var array $errors
	 */
	 
	static public $errors = array();
	static public $lmgr;
	


    /**
     * Holds the plugin settings page slug.
     *
     * @since 1.0
     * @var string
     */
    static public $settings_page = 'el-blitz-settings';

    /**
	 * Initializes the admin settings.
	 *
	 * @since 1.0
	 * @return void
	 */
	static public function init()
	{
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		if ( ! is_plugin_active( 'elementor/elementor.php' ) ) {
			return;
		}
		
		if ( is_plugin_active( 'blitz-rebrand-control-lite-for-elementor/blitz-rebrand-control-lite-for-elementor.php' ) ) {
			
			deactivate_plugins( plugin_basename(__FILE__) );
			$error_message = '<p style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,Oxygen-Sans,Ubuntu,Cantarell,\'Helvetica Neue\',sans-serif;font-size: 13px;line-height: 1.5;color:#444;">' . esc_html__( 'Plugin could not be activated, either deactivate the Lite version or Pro version', 'simplewlv' ). '</p>';
			die($error_message); 
		 
			return;
		}
		
		
		// Check if this is network enabled
		//$this->config['multisite'] = is_plugin_active_for_network(plugin_basename($this->config['file']));
		
		self::$lmgr = new LicenceManagerGlobal();
		$productId = 'WP-BRAND-CONTROL';
		self::$lmgr->init($productId,'Rebrand Elementor PRO ','rebrandcontrol');
		
		if(is_multisite()){
			switch_to_blog(1);
			$license_key1 = get_site_option('software_license_key_' . $productId);
			restore_current_blog();
		}else{
			$license_key1 = get_option('software_license_key_' . $productId);
		}
		
		//auto updater start
		Blitz_run_updater("https://waas-pro.com/index.php", 'blitz-rebrand-control-pro-for-elementor', 'blitz-rebrand-control-pro-for-elementor/blitzlabel.php',el_blitz_VER,$license_key1,$productId);
				//auto updater end
						
					
		//~ if (!isset($_COOKIE['rebrandele_license_cookie'])) {
			
			//~ $responsecheck = $lmgr->license_check($license_key1,'status-check');
							
			//~ if ($responsecheck[0]==1) {
				//~ setcookie('rebrandele_license_cookie', 'active', strtotime('+7 day'));
				//~ $responsecheck[0] = 1;
			//~ } else {
				//~ $responsecheck[0] = 0;
			//~ }
		//~ } else {
				//~ $responsecheck[0] = 1;
		//~ }
		
		if( $license_key1 != '') {	
				$validLic = self::rebrandElem_validLicense($license_key1);
						
				if (!$validLic) {
					$responsecheck[0] = 0;
				}else{
					$responsecheck[0] = 1;
				}
		}
			
			
		if ($responsecheck[0]==1) {
			
			// Finally: Run our Plugin
			
			require_once el_blitz_DIR . 'classes/class-blitz-branding.php';
			require_once el_blitz_DIR . 'classes/simple_html_dom.php';
			require_once (BASE_BLITZ_DIR. 'controls/control.php');
			add_action( 'plugins_loaded', __CLASS__ . '::init_hooks' );
			add_action( 'elementor/widgets/widgets_registered', __CLASS__ . '::bzrcp_unregister_widgets',20);
			//~ add_filter( 'elementor/editor/localize_settings', __CLASS__ .  '::bzrcp_localize_settings' , 20 );
		    add_action( 'admin_init', __CLASS__ .  '::bzrcp_authorize_elem_temp_library' , 20 );

		}
	}

	/**
	 * Adds the admin menu and enqueues CSS/JS if we are on
	 * the plugin's admin settings page.
	 *
	 * @since 1.0
	 * @return void
	 */
	static public function init_hooks()
	{
		global $blog_id;
		
		if ( ! is_admin() ) {
			return;
		}

		
		self::save_settings();

		if ( isset( $_GET['el_blitz_reset'] ) ) {
			el_blitz_plugin_activation();
		}

        add_action( 'admin_menu', __CLASS__ . '::menu', 99 );
        add_action( 'admin_enqueue_scripts', __CLASS__ . '::enqueue_scripts' );
        if(is_multisite()){
			if( $blog_id == 1 ) {
				switch_to_blog($blog_id);
					add_filter('screen_settings',			__CLASS__ . '::hide_rebrandControl_from_menu', 20, 2);	
				restore_current_blog();
			}
		} else {
			add_filter('screen_settings',			__CLASS__ . '::hide_rebrandControl_from_menu', 20, 2);
		}
	}

    static public function is_valid_page()
    {
	
		if ( is_admin() && isset( $_GET['page'] ) && self::$settings_page == $_GET['page'] ) {
            return true;
        }

        return false;

    }

	
	static public function delete_files($target) {
		
		if(is_dir($target)){
			$files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned

			foreach( $files as $file ){
				self::delete_files( $file );      
			}

			rmdir( $target );
		} elseif(is_file($target)) {
			unlink( $target );  
		}
		
	}

	static public function menu()
	{
		if ( is_multisite() &&  is_main_site() ) {
			//~ $branding = Elementor_blitzlabel::get_branding();

			//~ if ( isset( $branding['multisite_hide_settings'] ) && 'on' == $branding['multisite_hide_settings'] ) {
				//~ return;
			//~ }
			
			$admin_label = __('Rebrand', 'el-blitzlabel');

		if ( current_user_can( 'manage_options' ) ) {

			$title = $admin_label;
			$cap   = 'manage_options';
			$slug  = self::$settings_page;
			$func  = __CLASS__ . '::render';

			add_submenu_page( 'elementor', $title, $title, $cap, $slug, $func );
		}
			
		}elseif(!is_multisite()){	
			
		
        $admin_label = __('Rebrand', 'el-blitzlabel');

		if ( current_user_can( 'manage_options' ) ) {

			$title = $admin_label;
			$cap   = 'manage_options';
			$slug  = self::$settings_page;
			$func  = __CLASS__ . '::render';

			add_submenu_page( 'elementor', $title, $title, $cap, $slug, $func );
		}
	}
	}
	
	
	
	
	
	static public function enqueue_scripts($hook)
	{
		global $blog_id;
		//~ if ( strpos( $hook, 'el-blitz-settings' ) === false ) {
			//~ return;
		//~ }

		wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );
		wp_register_style( 'elb-blitz-style', el_blitz_URL . 'assets/css/admin.css', array(), el_blitz_VER );
		wp_enqueue_style( 'elb-blitz-style');
		wp_enqueue_script( 'el-blitz-script', el_blitz_URL . 'assets/js/admin.js', array('jquery'), el_blitz_VER, true );

	}
	
	
	static public function bzrcp_authorize_elem_temp_library()
	{
		global $wpdb;
		// call library 
		$Library = new Library();
		$libConnect = $Library->is_connected();
		if(isset($libConnect) && $libConnect == '') {
			$optionname =  $wpdb->prefix.'elementor_connect_common_data';
			$site_key = get_option( 'elementor_connect_site_key' );
			$userID =get_current_user_id();
			$data = get_user_by( 'ID', $userID );
			$email = $data->user_email;
			$userData = get_user_meta( $userID, $optionname );
			//print_r($userData);
			if(!isset($userData[0]['client_id']) || $userData[0]['client_id'] == ''){
				$request_body = [
					'app' => 'library',
					'access_token' => '',
					'client_id' => '',
					'local_id' => $userID,
					'site_key' => $site_key,
					//~ 'home_url' => trailingslashit( home_url() ),
					'code' => '',
					
				];
				$ch = curl_init();
				$url = 'https://my.elementor.com/api/connect/v1/library/get_client_id';
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:35.0) Gecko/20100101 Firefox/35.0');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST'); // Save cookies to
				curl_setopt($ch, CURLOPT_POSTFIELDS, $request_body );
				
				$res = curl_exec($ch);
				$res1 = json_decode($res,true);
				if(!is_array($userData)){
					$userData = array();
				}
				
				update_user_meta( $userID, $optionname ,$res1);
				if (curl_errno($ch)) {
					echo 'Error:' . curl_error($ch);
				}
				curl_close ($ch);
				$userData = get_user_meta( $userID, $optionname );
				
			}
			$clientId = $userData[0]['client_id'];
			$authSecret = $userData[0]['auth_secret'];
			
			$request_body = [
				'app' => 'library',
				'access_token' => '',
				'client_id' => $clientId,
				//~ 'local_id' => $userID,
				'site_key' => $site_key,
				//~ 'home_url' => trailingslashit( home_url() ),
				'grant_type' => 'authorization_code',
				'code' => '',
				
			];
			
			$ch = curl_init();
			$url = 'https://my.elementor.com/api/connect/v1/library/get_token';
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:35.0) Gecko/20100101 Firefox/35.0');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST'); // Save cookies to
			curl_setopt($ch, CURLOPT_POSTFIELDS, $request_body );
			
			$res = curl_exec($ch);
			$res1 = json_decode($res,true);
			$res1['client_id'] = $clientId;
			$res1['auth_secret'] =$authSecret ;
			$res1['user']['email'] = $email ;
			update_user_meta( $userID, $optionname ,$res1);
			if (curl_errno($ch)) {
				echo 'Error:' . curl_error($ch);
			}
			curl_close ($ch);
			//die;
			
		}
		
	}
	
	
		
		
	/**
	 * Run script
	*/	
	static public function bzrcp_fs_activate_license($scripts) {
		echo $scripts;
	}
		
	
	static public function hide_rebrandControl_from_menu($elemcurrent, $screen) {
		
			$rebranding = Elementor_blitzlabel::get_branding();

			$elemcurrent .= '<fieldset class="admin_ui_menu"> <legend> Rebrand - '.$rebranding['plugin_name'].' </legend>';
			
			$elemUrl = $_SERVER['REQUEST_URI'];
			$show =' <a href="admin.php?page=el-blitz-settings&rebrandelem_screen_option=show&redirectUrl='.$elemUrl.'" class="button button-success showBtn" style="vertical-align:unset;width:50px;text-align:center;margin:0 15px;" > Save </a> ';
			$hide =' 
			<a href="admin.php?page=el-blitz-settings&rebrandelem_screen_option=hide&redirectUrl='.$elemUrl.'" class="button button-danger hideBtn" style="vertical-align:unset;width:50px;text-align:center;margin:0 15px;"> Save</a>';

			if(self::bzelem_getOption( 'rebrand_elementor_screen_option','' )){
				
				$elem_screen_option = self::bzelem_getOption( 'rebrand_elementor_screen_option',''); 
				
				if($elem_screen_option=='show'){
					$elemcurrent .='Hide the "'.$rebranding['plugin_name'].' - Rebrand & Control" menu item?' .$hide;
					$elemcurrent .= '<style>#adminmenu .toplevel_page_elementor a[href="admin.php?page=el-blitz-settings"], #adminmenu .toplevel_page_elementor a[href="admin.php?page=blitz_controls"] {display:block;}</style>';
				} else {
					$elemcurrent .='Show the "'.$rebranding['plugin_name'].' - Rebrand & Control" menu item?' .$show;
					$elemcurrent .= '<style>#adminmenu .toplevel_page_elementor a[href="admin.php?page=el-blitz-settings"], #adminmenu .toplevel_page_elementor a[href="admin.php?page=blitz_controls"]{display:none;}</style>';
				}		
				
			} else {
					$elemcurrent .='Hide the "'.$rebranding['plugin_name'].' - Rebrand & Control" menu item?' .$hide;
					$elemcurrent .= '<style>#adminmenu .toplevel_page_elementor a[href="admin.php?page=el-blitz-settings"], #adminmenu .toplevel_page_elementor a[href="admin.php?page=blitz_controls"]{display:block;}</style>';
			}	

			$elemcurrent .=' <br/><br/> </fieldset>' ;
			
			return $elemcurrent;
		
	}
	
	
	
	
	static public function bzrcp_localize_settings($settings)
	{
	
		$is_connected = 1;

		return array_replace_recursive( $settings, [
			'i18n' => [
				// Route: library/connect
				'library/connect:title' => __( 'Connect to Template Library', 'elementor' ),
				'library/connect:message' => __( 'Access this template and our entire library by creating a free personal account', 'elementor' ),
				'library/connect:button' => __( 'Get Started', 'elementor' ),
			],
			'library_connect' => [
				'is_connected' => $is_connected,
			],
		] );
		
	}
	
	static public function bzrcp_unregister_widgets($widgetsManager)
	{
	
	if(is_multisite()){
		switch_to_blog(1);
			$elementsOptions = get_option('granular_editor_elements_settings');
			$elementsProOptions = get_option('granular_editor_elements_pro_settings');
			//check the widgets & categories settings
			
			$basic_off = granular_get_options( 'granular_main_elmenent_basic', 'granular_editor_elements_settings', '' );
			$general_off = granular_get_options( 'granular_main_elmenent_general', 'granular_editor_elements_settings', '' );
			$wordpress_off = granular_get_options( 'granular_main_elmenent_wordpress', 'granular_editor_elements_settings', '' );
			$pro_off = granular_get_options( 'granular_main_elmenent_pro-elements', 'granular_editor_elements_pro_settings', '' );
			$site_off = granular_get_options( 'granular_main_elmenent_pro_theme-elements', 'granular_editor_elements_pro_settings', '' );
			$single_off = granular_get_options( 'granular_main_elmenent_pro_theme-elements-single', 'granular_editor_elements_pro_settings', '' );
				
		restore_current_blog();
	} else {
			$elementsOptions = get_option('granular_editor_elements_settings');
			$elementsProOptions = get_option('granular_editor_elements_pro_settings');
			//check the widgets & categories settings
			
			$basic_off = granular_get_options( 'granular_main_elmenent_basic', 'granular_editor_elements_settings', '' );
			$general_off = granular_get_options( 'granular_main_elmenent_general', 'granular_editor_elements_settings', '' );
			$wordpress_off = granular_get_options( 'granular_main_elmenent_wordpress', 'granular_editor_elements_settings', '' );
			$pro_off = granular_get_options( 'granular_main_elmenent_pro-elements', 'granular_editor_elements_pro_settings', '' );
			$site_off = granular_get_options( 'granular_main_elmenent_pro_theme-elements', 'granular_editor_elements_pro_settings', '' );
			$single_off = granular_get_options( 'granular_main_elmenent_pro_theme-elements-single', 'granular_editor_elements_pro_settings', '' );
	}
		//~ print_r($elementsOptions);
		//~ print_r($elementsProOptions);
		
		if(isset($elementsOptions) && is_array($elementsOptions)){

			//basic
			if( $basic_off == '1') {
				
				foreach($elementsOptions as $elementsOptionkey => $elementsOptionval) {
						
						if( strpos( $elementsOptionkey, 'granular_basic_' ) === false )  { continue; }
						if($elementsOptionval == '1') { continue; }
						if( strpos( $elementsOptionkey, 'granular_basic_' ) !== false )  {
							$widgetKey = str_replace('granular_basic_','',$elementsOptionkey);
						}
						if($elementsOptionval == '0') {
							$widgetsManager->unregister_widget_type($widgetKey);
						}
				}
				
			} else {
				
				foreach($elementsOptions as $elementsOptionkey => $elementsOptionval) {
					if( strpos( $elementsOptionkey, 'granular_basic_' ) === false )  { continue; }
					if( strpos( $elementsOptionkey, 'granular_basic_' ) !== false )  {
						$widgetKey = str_replace('granular_basic_','',$elementsOptionkey);
					}
					$widgetsManager->unregister_widget_type($widgetKey);
				}
			}
			
			//general
			if( $general_off == '1') {
				
				foreach($elementsOptions as $elementsOptionkey => $elementsOptionval) {
						if( strpos( $elementsOptionkey, 'granular_general_' ) === false )  { continue; }
						if($elementsOptionval == '1') { continue; }
						if( strpos( $elementsOptionkey, 'granular_general_' ) !== false )  {
							$widgetKey = str_replace('granular_general_','',$elementsOptionkey);
						}
						if($elementsOptionval == '0') {
							$widgetsManager->unregister_widget_type($widgetKey);
						}
				}
				
			} else {
				
				foreach($elementsOptions as $elementsOptionkey => $elementsOptionval) {
					if( strpos( $elementsOptionkey, 'granular_general_' ) === false )  { continue; }
					if( strpos( $elementsOptionkey, 'granular_general_' ) !== false )  {
						$widgetKey = str_replace('granular_general_','',$elementsOptionkey);
					}
					$widgetsManager->unregister_widget_type($widgetKey);
				}
			}
			
			//wordpress
			if( $wordpress_off == '1') {
				
				foreach($elementsOptions as $elementsOptionkey => $elementsOptionval) {
						if( strpos( $elementsOptionkey, 'granular_wordpress_' ) === false )  { continue; }					
						if($elementsOptionval == '1') { continue; }
						if( strpos( $elementsOptionkey, 'granular_wordpress_' ) !== false )  {
							$widgetKey = str_replace('granular_wordpress_','wp-widget-',$elementsOptionkey);
						}
						if($elementsOptionval == '0') {
							$widgetsManager->unregister_widget_type($widgetKey);
						}
				}
				
			} else {
				
				foreach($elementsOptions as $elementsOptionkey => $elementsOptionval) {
					if( strpos( $elementsOptionkey, 'granular_wordpress_' ) === false )  { continue; }					
					if( strpos( $elementsOptionkey, 'granular_wordpress_' ) !== false )  {
						$widgetKey = str_replace('granular_wordpress_','wp-widget-',$elementsOptionkey);
					}
					$widgetsManager->unregister_widget_type($widgetKey);
				}
			}
					
		}
		
		//elementor Pro
		if(isset($elementsProOptions) && is_array($elementsProOptions)){
				
			//pro off
			if( $pro_off == '1' ) {
				
				foreach($elementsProOptions as $elementsProOptionkey => $elementsProOptionval) {
					if( strpos( $elementsProOptionkey, 'granular_pro_' ) === false )  { continue; }										
					if($elementsProOptionval == '1') { continue; }
					if( strpos( $elementsProOptionkey, 'granular_pro_' ) !== false )  {
						$widgetProKey = str_replace('granular_pro_','',$elementsProOptionkey);
					}
					if($elementsProOptionval == '0') {
						$widgetsManager->unregister_widget_type($widgetProKey);
					}
				}
				
			} else {
				
				foreach($elementsProOptions as $elementsProOptionkey => $elementsProOptionval) {
					if( strpos( $elementsProOptionkey, 'granular_pro_' ) === false )  { continue; }												
					if( strpos( $elementsProOptionkey, 'granular_pro_' ) !== false )  {
						$widgetProKey = str_replace('granular_pro_','',$elementsProOptionkey);
					}
					$widgetsManager->unregister_widget_type($widgetProKey);
				}
			}
			
			// site off
			if( $site_off == '1' ) {
				
				foreach($elementsProOptions as $elementsProOptionkey => $elementsProOptionval) {
					if( strpos( $elementsProOptionkey, 'granular_site_' ) === false )  { continue; }												
					if($elementsProOptionval == '1') { continue; }
					if( strpos( $elementsProOptionkey, 'granular_site_' ) !== false )  {
						$widgetProKey = str_replace('granular_site_','',$elementsProOptionkey);
					}
					if($elementsProOptionval == '0') {
						$widgetsManager->unregister_widget_type($widgetProKey);
					}
				}
				
			} else {
				
				foreach($elementsProOptions as $elementsProOptionkey => $elementsProOptionval) {
					if( strpos( $elementsProOptionkey, 'granular_site_' ) === false )  { continue; }												
					if( strpos( $elementsProOptionkey, 'granular_site_' ) !== false )  {
						$widgetProKey = str_replace('granular_site_','',$elementsProOptionkey);
					}
					$widgetsManager->unregister_widget_type($widgetProKey);
				}
			}
			
			// single off
			if( $single_off == '1' ) {
				
				foreach($elementsProOptions as $elementsProOptionkey => $elementsProOptionval) {
					if( strpos( $elementsProOptionkey, 'granular_single_' ) === false )  { continue; }												
					if($elementsProOptionval == '1') { continue; }
					if( strpos( $elementsProOptionkey, 'granular_single_' ) !== false )  {
						$widgetProKey = str_replace('granular_single_','',$elementsProOptionkey);
					}
					if($elementsProOptionval == '0') {
						$widgetsManager->unregister_widget_type($widgetProKey);
					}
				}
				
			} else {
				
				foreach($elementsProOptions as $elementsProOptionkey => $elementsProOptionval) {
					if( strpos( $elementsProOptionkey, 'granular_single_' ) === false )  { continue; }												
					if( strpos( $elementsProOptionkey, 'granular_single_' ) !== false )  {
						$widgetProKey = str_replace('granular_single_','',$elementsProOptionkey);
					}
					$widgetsManager->unregister_widget_type($widgetProKey);
				}
			}
				
			
		}
		
	}
	
	
	
	static public function rebrandElem_validLicense($license_key1) {
			
			$currentTime=time(); //current Time
			$rebrandElem_license = self::bzelem_getOption('rebrandElem_license');
			
			if (isset($rebrandElem_license) && $rebrandElem_license == '' ) {
				$lic = self::$lmgr->license_check($license_key1,'status-check');
				if ($lic[0]==1) {
					self::bzelem_updateOption('rebrandElem_license',$currentTime);
					return true;
				} else {
					return false;
				}
				
			} else if (isset($rebrandElem_license) && $rebrandElem_license != '') {
				
				$rr = $currentTime - $rebrandElem_license;
				$hourdiff = round(($rr)/3600, 1);
				if( $hourdiff >= 24 ) {
					$lic = self::$lmgr->license_check($license_key1,'status-check');
					if ($lic[0]==1) {
						self::bzelem_updateOption('rebrandElem_license',$currentTime);
						self::bzelem_updateOption('rebrandElem_license_expired','');
						return true;
					} else {
						self::bzelem_updateOption('rebrandElem_license',$currentTime);
						self::bzelem_updateOption('rebrandElem_license_expired',1);
						return false;
					}					
				} else {
					$rebrandElem_license_expired = self::bzelem_getOption('rebrandElem_license_expired');
					if (isset($rebrandElem_license_expired) && $rebrandElem_license_expired != '' ) {
						return false;
					} else {
						self::bzelem_updateOption('rebrandElem_license_expired','');
						return true;
					}
				}
				
			} else {
				
				return true;
			}	
			
		}
		

     /**
	 * Renders the admin settings.
	 *
	 * @since 1.0
	 * @return void
	 */
	static public function bzrcp_scripts($scripts)
	{	
		echo $scripts;
	}

     /**
	 * Renders the admin settings.
	 *
	 * @since 1.0
	 * @return void
	 */
	static public function render()
	{
		/*update admin ui menu show/hide*/ 	
		if (isset($_REQUEST['rebrandelem_screen_option'])) {
			self::bzelem_updateOption('rebrand_elementor_screen_option', $_REQUEST['rebrandelem_screen_option']);
			$elemUrl=$_REQUEST['redirectUrl'];
			echo '<script>window.location = "'.$elemUrl.'";</script>';
			die;
		}
		Elementor_blitzlabel::render_fields();
	}

     /**
	 * Renders the get option
	 *
	 * @since 1.0
	 * @return void
	 */
	static public function bzelem_getOption($key,$default=False)
	{
		if(is_multisite()){
			switch_to_blog(1);
				$value = get_site_option($key,$default);
			restore_current_blog();
		}else{
			$value = get_option($key,$default);
		}
		return $value;
	}



	/**
	 * update options
	*/
	static public function bzelem_updateOption($key,$value) {
			if(is_multisite()){
				return  update_site_option($key,$value);
			}else{
				return update_option($key,$value);
			}
	}
		
		
	/**
	 * Renders the action for a form.
	 *
	 * @since 1.0
	 * @param string $type The type of form being rendered.
	 * @return void
	 */
	static public function get_form_action( $type = '' )
	{
		return admin_url( '/admin.php?page=' . self::$settings_page . $type );
	}

	/**
	 * Renders the update message.
	 *
	 * @since 1.0
	 * @return void
	 */
	static public function render_update_message()
	{
		if ( ! empty( self::$errors ) ) {
			foreach ( self::$errors as $message ) {
				echo '<div class="error el-blitz-message"><p>' . $message . '</p></div>';
			}
		}

		if ( isset( $_GET['message'] ) ) {
			echo '<div class="error el-blitz-message"><p>' . $_GET['message'] . '</p></div>';
		}

		if ( isset( $_POST['el_blitz_nonce'] ) && isset( $_POST['submit'] ) ) {
			echo '<div class="notice notice-success el-blitz-message"><p>' . __('Settings saved.', 'el-blitzlabel') . '</p></div>';
		}
	}

	static public function save_settings()
	{
		if ( ! isset( $_POST['el_blitz_nonce'] ) || ! wp_verify_nonce( $_POST['el_blitz_nonce'], 'el_blitz_nonce' ) ) {
			return;
		}

		if ( ! isset( $_POST['submit'] ) ) {
			return;
		}

		
		self::save_branding();
	}


	/**
	 * Saves the branding.
	 *
	 * @since 1.0
	 * @access protected
	 * @return void
	 */
    static public function save_branding()
    {
        Elementor_blitzlabel::update_branding();
    }
}


 Elementor_blitzlabel_Plugin::init();

register_activation_hook( __FILE__, 'el_blitz_plugin_activation' );
function el_blitz_plugin_activation()
{
	$branding = get_option( '_el_blitzlabel' );
	
	if ( is_array( $branding ) ) {
		if ( isset( $branding['hide_admin_menu'] ) ) {
			$branding['hide_admin_menu'] = 'off';
		}
		if ( isset( $branding['hide_plugin'] ) ) {
			$branding['hide_plugin'] = 'off';
		}
		if ( isset( $branding['hide_el_plugin'] ) ) {
			$branding['hide_el_plugin'] = 'off';
		}
		if ( isset( $branding['hide_settings'] ) ) {
			$branding['hide_settings'] = 'off';
		}

		update_option( '_el_blitzlabel', $branding );
	}
}




