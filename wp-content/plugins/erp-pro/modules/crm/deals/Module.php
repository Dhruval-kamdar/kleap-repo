<?php
namespace weDevs\ERP_PRO\CRM\Deals;
/**
 * Plugin Name: WP ERP - Deals
 * Description: Deal Management add-on for WP ERP - CRM Module
 * Plugin URI: http://wperp.com/downloads/erp-deals
 * Author: weDevs
 * Author URI: http://wedevs.com
 * Version: 1.1.2
 * License: GPL2
 * Text Domain: erp-deals
 * Domain Path: languages
 *
 * Copyright (c) 2016 Tareq Hasan (email: info@wedevs.com). All rights reserved.
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
 * Deals plugin main class
 *
 * @since 1.0.0
 */
class Module {

	/**
	 * Add-on Version
	 *
	 * @var  string
	 */
	public $version = '1.1.2';

    /**
     * Initializes the class
     *
     * Checks for an existing instance
     * and if it doesn't find one, creates it.
     *
     * @since 1.0.0
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
     * @since 1.0.0
     *
     * @return void
     */
    private function __construct() {
        // on activate plugin register hook
        add_action( 'erp_pro_activated_module_deals', array( $this, 'activate' ) );

        // on register deactivation hook
        add_action( 'erp_pro_deactivated_module_deals', array( $this, 'deactivate' ) );

        // plugin not installed - notice
        add_action( 'plugins_loaded', [ $this, 'plugins_loaded' ] );

        // crm loaded hook
        add_action( 'erp_crm_loaded', [ $this, 'erp_crm_loaded' ] );

        add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), [ $this, 'plugin_action_links' ] );
    }

    /**
     * Plugins loaded hook
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function plugins_loaded() {

    }

    /**
     * Add action links
     *
     * @param $links
     *
     * @return array
     */
    public function plugin_action_links( $links ) {
        if ( version_compare( WPERP_VERSION, "1.4.0", '>=' ) ) {
            $links[] = '<a href="' . admin_url( 'admin.php?page=erp-crm&section=deals' ) . '">' . __( 'Manage Deals', 'erp-pro' ) . '</a>';
        }
        $links[] = '<a href="' . admin_url( 'admin.php?page=erp-settings&tab=erp-crm&section=erp_deals' ) . '">' . __( 'Settings', 'erp-pro' ) . '</a>';
        return $links;
    }

    /**
     * Executes during plugin activation
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function activate() {
        $this->create_table();
        $this->insert_initial_table_data();
        $this->create_files();
    }

    /**
     * Executes during plugin deactivation
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function deactivate() {

    }

    /**
     * Placeholder for creating tables while activating plugin
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function create_table() {

        global $wpdb;

        $collate = '';

        if ( $wpdb->has_cap( 'collation' ) ) {
            if ( ! empty($wpdb->charset ) ) {
                $collate .= "DEFAULT CHARACTER SET $wpdb->charset";
            }

            if ( ! empty($wpdb->collate ) ) {
                $collate .= " COLLATE $wpdb->collate";
            }
        }

        $table_schema = include_once dirname( __FILE__ ) . '/table-schema.php';

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        foreach ( $table_schema as $table ) {

            dbDelta( $table );
        }
    }

    /**
     * Insert default data for the plugin during installation
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function insert_initial_table_data() {
        include_once dirname( __FILE__ ) . '/table-data.php';
    }

    /**
     * Install files and folders for uploading files and prevent hotlinking
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function create_files() {
        $upload_dir      = wp_upload_dir();

        $files = array(
            array(
                'base'      => $upload_dir['basedir'] . '/erp-deals-uploads',
                'file'      => 'index.html',
                'content'   => ''
            ),
            array(
                'base'      => $upload_dir['basedir'] . '/erp-deals-uploads',
                'file'      => '.htaccess',
                'content'   => 'deny from all'
            ),
        );

        foreach ( $files as $file ) {
            if ( wp_mkdir_p( $file['base'] ) && !file_exists( trailingslashit( $file['base'] ) . $file['file'] ) ) {
                if ( $file_handle = @fopen( trailingslashit( $file['base'] ) . $file['file'], 'w' ) ) {
                    fwrite( $file_handle, $file['content'] );
                    fclose( $file_handle );
                }
            }
        }
    }

    /**
     * Executes if CRM is installed
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function erp_crm_loaded() {
        $this->define_constants();
        $this->includes();
    }

    /**
     * Define Add-on constants
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function define_constants() {
        define( 'WPERP_DEALS_VERSION', $this->version );
        define( 'WPERP_DEALS_FILE', __FILE__ );
        define( 'WPERP_DEALS_PATH', dirname( WPERP_DEALS_FILE ) );
        define( 'WPERP_DEALS_INCLUDES', WPERP_DEALS_PATH . '/includes' );
        define( 'WPERP_DEALS_URL', plugins_url( '', WPERP_DEALS_FILE ) );
        define( 'WPERP_DEALS_ASSETS', WPERP_DEALS_URL . '/assets' );
        define( 'WPERP_DEALS_VIEWS', WPERP_DEALS_PATH . '/views' );
    }

    /**
     * Include required files
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function includes() {
        include_once WPERP_DEALS_INCLUDES . '/models/deal.php';
        include_once WPERP_DEALS_INCLUDES . '/models/activity.php';
        include_once WPERP_DEALS_INCLUDES . '/models/activity-type.php';
        include_once WPERP_DEALS_INCLUDES . '/models/pipeline.php';
        include_once WPERP_DEALS_INCLUDES . '/models/pipeline-stage.php';
        include_once WPERP_DEALS_INCLUDES . '/models/stage-history.php';
        include_once WPERP_DEALS_INCLUDES . '/models/lost-reason.php';
        include_once WPERP_DEALS_INCLUDES . '/models/participant.php';
        include_once WPERP_DEALS_INCLUDES . '/models/agent.php';
        include_once WPERP_DEALS_INCLUDES . '/models/note.php';
        include_once WPERP_DEALS_INCLUDES . '/models/attachment.php';
        include_once WPERP_DEALS_INCLUDES . '/models/competitor.php';
        include_once WPERP_DEALS_INCLUDES . '/models/email.php';
        include_once WPERP_DEALS_INCLUDES . '/erp-helper.php';
        include_once WPERP_DEALS_INCLUDES . '/class-shortcodes.php';
        include_once WPERP_DEALS_INCLUDES . '/class-helpers.php';
        include_once WPERP_DEALS_INCLUDES . '/class-hooks.php';
        include_once WPERP_DEALS_INCLUDES . '/class-log.php';
        include_once WPERP_DEALS_INCLUDES . '/class-deals.php';

        // admin functionalities
        add_action( 'init', function () {
            if ( current_user_can( 'administrator' ) || erp_crm_is_current_user_manager() || erp_crm_is_current_user_crm_agent() ) {
                include_once WPERP_DEALS_INCLUDES . '/class-admin.php';
            }

            if ( !is_admin() ) {
                include_once WPERP_DEALS_INCLUDES . '/class-frontend.php';
            }
        } );


    }

}
