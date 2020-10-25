<?php

namespace WeDevs\ERP\Accounting\Payment_Gateway;

/**
 * Stripe payment gateway handler
 *
 * @since 1.0
 */
class Stripe {

    // Stripe secret key
    private $secret_key;

    // Stripe publishable key
    private $publishable_key;

    function __construct() {

        $this->set_keys();

        add_filter( 'erp_payment_settings', [ $this, 'add_settings' ] );
        add_filter( 'erp_payment_gateway_settings_fields', [ $this, 'settings_stripe' ] );

        add_action( 'erp_pg_active_gateways', [ $this, 'check_if_active' ] );
        add_action( 'erp_payment_gateway_stripe', [ $this, 'send_to_gateway' ], 10, 2 );
        add_action( 'wp_ajax_payment-gateway-stripe-token', [ $this, 'process_stripe_token' ] );
    }

    /**
     * Add Stripe to gateway listing in settings page
     *
     * @param $gateways
     * @return mixed
     * @since 1.0
     */
    public function add_settings( $gateways ) {
        $gateways['stripe'] = [
            'admin_label'    => __( 'Stripe', 'erp-pro' ),
            'checkout_label' => __( 'Stripe', 'erp-pro' ),
        ];

        return $gateways;
    }

    /**
     * Stripe gateway settings
     *
     * @param $fields
     * @return mixed
     * @since 1.0
     */
    public function settings_stripe( $fields ) {

        // Stripe Options
        $fields['stripe'][] = [
            'title' => __( 'Stripe Settings', 'erp-pro' ),
            'type'  => 'title',
            'desc'  => __( 'Stripe is one of the smart and easiest payment method.', 'erp-pro' )
        ];

        $fields['stripe'][] = [
            'title'   => __( 'Enable/Disable', 'erp-pro' ),
            'id'      => 'erp_pg_stripe_enable_disable',
            'type'    => 'checkbox',
            'desc'    => __( 'Enable Stripe', 'erp-pro' ),
            'default' => 'no'
        ];

        $fields['stripe'][] = [
            'title'   => __( 'Title', 'erp-pro' ),
            'id'      => 'erp_pg_stripe_title',
            'type'    => 'text',
            'default' => __( 'Stripe', 'erp-pro' ),
            'tooltip' => true,
            'desc'    => __( 'This is the title which user see on payment options', 'erp-pro' )
        ];

        $fields['stripe'][] = [
            'title'   => __( 'Description', 'erp-pro' ),
            'id'      => 'erp_pg_stripe_description',
            'type'    => 'text',
            'default' => __( 'Stripe is one of the smart and easiest payment method..', 'erp-pro' ),
            'tooltip' => true,
            'desc'    => __( 'This is the description which user see on payment options', 'erp-pro' )
        ];

        $fields['stripe'][] = [
            'title'   => __( 'Live Secret Key', 'erp-pro' ),
            'id'      => 'erp_pg_stripe_live_secret_key',
            'type'    => 'text',
            'tooltip' => true,
            'desc'    => __( 'Enter your Stripe Live Secret Key', 'erp-pro' )
        ];

        $fields['stripe'][] = [
            'title'   => __( 'Live Publishable Key', 'erp-pro' ),
            'id'      => 'erp_pg_stripe_live_publishable_key',
            'type'    => 'text',
            'tooltip' => true,
            'desc'    => __( 'Enter your Stripe Live Publishable Key', 'erp-pro' )
        ];

        $fields['stripe'][] = [
            'title'   => __( 'Test Mode', 'erp-pro' ),
            'id'      => 'erp_pg_stripe_enable_testmode',
            'type'    => 'checkbox',
            'desc'    => __( 'Enable test mode?', 'erp-pro' ),
            'default' => 'no'
        ];

        $fields['stripe'][] = [
            'title'   => __( 'Test Secret Key', 'erp-pro' ),
            'id'      => 'erp_pg_stripe_test_secret_key',
            'type'    => 'text',
            'tooltip' => true,
            'desc'    => __( 'Enter your Stripe Test Secret Key', 'erp-pro' )
        ];

        $fields['stripe'][] = [
            'title'   => __( 'Test Publishable Key', 'erp-pro' ),
            'id'      => 'erp_pg_stripe_test_publishable_key',
            'type'    => 'text',
            'tooltip' => true,
            'desc'    => __( 'Enter your Stripe Test Publishable Key', 'erp-pro' )
        ];

        $fields['stripe'][] = [
            'type' => 'sectionend',
            'id'   => 'script_styling_options'
        ];

        return $fields;
    }

    public function set_keys() {
        $is_test_mode          = get_option( 'erp_pg_stripe_enable_testmode', 'no' );
        $this->secret_key      = 'yes' == $is_test_mode ? get_option( 'erp_pg_stripe_test_secret_key' ) : get_option( 'erp_pg_stripe_live_secret_key' );
        $this->publishable_key = 'yes' == $is_test_mode ? get_option( 'erp_pg_stripe_test_publishable_key' ) : get_option( 'erp_pg_stripe_live_publishable_key' );
    }

    /**
     * check if stripe gateway is active
     *
     * @param $gateways
     * @return mixed
     * @since 1.0
     */
    public function check_if_active( $gateways ) {
        $is_stripe_active = get_option( 'erp_pg_stripe_enable_disable', 'no' );

        if ( 'yes' == $is_stripe_active ) {
            $gateways['stripe'] = [
                'title'       => get_option( 'erp_pg_stripe_title', __( 'Stripe', 'erp-pro' ) ),
                'description' => get_option( 'erp_pg_stripe_description' ),
                'value'       => 'stripe',
            ];
        }

        return $gateways;
    }

    /**
     * Submit to gateway
     *
     * @since 1.0
     */
    public function send_to_gateway( $transaction, $company ) {
        $logo = wp_get_attachment_image_url( $company->logo );

        if ( version_compare( WPERP_VERSION, '1.5.0', '>=' ) ) {

            if ( 'paid' == $transaction['status'] ) {
                return;
            }

            $amount = absint( $transaction['total_due'] * 100 );
            $logo   = wp_get_attachment_image_url( $company->logo );
            ?>
            <script src="https://checkout.stripe.com/checkout.js"></script>

            <script>
                var handler = StripeCheckout.configure({
                    key  : '<?php echo $this->publishable_key; ?>',
                    image: '<?php echo $logo; ?>',
                    token: function (token) {
                        // Use the token to create the charge with a server-side script.
                        // You can access the token ID with `token.id`
                        jQuery.post('<?php echo admin_url( 'admin-ajax.php?action=' ); ?>payment-gateway-stripe-token',
                            {
                                stripeToken   : token.id,
                                amount        : <?php echo $amount; ?>,
                                description   : '<?php echo $transaction['type']; ?>',
                                currency      : 'usd',
                                transaction_id: '<?php echo $transaction['voucher_no']; ?>',
                                customer_id   : '<?php echo $transaction['customer_id']; ?>',
                                line_items    : '<?php echo maybe_serialize( $transaction['line_items'] ); ?>',
                                trn_date      : '<?php echo $transaction['trn_date']; ?>',
                            },
                            function (data) {
                                if (true === data.success) {
                                    handler.close();
                                    window.location.reload(true);
                                }
                                ;
                            }
                        );

                    }
                });

                // Open Checkout with further options
                handler.open({
                    name       : '<?php echo $company->name; ?>',
                    description: '<?php echo $transaction['type']; ?>',
                    amount     : '<?php echo $amount; ?>'
                });
            </script>
            <?php
        } else {
            if ( 'awaiting_payment' != $transaction['status'] ) {
                return;
            }

            $amount = absint( $transaction['trans_total'] * 100 );
            ?>
            <script src="https://checkout.stripe.com/checkout.js"></script>

            <script>
                var handler = StripeCheckout.configure({
                    key  : '<?php echo $this->publishable_key; ?>',
                    image: '<?php echo $logo; ?>',
                    token: function (token) {
                        // Use the token to create the charge with a server-side script.
                        // You can access the token ID with `token.id`
                        jQuery.post('<?php echo admin_url( 'admin-ajax.php?action=' ); ?>payment-gateway-stripe-token',
                            {
                                stripeToken   : token.id,
                                amount        : <?php echo $amount; ?>,
                                description   : '<?php echo $transaction['type']; ?>',
                                currency      : '<?php echo $transaction['currency']; ?>',
                                transaction_id: '<?php echo $transaction['id']; ?>',
                                user_id       : '<?php echo $transaction['user_id']; ?>',
                                invoice_number: '<?php echo $transaction['invoice_number']; ?>'
                            },
                            function (data) {
                                if (true === data.success) {
                                    handler.close();
                                    window.location.reload(true);
                                }
                                ;
                            }
                        );

                    }
                });

                // Open Checkout with further options
                handler.open({
                    name       : '<?php echo $company->name; ?>',
                    description: '<?php echo $transaction['type']; ?>',
                    amount     : '<?php echo $amount; ?>'
                });
            </script>
            <?php
        }
        ?>
        <script>
            // Close Checkout on page navigation
            document.addEventListener('popstate', function () {
                handler.close();
            });
        </script>
        <?php
    }

    /**
     * Processinge Stripe Token
     *
     * @return mixed
     * @since 1.0
     */
    public function process_stripe_token() {
        // get stirpe token
        $stripe_token   = $_POST['stripeToken'];
        $amount         = $_POST['amount'];
        $description    = $_POST['description'];
        $currency       = $_POST['currency'];
        $transaction_id = $_POST['transaction_id'];
        $user_id        = $_POST['customer_id'];
        $account_id     = erp_get_option( 'erp_pg_payment_account_head', false );

        \Stripe\Stripe::setApiKey( $this->secret_key );

        \Stripe\Stripe::setAppInfo(
            'WP ERP Payment Gateway',
            '1.0.0',
            'https://wperp.com/downloads/payment-gateway',
            'pp_partner_Ee9F0QbhSGowvH'
        );

        // Create a charge: this will charge the user's card
        try {
            $charge = \Stripe\Charge::create( array(
                "amount"      => $amount, // Amount in cents
                "currency"    => $currency,
                "source"      => $stripe_token,
                "description" => $description
            ) );
        } catch ( \Stripe\Error\Card $e ) {
            // The card has been declined
        }

        $return_data = $charge->__toArray();

        $post_data   = $_POST;
        $return_data = array_merge( $return_data, $post_data );

        if ( 'succeeded' == $return_data['status'] ) {
            if ( version_compare( WPERP_VERSION, '1.5.0', '>=' ) ) {
                $payment_data                                = $this->format_data( $return_data );
                $payment_data['line_items'][0]['invoice_no'] = $transaction_id;
                $payment_data['line_items'][0]['line_total'] = $payment_data['amount'];
                $transaction                                 = erp_acct_insert_payment( $payment_data );

            } else {

                $total = number_format( $amount / 100, 2 );

                $args = [
                    'id'          => '',
                    'partial_id'  => [ $transaction_id ],
                    'items_id'    => [],
                    'type'        => 'sales',
                    'form_type'   => 'payment',
                    'account_id'  => $account_id,
                    'status'      => 'closed',
                    'user_id'     => $user_id,
//                'invoice_number' => $invoice_number,
//                'invoice_format' => $invoice_format,
                    'ref'         => '',
                    'issue_date'  => date( 'Y-m-d', $return_data['created'] ),
                    'summary'     => '',
                    'total'       => number_format( $amount / 100, 2 ),
                    'trans_total' => $total,
                    'files'       => '',
                    'currency'    => erp_get_currency(),
                    'line_total'  => [ $total ],
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
                    'line_total'  => $total,
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

    public function format_data( $data ) {
        $payment_data = [];

        $user_info = erp_get_people( $data['customer_id'] );
        $company   = new \WeDevs\ERP\Company();

        $ledger_map = \WeDevs\ERP\Accounting\Includes\Classes\Ledger_Map::getInstance();
        $ledger_id  = $ledger_map->get_ledger_id_by_slug( 'cash' );

        $payment_data['invoice_no']    = ! empty( $data['transaction_id'] ) ? $data['transaction_id'] : 0;
        $payment_data['customer_id']   = isset( $data['customer_id'] ) ? $data['customer_id'] : null;
        $payment_data['customer_name'] = isset( $data['first_name'] ) ? $data['first_name'] . ' ' . $data['last_name'] : '';
        $payment_data['trn_date']      = isset( $data['payment_date'] ) ? $data['payment_date'] : date( "Y-m-d" );
        $payment_data['created_at']    = date( "Y-m-d" );
        $payment_data['amount']        = isset( $data['amount'] ) ? $data['amount'] / 100 : 0;
        $payment_data['attachments']   = isset( $data['attachments'] ) ? $data['attachments'] : '';
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
