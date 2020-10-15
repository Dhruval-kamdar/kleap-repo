<?php
namespace weDevs\ERP_PRO\PRO\HR_Frontend;
/*
Plugin Name: WP ERP - HR Frontend
Plugin URI: https://wperp.com/
Description: Provides a brand new dashboard experience for WordPress ERP
Version: 2.1.2
Author: WP ERP
Author URI: https://wperp.com/
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: erp-hr-frontend
Domain Path: /languages
*/

/**
 * Copyright (c) 2017 weDevs (email: info@wedevs.com). All rights reserved.
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

/**
 * ERP_Frontend class
 *
 * @class ERP_Frontend The class that holds the entire ERP_Frontend plugin
 */
class Module {

    /**
     * Plugin version
     *
     * @var string
     */
    public $version = '2.1.2';

    public static $text_domain = 'erp-hr-frontend';

    /**
     * Constructor for the ERP_Frontend class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     *
     * @uses register_activation_hook()
     * @uses register_deactivation_hook()
     * @uses is_admin()
     * @uses add_action()
     */
    public function __construct() {

        $this->define_constants();

        // on activate plugin register hook
        add_action( 'erp_pro_activated_module_hr_frontend', array( $this, 'activate' ) );

        // on register deactivation hook
        add_action( 'erp_pro_deactivated_module_hr_frontend', array( $this, 'deactivate' ) );
        $this->includes();
        $this->init_hooks();
    }
    /**
     * Define the constants
     *
     * @return void
     */
    public function define_constants() {
        define( 'ERP_DASHBOARD_VERSION', $this->version );
        define( 'ERP_DASHBOARD_FILE', __FILE__ );
        define( 'ERP_DASHBOARD_PATH', dirname( ERP_DASHBOARD_FILE ) );
        define( 'ERP_DASHBOARD_INCLUDES', ERP_DASHBOARD_PATH . '/includes' );
        define( 'ERP_DASHBOARD_URL', plugins_url( '', ERP_DASHBOARD_FILE ) );
        define( 'ERP_DASHBOARD_ASSETS', ERP_DASHBOARD_URL . '/assets' );
    }

    /**
     * Initializes the ERP_Frontend() class
     *
     * Checks for an existing ERP_Frontend() instance
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
     * Placeholder for activation function
     *
     * Nothing being called here yet.
     */
    public function activate() {
        global $wp_rewrite;

        $wp_rewrite->flush_rules( false );

        update_option( 'erp_dashboard_version', ERP_DASHBOARD_VERSION );
    }

    /**
     * Placeholder for deactivation function
     *
     * Nothing being called here yet.
     */
    public function deactivate() {

    }

    /**
     * Include the required files
     *
     * @return void
     */
    public function includes() {
        require_once ERP_DASHBOARD_INCLUDES . '/functions.php';
        require_once ERP_DASHBOARD_INCLUDES . '/class-rewrites.php';
        require_once ERP_DASHBOARD_INCLUDES . '/class-settings.php';
    }

    /**
     * Initialize the hooks
     *
     * @return void
     */
    public function init_hooks() {
        add_action( 'plugins_loaded', array( $this, 'init_classes' ) );
    }

    /**
     * Init the classes
     *
     * @return void
     */
    public function init_classes() {
        new \ERP_Frontend_Rewrites();
    }

} // ERP_Frontend
