<?php
namespace WeDevs\ERP\CRM\Deals;

use WeDevs\ERP\Framework\Traits\Hooker;
use WeDevs\ERP\Framework\Traits\Ajax;
use WeDevs\ERP\CRM\Deals\Helpers;
use WeDevs\ERP\CRM\Deals\Deals;

/**
 * Ajax action hooks
 *
 * @since 1.0.0
 */
class Deal_Ajax {

    use Hooker;
    use Ajax;

    /**
     * The class constructor
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function __construct() {
        $this->action( 'wp_ajax_get_deals', 'get_deals' );
        $this->action( 'wp_ajax_get_deals_by_pipeline', 'get_deals_by_pipeline' );
        $this->action( 'wp_ajax_save_deal', 'save_deal' );
        $this->action( 'wp_ajax_delete_deal', 'delete_deal' );
        $this->action( 'wp_ajax_get_single_deal_data', 'get_single_deal_data' );
        $this->action( 'wp_ajax_search_deals', 'search_deals' );
        $this->action( 'wp_ajax_get_deal_pipeline_id', 'get_deal_pipeline_id' );
        $this->action( 'wp_ajax_get_activities', 'get_activities' );
        $this->action( 'wp_ajax_get_activity_list', 'get_activity_list' );
        $this->action( 'wp_ajax_save_activity', 'save_activity' );
        $this->action( 'wp_ajax_delete_activity', 'delete_activity' );
        $this->action( 'wp_ajax_update_deal_people', 'update_deal_people' );
        $this->action( 'wp_ajax_search_people', 'search_people' );
        $this->action( 'wp_ajax_add_agents', 'add_agents' );
        $this->action( 'wp_ajax_remove_agents', 'remove_agents' );
        $this->action( 'wp_ajax_save_deal_note', 'save_deal_note' );
        $this->action( 'wp_ajax_delete_note', 'delete_note' );
        $this->action( 'wp_ajax_add_deal_attachment', 'add_deal_attachment' );
        $this->action( 'wp_ajax_remove_deal_attachment', 'remove_deal_attachment' );
        $this->action( 'wp_ajax_erp_deals_save_email_template', 'save_email_template' );
        $this->action( 'wp_ajax_erp_deals_send_email', 'send_email' );
        $this->action( 'wp_ajax_save_competitor', 'save_competitor' );
        $this->action( 'wp_ajax_delete_competitor', 'delete_competitor' );
        $this->action( 'wp_ajax_get_changelog', 'get_changelog' );
        $this->action( 'wp_ajax_get_erp_deals_settings', 'get_erp_deals_settings' );
        $this->action( 'wp_ajax_save_pipeline', 'save_pipeline' );
        $this->action( 'wp_ajax_get_pipeline_deals_count', 'get_pipeline_deals_count' );
        $this->action( 'wp_ajax_delete_pipeline', 'delete_pipeline' );
        $this->action( 'wp_ajax_get_pipeline_stages', 'get_pipeline_stages' );
        $this->action( 'wp_ajax_reorder_stages', 'reorder_stages' );
        $this->action( 'wp_ajax_save_stage', 'save_stage' );
        $this->action( 'wp_ajax_get_stage_deals_count', 'get_stage_deals_count' );
        $this->action( 'wp_ajax_delete_stage', 'delete_stage' );
        $this->action( 'wp_ajax_save_activity_type', 'save_activity_type' );
        $this->action( 'wp_ajax_reorder_activity_types', 'reorder_activity_types' );
        $this->action( 'wp_ajax_save_lost_reason', 'save_lost_reason' );
        $this->action( 'wp_ajax_delete_lost_reason', 'delete_lost_reason' );
        $this->action( 'wp_ajax_get_people', 'get_people' );
        $this->action( 'wp_ajax_get_deal_primary_contacts', 'get_deal_primary_contacts' );
        $this->action( 'wp_ajax_get_overview_data', 'get_overview_data' );
    }

    /**
     * Get ERP Deals
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function get_deals() {
        $this->verify_nonce( 'erp-deals' );

        if ( !empty( $_GET['args'] ) ) {
            $args = $_GET['args'];
        } else {
            $args = [];
        }

        $data = [
            'deals' => deals()->get_deals( $args )
        ];

        $this->send_success( $data );
    }

    /**
     * Landing page data
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function get_deals_by_pipeline() {
        $this->verify_nonce( 'erp-deals' );

        $company_currency = erp_get_currency_symbol( erp_get_currency() );
        $company_currency = html_entity_decode( $company_currency );


        if ( !empty( $_GET['pipeline_id'] ) ) {
            $pipeline_id = $_GET['pipeline_id'];

        } else {
            // $pipeline_id = 1; // @todo: fetch default id from settings
            $pipeline_id = Helpers::get_pipelines()->first()->id;
        }

        $args = [];

        if ( !empty( $_GET['filters'] ) ) {
            $args['filters'] = $_GET['filters'];
        }

        $data = [
            'pipelineId'                => $pipeline_id,
            'pipeline'                  => deals()->get_deals_by_pipeline( $pipeline_id, $args ),
            'currencySymbol'            => $company_currency,
        ];

        if ( current_user_can( 'administrator' ) || erp_crm_is_current_user_manager() ) {
            $data['users'] = Helpers::get_crm_agents_with_current_user();
        }

        $this->send_success( $data );
    }

    /**
     * Single deal data
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function get_single_deal_data() {
        $this->verify_nonce( 'erp-deals' );

        if ( empty( $_GET['deal_id'] ) ) {
            $this->send_error( [ 'msg' => __( 'Invalid deal', 'erp-pro' ) ] );
        }

        $deal = deals()->get_single_deal_data( $_GET['deal_id'] );

        if ( is_wp_error( $deal ) ) {
            $this->send_error( [ 'msg' => $deal->get_error_message() ] );
        }

        $this->send_success( $deal );
    }

    /**
     * Search deals
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function search_deals() {
        $this->verify_nonce( 'erp-deals' );

        if ( empty( $_GET['s'] ) ) {
            $this->send_error( [ 'msg' => __( 'Invalid operation', 'erp-pro' ) ] );
        }

        $data = [
            'deals' => deals()->search_deals( $_GET['s'] )
        ];

        $this->send_success( $data );
    }

    /**
     * Get pipeline id of a deal
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function get_deal_pipeline_id() {
        $this->verify_nonce( 'erp-deals' );

        if ( empty( $_GET['deal_id'] ) ) {
            $this->send_error( [ 'msg' => __( 'Invalid deal', 'erp-pro' ) ] );
        }

        $deal = deals()->get_deal( $_GET['deal_id'], true );

        if ( empty( $deal ) ) {
            $this->send_error( [ 'msg' => __( 'Invalid deal', 'erp-pro' ) ] );
        }

        $data = [
            'pipeline_id' => $deal->pipeline_stage->pipeline->id
        ];

        $this->send_success( $data );
    }

    /**
     * Save deal data
     *
     * Insert or update deal
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function save_deal() {
        $this->verify_nonce( 'erp-deals' );

        $fields = [
            'id', 'title', 'stage_id', 'contact_id', 'company_id', 'owner_id', 'value',
            'currency', 'expected_close_date', 'lost_reason_id', 'lost_reason', 'lost_reason_comment'
        ];

        foreach ( $fields as $field ) {
            if ( isset( $_POST['deal'][ $field ] ) ) {
                $data[ $field ] = $_POST['deal'][ $field ];
            }
        }

        // get default currency
        // @todo: make this dynamic when ERP core will support multicurrency
        $data['currency'] = erp_get_currency();

        // set owner
        if ( !erp_crm_is_current_user_manager() ) {
            $data['owner_id'] = get_current_user_id();
        }

        // won
        if ( isset( $_POST['deal']['won'] ) ) {
            if ( filter_var( $_POST['deal']['won'], FILTER_VALIDATE_BOOLEAN ) ) {
                $data['won_at']              = current_time( 'mysql' );
                $data['lost_at']             = null;
                $data['lost_reason_id']      = null;
                $data['lost_reason']         = null;
                $data['lost_reason_comment'] = null;

            } else {
                $data['won_at'] = null;
            }
        }

        // lost
        if ( isset( $_POST['deal']['lost_reason_id'] ) ) {
            $data['lost_reason_id'] = absint( $_POST['deal']['lost_reason_id'] );
            $data['lost_reason']    = null;

        }

        if ( isset( $_POST['deal']['lost_reason'] ) && !empty( $_POST['deal']['lost_reason'] ) ) {
            $data['lost_reason_id'] = null;
            $data['lost_reason']    = $_POST['deal']['lost_reason'];
        }

        if ( isset( $data['lost_reason_id'] ) || isset( $data['lost_reason'] ) ) {
            $data['lost_at']             = current_time( 'mysql' );
            $data['won_at']              = null;
            $data['lost_reason_comment'] = isset( $_POST['deal']['lost_reason_comment'] ) ? $_POST['deal']['lost_reason_comment'] : null;
        }

        // reopen
        if ( isset( $_POST['deal']['reopen'] ) && filter_var( $_POST['deal']['reopen'], FILTER_VALIDATE_BOOLEAN ) ) {
            $data['won_at'] = null;
            $data['lost_at'] = null;
            $data['lost_reason_id'] = null;
            $data['lost_reason'] = null;
            $data['lost_reason_comment'] = null;
        }

        // participants
        if ( !empty( $_POST['deal']['add_participants'] ) ) {
            $data['add_participants'] = $_POST['deal']['add_participants'];
        }

        if ( !empty( $_POST['deal']['remove_participants'] ) ) {
            $data['remove_participants'] = $_POST['deal']['remove_participants'];
        }

        // save deal data
        $deal = deals()->save_deal( $data );

        // send error on failure
        if ( is_wp_error( $deal ) ) {
            $this->send_error( [ 'msg' => $deal->get_error_message() ] );
        }

        $data = [
            'deal' => $deal
        ];


        if ( !empty( $_POST['deal']['add_participants'] ) ) {
            $data['participants'] = deals()->get_deal_participants( $deal->id );
        }

        $this->send_success( $data );
    }

    /**
     * Trash, restore or permanently delete a deal
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function delete_deal() {
        $this->verify_nonce( 'erp-deals' );

        $operations = [ 'trash', 'restore', 'delete' ];

        if ( empty( $_POST['deal']['id'] ) || empty( $_POST['deal']['action'] ) || !in_array( $_POST['deal']['action'] , $operations ) ) {
            $this->send_error( ['msg' => __( 'Invalid operation', 'erp-pro' ) ] );
        }

        $deal = deals()->delete_deal( $_POST['deal']['id'], $_POST['deal']['action'] );

        if ( is_wp_error( $deal ) ) {
            $this->send_error( ['msg' => $deal->get_error_message() ] );
        }

        $this->send_success( [ 'deal' => $deal ] );
    }

    /**
     * Get all activities under a Deals
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function get_activities() {
        $this->verify_nonce( 'erp-deals' );

        if ( empty( $_GET['deal_id'] ) ) {
            $this->send_error( [ 'msg' => __( 'Invalid operation', 'erp-pro' ) ] );
        }

        $fields = [
            'id', 'type', 'title', 'deal_id', 'contact_id', 'company_id',
            'assigned_to_id', 'start', 'end', 'is_start_time_set', 'note', 'done_at'
        ];

        // set the sql modifiers
        if ( !empty( $_GET['args'] ) ) {
            $args = $_GET['args'];
        } else {
            $args = [];
        }

        // Agent can only see own activities
        if ( !( current_user_can( 'administrator' ) || erp_crm_is_current_user_manager() ) ) {
            $args['assigned_to_id'] = get_current_user_id();
        }

        $data = [
            'activities' => deals()->get_activities( $_GET['deal_id'], $fields, $args )
        ];

        $this->send_success( $data );
    }

    /**
     * Get activity list for activities menu page
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function get_activity_list() {
        $this->verify_nonce( 'erp-deals' );

        $args = [
            'with_names' => true,
            'limit' => 25,
        ];

        if ( empty( $_GET['filters']['status'] ) ) {
            $args['only_incomplete'] = true;

        } else {
            if ( 'completed' === $_GET['filters']['status'] ) {
                $args['only_completed'] = true;

            } else {
                $args['only_incomplete'] = true;
            }
        }

        if ( !empty( $_GET['exclude'] ) ) {
            $args['exclude'] = $_GET['exclude'];
        }

        if ( !empty( $_GET['filters']['type'] ) ) {
            $args['type'] = absint( $_GET['filters']['type'] );
        }

        if ( !empty( $_GET['filters']['period'] ) ) {

            switch ( $_GET['filters']['period'] ) {
                case 'planned':
                    $args['start'] = date( 'Y-m-d 00:00:00' );
                    break;

                case 'overdue':
                    $args['end'] = date( 'Y-m-d 00:00:00' );
                    break;

                case 'range':
                    if ( !empty( $_GET['filters']['from'] ) && !empty( $_GET['filters']['to'] ) ) {
                        $args['start']  = $_GET['filters']['from'];
                        $args['end']    = $_GET['filters']['to'];
                    }
                    break;
            }

        }

        $activities = deals()->get_activity_list( $args );
        $count = deals()->get_activity_list( $args, true );

        $this->send_success( [ 'activities' => $activities, 'count' => $count ] );
    }

    /**
     * Save activity ajax method
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function save_activity() {
        global $wpdb;

        $this->verify_nonce( 'erp-deals' );

        $fields = [
            'id', 'type', 'title', 'deal_id', 'contact_id', 'company_id',
            'assigned_to_id', 'start', 'end', 'is_start_time_set', 'note', 'done_at'
        ];

        foreach ( $fields as $field ) {
            if ( isset( $_POST['activity'][ $field ] ) ) {
                $data[ $field ] = $_POST['activity'][ $field ];
            }
        }

        if ( empty( $data['is_start_time_set'] ) ) {
            $data['is_start_time_set'] = 0;
        } else {
            $data['is_start_time_set'] = filter_var( $data['is_start_time_set'], FILTER_VALIDATE_BOOLEAN );
        }

        // set assigned_to_id
        if ( !( current_user_can( 'administrator' ) || erp_crm_is_current_user_manager() ) && isset( $data['assigned_to_id'] ) ) {
            $data['assigned_to_id'] = get_current_user_id();
        } else if ( isset( $data['assigned_to_id'] ) ) {
            $data['assigned_to_id'] = $data['assigned_to_id'];
        }

        // save activity data
        $activity = deals()->save_activity( $data );

        // send error on failure
        if ( is_wp_error( $activity ) ) {
            $this->send_error( [ 'msg' => $activity->get_error_message() ] );
        }

        // get the stage id and foremost start time of the activity that belongs to the deal
        $deal = $activity->deal;
        $foremost_activitiy = $deal->activities()->select( 'start' )->whereNull( 'done_at' )->orderBy( 'start', 'asc' )->take( 1 )->first();
        $start = !empty( $foremost_activitiy ) ? $foremost_activitiy->start : null;

        $data = [
            'deal' => [
                'id'        => $deal->id,
                'title'     => $deal->title,
                'stageId'   => $deal->stage_id,
                'actStart'  => $start
            ],

            // send activity data with contact and company names
            'activity' => \WeDevs\ERP\CRM\Deals\Models\Activity::where( $wpdb->prefix . 'erp_crm_deals_activities.id', $activity->id )->withNames()->first()
        ];

        $this->send_success( $data );
    }

    /**
     * Delete an activity
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function delete_activity() {
        $this->verify_nonce( 'erp-deals' );

        if ( empty( $_POST['id'] ) ) {
            $this->send_error( [ 'msg' => __( 'Invalid operation', 'erp-pro' ) ] );
        }

        $is_deleted = deals()->delete_activity( $_POST['id'] );

        if ( is_wp_error( $is_deleted ) ) {
            $this->send_error( [ 'msg' => $is_deleted->get_error_message() ] );

        } else if ( !$is_deleted ) {
            $this->send_error( [ 'msg' => __( 'Unable to delete activity', 'erp-pro' ) ] );
        }

        $this->send_success();
    }

    /**
     * Update contact and company for a deal
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function update_deal_people() {
        $this->verify_nonce( 'erp-deals' );

        if ( empty( $_POST['deal_id'] ) || empty( $_POST['type'] ) || empty( $_POST['people_id'] ) ) {
            $this->send_error( [ 'msg' => __( 'Invalid type or id', 'erp-pro' ) ] );
        }

        $data = [
            'id' => $_POST['deal_id']
        ];

        if ( 'contact' === $_POST['type'] ) {
            $data['contact_id'] = $_POST['people_id'];
        } else {
            $data['company_id'] = $_POST['people_id'];
        }

        // save deal data
        $deal = deals()->save_deal( $data );

        // send error on failure
        if ( is_wp_error( $deal ) ) {
            $this->send_error( [ 'msg' => $deal->get_error_message() ] );
        }

        // send data on success

        $data = [
            'people' => Helpers::get_people_by_id( $_POST['people_id'], $_POST['type'] )
        ];

        $this->send_success( $data );
    }

    /**
     * Search people - contact or comapny types
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function search_people() {
        $this->verify_nonce( 'erp-deals' );

        if ( !empty( $_GET['contact'] ) ) {
            $data = [
                'contacts' => Helpers::search_people( $_GET['s'] )
            ];

        } else if ( !empty( $_GET['company'] ) ) {
            $data = [
                'companies' => Helpers::search_people( $_GET['s'], 'company' )
            ];

        } else {
            $data = [
                'contacts' => Helpers::search_people( $_GET['s'] ),
                'companies' => Helpers::search_people( $_GET['s'], 'company' )
            ];
        }

        $this->send_success( $data );
    }

    /**
     * Add deal agents
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function add_agents() {
        $this->verify_nonce( 'erp-deals' );

        if ( empty( $_POST['deal_id'] ) || empty( $_POST['agents'] ) ) {
            $this->send_error( [ 'msg' => __( 'Invalid operation', 'erp-pro' ) ] );
        }

        $is_success = deals()->add_agents( $_POST['deal_id'], $_POST['agents'] );

        if ( !$is_success ) {
            $this->send_error( [ 'msg' => __( 'Unable to add agents. Please try again', 'erp-pro' ) ] );
        }

        $this->send_success();
    }

    /**
     * Remove deal agents
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function remove_agents() {
        $this->verify_nonce( 'erp-deals' );

        if ( empty( $_POST['deal_id'] ) || empty( $_POST['agents'] ) ) {
            $this->send_error( [ 'msg' => __( 'Invalid operation', 'erp-pro' ) ] );
        }

        $is_success = deals()->remove_agents( $_POST['deal_id'], $_POST['agents'] );

        if ( !$is_success ) {
            $this->send_error( [ 'msg' => __( 'Unable to remove agents. Please try again', 'erp-pro' ) ] );
        }

        $this->send_success();
    }

    /**
     * Save deal note
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function save_deal_note() {
        $this->verify_nonce( 'erp-deals' );

        $note = deals()->save_deal_note( $_POST['note'] );

        if ( is_wp_error( $note ) ) {
            $this->send_error( [ 'msg' => $note->get_error_message() ] );
        }

        $this->send_success( [ 'note' => $note ] );
    }

    /**
     * Delete deal note
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function delete_note() {
        $this->verify_nonce( 'erp-deals' );

        if ( empty( $_POST['id'] ) ) {
            $this->send_error( [ 'msg' => __( 'Invalid operation', 'erp-pro' ) ] );
        }

        $is_deleted = deals()->delete_note( $_POST['id'] );

        if ( is_wp_error( $is_deleted ) ) {
            $this->send_error( [ 'msg' => $is_deleted->get_error_message() ] );

        } else if ( !$is_deleted ) {
            $this->send_error( [ 'msg' => __( 'Unable to delete note', 'erp-pro' ) ] );
        }

        $this->send_success();
    }

    /**
     * Add deal attachment
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function add_deal_attachment() {
        $this->verify_nonce( 'erp-deals' );

        if ( empty( $_POST['deal_id'] ) && empty( $_POST['attachment_id'] ) ) {
            $this->send_error( [ 'msg' => __( 'Invalid operation', 'erp-pro' ) ] );
        }

        $attachment = deals()->add_attachment( $_POST['deal_id'], $_POST['attachment_id'] );

        if ( empty( $attachment->id ) ) {
            $this->send_error( [ 'msg' => __( 'Unable to add attachment. Please try again', 'erp-pro' ) ] );
        }

        $this->send_success();
    }

    /**
     * Remove attachment from a deal
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function remove_deal_attachment() {
        $this->verify_nonce( 'erp-deals' );

        if ( empty( $_POST['deal_id'] ) && empty( $_POST['attachment_id'] ) ) {
            $this->send_error( [ 'msg' => __( 'Invalid operation', 'erp-pro' ) ] );
        }

        $is_success = deals()->remove_attachment( $_POST['deal_id'], $_POST['attachment_id'] );

        if ( !$is_success ) {
            $this->send_error( [ 'msg' => __( 'Unable to remove attachment. Please try again', 'erp-pro' ) ] );
        }

        $this->send_success();
    }

    /**
     * Save email template
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function save_email_template() {
        $this->verify_nonce( 'erp-deals' );

        if ( empty( $_POST['template'] ) ) {
            $this->send_error( [ 'msg' => __( 'Invalid operation', 'erp-pro' ) ] );
        }

        $template = erp_crm_insert_save_replies( $_POST['template'] );

        if ( is_wp_error( $template ) ) {
            $this->send_error( [ 'msg' => $template->get_error_message() ] );

        } else {
            $data = [
                'msg' => __( 'Template saved successfully', 'erp-pro' )
            ];
        }

        $this->send_success( $data );
    }

    /**
     * Send email
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function send_email() {
        $this->verify_nonce( 'erp-deals' );

        if ( empty( $_POST['deal_id'] ) ) {
            $this->send_error( [ 'msg' => __( 'Invalid deal', 'erp-pro' ) ] );
        }

        if ( empty( $_POST['email']['to'] ) || !is_array( $_POST['email']['to'] ) ) {
            $this->send_error( [ 'msg' => __( 'Invalid email', 'erp-pro' ) ] );
        }

        if ( empty( $_POST['email']['subject'] ) ) {
            $this->send_error( [ 'msg' => __( 'Email subject is required', 'erp-pro' ) ] );
        }

        if ( empty( $_POST['email']['content'] ) ) {
            $this->send_error( [ 'msg' => __( 'Email body cannot be empty', 'erp-pro' ) ] );
        }

        if ( !empty( $_POST['parent_id'] ) ) {
            $parent_id = $_POST['parent_id'];
        } else {
            $parent_id = 0;
        }

        $to = [];
        foreach ( $_POST['email']['to'] as $contact ) {
            if ( empty( $contact['email'] ) || !is_email( $contact['email'] ) ) {
                $this->send_error( [ 'msg' => __( 'Invalid email', 'erp-pro' ) ] );
            }

            $to[] = $contact['email'];
        }

        if ( empty( $_POST['attachments'] ) ) {
            $attachment_ids = [];
        } else {
            $attachment_ids = $_POST['attachments'];
        }

        $emails = deals()->send_email( $_POST['deal_id'], $parent_id, $to, $_POST['email']['subject'], $_POST['email']['content'], $attachment_ids );

        if ( empty( $emails ) ) {
            $this->send_error( [ 'msg' => __( 'Could not send email. Please try again.', 'erp-pro' ) ] );

        } else if ( is_wp_error( $emails ) ) {
            $this->send_error( [ 'msg' => $emails->get_error_message() ] );

        } else {
            $data = [
                'emails' => $emails,
                'msg'    => __( 'Your email has been sent', 'erp-pro' )
            ];
        }

        $this->send_success( $data );
    }

    /**
     * Save deal competitor
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function save_competitor() {
        $this->verify_nonce( 'erp-deals' );

        if ( empty( $_POST['competitor'] ) ) {
            $this->send_error( [ 'msg' => __( 'Invalid operation', 'erp-pro' ) ] );
        }

        $competitor = deals()->save_competitor( $_POST['competitor'] );

        if ( is_wp_error( $competitor ) ) {
            $this->send_error( [ 'msg' => $competitor->get_error_message() ] );
        }

        $data = [ 'competitor'  => $competitor ];

        $this->send_success( $data );
    }

    /**
     * Delete competitor
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function delete_competitor() {
        $this->verify_nonce( 'erp-deals' );

        if ( empty( $_POST['id'] ) ) {
            $this->send_error( [ 'msg' => __( 'Invalid operation', 'erp-pro' ) ] );
        }

        $is_deleted = deals()->delete_competitor( $_POST['id'] );

        if ( is_wp_error( $is_deleted ) ) {
            $this->send_error( [ 'msg' => $is_deleted->get_error_message() ] );

        } else if ( !$is_deleted ) {
            $this->send_error( [ 'msg' => __( 'Unable to delete competitor', 'erp-pro' ) ] );
        }

        $this->send_success();
    }

    /**
     * Get deal changelog
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function get_changelog() {
        $this->verify_nonce( 'erp-deals' );

        if ( empty( $_POST['deal_id'] ) ) {
            $this->send_error( [ 'msg' => __( 'Invalid operation', 'erp-pro' ) ] );
        }

        $args = [
            'data_id' => $_POST['deal_id']
        ];

        $log = audit_log()->get_changelog( $args );

        $this->send_success( [ 'log' => $log ] );
    }

    /**
     * Get deal settings page data
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function get_erp_deals_settings() {
        $this->verify_nonce( 'erp-deals' );

        if ( !current_user_can( 'manage_options' ) ) {
            $this->send_error( [ 'msg' => __( "You don't have permission for this operation", 'erp-pro' ) ] );
        }

        $all_pipelines = \WeDevs\ERP\CRM\Deals\Models\Pipeline::orderBy( 'id', 'asc' )->get();
        $pipelines = [];

        foreach ( $all_pipelines as $pipeline ) {
            $pipelines[] = [
                'id'        => $pipeline->id,
                'title'     => $pipeline->title,
                'stages'    => $pipeline->stages()->orderBy( 'order', 'asc' )->get()
            ];
        }

        $data = [
            'pipelines'         => $pipelines,
            'life_stages'       => erp_crm_get_life_stages_dropdown_raw(),
            'activity_types'    => \WeDevs\ERP\CRM\Deals\Models\ActivityType::orderBy( 'order', 'asc' )->withTrashed()->get(),
            'lost_reasons'      => Helpers::get_lost_reasons()
        ];

        $this->send_success( $data );
    }

    /**
     * Save pipeline data
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function save_pipeline() {
        $this->verify_nonce( 'erp-deals' );

        if ( !current_user_can( 'manage_options' ) ) {
            $this->send_error( [ 'msg' => __( "You don't have permission for this operation", 'erp-pro' ) ] );
        }

        if ( empty( $_POST['pipeline'] ) ) {
            $this->send_error( [ 'msg' => __( 'Invalid operation', 'erp-pro' ) ] );
        }

        $pipeline = deals()->save_pipeline( $_POST['pipeline'] );

        if ( is_wp_error( $pipeline ) ) {
            $this->send_error( [ 'msg' => $pipeline->get_error_message() ] );
        }

        $this->send_success( [ 'pipeline' => $pipeline ] );
    }

    /**
     * Get pipeline deals count
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function get_pipeline_deals_count() {
        $this->verify_nonce( 'erp-deals' );

        if ( !current_user_can( 'manage_options' ) ) {
            $this->send_error( [ 'msg' => __( "You don't have permission for this operation", 'erp-pro' ) ] );
        }

        if ( empty( $_GET['pipeline_id'] ) ) {
            $this->send_error( [ 'msg' => __( 'Invalid operation', 'erp-pro' ) ] );
        }

        $deal_count = \WeDevs\ERP\CRM\Deals\Models\Pipeline::find( $_GET['pipeline_id'] )->deals->count();

        $this->send_success( [ 'deal_count' => $deal_count ] );
    }

    /**
     * Delete a pipeline
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function delete_pipeline() {
        $this->verify_nonce( 'erp-deals' );

        if ( !current_user_can( 'manage_options' ) ) {
            $this->send_error( [ 'msg' => __( "You don't have permission for this operation", 'erp-pro' ) ] );
        }

        if ( empty( $_POST['pipeline_id'] ) ) {
            $this->send_error( [ 'msg' => __( 'Invalid operation', 'erp-pro' ) ] );
        }

        $transfer_to_stage_id = isset( $_POST['transfer_to_stage_id'] ) ? $_POST['transfer_to_stage_id'] : 0;

        $is_deleted = deals()->delete_pipeline( $_POST['pipeline_id'], $transfer_to_stage_id );

        if ( is_wp_error( $is_deleted ) ) {
            $this->send_error( [ 'msg' => $is_deleted->get_error_message() ] );
        }

        $this->send_success();
    }

    /**
     * Get stages in a pipeline
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function get_pipeline_stages() {
        $this->verify_nonce( 'erp-deals' );

        if ( !current_user_can( 'manage_options' ) ) {
            $this->send_error( [ 'msg' => __( "You don't have permission for this operation", 'erp-pro' ) ] );
        }

        if ( empty( $_POST['pipeline_id'] ) ) {
            $this->send_error( [ 'msg' => __( 'Invalid operation', 'erp-pro' ) ] );
        }

        $stages = \WeDevs\ERP\CRM\Deals\Models\PipelineStage::where( 'pipeline_id', $_POST['pipeline_id'] )->orderBy( 'order', 'asc' )->get();

        $this->send_success( [ 'stages' => $stages ] );
    }

    /**
     * Reorder pipeline stages
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function reorder_stages() {
        $this->verify_nonce( 'erp-deals' );

        if ( !current_user_can( 'manage_options' ) ) {
            $this->send_error( [ 'msg' => __( "You don't have permission for this operation", 'erp-pro' ) ] );
        }

        if ( empty( $_POST['stages'] ) ) {
            $this->send_error( [ 'msg' => __( 'Invalid operation', 'erp-pro' ) ] );
        }

        deals()->reorder_stages( $_POST['stages'] );

        $this->send_success();
    }

    /**
     * Save pipeline stage
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function save_stage() {
        $this->verify_nonce( 'erp-deals' );

        if ( !current_user_can( 'manage_options' ) ) {
            $this->send_error( [ 'msg' => __( "You don't have permission for this operation", 'erp-pro' ) ] );
        }

        if ( empty( $_POST['stage'] ) ) {
            $this->send_error( [ 'msg' => __( 'Invalid operation', 'erp-pro' ) ] );
        }

        $stage = deals()->save_stage( $_POST['stage'] );

        if ( is_wp_error( $stage ) ) {
            $this->send_error( [ 'msg' => $stage->get_error_message() ] );
        }

        $this->send_success( [ 'stage' => $stage ] );
    }

    /**
     * Get deal count in a stage
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function get_stage_deals_count() {
        $this->verify_nonce( 'erp-deals' );

        if ( !current_user_can( 'manage_options' ) ) {
            $this->send_error( [ 'msg' => __( "You don't have permission for this operation", 'erp-pro' ) ] );
        }

        if ( empty( $_GET['stage_id'] ) ) {
            $this->send_error( [ 'msg' => __( 'Invalid operation', 'erp-pro' ) ] );
        }

        $stage = \WeDevs\ERP\CRM\Deals\Models\Deal::where( 'stage_id', $_GET['stage_id'] )->count();

        $this->send_success( [ 'deal_count' => $stage ] );
    }

    /**
     * Delete pipeline stage
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function delete_stage() {
        $this->verify_nonce( 'erp-deals' );

        if ( !current_user_can( 'manage_options' ) ) {
            $this->send_error( [ 'msg' => __( "You don't have permission for this operation", 'erp-pro' ) ] );
        }

        if ( empty( $_POST['stage_id'] ) ) {
            $this->send_error( [ 'msg' => __( 'Invalid operation', 'erp-pro' ) ] );
        }

        $transfer_to_stage_id = isset( $_POST['transfer_to_stage_id'] ) ? $_POST['transfer_to_stage_id'] : 0;

        $is_deleted = deals()->delete_stage( $_POST['stage_id'], $transfer_to_stage_id );

        if ( is_wp_error( $is_deleted ) ) {
            $this->send_error( [ 'msg' => $is_deleted->get_error_message() ] );
        }

        $this->send_success();
    }

    /**
     * Save activity type
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function save_activity_type() {
        $this->verify_nonce( 'erp-deals' );

        if ( !current_user_can( 'manage_options' ) ) {
            $this->send_error( [ 'msg' => __( "You don't have permission for this operation", 'erp-pro' ) ] );
        }

        if ( empty( $_POST['activity_type'] ) ) {
            $this->send_error( [ 'msg' => __( 'Invalid operation', 'erp-pro' ) ] );
        }

        if ( !empty( $_POST['trash'] ) ) {
            $activity_type = deals()->trash_activity_type( $_POST['activity_type']['id'] );
        } else {
            $activity_type = deals()->save_activity_type( $_POST['activity_type'] );
        }


        if ( is_wp_error( $activity_type ) ) {
            $this->send_error( [ 'msg' => $activity_type->get_error_message() ] );
        }

        $this->send_success( [ 'activity_type' => $activity_type ] );
    }

    /**
     * Reorder activity types
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function reorder_activity_types() {
        $this->verify_nonce( 'erp-deals' );

        if ( !current_user_can( 'manage_options' ) ) {
            $this->send_error( [ 'msg' => __( "You don't have permission for this operation", 'erp-pro' ) ] );
        }

        if ( empty( $_POST['activities'] ) ) {
            $this->send_error( [ 'msg' => __( 'Invalid operation', 'erp-pro' ) ] );
        }

        deals()->reorder_activity_types( $_POST['activities'] );

        $this->send_success();
    }

    /**
     * Save lost reason
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function save_lost_reason() {
        $this->verify_nonce( 'erp-deals' );

        if ( !current_user_can( 'manage_options' ) ) {
            $this->send_error( [ 'msg' => __( "You don't have permission for this operation", 'erp-pro' ) ] );
        }

        if ( empty( $_POST['lost_reason'] ) ) {
            $this->send_error( [ 'msg' => __( 'Invalid operation', 'erp-pro' ) ] );
        }

        $lost_reason = deals()->save_lost_reason( $_POST['lost_reason'] );

        if ( is_wp_error( $lost_reason ) ) {
            $this->send_error( [ 'msg' => $lost_reason->get_error_message() ] );
        }

        $this->send_success( [ 'lost_reason' => $lost_reason ] );
    }

    /**
     * Delete a lost reason
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function delete_lost_reason() {
        $this->verify_nonce( 'erp-deals' );

        if ( !current_user_can( 'manage_options' ) ) {
            $this->send_error( [ 'msg' => __( "You don't have permission for this operation", 'erp-pro' ) ] );
        }

        if ( empty( $_POST['lost_reason_id'] ) ) {
            $this->send_error( [ 'msg' => __( 'Invalid operation', 'erp-pro' ) ] );
        }

        $is_deleted = deals()->delete_lost_reason( $_POST['lost_reason_id'] );

        if ( is_wp_error( $is_deleted ) ) {
            $this->send_error( [ 'msg' => $is_deleted->get_error_message() ] );
        }

        $this->send_success();
    }

    /**
     * Get people details
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function get_people() {
        $this->verify_nonce( 'erp-deals' );

        if ( empty( $_GET['id'] ) ) {
            $this->send_error( [ 'msg' => __( 'Invalid operation', 'erp-pro' ) ] );
        }

        if ( empty( $_GET['type'] ) ) {
            $this->send_error( [ 'msg' => __( 'Invalid people type', 'erp-pro' ) ] );
        }

        $type = ( 'company' === $_GET['type'] ) ? 'company' : 'contact';

        // contact
        $people = Helpers::get_people_by_id( $_GET['id'], $type );


        $this->send_success( [ 'people' => $people ] );
    }

    /**
     * Get deal primary contacts
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function get_deal_primary_contacts() {
        $this->verify_nonce( 'erp-deals' );

        if ( empty( $_GET['deal_id'] ) ) {
            $this->send_error( [ 'msg' => __( 'Invalid operation', 'erp-pro' ) ] );
        }

        $data = Helpers::get_deal_primary_contacts( $_GET['deal_id'] );

        $this->send_success( $data );
    }

    /**
     * Get deal overview data
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function get_overview_data() {
        $this->verify_nonce( 'erp-deals' );

        require_once WPERP_DEALS_INCLUDES . '/class-statistics.php';

        $section = !empty( $_GET['section'] ) ? $_GET['section'] : null;
        $filters = !empty( $_GET['filters'] ) ? $_GET['filters'] : [];

        switch ( $section ) {
            case 'deal_progress':
                $data = [
                    'deals_progress_by_stages' => statistics()->deals_progress_by_stages( $filters )
                ];
                break;

            case 'activity_progress':
                $data = [
                    'activity_progress' => statistics()->activity_progress( $filters )
                ];
                break;

            default:
                $data = statistics()->get_overview( $filters );
                break;
        }

        $this->send_success( $data );
    }
}

new Deal_Ajax();
