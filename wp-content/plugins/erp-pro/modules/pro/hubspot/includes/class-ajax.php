<?php
namespace WeDevs\ERP\Hubspot;

use WeDevs\ERP\Framework\Traits\Ajax;
use WeDevs\ERP\Framework\Traits\Hooker;

/**
 * Ajax Class
 *
 * @package WP-ERP
 * @subpackage Hubspot
 */
class Ajax_Handler {

    use Ajax;
    use Hooker;

    /**
     * Class constructor.
     */
    public function __construct() {
        $this->action( 'wp_ajax_erp_hubspot_sync', 'erp_hubspot_sync' );
        $this->action( 'wp_ajax_erp_hubspot_refresh_email_lists', 'erp_hubspot_refresh_email_lists' );
    }

    /**
     * Synchronize contacts with hubspot.
     *
     * @return void
     */
    public function erp_hubspot_sync() {
        $this->verify_nonce( 'erp-hubspot-sync-nonce' );

        $hubspot_api_key = erp_hubspot_get_api_key();

        $hubspot = new Hubspot( $hubspot_api_key );

        $group_id      = $_POST['group_id'];
        $hubspot_list  = $_POST['hubspot_list'];
        $sync_type     = $_POST['sync_type'];
        $contact_owner = $_POST['contact_owner'];
        $life_stage    = $_POST['life_stage'];

        $limit = 50; // Limit to sync per request

        $attempt = get_option( 'erp_hubspot_sync_attempt', 1 );
        update_option( 'erp_hubspot_sync_attempt', $attempt + 1 );
        $vid_offset = get_option( 'erp_hubspot_vid_offset', null );

        $offset = ( $attempt - 1 ) * $limit;

        if ( $sync_type == 'contacts_to_hubspot' ) {
            if ( ! empty( $group_id ) ) {
                $contact_contact_group = erp_crm_get_subscriber_contact( ['number' => $limit, 'group_id' => $group_id, 'offset' => $offset] );
                $total_items = erp_crm_get_subscriber_contact( ['group_id' => $group_id, 'count' => true] );

                $contact_ids = [];
                foreach ( $contact_contact_group as $item ) {
                    $contact_ids[] = $item->user_id;
                }

                $contacts = erp_get_people_by( 'id', $contact_ids );
            } else {
                $contacts = erp_get_peoples( ['type' => 'contact', 'number' => $limit, 'offset' => $offset] );

                $total_items = erp_get_peoples_count( 'contact' );
            }

            if ( $contacts ) {
                $data = [];
                foreach ( $contacts as $contact ) {
                    $data[] = [
                        'email' => $contact->email,
                        'properties' => [
                            [
                                'property' => 'firstname',
                                'value'    => $contact->first_name,
                            ],
                            [
                                'property' => 'lastname',
                                'value'    => $contact->last_name
                            ],
                        ]
                    ];
                }

                $hubspot->bulk_subscribe_to_list( $hubspot_list, $data );
            }
        }

        if ( $sync_type == 'hubspot_to_contacts' ) {
            $members = $hubspot->get_subscribed_members( $hubspot_list, $vid_offset );

            update_option( 'erp_hubspot_vid_offset', $members['vid-offset'] );
            $has_more = $members['has-more'];

            $inserted_ids = [];
            foreach ( $members['contacts'] as $member ) {
                $data = [
                    'type'          => 'contact',
                    'first_name'    => $member['properties']['firstname']['value'],
                    'last_name'     => $member['properties']['lastname']['value'],
                    'email'         => $member['identity-profiles'][0]['identities'][0]['value'],
                    'contact_owner' => $contact_owner,
                    'life_stage'    => $life_stage,
                ];

                $contact_id = erp_hubspot_create_contact( $data );

                if ( ! empty( $group_id ) && ! is_wp_error( $contact_id ) ) {
                    erp_crm_create_new_contact_subscriber( ['user_id' => (int) $contact_id, 'group_id' => (int) $group_id] );
                }
            }
        }

        // re-calculate stats
        $synced = $attempt * $limit;
        if ( $sync_type == 'contacts_to_hubspot' ) {
            if ( $total_items <= $synced ) {
                $has_more = false;
            } else {
                $has_more = true;
            }
        }

        if ( ! $has_more ) {
            delete_option( 'erp_hubspot_vid_offset' );
            delete_option( 'erp_hubspot_sync_attempt' );
        }

        $this->send_success( [ 'synced' => $synced, 'has_more' => $has_more, 'message' => sprintf( __( 'Synced %d contacts.', 'erp-pro' ), $synced ) ] );
    }

    /**
     * Refresh email lists from server.
     *
     * @return void
     */
    public function erp_hubspot_refresh_email_lists() {
        $this->verify_nonce( 'erp-hubspot-refresh-lists-nonce' );

        $lists = erp_hubspot_refresh_email_lists();

        $options = get_option( 'erp_integration_settings_hubspot-integration', [] );
        $options['email_lists'] = $lists;
        update_option( 'erp_integration_settings_hubspot-integration', $options );

        $this->send_success( [ 'lists' => $lists ] );
    }
}
