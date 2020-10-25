<?php
namespace weDevs\ERP_PRO\PRO\Salesforce;
/**
 * Plugin Name: WP ERP - Salesforce Contacts Sync
 * Description: Sync your CRM contacts with salesforce
 * Plugin URI: https://wperp.com/downloads/salesforce-contacts-sync/
 * Author: weDevs
 * Author URI: http://wedevs.com
 * Version: 1.1.1
 * License: GPL2
 * Text Domain: erp-salesforce
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
if ( !defined( 'ABSPATH' ) ) exit;

class Module {
    /**
     * Add-on Version
     *
     * @var  string
     */
    public $version = '1.1.1';

    /**
     * Class constructor.
     */
    private function __construct() {
        // load the addon
        add_action( 'erp_crm_loaded', array( $this, 'plugin_init' ) );
    }

    /**
     * Initialize the class.
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Init the plugin.
     *
     * @return void
     */
    public function plugin_init() {
	    include dirname( __FILE__ ) . '/includes/erp-helper.php';
	    include dirname( __FILE__ ) . '/includes/functions.php';

        // Define constants
        $this->define_constants();

        // Instantiate classes
        $this->init_classes();

        // Initialize the action hooks
        $this->init_actions();

        // Initialize the filter hooks
        $this->init_filters();
    }

    /**
     * Define the plugin constants.
     *
     * @return void
     */
    private function define_constants() {
        define( 'ERP_SALESFORCE_FILE', __FILE__ );
        define( 'ERP_SALESFORCE_PATH', dirname( ERP_SALESFORCE_FILE ) );
        define( 'ERP_SALESFORCE_INCLUDES', ERP_SALESFORCE_PATH . '/includes' );
        define( 'ERP_SALESFORCE_VIEWS', ERP_SALESFORCE_INCLUDES . '/views' );
        define( 'ERP_SALESFORCE_URL', plugins_url( '', ERP_SALESFORCE_FILE ) );
        define( 'ERP_SALESFORCE_ASSETS', ERP_SALESFORCE_URL . '/assets' );
    }

    /**
     * Init the plugin classes.
     *
     * @return void
     */
    private function init_classes() {
        new \WeDevs\ERP\Salesforce\Admin_Menu();

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            new \WeDevs\ERP\Salesforce\Ajax_Handler();
        }
    }

    /**
     * Init the plugin actions.
     *
     * @return void
     */
    private function init_actions() {
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'admin_footer', 'erp_salesforce_enqueue_js' );
    }

    /**
     * Init the plugin filters.
     *
     * @return void
     */
    private function init_filters() {
        add_filter( 'erp_integration_classes', [ $this, 'register_integrations' ] );
        add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), [ $this, 'plugin_action_links' ] );
    }

    /**
     * Add action links
     *
     * @param $links
     *
     * @return array
     */
    public function plugin_action_links( $links ) {
        $links[] = '<a href="' . admin_url( 'admin.php?page=erp-settings&tab=erp-integration&section=salesforce' ) . '">' . __( 'Settings', 'erp-pro' ) . '</a>';
        return $links;
    }

    /**
     * Enqueue scripts.
     */
    public function enqueue_scripts() {
        // styles
        wp_enqueue_style( 'erp-salesforce-styles', ERP_SALESFORCE_ASSETS . '/css/style.css', false );
    }

    /**
     * Register integrations.
     *
     * @param  array $integrations
     *
     * @return array
     */
    public function register_integrations( $integrations ) {
        $integrations['Salesforce'] = new \WeDevs\ERP\Salesforce\Salesforce_Integration();

        return $integrations;
    }
}
