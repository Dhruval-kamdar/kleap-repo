<?php
namespace weDevs\ERP_PRO\ACC\PaymentGateway;
/**
 * Plugin Name: WP ERP - Payment Gateway
 * Description: Manage all payment gateways for ERP Accounting module
 * Plugin URI: http://wperp.com/downloads/asset-manager/
 * Author: weDevs
 * Author URI: http://wedevs.com
 * Version: 1.1.0
 * License: GPL2
 * Text Domain: erp-payment-gateway
 * Domain Path: languages
 *
 * Copyright (c) 2016 weDevs (email: info@wperp.com). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */


// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * WeDevs_ERP_Payment_Gateway Main Class
 */
class Module {

    /**
     * Add-on Version
     *
     * @var  string
     */
    public $version = '1.1.0';


    /**
     * SMS Gateway
     *
     * @since 1.0
     */
    public $gateway;

    /**
     * Initializes the WeDevs_ERP_Payment_Gateway class
     *
     * Checks for an existing WeDevs_ERP_Payment_Gateway instance
     * and if it doesn't find one, creates it.
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {

            $instance = new self();
        }
        return $instance;
    }

    /**
     * Constructor for the WeDevs_ERP_SMS class
     *
     * Sets up all the appropriate hooks and actions
     *
     * @return void
     * @since 1.0
     *
     */
    public function __construct() {

        // on activate plugin register hook
        add_action( 'erp_pro_activated_module_payment_gateway', array( $this, 'activate' ) );

        // on register deactivation hook
        add_action( 'erp_pro_deactivated_module_payment_gateway', array( $this, 'deactivate' ) );

        add_action( 'erp_accounting_loaded', [ $this, 'init_plugin' ] );
    }

    /**
     * Execute if ERP Accounting is installed
     *
     * @return void
     * @since 1.0
     */
    public function init_plugin() {

        /* Define constats */
        $this->define_constants();

        /* Include files */
        $this->includes();

        /* Instantiate classes */
        $this->init_classes();

        /* Initialize the action hooks */
        $this->init_actions();

        /* Initialize the filter hooks */
        $this->init_filters();
    }

    /**
     * Placeholder for activation function
     *
     * @return void
     * @since 1.0
     */
    public function activate() {

    }

    /**
     * Placeholder for deactivation function
     *
     * @return void
     * @since 1.0
     */
    public function deactivate() {

    }

    /**
     * Define Add-on constants
     *
     * @return void
     * @since 1.0
     */
    public function define_constants() {

        define( 'WPERP_PG_VERSION', $this->version );                    // Plugin Version
        define( 'WPERP_PG_FILE', __FILE__ );                             // Plugin Main Folder Path
        define( 'WPERP_PG_PATH', dirname( WPERP_PG_FILE ) );             // Parent Directory Path
        define( 'WPERP_PG_INCLUDES', WPERP_PG_PATH . '/includes' );      // Include Folder Path
        define( 'WPERP_PG_URL', plugins_url( '', WPERP_PG_FILE ) );      // URL Path
        define( 'WPERP_PG_ASSETS', WPERP_PG_URL . '/assets' );           // Asset Folder Path
        define( 'WPERP_PG_VIEWS', WPERP_PG_PATH . '/views' );            // View Folder Path
        define( 'WPERP_PG_JS_TMPL', WPERP_PG_VIEWS . '/js-templates' );  // JS Template Folder Path
    }


    /**
     * Include the required files
     *
     * @return void
     * @since 1.0
     *
     */
    public function includes() {

    }

    /**
     * Instantiate classes
     *
     * @return void
     * @since 1.0
     *
     */
    public function init_classes() {

        /* Payment gateway handler */
        new \WeDevs\ERP\Accounting\Payment_Gateway\Payment_Gateway_Handler();
        /* Instantiate General Settings Handler Class */
        new \WeDevs\ERP\Accounting\Payment_Gateway\General_Settings();
        /* Instantiate Stripe Handler Class */
        new \WeDevs\ERP\Accounting\Payment_Gateway\Paypal();
        /* Instantiate Stripe Handler Class */
        new \WeDevs\ERP\Accounting\Payment_Gateway\Stripe();
    }

    /**
     * Initializes action hooks
     *
     * @return  void
     * @since 1.0
     *
     */
    public function init_actions() {
        add_action( 'admin_enqueue_scripts', [ $this, 'register_scripts' ] );
        add_action( 'erp_settings_pages', [ $this, 'erp_pg_settings_page' ] );
        add_action( 'erp_readonly_invoice_header', [ $this, 'payment_gateway_frontend_style' ] );
        add_action( 'erp_readonly_invoice_footer', [ $this, 'payment_gateway_frontend_script' ] );
        if ( version_compare( WPERP_VERSION, '1.5.0', '>=' ) ) {
            add_action( 'erp_readonly_invoice_body', [ $this, 'payment_gateway_frontend_new' ], 10, 4 );
        } else {
            add_action( 'erp_readonly_invoice_body', [ $this, 'payment_gateway_frontend' ], 10, 4 );
        }
    }

    /**
     * Initializes action filters
     *
     * @return  void
     * @since 1.0
     *
     */
    public function init_filters() {

    }

    /**
     * Register all styles and scripts
     *
     * @return void
     * @since 1.0
     *
     */
    public function register_scripts() {
        wp_enqueue_script( 'erp-pg-main-script', WPERP_PG_ASSETS . '/js/payment-gateway.js', [ 'jquery', 'jquery-ui-datepicker' ] );
        wp_enqueue_style( 'erp-pg-main-style', WPERP_PG_ASSETS . '/css/payment-gateway.css' );
    }


    /**
     * Add a payment tab to ERP Settings page
     *
     * @param $settings Main Settings Instance
     * @return array
     * @since 1.0
     */
    function erp_pg_settings_page( $settings ) {
        $settings[] = new \WeDevs\ERP\Accounting\Payment_Gateway\Payment_Gateway_Settings();

        return $settings;
    }

    /**
     * Handles CSS styling for payment gateway frontend css
     *
     * @return void
     * @since 1.0
     */
    public function payment_gateway_frontend_style() {
        ?>
        <link rel="stylesheet" href="<?php echo WPERP_PG_ASSETS . '/css/payment-gateway-front.css' ?>">
        <?php
    }

    /**
     * Handles CSS styling for payment gateway frontend css
     *
     * @return void
     * @since 1.0
     */
    public function payment_gateway_frontend_script() {
        ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="<?php echo WPERP_PG_ASSETS . '/js/payment-gateway-front.js' ?>"></script>
        <?php
    }

    /**
     * Frontend Payment Gateway Handler
     *
     * @return void
     * @since 1.0
     */
    public function payment_gateway_frontend( $company, $user, $transaction, $invoice ) {

        if ( 'awaiting_payment' != $transaction->status ) {
            return;
        }

        $active_gateways = $this->get_active_gateways();
        include WPERP_PG_VIEWS . '/payment-gateway-frontend.php';
    }

    /**
     * Frontend Payment Gateway Handler
     *
     * @return void
     * @since 1.0
     */
    public function payment_gateway_frontend_new( $company, $user, $transaction_id, $transaction ) {

        if ( 'paid' == $transaction['status'] ) {
            return;
        }

        $active_gateways = $this->get_active_gateways();
        include WPERP_PG_VIEWS . '/payment-gateway-frontend.php';
    }

    /**
     * Get active payment gateways
     *
     * @return array
     * @since 1.0
     */
    public function get_active_gateways() {
        return apply_filters( 'erp_pg_active_gateways', [] );
    }
}
