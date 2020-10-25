<?php
namespace weDevs\ERP_PRO\PRO\HelpScout;
/**
 * Plugin Name: WP ERP - HelpScout
 * Plugin URI:  http://wperp.com
 * Description: HelpScout integration for WP ERP
 * Version:     1.1.1
 * Author:      WPERP
 * Author URI:  http://wperp.com
 * Donate link: http://wperp.com
 * License:     GPLv2+
 * Text Domain: erp-helpscout
 * Domain Path: /languages
 */

/**
 * Copyright (c) 2017 WPERP (email : support@wperp.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Main initiation class
 *
 * @since 1.1.0
 */
class Module {

    /**
     * Add-on Version
     *
     * @since 1.1.0
     * @var  string
     */
    public $version = '1.1.1';

    /**
     * Initializes the class
     *
     * Checks for an existing instance
     * and if it does't find one, creates it.
     *
     * @since 1.1.0
     *
     * @return object Class instance
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Constructor for the class
     *
     * Sets up all the appropriate hooks and actions
     *
     * @since 1.1.0
     *
     */
    private function __construct() {
        // on activate plugin register hook
        add_action( 'erp_pro_activated_module_help_scout', array( $this, 'activate' ) );

        // Define constants
        $this->define_constants();
        $this->include_functions();

        add_action( 'erp_crm_loaded', [ $this, 'init_plugin' ] );
    }

    /**
     * Init plugins
     *
     * @return void
     */
    public function init_plugin() {
        // check crm module is loaded
        if ( ! wperp()->modules->is_module_active('crm') ) {
            return;
        }

        $this->includes();
        $this->init_actions();
    }

    /**
     * Executes during plugin activation
     *
     * @since 1.1.0
     *
     * @return void
     */
    function activate() {

        //save options
        $options = get_option( 'erp_settings_erp-crm_help_scout' );
        if ( ! isset( $options['helpscout_secret_key'] ) || empty($options['helpscout_secret_key']) ) {
            $api_key                      = md5( get_site_url() . time() );
            $options['helpscout_secret_key'] = $api_key;
            update_option( 'erp_settings_erp-crm_help_scout', $options );
        }

        //override api end
        $callback_url                      = untrailingslashit( get_site_url() ) . '/erp-helpscout/api';
        $options['helpscout_callback_uri'] = $callback_url;
        update_option( 'erp_settings_erp-crm_help_scout', $options );

        // Create helpscout webhook
        create_helpscout_webhook();
    }


    /**
     * Define constants
     *
     * @since 1.1.0
     *
     * @return void
     */
    private function define_constants() {
        define( 'ERP_HELPSCOUT_VERSION', $this->version );
        define( 'ERP_HELPSCOUT_FILE', __FILE__ );
        define( 'ERP_HELPSCOUT_PATH', dirname( ERP_HELPSCOUT_FILE ) );
        define( 'ERP_HELPSCOUT_INCLUDES', ERP_HELPSCOUT_PATH . '/includes' );
        define( 'ERP_HELPSCOUT_URL', plugins_url( '', ERP_HELPSCOUT_FILE ) );
        define( 'ERP_HELPSCOUT_ASSETS', ERP_HELPSCOUT_URL . '/assets' );
    }

    /**
     * Include required files
     *
     * @since 1.1.0
     *
     * @return void
     */
    private function includes() {
        require ERP_HELPSCOUT_INCLUDES . '/class-user.php';
        require ERP_HELPSCOUT_INCLUDES . '/class-customer.php';
        require ERP_HELPSCOUT_INCLUDES . '/class-request.php';
        require ERP_HELPSCOUT_INCLUDES . '/class-endpoint.php';
        require ERP_HELPSCOUT_INCLUDES . '/class-settings.php';
        require ERP_HELPSCOUT_INCLUDES . '/class-ajax.php';
        require ERP_HELPSCOUT_INCLUDES . '/class-admin-menu.php';
        require ERP_HELPSCOUT_INCLUDES . '/api/class-helpscout-customer-controller.php';

        if ( class_exists( '\weDevs\ERP_PRO\CRM\Deals\Module' ) ) {
            require ERP_HELPSCOUT_INCLUDES . '/class-deal.php';
        }
    }

    /**
     * Initialize WordPress action hooks
     *
     * @since 1.1.0
     *
     * @return void
     */
    public function init_actions() {
        add_action( 'init', [ $this, 'instantiate' ] );
        add_action('admin_enqueue_scripts', [$this, 'load_admin_scripts_old']);
        add_action( 'erp_crm_feeds_nav_content', [ $this, 'helpscout_customer_activity_tab_content' ] );
        add_filter( 'erp_crm_customer_feeds_nav', [ $this, 'helpscout_customer_activity_tab' ] );
        add_filter( 'erp_rest_api_controllers', [ $this, 'load_helpscout_rest_controller' ] );
    }
    /**
     * Instantiate classes
     *
     * @since 1.1.0
     *
     * @return void
     */
    public function instantiate() {

        if ( $this->is_helpscout_request() && ! is_admin() ) {
            new \WeDevs\ERP\HelpScout\EndPoint();
        }

        if(is_admin()){
            new \WeDevs\ERP\HelpScout\Settings();
            new \WeDevs\ERP\HelpScout\Ajax();
            new \WeDevs\ERP\HelpScout\Admin_Menu();
        }
    }

    /**
     * Checks if its a request from helpscout or not
     *
     * @since 1.1.0
     * @return bool
     */
    private function is_helpscout_request() {
        $trigger = stristr( $_SERVER['REQUEST_URI'], '/erp-helpscout/api' ) !== false;

        return (bool) apply_filters( 'erp_helpscout_is_helpscout_request', $trigger );
    }

    /**
     * Load Helpscout rest controller
     *
     * @param  array $controllers
     * @return void
     */
    public function load_helpscout_rest_controller( $controllers ) {
        $controllers[] = '\WeDevs\ERP\HelpScout\API\Helpscout_Customer_Controller';

        return $controllers;
    }

    public function include_functions() {
        require ERP_HELPSCOUT_INCLUDES . '/functions.php';
    }

    /**
     * Loads admin scripts
     *
     * @since 1.1.0
     *
     * @param $hook
     */
    public function load_admin_scripts( $hook ) {
        if ( version_compare( WPERP_VERSION, "1.4.0", '<' ) ) {
            $this->load_admin_scripts_old( $hook );
            return;
        }

        if ( !erp_is_contacts_page() ) {
            return;
        }

        $contact_id = null;
        if ( isset( $_GET['id'] ) && ( !empty( $_GET['id'] ) ) ) {
            $contact_id = $_GET['id'];
        }

        wp_enqueue_style( 'erp-helpscout', ERP_HELPSCOUT_ASSETS . '/css/erp-helpscout.css', [], ERP_HELPSCOUT_VERSION );
        wp_enqueue_script('erp-helpscout', ERP_HELPSCOUT_ASSETS . '/js/erp-helpscout.js', ['jquery'], filemtime( ERP_HELPSCOUT_PATH . '/assets/js/erp-helpscout.js' ), true);
        wp_localize_script( 'erp-helpscout', 'erpSC', array(
            'ajaxurl'    => admin_url( 'admin-ajax.php' ),
            'contact_id' => $contact_id,
            'nonce'      => wp_create_nonce( 'erp-hc-nonce' )
        ) );
    }

    /**
     * Loads admin scripts
     *
     * @since 1.1.0
     *
     * @param $hook
     */
    public function load_admin_scripts_old( $hook ) {
        if ( 'crm_page_erp-sales-customers' !== $hook && 'wp-erp_page_erp-crm' !== $hook ) {
            return;
        }
        $contact_id = null;
        if(isset($_GET['id']) && (!empty($_GET['id']))){
            $contact_id = $_GET['id'];
        }

        wp_enqueue_style('erp-helpscout', ERP_HELPSCOUT_ASSETS . '/css/erp-helpscout.css', [], ERP_HELPSCOUT_VERSION);
        wp_enqueue_script('erp-helpscout', ERP_HELPSCOUT_ASSETS . '/js/erp-helpscout.js', ['jquery'], filemtime( ERP_HELPSCOUT_PATH . '/assets/js/erp-helpscout.js' ), true);
        wp_localize_script( 'erp-helpscout', 'erpSC', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'contact_id'   => $contact_id,
            'nonce'   => wp_create_nonce( 'erp-hc-nonce' )
        ) );
    }

    /**
     * HelpScout customer activity tab
     *
     * @param  array $tabs customer tab
     * @return array
     */
    public function helpscout_customer_activity_tab( $tabs ) {
        $tabs['helpscout']   = array(
            'title' => __( 'HelpScout', 'erp-pro' ),
            'icon'  => '<i class="fa fa-file-text-o"></i>'
        );

        return $tabs;
    }

    /**
     * Helpscout nav content
     *
     * @return void nav content
     */
    public function helpscout_customer_activity_tab_content() {
        if ( ! is_erp_crm_contact() ) {
            return;
        }
        $contact_id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;
        $contact = erp_get_people( $contact_id );
        $helpscout_user = get_helpscout_users();
        $helpscout_mailboxes = get_helpscout_mailbox();
        require ERP_HELPSCOUT_INCLUDES . '/views/conversation-form.php';
    }

}
