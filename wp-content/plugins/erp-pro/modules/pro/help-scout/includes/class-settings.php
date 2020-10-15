<?php
namespace WeDevs\ERP\HelpScout;

/**
 * Settings class
 *
 * @since 1.0.0
 *
 * @package WPERP|HelpScout
 */
class Settings {

    /**
     * Constructor function
     *
     * @since 1.0.0
     */
    public function __construct() {
        add_filter( 'erp_settings_crm_sections', [ $this, 'crm_sections_help_scout' ] );

        // Add fields to ERP Settings HelpScout section
        add_filter( 'erp_settings_crm_section_fields', [ $this, 'crm_sections_help_scout_fields' ], 10, 2 );
    }

    /**
     * Add plugin settings area in CRM settings tab
     *
     * @param array $sections
     *
     * @return array
     */
    public function crm_sections_help_scout( $sections ) {
        $sections['help_scout'] = __( 'HelpScout', 'erp-pro' );
        return $sections;
    }

    /**
     * Settings fields for HelpScout
     *
     * @param array  $fields
     *
     * @return array
     */
    public function crm_sections_help_scout_fields( $fields ) {
        $fields['help_scout'][] = [
            'title' => __( 'HelpScout Setting', 'erp-pro' ),
            'type'  => 'title',
        ];

        $fields['help_scout'][] = [
            'title' => __( 'App ID', 'erp-pro' ),
            'type'  => 'text',
            'id'    => 'helpscout_app_id',
            'custom_attributes' => [
                'disable' => 'disable'
            ],
        ];
        $fields['help_scout'][] = [
            'title' => __( 'App Secret', 'erp-pro' ),
            'type'  => 'text',
            'id'    => 'helpscout_app_secret',
        ];
        $fields['help_scout'][] = [
            'title' => __( 'Callback URI', 'erp-pro' ),
            'type'  => 'text',
            'id'    => 'helpscout_callback_uri',
        ];
        $fields['help_scout'][] = [
            'type' => 'sectionend',
            'id'   => 'script_styling_options'
        ];

        return $fields;
    }


}
