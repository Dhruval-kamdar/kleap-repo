<?php
namespace WeDevs\ERP\SMS;
use WeDevs\ERP\Framework\ERP_Settings_Page;
/**
 * General class
 */
class SMS_Settings extends ERP_Settings_Page {


    public function __construct() {
        $this->id            = 'erp-sms';
        $this->label         = __( 'SMS', 'erp-sms' );
        $this->single_option = true;
        $this->sections      = $this->get_sections();
    }

    /**
     * Get registered tabs
     *
     * @return array
     */
    public function get_sections() {
        $sections = [
            'gateway' => __( 'Gateway Settings', 'erp-sms' ),
        ];

        return $sections;
    }

    /**
     * Get sections fields
     *
     * @return array
     */
    public function get_section_fields( $section = '' ) {
        $options = [
            'twilio'       => __( 'Twilio', 'erp-sms' ),
            'clickatell'   => __( 'Clickatell', 'erp-sms' ),
            'smsglobal'    => __( 'SMSGlobal', 'erp-sms' ),
            'nexmo'        => __( 'Nexmo', 'erp-sms' ),
            'hoiio'        => __( 'Hoiio', 'erp-sms' ),
            'intellisms'   => __( 'Intellisms', 'erp-sms' ),
            'infobip'      => __( 'Infobip', 'erp-sms' ),
        ];

        $fields['gateway'] = [
            [
                'title' => __( '', 'erp-sms' ),
                'type' => 'title',
            ],
            [
                'title'   => __( 'Active Gateway', 'erp-sms' ),
                'type'    => 'select',
                'options' => $options,
                'id'      => 'erp_sms_selected_gateway'
            ],
            [
                'type'  => 'sectionend',
                'id'    => 'erp_sms_script_styling_options'
            ],
            [
                'title' => __( 'Clickatell', 'erp-sms' ),
                'type' => 'title'
            ],
            [
                'title' => __( 'Username', 'erp-sms' ),
                'type'  => 'text',
                'id'    => 'erp_sms_clickatell_username'
            ],
            [
                'title' => __( 'Password', 'erp-sms' ),
                'type'  => 'text',
                'id'    => 'erp_sms_clickatell_password'
            ],
            [
                'title' => __( 'API ID', 'erp-sms' ),
                'type'  => 'text',
                'id'    => 'erp_sms_clickatell_api_id'
            ],
            [
                'type'  => 'sectionend',
                'id'    => 'erp_sms_script_styling_options'
            ],
            [
                'title' => __( 'Twilio', 'erp-sms' ),
                'type' => 'title'
            ],
            [
                'title' => __( 'Number From', 'erp-sms' ),
                'type'  => 'text',
                'id'    => 'erp_sms_twilio_number_from'
            ],
            [
                'title' => __( 'Account SID', 'erp-sms' ),
                'type'  => 'text',
                'id'    => 'erp_sms_twilio_account_sid'
            ],
            [
                'title' => __( 'Auth Token', 'erp-sms' ),
                'type'  => 'text',
                'id'    => 'erp_sms_twilio_auth_token'
            ],
            [
                'type'  => 'sectionend',
                'id'    => 'erp_sms_script_styling_options'
            ],
            [
                'title' => __( 'SMSGlobal', 'erp-sms' ),
                'type' => 'title'
            ],
            [
                'title' => __( 'Username', 'erp-sms' ),
                'type'  => 'text',
                'id'    => 'erp_sms_smsglobal_username'
            ],
            [
                'title' => __( 'Password', 'erp-sms' ),
                'type'  => 'text',
                'id'    => 'erp_sms_smsglobal_password'
            ],
            [
                'title' => __( 'From', 'erp-sms' ),
                'type'  => 'text',
                'id'    => 'erp_sms_smsglobal_from'
            ],
            [
                'type'  => 'sectionend',
                'id'    => 'erp_sms_script_styling_options'
            ],
            [
                'title' => __( 'Nexmo', 'erp-sms' ),
                'type' => 'title'
            ],
            [
                'title' => __( 'API Key', 'erp-sms' ),
                'type'  => 'text',
                'id'    => 'erp_sms_nexmo_apikey'
            ],
            [
                'title' => __( 'API Secret', 'erp-sms' ),
                'type'  => 'text',
                'id'    => 'erp_sms_nexmo_apisecret'
            ],
            [
                'title' => __( 'Sender ID', 'erp-sms' ),
                'type'  => 'text',
                'id'    => 'erp_sms_nexmo_sender_id'
            ],
            [
                'type'  => 'sectionend',
                'id'    => 'erp_sms_script_styling_options'
            ],
            [
                'title' => __( 'Hoiio', 'erp-sms' ),
                'type' => 'title'
            ],
            [
                'title' => __( 'App ID', 'erp-sms' ),
                'type'  => 'text',
                'id'    => 'erp_sms_hoiio_app_id'
            ],
            [
                'title' => __( 'Access Token', 'erp-sms' ),
                'type'  => 'text',
                'id'    => 'erp_sms_hoiio_access_token'
            ],
            [
                'type'  => 'sectionend',
                'id'    => 'erp_sms_script_styling_options'
            ],
            [
                'title' => __( 'Intellisms', 'erp-sms' ),
                'type' => 'title'
            ],
            [
                'title' => __( 'Username', 'erp-sms' ),
                'type'  => 'text',
                'id'    => 'erp_sms_intellisms_username'
            ],
            [
                'title' => __( 'Password', 'erp-sms' ),
                'type'  => 'text',
                'id'    => 'erp_sms_intellisms_password'
            ],
            [
                'title' => __( 'Sender', 'erp-sms' ),
                'type'  => 'text',
                'id'    => 'erp_sms_intellisms_sender'
            ],
            [
                'type'  => 'sectionend',
                'id'    => 'erp_sms_script_styling_options'
            ],
            [
                'title' => __( 'Infobip', 'erp-sms' ),
                'type' => 'title'
            ],
            [
                'title' => __( 'Username', 'erp-sms' ),
                'type'  => 'text',
                'id'    => 'erp_sms_infobip_username'
            ],
            [
                'title' => __( 'Password', 'erp-sms' ),
                'type'  => 'text',
                'id'    => 'erp_sms_infobip_password'
            ],
            [
                'title' => __( 'Sender', 'erp-sms' ),
                'type'  => 'text',
                'id'    => 'erp_sms_infobip_sender'
            ],
            [
                'type'  => 'sectionend',
                'id'    => 'erp_sms_script_styling_options'
            ],
        ];

        return $fields[$section];
    }
}

return new SMS_Settings();