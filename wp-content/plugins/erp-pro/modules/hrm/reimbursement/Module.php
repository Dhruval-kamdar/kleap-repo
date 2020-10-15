<?php
namespace weDevs\ERP_PRO\HRM\Reimbursement;
/**
 * Plugin Name: WP ERP - Reimbursement
 * Description: Reimbursement addon for WP ERP - Accounting module.
 * Plugin URI: https://wperp.com/accounting/reimbursement
 * Author: weDevs
 * Author URI: https://wedevs.com
 * Version: 1.2.2
 * License: GPL2
 * Text Domain: erp-reimbursement
 * Domain Path: i18n/languages
 *
 * Copyright (c) 2016 weDevs (email: info@wedevs.com). All rights reserved.
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
 * Accounting reimbursement plugin main class
 */
class Module {
    /**
     * Version
     *
     * @var  string
     */
    public $version = '1.2.2';

    /**
     * Holds various class instances
     *
     * @var array
     */
    private $container = [];

    /**
     * Initializes the class
     *
     * Checks for an existing instance
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
     * Constructor for the class
     *
     * Sets up all the appropriate hooks and actions
     */
    private function __construct() {
        // on activate plugin register hook
        add_action( 'erp_pro_activated_module_reimbursement', array( $this, 'activate' ) );

        // on register deactivation hook
        add_action( 'erp_pro_deactivated_module_reimbursement', array( $this, 'deactivate' ) );

        add_action( 'admin_footer', array( $this, 'admin_js_templates' ) );

        // on ERP CRM loaded hook
        add_action( 'erp_accounting_loaded', [ $this, 'erp_accounting_loaded' ] );

        //migrate reimbursement data
        add_action( 'erp_acct_after_new_acct_populate_data', array( $this, 'process_reimbursement_trns' ) );
    }

    /**
     * Print JS templates in footer
     *
     * @return void
     */
    public function admin_js_templates() {
        global $current_screen;

        if ( version_compare( WPERP_VERSION, '1.5.0', '>=' ) ) {
            return;
        }

        if ( isset( $_GET['section'] ) && 'reimbursement' == $_GET['section'] ) {
            erp_get_js_template( WPERP_ACCOUNTING_JS_TMPL . '/invoice.php', 'erp-ac-invoice-payment-pop' );
        }
    }

    /**
     * Executes while Plugin Activation
     *
     * @return void
     */
    public function activate() {

        update_option( 'erp_reimb_version', '1.2.1' ); //* Todo: Update this code

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

        /** Table Schemas
        * https://codex.wordpress.org/Creating_Tables_with_Plugins
        * don't remove the spaces after PRIMARY KEY
        */
        $table_schema = [
            "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}erp_acct_reimburse_requests` (
                id int(11) NOT NULL AUTO_INCREMENT,
                people_id int(11) DEFAULT NULL,
                trn_date date DEFAULT NULL,
                status varchar(50) DEFAULT NULL,
                amount_total decimal(10,2) DEFAULT 0,
                reference varchar(50) DEFAULT NULL,
                particulars varchar(255) DEFAULT NULL,
                attachments varchar(255) DEFAULT NULL,
                created_at date DEFAULT NULL,
                created_by varchar(50) DEFAULT NULL,
                updated_at date DEFAULT NULL,
                updated_by varchar(50) DEFAULT NULL,
                PRIMARY KEY  (id)
            ) $collate;",

            "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}erp_acct_reimburse_request_details (
                id int(11) NOT NULL AUTO_INCREMENT,
                request_id int(11) DEFAULT NULL,
                amount decimal(10,2) DEFAULT 0,
                particulars varchar(255) DEFAULT NULL,
                created_at date DEFAULT NULL,
                created_by varchar(50) DEFAULT NULL,
                updated_at date DEFAULT NULL,
                updated_by varchar(50) DEFAULT NULL,
                PRIMARY KEY  (id)
            ) $collate;"
        ];

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        foreach ( $table_schema as $table ) {
            dbDelta( $table );
        }
    }

    /**
     * @since 1.2.2
     */
    public function deactivate() {
        // nothing added here for now.
    }

    /**
     * Executes if CRM is installed
     *
     * @return boolean|void
     */
    public function erp_accounting_loaded() {
        $this->define_constants();
        $this->includes();
        $this->inistantiate();
    }

    /**
     * Define Add-on constants
     *
     * @return void
     */
    private function define_constants() {
        define( 'WPERP_REIMBURSEMENT_VERSION', $this->version );
        define( 'ERP_REIMBURSEMENT_FILE', __FILE__ );
        define( 'ERP_REIMBURSEMENT_PATH', dirname( ERP_REIMBURSEMENT_FILE ) );
        define( 'ERP_REIMBURSEMENT_INCLUDES', ERP_REIMBURSEMENT_PATH . '/includes' );
        define( 'ERP_REIMBURSEMENT_URL', plugins_url( '', ERP_REIMBURSEMENT_FILE ) );
        define( 'ERP_REIMBURSEMENT_ASSETS', ERP_REIMBURSEMENT_URL . '/assets' );
        define( 'ERP_REIMBURSEMENT_API', ERP_REIMBURSEMENT_INCLUDES . '/api' );

        define( 'WPERP_REIMBURSEMENT_PATH', ERP_REIMBURSEMENT_PATH . '/deprecated' );
        define( 'WPERP_REIMBURSEMENT_INCLUDES', WPERP_REIMBURSEMENT_PATH . '/includes' );
        define( 'WPERP_REIMBURSEMENT_URL', plugins_url( 'deprecated', ERP_REIMBURSEMENT_FILE ) );
        define( 'WPERP_REIMBURSEMENT_ASSETS', WPERP_REIMBURSEMENT_URL . '/assets' );
        define( 'WPERP_REIMBURSEMENT_VIEWS', WPERP_REIMBURSEMENT_PATH . '/views' );
    }

    /**
     * Define constant if not already set
     *
     * @param string $name
     * @param string|bool $value
     * @return type
     */
    private function define( $name, $value ) {
        if ( ! defined( $name ) ) {
            define( $name, $value );
        }
    }

    /**
     * Include required files
     *
     * @return void
     */
    private function includes() {
        if ( version_compare( WPERP_VERSION, '1.5.0', '<' ) ) {
            include_once WPERP_REIMBURSEMENT_INCLUDES . '/functions-reimburs.php';
            include_once WPERP_REIMBURSEMENT_INCLUDES . '/class-transaction-list-table.php';
            include_once WPERP_REIMBURSEMENT_INCLUDES . '/class-admin.php';
        } else {
            require_once ERP_REIMBURSEMENT_INCLUDES . '/classes/class-assets.php';
            require_once ERP_REIMBURSEMENT_API . '/class-controller-rest-api.php';

            if ( $this->is_request( 'admin' ) ) {
                require_once ERP_REIMBURSEMENT_INCLUDES . '/classes/class-i18n.php';
                require_once ERP_REIMBURSEMENT_INCLUDES . '/classes/class-admin.php';

                require_once ERP_REIMBURSEMENT_INCLUDES . '/classes/class-updates.php';
                require_once ERP_REIMBURSEMENT_INCLUDES . '/classes/updates/bp/old-reimb-request-mig.php';
            }

            foreach ( glob( ERP_REIMBURSEMENT_INCLUDES . '/functions/*.php' ) as $filename ) {
                include_once $filename;
            }
        }
    }

	/**
	 * Initiate all classes
	 *
	 * @return void
	 * @since 1.0.0
	 */
    public function inistantiate() {

        if ( version_compare( WPERP_VERSION, '1.5.0', '<' ) ) {
            return;
        }

        if ( $this->is_request( 'admin' ) ) {
            $this->container['i18n']  = new \Erp_People_Trn\Erp_Reimbursement_i18n();
            $this->container['admin'] = new \Erp_People_Trn\Admin();
            $this->container['updates'] = new \Erp_People_Trn\Updates();

            global $bg_process_reimb;
            $bg_process_reimb = new \Erp_People_Trn\Updates\BP\Old_Reimb_Request_Migration();
        }

        $this->container['rest']   = new \Erp_People_Trn\API\REST_API();
        $this->container['assets'] = new \Erp_People_Trn\Assets();
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
     * Migrate reimbursement to people transactions
     */
    protected function process_reimbursement_trns() {
        global $wpdb;

        $reimbur_sql = "SELECT a.user_id, sum(a.trans_total - b.credit) AS balance FROM {$wpdb->prefix}erp_ac_transactions AS a
        INNER JOIN
        (SELECT sum(trans_total) AS credit, user_id FROM {$wpdb->prefix}erp_ac_transactions WHERE form_type='reimbur_payment' GROUP BY user_id) AS b
        ON a.user_id = b.user_id
        WHERE a.form_type='reimbur_invoice' GROUP BY a.user_id";

        $old_reimbur_data = $wpdb->get_results( $reimbur_sql, ARRAY_A );

        $created_at   = date( "Y-m-d" );
        $created_by   = get_current_user_id();
        $voucher_no   = 0;
        $voucher_type = '';
        $amount       = (float) $old_reimbur_data['balance'];
        $currency     = erp_get_option( 'erp_currency', 'erp_settings_general', 'USD' );

        if ( $amount <= 0 ) {
            $voucher_type = 'debit';
        } else {
            $voucher_type = 'credit';
        }

        try {
            $wpdb->query( 'START TRANSACTION' );

            $wpdb->insert( $wpdb->prefix . 'erp_acct_voucher_no', array(
                'type'       => 'people_trn',
                'currency'   => $currency,
                'created_at' => $created_at,
                'created_by' => $created_by,
            ) );

            $voucher_no = $wpdb->insert_id;

            $wpdb->insert( $wpdb->prefix . 'erp_acct_people_trn', array(
                'id'           => $voucher_no,
                'people_id'    => $old_reimbur_data['user_id'],
                'voucher_no'   => $voucher_no,
                'amount'       => $amount,
                'trn_date'     => '',
                'trn_by'       => '',
                'particulars'  => __( 'Migrated from Reimbursement', 'erp-pro' ),
                'voucher_type' => $voucher_type,
                'created_at'   => $created_at,
                'created_by'   => $created_by,
            ) );

            $wpdb->query( 'COMMIT' );

        } catch ( Exception $e ) {
            $wpdb->query( 'ROLLBACK' );
            return new WP_error( 'people-trn-exception', $e->getMessage() );
        }


        return false;
    }

}
