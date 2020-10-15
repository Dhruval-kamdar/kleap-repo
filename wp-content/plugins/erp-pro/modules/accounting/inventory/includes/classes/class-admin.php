<?php

namespace Erp_Inventory;

/**
 * Admin Pages Handler
 */
class Admin {

    public function __construct() {
        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
        add_action( 'erp_acct_js_hook_loaded', [ $this, 'enqueue_scripts' ] );
    }

    /**
     * Register our menu page
     *
     * @return void
     */
    public function admin_menu() {
        if ( function_exists( 'erp_add_menu' ) ) {
            erp_add_menu( 'accounting', [
                'title'      => __( 'Inventory', 'erp-pro' ),
                'capability' => 'erp_ac_view_sale',
                'slug'       => 'inventory',
                'position'   => 190
            ] );
        }

        $page = isset( $_GET['page'] ) ? wp_unslash( $_GET['page'] ) : '';

        if ( $page === 'erp-accounting' ) : ?>
        <script>
        window.erpInventory = JSON.parse('<?php
            echo json_encode( apply_filters( 'erp_inventory_localized_data', [] ) );
        ?>');
        </script><?php endif;
    }

    /**
     * Initialize our hooks for the admin page
     *
     * @return void
     */
    public function init_hooks() {
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
    }

    /**
     * Load scripts and styles for the app
     *
     * @return void
     */
    public function enqueue_scripts() {
        wp_enqueue_style( 'erp-acct-inventory-admin' );
        wp_enqueue_script( 'erp-acct-inventory-admin' );
    }
}
