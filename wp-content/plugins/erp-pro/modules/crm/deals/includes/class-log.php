<?php
namespace WeDevs\ERP\CRM\Deals;

use WeDevs\ERP\Framework\Traits\Hooker;
use WeDevs\ERP\CRM\Deals\Helpers;

/**
 * Deal audit log
 *
 * @since 1.0.0
 */
class Log {

    use Hooker;

    private $special_field_vals = [];

    /**
     * Initializes the class
     *
     * Checks for an existing instance
     * and if it doesn't find one, creates it.
     *
     * @since 1.0.0
     *
     * @return object Class instance
     */
    public static function instance() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * The class constructor
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function __construct() {
        $this->action( 'erp_deals_save_deal', 'erp_deals_save_deal', 10, 3 );
        $this->action( 'erp_deals_delete_deal', 'erp_deals_delete_deal', 10, 2 );
        $this->action( 'erp_deals_save_activity', 'erp_deals_save_activity', 10, 3 );
        $this->action( 'erp_deals_delete_activity', 'erp_deals_delete_activity', 10, 3 );
        $this->action( 'erp_deals_add_agents', 'erp_deals_add_agents', 10, 2 );
        $this->action( 'erp_deals_remove_agents', 'erp_deals_remove_agents', 10, 2 );
        $this->action( 'erp_deals_add_attachment', 'erp_deals_add_attachment', 10, 2 );
        $this->action( 'erp_deals_remove_attachments', 'erp_deals_remove_attachments', 10, 2 );
        $this->action( 'erp_deals_save_note', 'erp_deals_save_note', 10, 4 );
        $this->action( 'erp_deals_delete_note', 'erp_deals_delete_note' );
    }

    /**
     * Store audit log
     *
     * @since 1.0.0
     *
     * @param array $args
     *
     * @return void
     */
    public function audit_log( $args ) {
        // the message field is required
        if ( empty( $args['message'] ) ) {
            return;
        }

        $defaults = [
            'component'     => 'CRM',
            'sub_component' => 'Deals',
            'changetype'    => 'add',
            'created_by'    => get_current_user_id(),
        ];


        $args = wp_parse_args( $args, $defaults );

        if ( !empty( $args['old_value'] ) ) {
            $args['old_value'] = base64_encode( maybe_serialize( $args['old_value'] ) );
        }

        if ( !empty( $args['new_value'] ) ) {
            $args['new_value'] = base64_encode( maybe_serialize( $args['new_value'] ) );
        }

        erp_log()->insert_log( $args );
    }

    /**
     * Formatted value for a changed field
     *
     * @since 1.0.0
     *
     * @param string     $key
     * @param string|int $val
     *
     * @return string
     */
    public function get_special_field_vals( $key, $val ) {
        if ( isset( $this->special_field_vals[ $key ][ $val ] ) ) {
            return $this->special_field_vals[ $key ][ $val ];
        }

        $value = '';

        switch ( $key ) {
            case 'contact_id':
                $contact = Helpers::get_people_by_id( $val );
                $value = sprintf(
                    '<a href="%s" target="_blank">%s</a>',
                    $contact['details_url'],
                    $contact['first_name'] . ' ' . $contact['last_name']
                );
                break;

            case 'company_id':
                $company = Helpers::get_people_by_id( $val );
                $value = sprintf(
                    '<a href="%s" target="_blank">%s</a>',
                    $company['details_url'],
                    $company['company']
                );
                break;

            case 'stage_id':
                $value = \WeDevs\ERP\CRM\Deals\Models\PipelineStage::find( $val )->title;
                break;

            case 'owner_id':
            case 'assigned_to_id':
            case 'agent_id':
                $user = \WP_User::get_data_by( 'ID', $val );
                if ( !empty( $user ) ) {
                    $value = sprintf(
                        '<a href="%s" target="_blank">%s</a>',
                        add_query_arg( 'user_id', $val, self_admin_url( 'user-edit.php' ) ),
                        $user->display_name
                    );
                }
                break;

            case 'attachment_id':
                $meta_data = wp_prepare_attachment_for_js( $val );
                $value = $meta_data['filename'];
                break;
        }

        $this->special_field_vals[ $key ][ $val ] = $value;

        return $value;
    }

    /**
     * Calculate the old and new values
     *
     * @since 1.0.0
     *
     * @param array  $new_data
     * @param array  $old_data
     * @param string $dataType
     *
     * @return array
     */
    private function get_diff( $new_data, $old_data, $dataType = '' ) {
        $diff = [];
        $special_fields = [
            'contact_id'        => 'contact',
            'company_id'        => 'company',
            'stage_id'          => 'stage',
            'owner_id'          => 'owner',
            'assigned_to_id'    => 'assigned_to',
        ];

        $old_diff = array_diff( $old_data, $new_data );
        $old_values = [];
        $new_values = [];

        if ( !empty( $old_diff ) ) {

            foreach( $old_diff as $key => $value ) {
                if ( 'activity' === $dataType && 'type' === $key ) {
                    $old_values[ $key ] = \WeDevs\ERP\CRM\Deals\Models\ActivityType::find( $old_data[ $key ] )->title;
                    $new_values[ $key ] = \WeDevs\ERP\CRM\Deals\Models\ActivityType::find( $new_data[ $key ] )->title;

                } else if ( array_key_exists( $key, $special_fields ) ) {
                    $old_values[ $special_fields[ $key ] ] = $this->get_special_field_vals( $key, $old_diff[ $key ] );
                    $new_values[ $special_fields[ $key ] ] = $this->get_special_field_vals( $key, $new_data[ $key ] );

                } else if ( isset( $new_data[ $key ] ) ) {
                    $old_values[ $key ] = $old_data[ $key ];
                    $new_values[ $key ] = $new_data[ $key ];
                } else {
                    $old_values[ $key ] = $old_data[ $key ];
                    $new_values[ $key ] = null;
                }

            }

            $diff = [
                'old_value' => $old_values,
                'new_value' => $new_values
            ];

        } else {
            $new_diff = array_diff( $new_data, $old_data );
            $old_values = [];
            $new_values = [];

            if ( !empty( $new_diff ) ) {
                foreach( $new_diff as $key => $value ) {

                    if ( array_key_exists( $key, $special_fields ) ) {
                        $old_values[ $special_fields[ $key ] ] = $this->get_special_field_vals( $key, $old_data[ $key ] );
                        $new_values[ $special_fields[ $key ] ] = $this->get_special_field_vals( $key, $new_diff[ $key ] );

                    } else if ( isset( $old_data[ $key ] ) ) {
                        $old_values[ $key ] = $old_data[ $key ];
                        $new_values[ $key ] = $new_data[ $key ];

                    } else {
                        $old_values[ $key ] = null;
                        $new_values[ $key ] = $new_data[ $key ];
                    }

                }

                $diff = [
                    'old_value' => $old_values,
                    'new_value' => $new_values
                ];
            }
        }

        return $diff;
    }

    /**
     * Log after save a deal
     *
     * @since 1.0.0
     *
     * @param object  $deal     Eloquent Deal model with new data
     * @param array   $old_data Old deal data
     * @param boolean $is_new   Is new deal or updating an existing deal
     *
     * @return void
     */
    public function erp_deals_save_deal( $deal, $old_data, $is_new ) {
        $new_data = $deal->toArray();
        unset( $new_data['id'] );
        unset( $new_data['created_by'] );
        unset( $new_data['created_at'] );
        unset( $new_data['updated_at'] );
        unset( $new_data['deleted_at'] );

        $link = Helpers::admin_url( [ 'sub-section' => 'all-deals','action' => 'view-deal', 'id' => $deal->id ] );
        $title = $deal->title;

        if ( !$is_new ) {
            $diff = $this->get_diff( $new_data, $old_data );
        }

        if ( $is_new ) {
            $args = [
                'data_id' => $deal->id,
                'message' => sprintf( __( '<span data-type="deal">New deal</span>: <a href="%s" target="_blank">%s</a>', 'erp-pro' ), $link, $title )
            ];

        } else if ( !empty( $diff ) ) {
            $args = [
                'data_id'       => $deal->id,
                'changetype'    => 'edit',
                'message'       => sprintf( __( '<span data-type="deal">Updated</span> <a href="%s" target="_blank">%s</a>', 'erp-pro' ), $link, $title ),
                'old_value'     => $diff['old_value'],
                'new_value'     => $diff['new_value']
            ];

        } else {
            return;
        }

        $this->audit_log( $args );
    }

    /**
     * Log after trash, restore or delete a deal
     *
     * @since 1.0.0
     *
     * @param object $deal Eloquent Deal model
     * @param string $action trash, restore or delete
     *
     * @return void
     */
    public function erp_deals_delete_deal( $deal, $action ) {
        $link = Helpers::admin_url( [ 'sub-section' => 'all-deals', 'action' => 'view-deal', 'id' => $deal->id ] );
        $title = $deal->title;

        switch ( $action ) {
            case 'trash':
                $message = sprintf( __( '<span data-type="deal">Updated</span>: <a href="%s" target="_blank">%s</a> (<span data-sub-changes="trashed">trashed</span>)', 'erp-pro' ), $link, $title );
                break;

            case 'restore':
                $message = sprintf( __( '<span data-type="deal">Updated</span>: <a href="%s" target="_blank">%s</a> (<span data-sub-changes="restored">restored</span>)', 'erp-pro' ), $link, $title );
                break;

            case 'delete':
                $message = sprintf( __( '<span data-type="deal">Deleted</span>: %s', 'erp-pro' ), $title );
                break;

            default:
                return;
        }

        $args = [
            'data_id'       => $deal->id,
            'message'       => $message,
            'changetype'    => 'edit'
        ];

        if ( 'delete' === $action ) {
            $args['changetype'] = 'delete';
        }

        $this->audit_log( $args );
    }

    /**
     * Log after save an activity
     *
     * @since 1.0.0
     *
     * @param object  $activity Eloquent Activity model containing new/updated activity data
     * @param array   $old_data Old activity data
     * @param boolean $is_new   Is new activity or updating an existing activity
     *
     * @return void
     */
    public function erp_deals_save_activity( $activity, $old_data, $is_new ) {
        $new_data = $activity->toArray();
        unset( $new_data['id'] );
        unset( $new_data['created_by'] );
        unset( $new_data['created_at'] );
        unset( $new_data['updated_at'] );
        unset( $new_data['deleted_at'] );

        if ( !$is_new ) {
            $format = erp_get_option( 'date_format', 'erp_settings_general', 'd-m-Y' );
            $new_data['due_date'] = date( $format, strtotime( $new_data['start']  ) );
            $new_data['time'] = !empty( $new_data['is_start_time_set'] ) ? date( 'h:i', strtotime( $new_data['start'] ) ) : null;
            $new_data['duration'] = ( $new_data['start'] === $new_data['end'] ) ? null : Helpers::calculate_duration( $new_data['start'], $new_data['end'] );

            $old_data['due_date'] = date( $format, strtotime( $old_data['start'] ) );
            $old_data['time'] = !empty( $old_data['is_start_time_set'] ) ? date( 'h:i', strtotime( $old_data['start'] ) ) : null;
            $old_data['duration'] = ( $old_data['start'] === $old_data['end'] ) ? null : Helpers::calculate_duration( $old_data['start'], $old_data['end'] );

            unset( $new_data['start'] );
            unset( $new_data['end'] );
            unset( $new_data['is_start_time_set'] );
            unset( $old_data['start'] );
            unset( $old_data['end'] );
            unset( $old_data['is_start_time_set'] );
        }

        $diff = $this->get_diff( $new_data, $old_data, 'activity' );
        $link = Helpers::admin_url( [ 'sub-section' => 'all-deals', 'action' => 'view-deal', 'id' => $activity->deal_id ] );
        $title = $activity->title;

        if ( $is_new ) {
            $args = [
                'data_id' => $activity->deal_id,
                'message' => sprintf( __( '<span data-type="activity" data-type-id="%d">New activity</span>: <a href="%s" target="_blank">%s</a>', 'erp-pro' ), $activity->id, $link, $title )
            ];

        } else if ( !empty( $diff ) ) {
            $args = [
                'data_id'       => $activity->deal_id,
                'changetype'    => 'edit',
                'message'       => sprintf( __( '<span data-type="activity" data-type-id="%d">Updated activity</span>: <a href="%s" target="_blank">%s</a>', 'erp-pro' ), $activity->id, $link, $title ),
                'old_value'     => $diff['old_value'],
                'new_value'     => $diff['new_value']
            ];

        } else {
            return;
        }

        $this->audit_log( $args );
    }

    /**
     * Log after delete an activity
     *
     * @since 1.0.0
     *
     * @param objeect $activity Eloquent Activity model containing new/updated activity data
     *
     * @return void
     */
    public function erp_deals_delete_activity( $activity ) {
        $args = [
            'data_id'       => $activity->deal_id,
            'changetype'    => 'delete',
            'message'       => sprintf( __( '<span data-type="activity">Deleted activity</span>: <span data-title>%s</span>', 'erp-pro' ), $activity->title ),
        ];

        $this->audit_log( $args );
    }

    /**
     * Log after add agents to a deal
     *
     * @since 1.0.0
     *
     * @param object $deal   Eloquent Deal model
     * @param array  $agents Array of Eloquent Agent models
     *
     * @return void
     */
    public function erp_deals_add_agents( $deal, $agents ) {
        $agentNames = [];

        foreach ( $agents as $agent ) {
            $agentNames[] = $this->get_special_field_vals( 'agent_id', $agent->agent_id );
        }

        $link = Helpers::admin_url( [ 'sub-section' => 'all-deals', 'action' => 'view-deal', 'id' => $deal->id ] );
        $title = $deal->title;

        $args = [
            'data_id'       => $deal->id,
            'changetype'    => 'edit',
            'message'       => sprintf( '<span data-type="agents">%s</span> <a href="%s" target="_blank">%s</a> - <span data-title>%s: %s</span>',
                __( 'Updated', 'erp-pro' ),
                $link,
                $title,
                ( count($agentNames) < 2 ) ? __( 'Added agent', 'erp-pro' ) : __( 'Added agents', 'erp-pro' ),
                implode( ', ' , $agentNames )
            ),
        ];

        $this->audit_log( $args );

    }

    /**
     * Log after remove agents from a deal
     *
     * @since 1.0.0
     *
     * @param object $deal      Eloquent Deal model
     * @param array  $agent_ids Removed agent ids
     *
     * @return void
     */
    public function erp_deals_remove_agents( $deal, $agent_ids ) {
        $agentNames = [];

        foreach ( $agent_ids as $agent_id ) {
            $agentNames[] = $this->get_special_field_vals( 'agent_id', $agent_id );
        }

        $link = Helpers::admin_url( [ 'sub-section' => 'all-deals', 'action' => 'view-deal', 'id' => $deal->id ] );
        $title = $deal->title;

        $args = [
            'data_id'       => $deal->id,
            'changetype'    => 'edit',
            'message'       => sprintf( '<span data-type="agents">%s</span> <a href="%s" target="_blank">%s</a> - <span data-title>%s: %s</span>',
                __( 'Updated', 'erp-pro' ),
                $link,
                $title,
                ( count($agentNames) < 2 ) ? __( 'Removed agent', 'erp-pro' ) : __( 'Removed agents', 'erp-pro' ),
                implode( ', ' , $agentNames )
            ),
        ];

        $this->audit_log( $args );
    }

    /**
     * Log after add attachment to a deal
     *
     * @since 1.0.0
     *
     * @param object $deal       Eloquent Deal model
     * @param array  $attachment Eloquent Attachment model
     *
     * @return void
     */
    public function erp_deals_add_attachment( $deal, $attachment ) {
        $args = [
            'data_id'       => $deal->id,
            'changetype'    => 'edit',
            'message'       => sprintf( '<span data-type="attachment">%s</span> <a href="%s" target="_blank">%s</a> - <span data-title>%s: %s</span>',
                __( 'Updated', 'erp-pro' ),
                Helpers::admin_url( [ 'action' => 'view-deal', 'id' => $deal->id ] ),
                $deal->title,
                __( 'Add attachment', 'erp-pro' ),
                $this->get_special_field_vals( 'attachment_id', $attachment->attachment_id )
            ),
        ];

        $this->audit_log( $args );
    }

    /**
     * Log after remove attachments from a deal
     *
     * @since 1.0.0
     *
     * @param object $deal           Eloquent Deal model
     * @param array  $attachment_ids Removed attachment ids
     *
     * @return void
     */
    public function erp_deals_remove_attachments( $deal, $attachment_ids ) {
        $attachment_names = [];

        foreach ( $attachment_ids as $attachment_id ) {
            $attachment_names[] = $this->get_special_field_vals( 'attachment_id', $attachment_id );
        }

        $args = [
            'data_id'       => $deal->id,
            'changetype'    => 'delete',
            'message'       => sprintf( '<span data-type="attachment">%s</span> <a href="%s" target="_blank">%s</a> - <span data-title>%s: %s</span>',
                __( 'Updated', 'erp-pro' ),
                Helpers::admin_url( [ 'sub-section' => 'all-deals', 'action' => 'view-deal', 'id' => $deal->id ] ),
                $deal->title,
                ( count($attachment_names) < 2 ) ? __( 'Removed attachment', 'erp-pro' ) : __( 'Removed attachments', 'erp-pro' ),
                implode( ', ' , $attachment_names )
            ),
        ];

        $this->audit_log( $args );
    }

    /**
     * Log after create/update a note
     *
     * @since 1.0.0
     *
     * @param object  $deal     Eloquent Deal model
     * @param object  $note     Eloquent Note model containing new/updated note data
     * @param array   $old_data Old note data
     * @param boolean $is_new   Is new note or updating an existing one
     *
     * @return void
     */
    public function erp_deals_save_note( $deal, $note, $old_data, $is_new ) {
        $link = Helpers::admin_url( [ 'sub-section' => 'all-deals', 'action' => 'view-deal', 'id' => $deal->id ] );
        $title = $deal->title;
        $change_type = $is_new ? 'add' : 'edit';
        $change_msg = $is_new ? __( 'Added new note', 'erp-pro' ) : __( 'Edited note', 'erp-pro' );
        $message = sprintf( __( '<span data-type="deal">Updated</span> <a href="%s" target="_blank">%s</a> - <span data-sub-changes="%s">%s</span>', 'erp-pro' ), $link, $title, $change_type, $change_msg );

        $args = [
            'data_id'       => $deal->id,
            'changetype'    => 'edit',
            'message'       => $message,
            'old_value'     => [ 'note' => $old_data['note'] ],
            'new_value'     => [ 'note' => $note->note ],
        ];

        if ( !$is_new ) {
            if ( !$old_data['is_sticky'] && $note->is_sticky ) {
                $change_msg = __( 'Pinned a note', 'erp-pro' );
                $args['message'] = sprintf( __( '<span data-type="deal">Updated</span> <a href="%s" target="_blank">%s</a> - <span data-sub-changes="%s">%s</span>', 'erp-pro' ), $link, $title, 'sticky', $change_msg );
                $args['old_value'] = [ 'note' => $old_data['note'], 'pinned' => '' ];
                $args['new_value'] = [ 'note' => $note->note, 'pinned' => __( 'yes', 'erp-pro' ) ];

            } else if ( $old_data['is_sticky'] && !$note->is_sticky ) {
                $change_msg = __( 'Unpinned a note', 'erp-pro' );
                $args['message'] = sprintf( __( '<span data-type="deal">Updated</span> <a href="%s" target="_blank">%s</a> - <span data-sub-changes="%s">%s</span>', 'erp-pro' ), $link, $title, 'sticky', $change_msg );
                $args['old_value'] = [ 'note' => $old_data['note'], 'pinned' => __( 'yes', 'erp-pro' ) ];
                $args['new_value'] = [ 'note' => $note->note, 'pinned' => '' ];
            }
        }

        $this->audit_log( $args );
    }

    /**
     * Log after delete a note
     *
     * @since 1.0.0
     *
     * @param object $note Eloquent Note model
     *
     * @return void
     */
    public function erp_deals_delete_note( $note ) {
        $deal = $note->deal;

        $link = Helpers::admin_url( [ 'sub-section' => 'all-deals', 'action' => 'view-deal', 'id' => $deal->id ] );
        $title = $deal->title;
        $change_type = 'delete';
        $change_msg = __( 'Deleted note', 'erp-pro' );

        $args = [
            'data_id'       => $deal->id,
            'changetype'    => 'edit',
            'message'       => sprintf( __( '<span data-type="deal">Updated</span> <a href="%s" target="_blank">%s</a> - <span data-sub-changes="%s">%s</span>', 'erp-pro' ), $link, $title, $change_type, $change_msg ),
            'old_value'     => [ 'note' => $note->note ],
            'new_value'     => [ 'note' => null ],
        ];

        $this->audit_log( $args );
    }

    /**
     * Deal changelog
     *
     * @since 1.0.0
     *
     * @param array $args
     *
     * @return array
     */
    public function get_changelog( $args = [] ) {
        $users = [];

        $defaults = [
            'component' => 'CRM',
            'sub_component' => 'Deals',
            'data_id' => 0
        ];

        $args = wp_parse_args( $args, $defaults );

        $audit_logs = erp_log()->get( $args );

        // instead of foreach, we're using array_map on $audit_log
        $changelog = array_map( function ( $log ) use ( &$users ) {
            if ( !array_key_exists( $log->created_by , $users ) ) {
                $user = \WP_User::get_data_by( 'ID', $log->created_by );
                $users[ $log->created_by ] = $user->display_name;
            }

            $audit_log = [
                'type'        => '',
                'title'       => '',
                'change_type' => '',
                'created_at'  => $log->created_at,
                'created_by'  => $users[ $log->created_by ]
            ];

            // find out type like deal, activity, competitor etc
            if ( preg_match( '/data-type="(.*?)"/' , $log->message, $match ) ) {
                $audit_log['type'] = $match[1];
            }

            if ( preg_match( '/<span data-title>(.*)<\/span>/' , $log->message, $match ) ) {
                $audit_log['title'] = $match[1];

            } else if ( preg_match( '/<a.*>(.*)<\/a>/' , $log->message, $match ) ) {
                $audit_log['title'] = $match[1];
            }

            $audit_log['sub_change_type'] = '';
            $audit_log['sub_change_msg'] = '';
            if ( preg_match( '/<span data-sub-changes="(.*)">(.*)<\/span>/', $log->message, $match ) ) {
                $audit_log['sub_change_type'] = $match[1];
                $audit_log['sub_change_msg'] = $match[2];
            }


            switch ( $log->changetype ) {

                case 'edit':
                    $audit_log['change_type'] = 'edit';
                    $audit_log['old_values']  = maybe_unserialize( base64_decode( $log->old_value ) );
                    $audit_log['new_values']  = maybe_unserialize( base64_decode( $log->new_value ) );
                    break;

                case 'add':
                    $audit_log['change_type'] = 'add';
                    break;

                case 'delete':
                    $audit_log['change_type'] = 'delete';
                    break;

            }

            return $audit_log;

        }, $audit_logs );

        return $changelog;
    }
}

/**
 * Class instance
 *
 * @since 1.0.0
 *
 * @return object
 */
function audit_log() {
    return Log::instance();
}

// Make an instance immediately when include this file,
// so that the hook will be activated
audit_log();
