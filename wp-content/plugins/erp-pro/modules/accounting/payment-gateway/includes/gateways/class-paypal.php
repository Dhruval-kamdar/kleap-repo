<?php

namespace WeDevs\ERP\Accounting\Payment_Gateway;

/**
 * PayPal payment gateway handler
 *
 * @since 1.0
 */
class Paypal {

    private $gateway_url;
    private $gateway_cancel_url;
    private $test_mode;

    public function __construct() {
        $this->gateway_url        = 'https://www.paypal.com/webscr/?';
        $this->gateway_cancel_url = 'https://api-3t.paypal.com/nvp';
        $this->test_mode          = false;

        add_filter( 'erp_payment_settings', [ $this, 'add_settings' ] );
        add_filter( 'erp_payment_gateway_settings_fields', [ $this, 'settings_paypal' ] );
        add_action( 'erp_payment_gateway_paypal', [ $this, 'send_to_gateway' ], 10, 2 );
        add_action( 'erp_pg_active_gateways', [ $this, 'check_if_active' ] );
        add_action( 'init', [ $this, 'paypal_success' ] );
    }

    public function add_settings( $gateways ) {
        $gateways['paypal'] = [
            'admin_label'    => __( 'PayPal', 'erp-pro' ),
            'checkout_label' => __( 'PayPal', 'erp-pro' ),
        ];

        return $gateways;
    }

    public function settings_paypal( $fields ) {

        // PayPal Optins
        $fields['paypal'][] = [
            'title' => __( 'Paypal Settings', 'erp-pro' ),
            'type'  => 'title',
            'desc'  => __( 'PayPal standard sends customers to PayPal to enter their payment information. PayPal IPN requires fsockopen/cURL support to update order statuses after payment.', 'erp-pro' )
        ];

        $fields['paypal'][] = [
            'title'   => __( 'Enable/Disable', 'woocommerce' ),
            'id'      => 'erp_pg_paypal_enable_disable',
            'type'    => 'checkbox',
            'desc'    => __( 'Enable PayPal', 'woocommerce' ),
            'default' => 'no'
        ];

        $fields['paypal'][] = [
            'title'   => __( 'Title', 'erp-pro' ),
            'id'      => 'erp_pg_paypal_title',
            'type'    => 'text',
            'default' => __( 'PayPal', 'erp-pro' ),
            'tooltip' => true,
            'desc'    => __( 'This is the title which user see on payment options', 'erp-pro' )
        ];

        $fields['paypal'][] = [
            'title'   => __( 'Description', 'erp-pro' ),
            'id'      => 'erp_pg_paypal_description',
            'type'    => 'text',
            'default' => __( 'Pay via PayPal; you can pay with your credit card if you don\'t have a PayPal account.', 'erp-pro' ),
            'tooltip' => true,
            'desc'    => __( 'This is the description which user see on payment options', 'erp-pro' )
        ];

        $fields['paypal'][] = [
            'title'   => __( 'Paypal Email', 'erp-pro' ),
            'id'      => 'erp_pg_paypal_receiver_email',
            'type'    => 'text',
            'tooltip' => true,
            'desc'    => __( 'Please enter your PayPal email address', 'erp-pro' )
        ];

        $fields['paypal'][] = [
            'title'   => __( 'Paypal Sandbox', 'erp-pro' ),
            'id'      => 'erp_pg_paypal_sandbox',
            'type'    => 'checkbox',
            'desc'    => __( 'Enable PayPal Sandbox', 'erp-pro' ),
            'default' => 'no'
        ];

        $fields['paypal'][] = [
            'type' => 'sectionend',
            'id'   => 'script_styling_options'
        ];

        return $fields;
    }

    public function check_if_active( $gateways ) {
        $is_paypal_active = get_option( 'erp_pg_paypal_enable_disable', 'no' );

        if ( 'yes' == $is_paypal_active ) {
            $gateways['paypal'] = [
                'title'       => get_option( 'erp_pg_paypal_title', __( 'Paypal', 'erp-pro' ) ),
                'description' => get_option( 'erp_pg_paypal_description' ),
                'email'       => get_option( 'erp_pg_paypal_receiver_email' ),
                'value'       => 'paypal'
            ];
        }

        return $gateways;
    }

    /**
     * Prepare the payment form and send to paypal
     *
     * @param array $data payment info
     * @since 1.0
     */
    public function send_to_gateway( $transaction, $company ) {
        $listener_url = add_query_arg( 'action', 'erp_pg_paypal_success', admin_url() );

        error_log( print_r($transaction, true ) );

        if ( version_compare( WPERP_VERSION, '1.5.0', '>=' ) ) {
            $paypal_args = array(
                'cmd'           => '_xclick',
                'business'      => get_option( 'erp_pg_paypal_receiver_email' ),
                'amount'        => number_format( $transaction['total_due'], 2, '.', '' ),
                'item_name'     => json_encode( $transaction['line_items'] ),
                'no_shipping'   => '1',
                'shipping'      => '0',
                'no_note'       => '1',
                'currency_code' => $transaction['currency'],
                'charset'       => 'UTF-8',
                'rm'            => '2',
                'custom'        => json_encode( [
                    'transaction_id' => $transaction['voucher_no'],
                    'user_id'        => $transaction['customer_id'],
                ] ),
                'return'        => '',
                'notify_url'    => $listener_url,
            );
        } else {
            $paypal_args = array(
                'cmd'           => '_xclick',
                'business'      => get_option( 'erp_pg_paypal_receiver_email' ),
                'amount'        => number_format( $transaction['trans_total'], 2, '.', '' ),
                'item_name'     => $transaction['type'],
                'no_shipping'   => '1',
                'shipping'      => '0',
                'no_note'       => '1',
                'currency_code' => $transaction['currency'],
                'item_number'   => $transaction['invoice_number'],
                'charset'       => 'UTF-8',
                'rm'            => '2',
                'custom'        => json_encode( [
                    'transaction_id' => $transaction['id'],
                    'user_id'        => $transaction['user_id'],
                ] ),
                'return'        => '',
                'notify_url'    => $listener_url,
            );
        }

        $this->set_mode();

        $paypal_url = $this->gateway_url . http_build_query( $paypal_args );

        wp_redirect( $paypal_url );
        exit;
    }

    /**
     * Set the payment mode to sandbox or live
     *
     * @since 1.0
     */
    public function set_mode() {
        if ( get_option( 'erp_pg_paypal_sandbox' ) == 'yes' ) {
            $this->gateway_url        = 'https://www.sandbox.paypal.com/cgi-bin/webscr/?';
            $this->gateway_cancel_url = 'https://api-3t.sandbox.paypal.com/nvp';
            $this->test_mode          = true;
        }
    }

    /**
     * Handle the payment info sent from paypal
     *
     * @since 1.0
     */
    public function paypal_success() {
        global $wpdb;

        $insert_payment = false;

        if ( isset( $_GET['action'] ) && $_GET['action'] == 'erp_pg_paypal_success' ) {

            if( ! isset( $_POST ) ) {
                return;
            }

            $postdata = $_POST;

            $amount                  = $postdata['mc_gross'];
            $issue_date              = $postdata['payment_date'];
            $custom                  = (array) json_decode( stripcslashes( $postdata['custom'] ) );
            $postdata['invoice_no']  = $custom['transaction_id'];
            $postdata['customer_id'] = $custom['user_id'];
            $account_id              = erp_get_option( 'erp_pg_payment_account_head', false, 7 );
            $transaction             = [];

            if ( isset( $postdata['txn_type'] ) && ( $postdata['txn_type'] == 'web_accept' ) && ( strtolower( $postdata['payment_status'] ) == 'completed' ) ) {

                //verify payment
                $verified = $this->validateIpn();
                $status   = 'web_accept';

                if ( $verified ) {
                    $insert_payment = true;
                }

            }

            if ( $insert_payment ) {

                if ( version_compare( WPERP_VERSION, '1.5.0', '>=' ) ) {
                    $payment_data = $this->format_data( $postdata );
                    $payment_data['line_items'][0]['invoice_no'] = $postdata['invoice_no'];
                    $payment_data['line_items'][0]['line_total'] = $payment_data['amount'];
                    $transaction = erp_acct_insert_payment( $payment_data );
                } else {
                    $args = [
                        'id'          => '',
                        'partial_id'  => [ $transaction_id ],
                        'items_id'    => [],
                        'type'        => 'sales',
                        'form_type'   => 'payment',
                        'account_id'  => $account_id,
                        'status'      => 'closed',
                        'user_id'     => $user_id,
                        'ref'         => '',
                        'issue_date'  => date( 'Y-m-d', strtotime( $issue_date ) ),
                        'summary'     => '',
                        'total'       => $amount,
                        'trans_total' => $amount,
                        'files'       => '',
                        'currency'    => erp_get_currency(),
                        'line_total'  => [ $amount ],
                        'journals_id' => [],
                    ];

                    $items[] = [
                        'item_id'     => [],
                        'journal_id'  => [],
                        'account_id'  => 1,
                        'description' => '',
                        'qty'         => 1,
                        'unit_price'  => 0,
                        'discount'    => 0,
                        'line_total'  => $amount,
                        'tax'         => 0,
                        'tax_rate'    => '0.00',
                        'tax_journal' => 0
                    ];

                    $transaction = erp_ac_insert_transaction( $args, $items );
                }


                if ( ! is_wp_error( $transaction ) ) {
                    wp_send_json_success();
                }
            }
        }
    }

    /**
     * Validate the IPN notification
     *
     * @param none
     * @return boolean
     */
    public function validateIpn() {
        global $wp_version;

        $this->set_mode();

        // Get received values from post data
        $validate_ipn = array( 'cmd' => '_notify-validate' );
        $validate_ipn += wp_unslash( $_POST );

        // Send back post vars to paypal
        $params = array(
            'body'        => $validate_ipn,
            'timeout'     => 60,
            'httpversion' => '1.1',
            'compress'    => false,
            'decompress'  => false,
        );

        $response = wp_safe_remote_post( $this->gateway_url, $params );

        // check to see if the request was valid
        if ( ! is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 && strstr( $response['body'], 'VERIFIED' ) ) {
            return true;
        }

        return false;
    }

    public function format_data( $data ) {
        $payment_data = [];

        $user_info = erp_get_people( $data['customer_id'] );
        $company   = new \WeDevs\ERP\Company();

        $ledger_map = \WeDevs\ERP\Accounting\Includes\Classes\Ledger_Map::getInstance();
        $ledger_id  = $ledger_map->get_ledger_id_by_slug( 'cash' );

        $payment_data['invoice_no']    = ! empty( $data['voucher_no'] ) ? $data['voucher_no'] : 0;
        $payment_data['customer_id']   = isset( $data['customer_id'] ) ? $data['customer_id'] : null;
        $payment_data['customer_name'] = isset( $data['first_name'] ) ? $data['first_name'] . ' ' . $data['last_name'] : '';
        $payment_data['trn_date']      = isset( $data['payment_date'] ) ? $data['payment_date'] : date( "Y-m-d" );
        $payment_data['line_items']    = isset( $data['line_items'] ) ? $data['line_items'] : array();
        $payment_data['created_at']    = date( "Y-m-d" );
        $payment_data['amount']        = isset( $data['mc_gross'] ) ? $data['mc_gross'] : 0;
        $payment_data['attachments']   = isset( $data['attachments'] ) ? $data['attachments'] : '';
        $payment_data['voucher_type']  = isset( $data['type'] ) ? $data['type'] : '';
        $payment_data['particulars']   = isset( $data['particulars'] ) ? $data['particulars'] : '';
        $payment_data['trn_by']        = isset( $data['txn_type'] ) ? $data['txn_type'] : '';
        $payment_data['deposit_to']    = isset( $ledger_id ) ? $ledger_id : 0;
        $payment_data['status']        = 4;
        $payment_data['check_no']      = isset( $data['check_no'] ) ? $data['check_no'] : 0;
        $payment_data['pay_to']        = isset( $user_info ) ? $user_info->first_name . ' ' . $user_info->last_name : '';
        $payment_data['name']          = isset( $data['name'] ) ? $data['name'] : $company->name;
        $payment_data['bank']          = isset( $data['bank'] ) ? $data['bank'] : '';
        $payment_data['voucher_type']  = 'payment';
        $payment_data['created_at']    = isset( $data['created_at'] ) ? $data['created_at'] : null;
        $payment_data['created_by']    = isset( $data['created_by'] ) ? $data['created_by'] : '';
        $payment_data['updated_at']    = isset( $data['updated_at'] ) ? $data['updated_at'] : null;
        $payment_data['updated_by']    = isset( $data['updated_by'] ) ? $data['updated_by'] : '';

        return $payment_data;
    }
}
