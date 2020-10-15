<?php

namespace WeDevs\ERP\Accounting\Payment_Gateway;

/**
 * PayPal payment gateway handler
 *
 * @since 1.0
 */
class General_Settings {

    function __construct() {
        add_filter( 'erp_payment_settings', [ $this, 'add_settings' ] );
        add_filter( 'erp_payment_gateway_settings_fields', [ $this, 'settings_general' ] );
    }

    public function add_settings( $gateways ) {
        $gateways['general'] = [
            'admin_label'    => __( 'General', 'erp-pro' ),
            'checkout_label' => __( 'General', 'erp-pro' ),
        ];

        return $gateways;
    }

    public function settings_general( $fields ) {

        if ( version_compare( WPERP_VERSION, '1.5.0', '>=' ) ) {
            $deposit_to = erp_acct_get_bank_dropdown();
        } else {
            $deposit_to = erp_ac_get_bank_dropdown();
        }

        // General Optins
        $fields['general'][] = [
            'title' => __( 'General Settings', 'erp-pro' ),
            'type'  => 'title'
        ];

        $fields['general'][] = [
            'title'   => __( 'Payment account', 'erp-pro' ),
            'type'    => 'select',
            'options' => $deposit_to,
            'id'      => 'erp_pg_payment_account_head',
            'desc'    => __( '', 'erp-pro' ),
            'class'   => 'erp-select2',
            'tooltip' => true,
            'default' => ''
        ];

        $fields['general'][] = [
            'type' => 'sectionend',
            'id'   => 'script_styling_options'
        ];

        return $fields;
    }
}
