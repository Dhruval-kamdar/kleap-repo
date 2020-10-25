<?php
namespace weDevs\ERP_PRO\ACC\Inventory;
/*
Plugin Name: WP ERP - Inventory
Plugin URI: http://wperp.com/downloads/inventory/
Description: Manage and display your products purchase, order and stock.
Version: 1.3.1
Author: weDevs
Author URI: http://wedevs.com
Text Domain: erp-inventory
Domain Path: languages
License: GPL2
*/

/**
 * Copyright (c) YEAR Your Name (email: Email). All rights reserved.
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
 * Base_Plugin class
 *
 * @class Base_Plugin The class that holds the entire Base_Plugin plugin
 */
class Module {

    /**
     * Constructor for the Base_Plugin class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     *
     * @uses register_activation_hook()
     * @uses register_deactivation_hook()
     * @uses is_admin()
     * @uses add_action()
     */

    /* plugin version
    *
    * @var string
    */
    public $version = '1.3.1';

    /**
     * Holds various class instances
     *
     * @var array
     */
    private $container = array();


    public function __construct() {

        $this->define_constants();

        add_action( 'erp_accounting_loaded', array( $this, 'erp_accounting_loaded_hook' ) );
        if ( version_compare( ERP_INVENTORY_VERSION, '1.1.0', '<' ) ) {
            add_action( 'admin_print_footer_scripts', array( $this, 'highlight_menu' ) );
            add_filter( 'parent_file', array( $this, 'highlight_submenu' ) );
        }

        // on activate plugin register hook
        add_action( 'erp_pro_activated_module_inventory', array( $this, 'activate' ) );

        // on register deactivation hook
        add_action( 'erp_pro_deactivated_module_inventory', array( $this, 'deactivate' ) );

    }

    public function erp_accounting_loaded_hook() {

        $this->includes();

        $this->init_classes();

        $this->actions();

        $this->filters();

    }

    /**
     * Initializes the Base_Plugin() class
     *
     * Checks for an existing Base_Plugin() instance
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
     * define the plugin constant
     *
     * @return void
     */
    public function define_constants() {
        define( 'ERP_INVENTORY_VERSION', $this->version );
        define( 'ERP_INVENTORY_FILE', __FILE__ );
        define( 'ERP_INVENTORY_PATH', dirname( ERP_INVENTORY_FILE ) );
        define( 'ERP_INVENTORY_INCLUDES', ERP_INVENTORY_PATH . '/includes' );
        define( 'ERP_INVENTORY_URL', plugins_url( '', ERP_INVENTORY_FILE ) );
        define( 'ERP_INVENTORY_ASSETS', ERP_INVENTORY_URL . '/assets' );
        define( 'ERP_INVENTORY_API', ERP_INVENTORY_INCLUDES . '/api' );

        define( 'WPERP_INV_PATH', ERP_INVENTORY_PATH . '/deprecated' );
        define( 'WPERP_INV_INCLUDES', WPERP_INV_PATH . '/includes' );
        define( 'WPERP_INV_URL', plugins_url( 'deprecated', ERP_INVENTORY_FILE ) );
        define( 'WPERP_INV_ASSETS', WPERP_INV_URL . '/assets' );
        define( 'WPERP_INV_VIEWS', WPERP_INV_INCLUDES . '/admin/views' );
        define( 'WPERP_INV_JS_TMPL', WPERP_INV_VIEWS . '/js-templates' );
    }

    /**
     * include necessary files
     *
     * @return void
     */
    public function includes() {
        if ( version_compare( WPERP_VERSION, '1.5.0', '<' ) ) {
            require_once WPERP_INV_INCLUDES . '/functions-inventory.php';
            require_once WPERP_INV_INCLUDES . '/class-ajax.php';
            require_once WPERP_INV_INCLUDES . '/class-inventory.php';
            require_once WPERP_INV_INCLUDES . '/class-form-handler.php';

            if ( ! class_exists( 'WP_List_Table' ) ) {
                require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
            }
        } else {
            require_once ERP_INVENTORY_INCLUDES . '/classes/class-assets.php';
            require_once ERP_INVENTORY_API . '/class-controller-rest-api.php';

            if ( $this->is_request( 'admin' ) ) {
                require_once ERP_INVENTORY_INCLUDES . '/classes/class-i18n.php';
                require_once ERP_INVENTORY_INCLUDES . '/classes/class-admin.php';
            }

            foreach ( glob( ERP_INVENTORY_INCLUDES . '/functions/*.php' ) as $filename ) {
                include_once $filename;
            }
        }
    }

    /**
     * init classes
     *
     * @return void
     */
    public function init_classes() {
        if ( version_compare( WPERP_VERSION, '1.5.0', '<' ) ) {
            new \WeDevs\ERP\Inventory\Ajax_Handler();
            new \WeDevs\ERP\Inventory\Inventory();
        } else {
            if ( $this->is_request( 'admin' ) ) {
                $this->container['i18n']  = new \Erp_Inventory\ERP_Inventory_i18n();
                $this->container['admin'] = new \Erp_Inventory\Admin();
            }

            $this->container['rest']   = new \Erp_Inventory\API\REST_API();
            $this->container['assets'] = new \Erp_Inventory\Assets();
        }

    }

    /**
     * actions
     *
     * @return void
     */
    public function actions() {

        if ( version_compare( WPERP_VERSION, '1.5.0', '>=' ) ) {
            add_action( 'erp_acct_after_new_acct_populate_data', array( $this, 'migrate_inventory_data' ) );
            return;
        }

        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'admin_menu', array( $this, 'set_inventory_menu' ), 11 );
        add_action( 'parent_file', array( $this, 'product_category_menu_correction' ) );
        add_action( 'parent_file', array( $this, 'product_menu_correction' ) );
        add_action( 'erp_ac_trans_form_body_view', array( $this, 'inventory_product_dropdown' ), 10, 3 );
        add_action( 'admin_notices', array( $this, 'inv_show_admin_notice' ) );
    }

    /*
     * product duplicate in csv file
     *
     * @return bool
     */
    public function array_has_duplicate_values( $array ) {
        if ( count( $array ) !== count( array_unique( $array ) ) ) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * show admin notice
     * para
     * @return void
     */
    public function inv_show_admin_notice() {
        if ( version_compare( WPERP_VERSION, '1.5.0', '>=' ) ) {
            return;
        }

        if ( isset( $_REQUEST['action'] ) == 'edit' ) {
            if ( isset( $_REQUEST['invprod-msg'] ) && $_REQUEST['invprod-msg'] == 2 ) { ?>
                <div class="notice notice-error is-dismissible">
                <p><?php _e( 'Product item code must be unique.', 'erp-pro' ); ?></p>
                </div><?php }
            if ( isset( $_REQUEST['invprod-msg'] ) && $_REQUEST['invprod-msg'] == 3 ) { ?>
                <div class="notice notice-error is-dismissible">
                <p><?php _e( 'Please enter numeric number value in any price fields.', 'erp-pro' ); ?></p>
                </div><?php }
            if ( isset( $_REQUEST['invprod-msg'] ) && $_REQUEST['invprod-msg'] == 4 ) { ?>
                <div class="notice notice-error is-dismissible">
                <p><?php _e( 'Cost price should be less than sale price.', 'erp-pro' ); ?></p>
                </div><?php }
        }
        if ( isset( $_REQUEST['invprod-msg'] ) && $_REQUEST['invprod-msg'] == 5 ) { ?>
            <div class="notice notice-error is-dismissible">
                <p><?php _e( 'Given file is not csv. Please give a csv file.', 'erp-pro' ); ?></p>
            </div>
        <?php }
        if ( isset( $_REQUEST['invprod-msg'] ) && $_REQUEST['invprod-msg'] == 6 ) { ?>
            <div class="notice notice-success is-dismissible">
                <p><?php _e( 'Products imported successfully.', 'erp-pro' ); ?></p>
            </div>
        <?php }
        if ( isset( $_REQUEST['invprod-msg'] ) && $_REQUEST['invprod-msg'] == 7 ) { ?>
            <div class="notice notice-error is-dismissible">
                <p><?php _e( 'Please give an unique product name in your csv file then import again.', 'erp-pro' ); ?></p>
            </div>
        <?php }
        if ( isset( $_REQUEST['invprod-msg'] ) && $_REQUEST['invprod-msg'] == 8 ) { ?>
            <div class="notice notice-error is-dismissible">
                <p><?php _e( 'Item code should not be empty. Please enter unique item code value in your csv file then import again.', 'erp-pro' ); ?></p>
            </div>
        <?php }
        if ( isset( $_REQUEST['invprod-msg'] ) && $_REQUEST['invprod-msg'] == 9 ) { ?>
            <div class="notice notice-error is-dismissible">
                <p><?php _e( "Item code should be unique. One of your item code value is matching to your published product's item code. Please enter unique item code value in your csv file then try again.", 'erp-pro' ); ?></p>
            </div>
        <?php }
        if ( isset( $_REQUEST['invprod-msg'] ) && $_REQUEST['invprod-msg'] == 10 ) { ?>
            <div class="notice notice-error is-dismissible">
                <p><?php _e( 'Product name should not be empty. Please enter product name in your csv file then import again.', 'erp-pro' ); ?></p>
            </div>
        <?php }
        if ( isset( $_REQUEST['invprod-msg'] ) && $_REQUEST['invprod-msg'] == 11 ) { ?>
            <div class="notice notice-error is-dismissible">
                <p><?php _e( 'Product importing failed', 'erp-pro' ); ?></p>
            </div>
        <?php }
    }

    /**
     * add bulk product add button
     *
     * @return void
     */
    public function add_bulk_product_add_button() {
        if ( version_compare( WPERP_VERSION, '1.5.0', '>=' ) ) {
            return;
        }
        ?>
        <script>
            jQuery(function () {
                jQuery("body.post-type-erp_inv_product .wrap h1").append('<a href="#" id="product-import-button" class="page-title-action"><?php _e( 'Import Products', 'erp-pro' );?></a><span class="spinner import-spinner"></span>');
            });
        </script>
        <form method="post" enctype="multipart/form-data" style="position: absolute; visibility: hidden;">
            <input type="file" id="erp-inv-product-import-input">
        </form><?php
    }

    /**
     * bind inventory product dropdown
     *
     * @return void
     */
    public function inventory_product_dropdown( $head, $header_slug, $item ) {
        if ( version_compare( WPERP_VERSION, '1.5.0', '>=' ) ) {
            return;
        }
        $dropdown_html = erp_inv_render_product_dropdown_html( $head, $header_slug, $item );
        echo $dropdown_html;
    }

    /*
     * making product purchase button
     *
     * @return void
     */
    public function purchase_bill_link() {
        if ( version_compare( WPERP_VERSION, '1.5.0', '>=' ) ) {
            return;
        }
        printf( '<a class="add-new-h2" href="%s%s" title="%s">%s</a> ', admin_url( 'admin.php?page=erp-accounting&section=expense&action=new&type=' ),
            'purchase_bill', 'A product purchase that has been made as credit from vendor.', 'Product Purchase' );
    }

    /*
     * making product sales button
     *
     * @return void
     */
    public function sales_invoice_link() {
        if ( version_compare( WPERP_VERSION, '1.5.0', '>=' ) ) {
            return;
        }
        printf( '<a class="add-new-h2" href="%s%s" title="%s">%s</a> ', admin_url( 'admin.php?page=erp-accounting&section=sales&action=new&type=' ),
            'sales_invoice', 'A product sales that has been made as credit from customer.', 'Product Sales' );
    }

    /**
     * filters
     *
     * @return void
     */
    public function filters() {
        if ( version_compare( WPERP_VERSION, '1.5.0', '>=' ) ) {
            return;
        }
        add_filter( 'erp_ac_reports', array( $this, 'inventory_reports' ) );
        add_filter( 'erp_ac_reporting_pages', array( $this, 'inventory_report_pages' ), 10, 2 );
        add_filter( 'erp_ac_trans_form_header', [ $this, 'inventory_product_purchase_n_sales_header' ] );
        add_filter( 'erp_ac_transaction_lines', [ $this, 'add_product_id_to_item_list' ], 10, 3 );
        add_filter( 'erp_import_export_csv_fields', [ $this, 'import_product_type' ] );
    }

    /*
     * Filters the export import types
     *
     * @return array
     */
    public function import_product_type( $export_import_fields ) {
        if ( version_compare( WPERP_VERSION, '1.5.0', '>=' ) ) {
            return;
        }

        $eif['product'] = [
            'required_fields' => [
                'product_name',
                'sku'
            ],
            'fields'          => [
                'product_name',
                'asset_account',
                'sku',
                'cost_price',
                'purchase_account',
                'tax_rate_on_purchase',
                'purchase_description',
                'sale_price',
                'sale_account',
                'tax_rate_on_sale',
                'sale_description',
                'short_description'
            ]
        ];
        return $export_import_fields + $eif;
    }

    /*
     * added product id to line items
     *
     * @return array
     */
    public function add_product_id_to_item_list( $line_item, $key, $posted ) {
        if ( version_compare( WPERP_VERSION, '1.5.0', '>=' ) ) {
            return;
        }

        if ( isset( $posted['inv-product-id'][$key] ) ) {
            $line_item['product_id'] = $posted['inv-product-id'][$key];
        }
        return $line_item;
    }

    /*
     * add product drop down in expense
     *
     * @return array
     */
    public function inventory_product_purchase_n_sales_header( $input ) {
        if ( version_compare( WPERP_VERSION, '1.5.0', '>=' ) ) {
            return;
        }

        $new_col_header = [
            'product_header' => __( 'Product', 'erp-pro' )
        ];
        $new_col        = $new_col_header + $input;
        return $new_col;
    }

    /*
     * adding inventory reports title and description
     *
     * @return array
     */
    public function inventory_reports( $reports ) {
        if ( version_compare( WPERP_VERSION, '1.5.0', '>=' ) ) {
            return;
        }

        $reps = [
            'purchase-report'  => [
                'title'       => __( 'Product Purchase', 'erp-pro' ),
                'description' => __( 'Product Purchases history will be shown here with date between facility', 'erp-pro' )
            ],
            'sales-report'     => [
                'title'       => __( 'Product Sales', 'erp-pro' ),
                'description' => __( 'Product Sales history will be shown here with date between facility', 'erp-pro' )
            ],
            'inventory-report' => [
                'title'       => __( 'Inventory Report', 'erp-pro' ),
                'description' => __( 'Product purchase and sales history will be shown here with date between facility', 'erp-pro' )
            ]
        ];

        return $reports + $reps;
    }

    /*
     * adding inventory reports purchase page
     *
     * @return array
     */
    public function inventory_report_pages( $template, $type ) {
        if ( version_compare( WPERP_VERSION, '1.5.0', '>=' ) ) {
            return;
        }

        if ( 'purchase-report' === $type ) {
            $template = require_once WPERP_INV_VIEWS . '/reports/purchase-report.php';
        } else {
            if ( 'sales-report' === $type ) {
                $template = require_once WPERP_INV_VIEWS . '/reports/sales-report.php';
            } else {
                if ( 'inventory-report' === $type ) {
                    $template = require_once WPERP_INV_VIEWS . '/reports/inventory-report.php';
                }
            }
        }

        return $template;
    }

    /**
     * Enqueue admin scripts
     *
     * @since 1.0.0
     * @since 1.1.0 Load scripts only in specific pages
     *
     * Allows plugin assets to be loaded.
     *
     * @uses wp_enqueue_script()
     * @uses wp_localize_script()
     * @uses wp_enqueue_style
     */
    public function enqueue_scripts( $hook ) {
        global $post;
        global $current_screen;

        if ( version_compare( WPERP_VERSION, '1.5.0', '>=' ) ) {
            return;
        }

        if ( 'wp-erp_page_erp-accounting' == $current_screen->base ) {
            $hook_suffix = isset( $_GET['section'] ) ? $_GET['section'] : 'dashboard';
            $hook        .= '-' . $hook_suffix;
        }

        $inventory_pages = [
            'accounting_page_erp-accounting-sales',
            'accounting_page_erp-accounting-expense',
            'accounting_page_erp-accounting-reports'
        ];

        if ( ! self::need_backward_compatible() ) {
            $inventory_pages = [
                'wp-erp_page_erp-accounting-sales',
                'wp-erp_page_erp-accounting-expense',
                'wp-erp_page_erp-accounting-reports'
            ];
        }

        $enqueue_scripts = false;

        if ( in_array( $hook, $inventory_pages ) ) {
            $enqueue_scripts = true;
        } else {
            if ( ! empty( $post->post_type ) && 'erp_inv_product' === $post->post_type ) {
                $enqueue_scripts = true;
            }
        }

        if ( ! $enqueue_scripts ) {
            return;
        }

        /**
         * All styles goes here
         */
        wp_enqueue_style( 'erp-inventory-style', WPERP_INV_ASSETS . '/css/stylesheet.css' );
        wp_enqueue_style( 'erp-timepicker' );
        wp_enqueue_style( 'erp-fullcalendar' );
        wp_enqueue_style( 'erp-sweetalert' );

        /**
         * All scripts goes here
         */
        wp_enqueue_script( 'erp-vuejs' );
        wp_enqueue_script( 'erp-inventory-script', WPERP_INV_ASSETS . '/js/erp-inventory.js', array( 'jquery' ), false, true );
        wp_enqueue_script( 'erp-app-inventory', WPERP_INV_ASSETS . '/js/app-inventory.js', array( 'jquery' ), false, true );
        wp_enqueue_script( 'jquery-ui-autocomplete' );
        wp_enqueue_script( 'erp-timepicker' );
        wp_enqueue_script( 'erp-fullcalendar' );
        wp_enqueue_script( 'erp-sweetalert' );

        $localize_scripts = [
            'nonce'     => wp_create_nonce( 'inventory_nonce' ),
            'admin_url' => admin_url( 'admin.php' ),
        ];

        wp_localize_script( 'erp-inventory-script', 'wpErpInv', $localize_scripts );
    }

    /**
     * set inventory menu
     *
     * @return void
     */
    public function set_inventory_menu() {
        if ( version_compare( WPERP_VERSION, '1.5.0', '>=' ) ) {
            return;
        }
        /* inventory menu */
        $capability = 'erp_ac_manager';

        if ( self::need_backward_compatible() ) {
            $this->get_old_menus( $capability );
        } else {
            $this->get_new_menus( $capability );
        }
    }

    /**
     * product category menu correction
     * para
     * @return void
     **/
    public function product_category_menu_correction() {
        global $current_screen;

        if ( version_compare( WPERP_VERSION, '1.5.0', '>=' ) ) {
            return;
        }

        $taxonomy = $current_screen->taxonomy;
        if ( $taxonomy == 'product_category' ) {
            $parent_file = 'erp-accounting';
            return $parent_file;
        }
    }

    /**
     * product menu correction
     * para
     * @return void
     **/
    public function product_menu_correction() {
        global $current_screen;

        if ( version_compare( WPERP_VERSION, '1.5.0', '>=' ) ) {
            return;
        }

        $post_type = $current_screen->post_type;
        if ( $post_type == 'erp_inv_product' ) {
            $parent_file = 'erp-accounting';
            return $parent_file;
        }
    }

    /**
     * check if backward compatibility is needed
     * para
     * @return boolean
     **/
    public static function need_backward_compatible() {
        if ( version_compare( WPERP_VERSION, '1.5.0', '>=' ) ) {
            return;
        }

        $installed_version = get_option( 'wp_erp_version' );
        $new_version       = '1.4.0';

        if ( ! is_null( $installed_version ) && version_compare( $installed_version, $new_version, '<' ) ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * get old menus
     * para
     * @return void
     **/
    public function get_old_menus( $capability ) {
        add_submenu_page( 'erp-accounting', __( 'Inventory', 'erp-pro' ), __( 'Inventory', 'erp-pro' ),
            $capability, 'edit.php?post_type=erp_inv_product' );
        add_submenu_page( 'erp-accounting', __( 'Product categories', 'erp-pro' ), __( 'Product categories', 'erp-pro' ),
            $capability, 'edit-tags.php?taxonomy=product_category' );
    }

    /**
     * get new menus
     * para
     * @return mixed
     **/
    public function get_new_menus( $capability ) {
        if ( version_compare( WPERP_VERSION, '1.5.0', '>=' ) ) {
            return;
        }
        erp_add_menu( 'accounting', [
            'title'      => __( 'Inventory', 'erp' ),
            'capability' => $capability,
            'slug'       => 'erp_inv_product',
            'position'   => 38,
        ] );
        erp_add_submenu( 'accounting', 'erp_inv_product', array(
            'title'      => __( 'Product Categories', 'erp' ),
            'capability' => $capability,
            'slug'       => 'erp_inv_product_category',
            'position'   => 1,
        ) );
    }

    /**
     * Highlight Menu for inventory
     */
    public function highlight_menu() {
        $screen = get_current_screen();

        if ( $screen->id != 'edit-erp_inv_product' || $screen->id != 'edit-product_category' ) {
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    $('li.toplevel_page_erp').removeClass('wp-not-current-submenu').addClass('wp-has-current-submenu wp-menu-open');
                    $('li.toplevel_page_erp a:first').removeClass('wp-not-current-submenu').addClass('wp-has-current-submenu wp-menu-open');
                });
            </script>
            <?php
        }

    }

    /**
     * Highlight sunbmenu for inventory
     *
     * @param $parent_file
     *
     * @return string
     */
    public function highlight_submenu( $parent_file ) {
        global $parent_file, $submenu_file, $post_type;
        $screen = get_current_screen();
        if ( 'erp_inv_product' == $post_type || $screen->id == 'edit-product_category' ) {
            $parent_file  = 'admin.php?page=erp';
            $submenu_file = 'erp-accounting';
        }
        return $parent_file;
    }

    /**
     * Magic getter to bypass referencing plugin.
     *
     * @param $prop
     *
     * @return mixed
     */
    public function __get( $prop ) {
        if ( array_key_exists( $prop, $this->container ) ) {
            return $this->container[$prop];
        }

        return $this->{$prop};
    }

    /**
     * Magic isset to bypass referencing plugin.
     *
     * @param $prop
     *
     * @return mixed
     */
    public function __isset( $prop ) {
        return isset( $this->{$prop} ) || isset( $this->container[$prop] );
    }

    /**
     * What type of request is this?
     *
     * @param string $type admin, ajax, cron or frontend.
     *
     * @return bool
     */
    private function is_request( $type ) {
        switch ( $type ) {
            case 'admin' :
                return is_admin();

            case 'ajax' :
                return defined( 'DOING_AJAX' );

            case 'rest' :
                return defined( 'REST_REQUEST' );

            case 'cron' :
                return defined( 'DOING_CRON' );
        }
    }

    /**
     * Placeholder for activation function
     *
     * Nothing being called here yet.
     */
    public function activate() {

        $this->create_table();

        $installed = get_option( 'erp_acct_inventory_installed' );

        if ( ! $installed ) {
            update_option( 'erp_acct_inventory_installed', time() );
        }

        update_option( 'erp_acct_inventory_version', ERP_INVENTORY_VERSION );
    }

    /**
     * Placeholder for creating tables while activationg plugin
     *
     * @return void
     * @since 1.2
     */
    private function create_table() {

        global $wpdb;

        $collate = '';

        if ( $wpdb->has_cap( 'collation' ) ) {
            if ( ! empty( $wpdb->charset ) ) {
                $collate .= "DEFAULT CHARACTER SET $wpdb->charset";
            }

            if ( ! empty( $wpdb->collate ) ) {
                $collate .= " COLLATE $wpdb->collate";
            }
        }

        /* Table Schemas */
        $table_schema = [
            "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}erp_acct_product_price` (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `product_id` int(11) DEFAULT NULL,
                `trn_no` int(11) DEFAULT NULL,
                `price` decimal(10,2) DEFAULT 0,
                `trn_date` date DEFAULT NULL,
                `created_at` date DEFAULT NULL,
                `created_by` varchar(50) DEFAULT NULL,
                `updated_at` date DEFAULT NULL,
                `updated_by` varchar(50) DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) $collate;",
        ];

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        foreach ( $table_schema as $table ) {
            dbDelta( $table );
        }
    }

    /**
     * Migrate inventory data to new  accounting tables
     */
    public function migrate_inventory_data() {
        global $wpdb;

        $created_by = get_current_user_id();
        $created_at = date( 'Y-m-d' );

        $inv_categories = get_terms( array(
            'taxonomy'   => 'product_category',
            'hide_empty' => false,
        ) );

        if ( !empty( $inv_categories ) ) {
            foreach ( $inv_categories as $inv_category ) {
                $wpdb->insert( $wpdb->prefix . 'erp_acct_product_categories', array(
                    'id'         => $inv_category->term_id,
                    'name'       => $inv_category->name,
                    'parent'     => $inv_category->parent,
                    'created_at' => $created_at,
                    'created_by' => $created_by,
                ) );
            }
        }

        $inv_products = get_posts( array( 'post_type' => 'erp_inv_product', 'posts_per_page' => -1, 'order' => 'ASC' ) );

        if ( !empty( $inv_products ) ) {
            foreach ( $inv_products as $inv_product ) {
                $get_cost_price = ( get_post_meta( $inv_product->ID, '_cost_price', true ) == "" ? 0 : get_post_meta( $inv_product->ID, '_cost_price', true ) );
                $get_sale_price = ( get_post_meta( $inv_product->ID, '_sale_price', true ) == "" ? 0 : get_post_meta( $inv_product->ID, '_sale_price', true ) );

                $categories = get_the_terms( $inv_product->ID, 'product_category' );
                $category   = array_pop( $categories );

                $wpdb->insert( $wpdb->prefix . 'erp_acct_products', array(
                    'id'              => $inv_product->ID,
                    'name'            => $inv_product->post_title,
                    'product_type_id' => 1,
                    'category_id'     => $category->term_id,
                    'tax_cat_id'      => null,
                    'vendor'          => null,
                    'cost_price'      => $get_cost_price,
                    'sale_price'      => $get_sale_price,
                    'created_at'      => $created_at,
                    'created_by'      => $created_by,
                ) );
            }
        }
    }

    /**
     * Placeholder for deactivation function
     *
     * Nothing being called here yet.
     */
    public function deactivate() {

    }


} // WeDevs_ERP_Inventory
