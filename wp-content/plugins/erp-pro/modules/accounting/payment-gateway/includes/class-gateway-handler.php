<?php

namespace WeDevs\ERP\Accounting\Payment_Gateway;

/**
 * Class Payment_Gateway_Handler
 *
 * @since 1.0
 */
class Payment_Gateway_Handler {

    /* Constructor function */
    public function __construct() {

        add_action( 'init', [ $this, 'payment_gateway_submission' ] );
    }

    public function payment_gateway_submission() {

        if ( ! isset( $_POST['erp_payment_submit'] ) ) {
            return;
        }

        $payment_gateway_method = isset( $_POST['erp_pg_payment_method'] ) ? $_POST['erp_pg_payment_method'] : '';

        if ( ! $payment_gateway_method ) {
            return;
        }

        $transaction = $this->_verify_page();
        $company     = new \WeDevs\ERP\Company();

        do_action( 'erp_payment_gateway_' . $payment_gateway_method, $transaction, $company );
    }

    /**
     * Checks if the current page is valid
     *
     * @return array|bool
     * @since 1.0
     */
    function _verify_page() {
        $verified       = false;
        $query          = isset( $_REQUEST['query'] ) ? esc_attr( $_REQUEST['query'] ) : '';
        $transaction_id = isset( $_REQUEST['trans_id'] ) ? intval( $_REQUEST['trans_id'] ) : '';
        $auth_id        = isset( $_REQUEST['auth'] ) ? esc_attr( $_REQUEST['auth'] ) : '';

        if ( ! $query || ! $transaction_id || ! $auth_id ) {
            return false;
        }

        if ( version_compare( WPERP_VERSION, '1.5.0', '>=' ) ) {
            $transaction = erp_acct_get_transaction( $transaction_id );
            if ( $transaction ) {
                $verified = erp_acct_get_invoice_link_hash( $transaction_id, $transaction['type'] );
            }

        } else {
            $transaction = erp_ac_get_transaction( $transaction_id );
            if ( $transaction ) {
                $verified = erp_ac_verify_invoice_link_hash( $transaction, $auth_id );
            }
        }


        if ( ! $verified ) {
            return false;
        }

        return $transaction;
    }
}
