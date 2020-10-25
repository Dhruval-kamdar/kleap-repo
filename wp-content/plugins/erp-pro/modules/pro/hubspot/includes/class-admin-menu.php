<?php

namespace WeDevs\ERP\Hubspot;

class Admin_Menu {
    /**
     * Class contructor.
     */
    public function __construct() {
        add_action( 'admin_menu', [ $this, 'admin_menu'] );
    }

    /**
     * Register the admin menu.
     *
     * @return void
     */
    public function admin_menu() {
        $capabilities = erp_crm_get_manager_role();
        if ( version_compare( WPERP_VERSION, '1.4.0', '<' ) ) {

            add_submenu_page( 'erp-sales', __( 'Hubspot', 'erp-pro' ), __( 'Hubspot', 'erp-pro' ), $capabilities, 'erp-sales-hubspot', array( $this, 'hubspot_page' ) );
        } else {
            erp_add_menu( 'crm', array(
                'title'         =>  __( 'Hubspot', 'erp-pro' ),
                'slug'          =>  'hubspot',
                'capability'    =>  $capabilities,
                'callback'      =>  [ $this, 'hubspot_page' ],
                'position'      =>  40
            ) );
        }

    }

    /**
     * Display the hubspot page.
     *
     * @return void
     */
    public function hubspot_page() {
        $action = isset( $_GET['action'] ) ? $_GET['action'] : 'dashboard';

        if ( $action == 'disconnect' ) {
            delete_option( 'erp_integration_settings_hubspot-integration' );
        }

        $hubspot_settings_url = admin_url( 'admin.php?page=erp-settings&tab=erp-integration&section=hubspot' );

        $api_key = erp_hubspot_get_api_key();

        if ( ! $api_key ) {
            ?>
            <div class="wrap">
                <h2><?php _e( 'Hubspot Contacts Sync', 'erp-Hubspot' ); ?></h2>
                <p><?php _e( 'You\'re not connected with your Hubspot account yet. Click on below button to configure.', 'erp_hubspot' ); ?></p>
                <a href="<?php echo $hubspot_settings_url ?>"><button class="button-secondary">Configure</button></a>
            </div>
            <?php
        } else {
            include dirname( __FILE__ ) . '/views/dashboard.php';
        }
    }
}
