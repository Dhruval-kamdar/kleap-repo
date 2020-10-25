<?php

namespace WeDevs\ERP\Accounting\Payment_Gateway;

use WeDevs\ERP\Framework\ERP_Settings_Page;

/**
 * General class
 */
class Payment_Gateway_Settings extends ERP_Settings_Page {


    public function __construct() {
        $this->id            = 'erp-payment-gateway';
        $this->label         = __( 'Payment', 'erp-pro' );
        $this->single_option = true;
        $this->sections      = $this->get_sections();
    }

    /**
     * Get registered tabs
     *
     * @return array
     */
    public function get_sections() {

        $gateways = apply_filters( 'erp_payment_settings', [] );
        $sections = [];

        foreach ( $gateways as $key => $val ) {
            $sections[$key] = $val['admin_label'];
        }

        return $sections;
    }

    /**
     * Get sections fields
     *
     * @return array
     */
    public function get_section_fields( $section = '' ) {

        $fields  = apply_filters( 'erp_payment_gateway_settings_fields', [] );
        $section = $section === false ? $fields['payment'] : $fields[$section];

        return $section;
    }
}
