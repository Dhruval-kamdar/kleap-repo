<?php
namespace WeDevs\ERP\Zendesk;
/**
 * Settings Class
 */

class Settings {

    /**
     * Constructor function
     */
    function __construct() {
        add_filter( 'erp_settings_crm_sections', array( $this, 'crm_sections_zendesk_integration' ) );

        // Add fields to ERP Settings Zendesk Integration section
        add_filter( 'erp_settings_crm_section_fields', [ $this, 'crm_sections_zendesk_integration_fields' ], 10, 2 );
    }

    /**
     * CRM Sections
     *
     * @param  array $sections
     * @return array
     */
    public function crm_sections_zendesk_integration( $sections ) {
       $sections['zendesk_integration'] = __( 'Zendesk', 'erp-pro' );

       return $sections;
    }

    /**
     * CRM Zendesk Integration fields
     *
     * @param  array $fields
     * @return array
     */
    public function crm_sections_zendesk_integration_fields( $fields ) {
        $fields['zendesk_integration'][] = [
            'title' => __( 'Zendesk Integration', 'erp-pro' ),
            'type'  => 'title',
        ];

        $fields['zendesk_integration'][] = [
            'title' => __( 'Zendesk subdomain', 'erp-pro' ),
            'type'  => 'text',
            'id'    => 'zendesk_subdomain',
            'desc'    => __( 'e.g. <strong>mysub.zendesk.com</strong>', 'erp' ),
        ];

        $fields['zendesk_integration'][] = [
            'title' => __( 'Zendesk Email', 'erp-pro' ),
            'type'  => 'email',
            'id'    =>  'zendesk_login_email'
        ];

        $fields['zendesk_integration'][] = [
            'title' => __( 'Zendesk Password', 'erp-pro' ),
            'type'  => 'password',
            'id'    =>  'zendesk_password'
        ];

        $fields['zendesk_integration'][] = [
            'type' => 'sectionend',
            'id'   => 'script_styling_options'
        ];

        return $fields;
    }
}
