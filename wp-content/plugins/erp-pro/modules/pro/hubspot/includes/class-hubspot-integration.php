<?php
namespace WeDevs\ERP\Hubspot;

use WeDevs\ERP\Integration;
use WeDevs\ERP\Framework\Traits\Hooker;

/**
 * Hubspot Integration
 */
class Hubspot_Integration extends Integration {

    use Hooker;

    /**
     * Class constructor.
     */
    function __construct() {
        $this->id          = 'hubspot-integration';
        $this->title       = __( 'Hubspot', 'wp-erp' );
        $this->description = __( 'Hubspot Add-on for WP-ERP.', 'wp-erp' );

        $this->init_settings();
        $this->action( $this->get_option_id() . '_action', 'update_option_action', 10, 2 );
        $this->filter( $this->get_option_id() . '_filter', 'update_option_filter', 10, 2 );

        parent::__construct();
    }

    /**
     * Get the title of this setting.
     *
     * @return string
     */
    public function get_title() {
        return $this->title;
    }

    /**
     * Get the description of this setting.
     *
     * @return string
     */
    public function get_description() {
        return $this->description;
    }

    /**
     * Get the fields of this setting.
     *
     * @return array
     */
    public function init_settings() {
        $desc_text = '<a target="_blank" href="https://developers.hubspot.com/docs/faq/how-do-i-create-an-app-in-hubspot">I need help getting my API key!</a>';

        $api_key = erp_hubspot_get_api_key();
        if ( $api_key ) {
            $url       = admin_url( 'admin.php?page=erp-crm&section=hubspot&action=disconnect' );
            $desc_text = '<a href="' . $url . '">Disconnect</a>';
        }

        $this->form_fields = [
            [
                'title'             => __( 'API Key', 'erp-pro' ),
                'id'                => 'api_key',
                'type'              => 'text',
                //'custom_attributes' => ['placeholder' => __( 'Your Hubspot API key', 'erp-pro' ) ], //todo: this line is displaying formatting error on frontend
                'desc'              => __( $desc_text, 'erp-pro' ),
            ],
        ];

        return $this->form_fields;
    }

    /**
     * Add action of this setting.
     *
     * @param  array $update_options
     *
     * @return boolean
     */
    public function update_option_action( $update_options ) {
        if( isset ( $update_options['api_key'] ) ) {
            $api_key = $update_options['api_key'];

            $hubspot = new Hubspot( $api_key );
            if ( $hubspot->is_connected() ) {
                return true;
            } else {
                echo '<h2>Invalid API key. Enter correct one! <a href="' . $_SERVER['HTTP_REFERER'] . '">Back</a></h2>';
                exit;
            }
        }

        return true;
    }

    /**
     * Add filter of this setting.
     *
     * @param  array $update_options
     *
     * @return array
     */
    public function update_option_filter( $update_options ) {
        if( isset ( $update_options['api_key'] ) ) {
            $update_options['email_lists'] = erp_hubspot_refresh_email_lists( $update_options['api_key'] );
        }

        return $update_options;
    }
}
