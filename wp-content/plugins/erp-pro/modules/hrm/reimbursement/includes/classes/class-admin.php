<?php

namespace Erp_People_Trn;

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
                'title'      => __( 'Reimbursement', 'erp-pro' ),
                'capability' => 'erp_ac_view_sale',
                'slug'       => 'reimbursements',
                'position'   => 190
            ] );
            if ( current_user_can( 'employee' ) ) {
                erp_add_menu( 'hr', array(
                    'title'      => __( 'Reimbursement', 'erp' ),
                    'capability' => 'employee',
                    'slug'       => 'reimbursement',
                    'callback'   => [ $this, 'reimbursement_page' ],
                    'position'   => 190
                ) );
            }
            erp_add_submenu( 'accounting', 'reimbursements', [
                'title'      => __( 'Requests', 'erp-acct-asset-management' ),
                'capability' => 'erp_ac_manager',
                'slug'       => 'reimbursements/requests',
                'position'   => 202
            ] );
        }

        $page    = isset( $_GET['page'] )    ? wp_unslash( $_GET['page'] ) : '';
        $section = isset( $_GET['section'] ) ? wp_unslash( $_GET['section'] ) : '';

        if ( $page === 'erp-hr' ) {
            if ( $section === 'reimbursement' ) { ?>
                <script>
                    window.erpReimbursement = JSON.parse('<?php echo json_encode(
                        apply_filters( 'erp_reimbursement_localized_data', [] )
                    ); ?>');
                </script>
            <?php }
        } elseif( $page === 'erp-accounting' ) { ?>
            <script>
                window.erpReimbursement = JSON.parse('<?php echo json_encode(
                    apply_filters( 'erp_reimbursement_localized_data', [] )
                ); ?>');
            </script> <?php
        }
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
        wp_enqueue_style( 'erp-reimbursement-admin' );
        wp_enqueue_script( 'erp-reimbursement-admin' );
    }

    /**
     * Render our admin page
     *
     * @return void
     */
    public function reimbursement_page() {
        ?>
        <script>
            window.erpAcct = {};

            function acct_get_lib(arg) {
                return null;
            }
        </script>
        <?php

        // $action   = isset( $_GET['action'] ) ? $_GET['action'] : 'list';
        echo '<div class="wrap"><div id="erp-reimbursement"></div></div>';
    }
}
