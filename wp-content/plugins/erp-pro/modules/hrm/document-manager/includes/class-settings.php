<?php

namespace WeDevs\ERP\HRM\ERP_Document;

use WeDevs\ERP\Framework\ERP_Settings_Page;

/**
 * General class
 */
class Settings extends ERP_Settings_Page {


    function __construct() {
        $this->id            = 'erp-dm';
        $this->label         = __( 'Document Manager', 'erp-pro' );
        $this->single_option = true;
        $this->sections      = $this->get_sections();
        add_action( 'erp_admin_field_sync_dropbox_employees', [ $this, 'sync_dropbox_employees' ] );
    }

    /**
     * Get sections
     *
     * @return array
     */
    public function get_sections() {
        $sections = array(
            'general'       => __( '', 'erp-pro' ),
        );

        return apply_filters( 'erp_get_sections_' . $this->id, $sections );
    }

    /**
     * Get sections fields
     *
     * @return array
     */
    public function get_section_fields( $section = '' ) {


        $fields['general'] = array(
            array( 'title' => __( '', 'erp-pro' ), 'type' => 'title', 'desc' => '', 'id' => 'general_options' ),

            array(
                'title' => __( 'Document manager Settings', 'erp-pro' ),
                'type'  => 'title',
                'desc'  => __( 'Settings for document manager.', 'erp-pro' ),
                'id'    => 'dm_settings'
            ),

            array(
                'title'   => __( 'Dropbox Access Token', 'erp-pro' ),
                'id'      => 'dropbox_access_token', // key for getting output from option table
                'type'    => 'text',
                'desc'    => __( 'This key will be used to access specific dropbox account. <a target="_blank" href="https://www.dropbox.com/developers/apps">Click Here</a> to create dropbox api access token.', 'erp-pro' ),
            ),

            array(
                'type' => 'sync_dropbox_employees',
            ),

            array(
                'title'   => __( 'Enable Dropbox', 'erp-pro' ),
                'id'      => 'enable_dropbox', // key for getting output from option table
                'type'    => 'checkbox',
                'desc'    => __( 'This check will be used to enable OR disable dropbox functionality', 'erp-pro' ),
                'default' => 'yes'
            ),

            array(
                'title'   => __( 'Enable Local directory', 'erp-pro' ),
                'id'      => 'enable_local_directory', // key for getting output from option table
                'type'    => 'checkbox',
                'desc'    => __( 'This check will be used to enable OR disable local directory functionality', 'erp-pro' ),
                'default' => 'yes'
            ),

            array(
                'type' => 'sectionend',
                'id'   => 'script_styling_options'
            )
        );

        $section = false === $section ? $fields['checkout'] : isset( $fields[ $section ] ) ? $fields[ $section ] : array();

        return apply_filters( 'erp_dm_settings_section_fields_' . $this->id, $section );
    }


    /**
     * Display imap test connection button.
     *
     * @return void
     */
    public function sync_dropbox_employees() {
        if( ! empty( $this->get_option( 'dropbox_access_token' ) ) ) {
            ?>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    &nbsp;
                </th>
                <td class="forminp forminp-text">
                    <a id="sync_dropbox_employees"
                       class="button-secondary"><?php esc_attr_e('Dropbox connection test', 'erp-pro'); ?>
                        <span class="erp-loader" id="erp-loader" style="display: none;"></span>
                    </a>
                    <p class="description"><?php esc_attr_e('Click on the above button to check connection.', 'erp-pro'); ?></p>
                </td>
            </tr>
            <script>
                jQuery( document ).ready(function() {
                    jQuery('#sync_dropbox_employees').click(function(){
                        jQuery( '#sync_dropbox_employees' ).addClass('loading');
                        jQuery.post( wpErp.ajaxurl,
                            {
                                action      : 'wp-erp-sync-employees-dropbox',
                                sync        : 'yes',
                                token       : jQuery('#dropbox_access_token').val(),
                                _wpnonce    : wpErp.nonce
                            }, function ( response ) {
                                if ( response.success === true ) {
                                    jQuery( '#sync_dropbox_employees' ).removeClass('loading');
                                    console.log(response);
                                    if( response.data.error ) {
                                        swal({
                                            type: 'error',
                                            title: 'Error',
                                            text: 'Failed to connect',
                                            footer: ''
                                        });
                                    } else {
                                        swal({
                                            type: 'success',
                                            title: 'Success',
                                            text: 'Successfully connected',
                                            footer: ''
                                        });
                                    }
                                }
                            }
                        );
                    });
                });
            </script>
            <style>
                .forminp a.loading {
                    width: 186px;
                }
                .forminp a.loading .erp-loader{
                    margin-top: 5px;
                    margin-left: 5px;
                    display: inline !important;
                }
            </style>
            <?php
        }
    }

}

return new Settings();
