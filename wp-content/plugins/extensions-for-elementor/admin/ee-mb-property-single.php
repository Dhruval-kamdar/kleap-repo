<?php
namespace ElementorExtensions\Admin;

if ( ! defined( 'ABSPATH' ) ) exit;

use ElementorExtensions\Admin\EE_MB_Setting_Common;

class EE_MB_Property_Single extends EE_MB_Setting_Common{

    private static $_instance;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function __construct(){
         add_action('init', [ $this, 'add_actions' ] );
         add_action( 'admin_post_elementor_extensions_settings', [ $this, 'action_ee_mb_property_settings_tab' ] );     
    }

    public function add_actions(){
        add_action( 'admin_menu', array( $this, 'ee_mb_add_property_settings_page' ) );
    }

    public function ee_mb_add_property_settings_page(){
        add_submenu_page(
            'elementor-extensions-property-management',
            __( 'Settings', 'elementor-extensions' ),
            __( 'Settings', 'elementor-extensions' ),
            'manage_options',
            'ee-mb-property-search',
            [ $this, 'ee_mb_property_tabs' ]
        );
    }

    public function ee_mb_property_tabs(){

        $single_page = self::get_settings_key('ee_mb_single_page');
        $ee_mb_agent = self::get_settings_key('ee_mb_agent');
        $general = self::get_settings_key('ee_mb_property_setting');
        include_once(ELEMENTOR_EXTENSIONS_PATH.'admin/views/property/init.php');
    }

    public function action_ee_mb_property_settings_tab(){
        
        $this->ee_mb_single_page_setting_tab_saving();
        $this->ee_mb_agent_setting_tab_saving();
        $this->ee_mb_general_setting_tab_saving();
    }

    public function ee_mb_single_page_setting_tab_saving(){

        if(isset($_POST['btn_update_single_page']) && $_SERVER['REQUEST_METHOD'] === 'POST' && wp_verify_nonce( $_POST['single_page_settings_nounce'], 'single_page_settings' )):

            /*@ Sanitize array request */
            $request = (!empty($_POST['ee_mb_single_page'])) ? EE_MB_Setting_Common::sanitize($_POST['ee_mb_single_page']) : '';

            $single_page = json_encode($request);
            update_option( 'ee_mb_single_page', $single_page );

            $integration_tab_url = $_POST['_wp_http_referer']."&saved=1#single_page";
            wp_safe_redirect($integration_tab_url);
            die;
        endif;
    }

    public function ee_mb_agent_setting_tab_saving(){

        if(isset($_POST['btn_update_agent']) && $_SERVER['REQUEST_METHOD'] === 'POST' && wp_verify_nonce( $_POST['ee_mb_agent_settings_nounce'], 'ee_mb_agent_settings' )):

            /*@ Sanitize array request */
            $request = (!empty($_POST['ee_mb_agent'])) ? EE_MB_Setting_Common::sanitize($_POST['ee_mb_agent']) : '';

            $single_page = json_encode($request);
            update_option( 'ee_mb_agent', $single_page );

            $integration_tab_url = $_POST['_wp_http_referer']."&saved=1#agent";
            wp_safe_redirect($integration_tab_url);
            die;
        endif;
    }

    public function ee_mb_general_setting_tab_saving(){

        if(isset($_POST['btn_update_general']) && $_SERVER['REQUEST_METHOD'] === 'POST' && wp_verify_nonce( $_POST['ee_mb_general_settings_nounce'], 'ee_mb_general_settings' )):

            /*@ Sanitize array request */
            $request = (!empty($_POST['ee_mb_property_setting'])) ? EE_MB_Setting_Common::sanitize($_POST['ee_mb_property_setting']) : '';

            $general_setting = json_encode($request);
            update_option( 'ee_mb_property_setting', $general_setting );

            $general_setting_tab_url = $_POST['_wp_http_referer']."&saved=1#general";
            wp_safe_redirect($general_setting_tab_url);
            die;
        endif;
    }
}
