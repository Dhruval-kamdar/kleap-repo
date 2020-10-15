<?php
namespace WeDevs\ERP\CRM\Deals;

use WeDevs\ERP\CRM\Deals\Models\Deal as DealModel;
use WeDevs\ERP\CRM\Deals\Models\ActivityType as ActivityTypeModel;
use WeDevs\ERP\CRM\Deals\Models\Activity as ActivityModel;
use WeDevs\ERP\CRM\Deals\Models\Pipeline as PipelineModel;
use WeDevs\ERP\CRM\Deals\Models\StageHistory as StageHistoryModel;
use WeDevs\ERP\CRM\Deals\Models\PipelineStage as PipelineStageModel;
use WeDevs\ERP\CRM\Deals\Models\Participant as ParticipantModel;
use WeDevs\ERP\CRM\Deals\Models\Agent as AgentModel;
use WeDevs\ERP\CRM\Deals\Models\Email as EmailModel;
use WeDevs\ERP\CRM\Deals\Models\Note as NoteModel;
use WeDevs\ERP\CRM\Deals\Models\Attachment as AttachmentModel;
use WeDevs\ERP\CRM\Deals\Models\Competitor as CompetitorModel;
use WeDevs\ERP\CRM\Deals\Models\LostReason as LostReasonModel;
use \WeDevs\ORM\Eloquent\Facades\DB;

use WeDevs\ERP\CRM\Deals\Helpers;

/**
 * Deals object
 *
 * @since 1.0.0
 */
class Deals {

    /**
     * Current user id
     *
     * @var integer
     */
    private $current_user_id = 0;

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
     * Class constructor
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function __construct() {
        $this->current_user_id = get_current_user_id();
    }

    /**
     * Get ERP Deals
     *
     * @since 1.0.0
     *
     * @param array $args
     *
     * @return object Eloquent Collection object of Deal models
     */
    public function get_deals( $args ) {
        $defaults = [
            'limit'     => 20,
            'orderBy'   => 'id',
            'order'     => 'desc',
        ];

        $args = wp_parse_args( $args, $defaults );

        $deals = DealModel::orderBy( $args['orderBy'], $args['order'] );

        if ( -1 !== $args['limit'] && !empty( $args['limit'] ) ) {
            $deals->take( $args['limit'] );
        }

        if ( !empty( $args['from'] ) ) {
            $deals->where( 'created_at', '>=', $args['from'] );
        }

        if ( !empty( $args['to'] ) ) {
            $deals->where( 'created_at', '<=', $args['to'] );
        }

        $deals = $deals->get();

        $contacts = [];
        $companies = [];
        $crm_agents = Helpers::get_crm_agents();
        $agents = [];

        foreach ( $crm_agents as $agent ) {
            $agents[ $agent->ID ] = $agent->display_name;
        }

        $currency_symbol = erp_get_currency_symbol( erp_get_currency() );

        // include extra data that not found in deals table
        $deals->map( function ( $deal ) use ( $args, &$contacts, &$companies, $agents, $currency_symbol ){
            $deal->currency_symbol = $currency_symbol;

            if ( !empty( $args['with_names'] ) ) {

                if ( array_key_exists( $deal->owner_id, $agents ) ) {
                    $deal->owner = $agents[ $deal->owner_id ];
                }

                if ( !array_key_exists( $deal->contact_id, $contacts ) ) {
                    $contacts[ $deal->contact_id ] = Helpers::get_people_by_id( $deal->contact_id, 'contact' );
                }

                $deal->contact = $contacts[ $deal->contact_id ];

                if ( !array_key_exists( $deal->company_id, $companies ) ) {
                    $companies[ $deal->company_id ] = Helpers::get_people_by_id( $deal->company_id, 'company' );
                }

                $deal->company = $companies[ $deal->company_id ];

            }
        } );

        return $deals;
    }

    /**
     * Deals data by Pipelines
     *
     * @since 1.0.0
     *
     * @param id    $pipeline_id
     * @param array $args
     *
     * @return array
     */
    public function get_deals_by_pipeline( $pipeline_id, $args = [] ) {
        $pipeline_data = [];

        $pipelines = PipelineModel::find( $pipeline_id );

        if ( empty( $pipelines ) ) {
            return $pipeline_data;
        }

        // get stages belongs to pipeline_id
        $stages = $pipelines->stages()->orderBy( 'order', 'asc' )->get();
        $crm_agents = Helpers::get_crm_agents();

        // cache the owner avatars
        $owner_avatars = [];

        foreach ( $stages as $i => $stage ) {
            $prefix = DB::instance()->db->prefix;

            $deals = DB::table( 'erp_crm_deals as deal' )
                       ->select(
                            'deal.id', 'deal.title', 'deal.stage_id', 'deal.owner_id', 'deal.company_id',
                            'comp.company', 'deal.won_at', 'deal.lost_at', 'deal.deleted_at',
                            'deal.value', 'deal.currency', DB::raw( 'MIN(act.start) as start' ), 'act.is_start_time_set',
                            'deal.contact_id', DB::raw( "concat_ws( ' ', cont.first_name, cont.last_name ) contact" )
                        )
                       ->leftJoin( "{$prefix}erp_crm_deals_activities as act", function ( $join ) {
                            $join->on( 'deal.id', '=', 'act.deal_id' )->whereNull( 'act.done_at' );
                       } )
                       ->leftJoin( "{$prefix}erp_peoples as comp", 'deal.company_id', '=', 'comp.id' )
                       ->leftJoin( "{$prefix}erp_peoples as cont", 'deal.contact_id', '=', 'cont.id' )
                       ->where( 'deal.stage_id', $stage->id )
                       ->groupBy( 'deal.id' )
                       ->orderBy( DB::raw( 'isnull(start), start' ), 'asc' )
                       ->orderBy( 'deal.id', 'asc' );

            // filter won/lost/deleted deals
            if ( !empty( $args['filters']['status'] ) ) {
                switch ( $args['filters']['status'] ) {
                    case 'won':
                        $deals->whereNotNull( 'deal.won_at' );
                        $deals->whereNull( 'deal.deleted_at' );
                        break;

                    case 'lost':
                        $deals->whereNotNull( 'deal.lost_at' );
                        $deals->whereNull( 'deal.deleted_at' );
                        break;

                    case 'deleted':
                        $deals->whereNotNull( 'deal.deleted_at' );
                        break;

                    default:
                        $deals->whereNull( 'deal.won_at' )->whereNull( 'deal.lost_at' );
                        $deals->whereNull( 'deal.deleted_at' );
                        break;
                }

            } else {
                $deals->whereNull( 'deal.won_at' )->whereNull( 'deal.lost_at' );
                $deals->whereNull( 'deal.deleted_at' );
            }

            // filter owner
            if ( !empty( $args['filters']['owner'] ) ) {
                $deals->where( 'deal.owner_id', $args['filters']['owner'] );
            }

            // deal read permission
            if ( !(current_user_can( 'administrator' ) || erp_crm_is_current_user_manager() ) ) {
                // add left join statement
                $deals->leftJoin( "{$prefix}erp_crm_deals_agents as agent", 'deal.id', '=', 'agent.deal_id' );

                // add where clause statement
                $deals->where( function ( $query ) {
                    // and (deal.owner_id = 102 or agent.agent_id = 102)
                    $query->where( 'deal.owner_id',  $this->current_user_id )
                           ->orWhere( 'agent.agent_id', $this->current_user_id );
                } );
            }

            $deals = $deals->get();

            // ready the stage data
            $pipeline_data[ $i ] = [
                'id'    => $stage->id,
                'title' => $stage->title,
                'deals' => []
            ];

            if ( !empty( $deals ) ) {

                foreach ( $deals as $deal ) {

                    // deal owner
                    $owner = array_filter( $crm_agents, function ( $agent ) use ( $deal ) {
                        return absint( $agent->ID ) === absint( $deal->owner_id );
                    } );

                    $owner = array_pop( $owner );

                    // currency
                    $currency = erp_get_currency_symbol( $deal->currency );
                    $currency = html_entity_decode( $currency );

                    // avatar
                    if ( in_array( $deal->owner_id, $owner_avatars ) ) {
                        $owner_avatar = $owner_avatars[ $deal->owner_id ];
                    } else {
                        $owner_avatar = get_avatar_url( $deal->owner_id, [ 'size' => 24 ] );

                        $owner_avatars[ $deal->owner_id ] = $owner_avatar;
                    }

                    // format deal data
                    // NOTE: keys are already camelized
                    $pipeline_data[$i]['deals'][] = [
                        'id'              => absint( $deal->id ),
                        'title'           => $deal->title,
                        'stageId'         => $deal->stage_id,
                        'owner'           => [
                            'id'          => absint( $deal->owner_id ),
                            'name'        => $owner->display_name,
                            'img'         => $owner_avatar
                        ],
                        'value'           => number_format( $deal->value, 2, '.', '' ),
                        'currency'        => $currency,
                        'contactId'       => $deal->contact_id,
                        'contact'         => $deal->contact,
                        'companyId'       => $deal->company_id,
                        'company'         => $deal->company,
                        'actStart'        => $deal->start,
                        'isStartTimeSet'  => $deal->is_start_time_set,
                        'wonAt'           => $deal->won_at,
                        'lostAt'          => $deal->lost_at,
                        'deletedAt'       => $deal->deleted_at
                    ];

                }

            }
        }

        return $pipeline_data;
    }

    /**
     * Get a deal basic data
     *
     * @since 1.0.0
     *
     * @param int $deal_id
     *
     * @return object Eloquent Deal model
     */
    public function get_deal( $deal_id, $withTrashed = false ) {
        return DealModel::readable( $deal_id, $withTrashed )->first();
    }

    /**
     * Search deals
     *
     * Ignore won, lost and trashed deals
     *
     * @since 1.0.0
     *
     * @param string $s
     *
     * @return object Eloquent Collection object of Deal models
     */
    public function search_deals( $s ) {
        global $wpdb;
        $deal_tbl = $wpdb->prefix . 'erp_crm_deals';

        $deals = DealModel::readable()
                 ->where( $deal_tbl . '.title', 'like', "%$s%" )
                 ->whereNull( $deal_tbl . '.won_at' )
                 ->whereNull( $deal_tbl . '.lost_at' )
                 ->get();

        return $deals;
    }

    /**
     * Single deal data
     *
     * @since 1.0.0
     *
     * @param int $deal_id
     *
     * @return array|object Returns WP_Error object in case of non-existing deal
     */
    public function get_single_deal_data( $deal_id ) {
        $deal_id = absint( $deal_id );

        $deal = $this->get_deal( $deal_id, true );

        // in case of deleted, non-existing or user doesn't have read permission
        if ( empty( $deal ) ) {
            return new \WP_Error( 'erp_deals_get_single_deal_data', __( 'Deal does not exist', 'erp-pro' ) );
        }

        // contact
        $deal->contact = Helpers::get_people_by_id( $deal->contact_id, 'contact' );

        // company
        $deal->company = Helpers::get_people_by_id( $deal->company_id, 'company' );

        $currency_symbol = erp_get_currency_symbol( erp_get_currency() );
        $deal->currency_symbol = html_entity_decode( $currency_symbol );

        // list of CRM Agents w/ managers
        $crm_agents = [];
        foreach ( Helpers::get_crm_agents() as $i => $agent ) {
            $crm_agents[] = [
                'id'        => $agent->ID,
                'name'      => $agent->display_name,
                'avatar'    => get_avatar_url( $agent->ID, [ 'size' => 48 ] ),
                'link'      => add_query_arg( 'user_id', $agent->ID, self_admin_url( 'user-edit.php' ) )
            ];
        }

        $all_pipelines = PipelineModel::orderBy( 'id', 'asc' )->get();
        $pipelines = [];
        foreach ( $all_pipelines as $pipeline ) {
            $stages = $pipeline->stages()->select( [ 'id', 'title' ] )->orderBy( 'order', 'asc' )->get();

            $pipelines[] = [
                'id'        => $pipeline->id,
                'title'     => $pipeline->title,
                'stages'    => $stages
            ];

            // find pipeline in which currently the deal in
            $deal_stage = $stages->filter( function( $stage ) use ( $deal )  {
                return absint( $stage->id ) === absint( $deal->stage_id );
            } );

            if ( $deal_stage->first() ) {
                $deal->stage_title      = $deal_stage->first()->title;
                $deal->pipeline_id      = $pipeline->id;
                $deal->pipeline_title   = $pipeline->title;
            }
        }

        // stage history
        $deal->stage_histories = $deal->stage_histories()->select( 'stage_id', 'in', 'out' )->orderBy( 'id', 'asc' )->get();

        // deal participants/people other than primary contact and company
        $deal->participants = $this->get_deal_participants( $deal->id );

        // deal agents
        $deal_agents = AgentModel::select( 'agent_id' )->where( 'deal_id', $deal->id )->get()->toArray();
        $deal->agents = wp_list_pluck( $deal_agents, 'agent_id' );

        // deal attachments
        $deal->attachments = $this->get_deal_attachments( $deal->id );

        // notes
        $deal->notes = $this->get_deal_notes( $deal_id );

        // Agent can only see own activities
        $args = [
            'with_names' => true
        ];

        if ( !( current_user_can( 'administrator' ) || erp_crm_is_current_user_manager() ) ) {
            $args['assigned_to_id'] = $this->current_user_id;
        }

        // activities
        $deal->activities = $this->get_activities( $deal_id, [], $args );

        // competitors
        $deal->competitors = $deal->competitors()->orderBy( 'id', 'asc' )->get();

        // emails
        $deal->emails = $this->get_deal_emails( $deal->id );

        return [
            'deal'              => $deal->toArray(),
            'crm_agents'        => $crm_agents,
            'pipelines'         => $pipelines,
            'feeds'             => [
                [
                    'type'      => 'activity',
                    'tabTitle'  => __( 'Add activity', 'erp-pro' ),
                    'icon'      => 'dashicons dashicons-calendar-alt',
                ],
                [
                    'type'      => 'note',
                    'tabTitle'  => __( 'Take notes', 'erp-pro' ),
                    'icon'      => 'dashicons dashicons-welcome-write-blog',
                ],
                [
                    'type'      => 'email',
                    'tabTitle'  => __( 'Send Mail', 'erp-pro' ),
                    'icon'      => 'dashicons dashicons-email-alt',
                ],
                [
                    'type'      => 'attachment',
                    'tabTitle'  => __( 'Upload Files', 'erp-pro' ),
                    'icon'      => 'dashicons dashicons-paperclip',
                ],
            ],
            'current_user_id'   => $this->current_user_id
        ];
    }

    /**
     * Save a Deal
     *
     * Insert new deal or update existing when deal id provided.
     *
     * @since 1.0.0
     *
     * @param array $data deal data
     *
     * @return object Eloquent Deal model or WP_Error object
     */
    public function save_deal( $data ) {
        if ( empty( $data['id'] ) ) {
            $data['id'] = 0;
        }

        $deal = DealModel::firstOrNew( [ 'id' => $data['id'] ] );

        $current_data = [
            'title'                 => $deal->title,
            'stage_id'              => $deal->stage_id,
            'contact_id'            => $deal->contact_id,
            'company_id'            => $deal->company_id,
            'owner_id'              => $deal->owner_id,
            'value'                 => $deal->value,
            'currency'              => $deal->currency,
            'expected_close_date'   => $deal->expected_close_date,
            'won_at'                => $deal->won_at,
            'lost_at'               => $deal->lost_at,
            'lost_reason_id'        => $deal->lost_reason_id,
            'lost_reason'           => $deal->lost_reason,
            'lost_reason_comment'   => $deal->lost_reason_comment
        ];

        $values = wp_parse_args( $data, $current_data );

        // for some reason, without this condition, expected_close_date
        // will have 0000-00-00 00:00:00 instead of NULL for null value!!!
        if ( empty( $values['expected_close_date'] ) ) {
            $values['expected_close_date'] = null;
        }

        // validations
        if (
                ( empty( $values['contact_id'] ) || !Helpers::is_contact_exists( $values['contact_id'] ) )
            &&  ( empty( $values['company_id'] ) || !Helpers::is_company_exists( $values['company_id'] ) )
        ) {
            return new \WP_Error( 'erp_deals_save_deal_invalid_contact_id', __( 'Either contact or company name is required', 'erp-pro' ) );
        }

        if ( empty( $values['title'] ) ) {
            return new \WP_Error( 'erp_deals_save_deal_invalid_title', __( 'Deal title is required', 'erp-pro' ) );
        }

        if ( !PipelineStageModel::where( 'id', $values['stage_id'] )->exists() ) {
            return new \WP_Error( 'erp_deals_save_deal_invalid_stage', __( 'Invalid pipline stage', 'erp-pro' ) );
        }

        if ( !empty( $values['lost_reason_id'] ) && empty( Helpers::get_lost_reason( $values['lost_reason_id'] ) ) ) {
            return new \WP_Error( 'erp_deals_save_deal_invalid_lost_title_id', __( 'Invalid lost reason', 'erp-pro' ) );
        }

        // store deal data
        if ( $deal->exists ) {
            $is_new = false;
            $deal->update( $values );

        } else {
            $is_new = true;
            $values['created_by'] = $this->current_user_id;
            $deal->setRawAttributes( $values, true );
            $deal->save();
        }

        // throw error on failure
        if ( empty( $deal->id ) ) {
            return new \WP_Error( 'erp_deals_save_deal_error_saving', __( 'Could not save the deal. Please try again.', 'erp-pro' ) );
        }

        /**
         * Action Hook - after create/update a deal
         *
         * @since 1.0.0
         *
         * @param object  $deal         Eloquent Deal model with new data
         * @param array   $current_data Old deal data
         * @param boolean $is_new       Is new deal or updating an existing deal
         */
        do_action( 'erp_deals_save_deal', $deal, $current_data, $is_new );

        // update stage history
        if ( absint( $current_data['stage_id'] ) !== absint( $deal->stage_id ) ) {
            // in
            $pipeline = PipelineStageModel::get()->toArray();
            usort( $pipeline, function( $a, $b ) {
                return $a['order'] - $b['order'];
            });

            StageHistoryModel::where( 'deal_id', $deal->id )->delete();
            foreach( $pipeline as $pl ) {
                $in = new StageHistoryModel;
                $in->deal_id                  = $deal->id;
                $in->stage_id                 = $pl['id'];
                $in->in                       = current_time( 'mysql' );
                $in->out                      = null;
                $in->in_amount                = $deal->value;
                $in->expected_close_date      = $deal->expected_close_date;
                $in->modified_by              = $this->current_user_id;
                $in->save();

                if( $pl['id'] == $data['stage_id'] ) {
                    break;
                }
            }

            // out. this will not execute for new deals
            $out = null;
            if ( !empty( $current_data['stage_id'] ) ) {
                $out = StageHistoryModel::where( 'deal_id', $deal->id )
                    ->where( 'stage_id', $current_data['stage_id'] )
                    ->whereNull( 'out' )
                    ->update( [ 'out' => current_time( 'mysql' ) ] );
            }

            /**
             * Action Hook - after changing stage
             *
             * @since 1.0.0
             *
             * @param object  $deal Eloquent Deal model with new data
             * @param array   $in   Eloquent StageHistory model
             * @param array   $out  Eloquent StageHistory model
             */
            do_action( 'erp_deals_change_stage', $deal, $in, $out );
        }

        // Add participants. $data['add_participants'] must be an array
        if ( !empty( $data['add_participants'] ) && is_array( $data['add_participants'] ) ) {
            $participants = [];

            foreach ( $data['add_participants'] as $participant ) {
                if ( !empty( $participant['id'] ) && !empty( $participant['type'] ) ) {
                    $participants[] = new ParticipantModel( [
                        'people_id'     => $participant['id'],
                        'people_type'   => $participant['type'],
                        'added_by'      => $this->current_user_id
                    ] );
                }
            }

            if ( !empty( $participants ) ) {
                $participants = $deal->participants()->saveMany( $participants );

                /**
                 * Action Hook - after inserting deal participants
                 *
                 * @since 1.0.0
                 *
                 * @param object $deal         Eloquent Deal model
                 * @param array  $participants Array of Eloquent Participant models
                 */
                do_action( 'erp_deals_add_participants', $deal, $participants );
            }
        }

        // Remove participants. $data['remove_participants'] must be an array
        if ( !empty( $data['remove_participants'] ) && is_array( $data['remove_participants'] ) ) {
            $deleted = $deal->participants()->whereIn( 'people_id', $data['remove_participants'] )->delete();

            if ( $deleted ) {
                /**
                 * Action Hook - after deleting deal participants
                 *
                 * @since 1.0.0
                 *
                 * @param object $deal         Eloquent Deal model
                 * @param array  $participants Removed participant ids
                 */
                do_action( 'erp_deals_remove_participants', $deal, $data['remove_participants'] );
            }
        }

        return $deal;
    }

    /**
     * Trash, restore or permanently delete a deal
     *
     * Deleting a deal will also delete its activities
     *
     * @since 1.0.0
     *
     * @param int    $deal_id
     * @param string $action
     *
     * @return object Eloquent Deal model or WP_Error object
     */
    public function delete_deal( $deal_id, $action ) {
        $deal = $this->get_deal( $deal_id, true );

        if ( empty( $deal ) ) {
            return new \WP_Error( 'erp_deals_delete_deal', __( 'Invalid deal', 'erp-pro' ) );
        }

        switch ( $action ) {
            case 'trash':
                $deal->delete();
                $deal->activities()->delete();
                break;

            case 'restore':
                $deal->restore();
                $deal->activities()->restore();
                break;

            case 'delete':
                if ( !(
                    current_user_can( 'administrator' ) ||
                    erp_crm_is_current_user_manager() ||
                    absint( $this->current_user_id ) === absint( $deal->owner_id )
                ) ) {
                    return new \WP_Error( 'erp_deals_delete_deal', __( 'You do not have permission to delete a deal', 'erp-pro' ) );
                }

                $deal->activities()->forceDelete();
                $deal->stage_histories()->forceDelete();
                $deal->participants()->forceDelete();
                $deal->agents()->forceDelete();
                $deal->notes()->forceDelete();
                $deal->attachments()->forceDelete();
                $deal->competitors()->forceDelete();
                $deal->emails()->forceDelete();
                $deal->forceDelete();
                break;

            default:
                return new \WP_Error( 'erp_deals_delete_deal', __( 'Invalid delete action', 'erp-pro' ) );
                break;
        }

        /**
         * Action Hook - after performing an action to a deal
         *
         * @since 1.0.0
         *
         * @param object $deal   Eloquent Deal model
         * @param string $action trash, restore or delete
         */
        do_action( 'erp_deals_delete_deal', $deal, $action );

        return $deal;
    }

    /**
     * Get a single activity primary data
     *
     * @since 1.0.0
     *
     * @param int $activity_id
     *
     * @return object Eloquent Activity model
     */
    public function get_activity( $activity_id ) {
        return ActivityModel::where( 'id', $activity_id )->first();
    }

    /**
     * Get all activities under a deal
     *
     * @since 1.0.0
     *
     * @param int   $deal_id
     * @param array $fields  Column names/fields of erp_crm_deals_activities table
     * @param array $args    Query modifier options
     *
     * @return object Eloquent Collection object
     */
    public function get_activities( $deal_id, $fields = [], $args = [] ) {
        $query = ActivityModel::where( 'deal_id', $deal_id );

        if ( !empty( $fields ) && is_array( $fields ) ) {
            $query->addSelect( $fields );
        }

        if ( !empty( $args['with_names'] ) ) {
            $query->withNames();
        }

        if ( !empty( $args['only_incomplete'] ) ) {
            $query->whereNull( 'done_at' );
        }

        if ( !empty( $args['only_completed'] ) ) {
            $query->whereNotNull( 'done_at' );
        }

        if ( !empty( $args['assigned_to_id'] ) ) {
            $query->where( 'assigned_to_id', $args['assigned_to_id'] );
        }

        if ( !empty( $args['offset'] ) ) {
            $query->offset( $args['offset'] );
        }

        if ( !empty( $args['limit'] ) ) {
            $query->limit( $args['limit'] );
        }

        return $query->get();
    }

    /**
     * Get activity list
     *
     * @since 1.0.0
     *
     * @param array $args
     * @param boolean $count_total
     *
     * @return object|int Eloquent Collection object or activities count
     */
    public function get_activity_list( $args = [], $count_total = false ) {
        global $wpdb;
        $activity_tbl = $wpdb->prefix . 'erp_crm_deals_activities';

        $query = ActivityModel::inAllReadableDeals( $count_total );

        if ( !empty( $args['only_incomplete'] ) ) {
            $query->whereNull( 'done_at' );
        }

        if ( !empty( $args['only_completed'] ) ) {
            $query->whereNotNull( 'done_at' );
        }

        if ( !empty( $args['assigned_to_id'] ) ) {
            $query->where( 'assigned_to_id', $args['assigned_to_id'] );
        }

        if ( empty( $args['assigned_to_id'] ) && erp_crm_is_current_user_crm_agent() ) {
            $query->where( 'assigned_to_id', get_current_user_id() );
        }

        if ( !empty( $args['type'] ) ) {
            $query->where( 'type', $args['type'] );
        }

        if ( !empty( $args['start'] ) ) {
            $query->where( 'start', '>=', $args['start'] );
        }

        if ( !empty( $args['end'] ) ) {
            $query->where( 'end', '<', $args['end'] );
        }

        if ( !$count_total ) {
            $query->withNames();
            $query->orderBy( $activity_tbl . '.start', 'asc' );
            $query->orderBy( $activity_tbl . '.id', 'asc' );

            if ( !empty( $args['offset'] ) ) {
                $query->offset( $args['offset'] );
            }

            if ( !empty( $args['limit'] ) ) {
                $query->limit( $args['limit'] );
            }

            if ( !empty( $args['exclude'] ) ) {
                $query->whereNotIn( $activity_tbl . '.id', $args['exclude'] );
            }
        }

        return $count_total ? $query->count() : $query->get();
    }

    /**
     * Save an activity
     *
     * @since 1.0.0
     *
     * @param array $data
     *
     * @return object Eloquent Activity model or WP_Error object
     */
    public function save_activity( $data ) {
        if ( empty( $data['id'] ) ) {
            $data['id'] = 0;
        }

        $activity = ActivityModel::firstOrNew( [ 'id' => $data['id'] ] );

        $current_data = [
            'type'              => $activity->type,
            'title'             => $activity->title,
            'deal_id'           => $activity->deal_id,
            'contact_id'        => $activity->contact_id,
            'company_id'        => $activity->company_id,
            'assigned_to_id'    => $activity->assigned_to_id,
            'start'             => $activity->start,
            'end'               => $activity->end,
            'is_start_time_set' => $activity->is_start_time_set,
            'note'              => $activity->note,
            'done_at'           => $activity->done_at
        ];

        $values = wp_parse_args( $data, $current_data );

        // validations
        if ( empty( $values['type'] ) ) {
            return new \WP_Error( 'erp_deals_save_activity', __( 'Invalid activity type', 'erp-pro' ) );
        }

        if ( empty( $values['title'] ) ) {
            return new \WP_Error( 'erp_deals_save_activity', __( 'Invalid activity title', 'erp-pro' ) );
        }

        if ( empty( $values['deal_id'] ) ) {
            return new \WP_Error( 'erp_deals_save_activity', __( 'Invalid activity deal id', 'erp-pro' ) );
        }

        if ( empty( $values['assigned_to_id'] ) ) {
            return new \WP_Error( 'erp_deals_save_activity', __( 'Invalid CRM agent', 'erp-pro' ) );
        }

        if ( empty( $values['start'] ) ) {
            return new \WP_Error( 'erp_deals_save_activity', __( 'Invalid start date', 'erp-pro' ) );
        }

        if ( $activity->id && !$this->is_user_can_edit_activity( $this->current_user_id, $activity->id ) ) {
            return new \WP_Error( 'erp_deals_save_activity', __( 'You do not have permission to edit this activity', 'erp-pro' ) );
        }

        // agent cannot assign other agent to an activity
        if (
            !( current_user_can( 'administrator' ) || erp_crm_is_current_user_manager() || erp_crm_is_current_user_crm_agent() ) &&
             absint( $values['assigned_to_id'] ) === $this->current_user_id
        ) {
            return new \WP_Error( 'erp_deals_save_activity', __( 'You do not have permission to assign other agent to an activity', 'erp-pro' ) );
        }

        // track activity done at and done by data
        if ( !empty( $data['done_at'] ) && empty( $activity->done_at ) ) {
            $values['done_at'] = current_time( 'mysql' );
            $values['done_by'] = $this->current_user_id;

        } else if ( isset( $data['done_at'] ) && empty( $data['done_at'] ) ) {
            $values['done_at'] = null;
            $values['done_by'] = null;
        }

        // store
        if ( $activity->exists ) {
            $is_new = false;
            $activity->update( $values );

        } else {
            $is_new = true;
            $values['created_by'] = $this->current_user_id;
            $activity->setRawAttributes( $values, true );
            $activity->save();
        }

        // throw error on failure
        if ( empty( $activity->id ) ) {
            return new \WP_Error( 'erp_deals_save_activity_error_saving', __( 'Could not save the activity. Please try again.', 'erp-pro' ) );
        }

        /**
         * Action Hook - after create/update an activity
         *
         * @since 1.0.0
         *
         * @param object  $activity     Eloquent Activity model containing new/updated activity data
         * @param array   $current_data Old activity data
         * @param boolean $is_new       Is new activity or updating an existing activity
         */
        do_action( 'erp_deals_save_activity', $activity, $current_data, $is_new );

        return $activity;
    }

    /**
     * Delete an activity
     *
     * @since 1.0.0
     *
     * @param int $activity_id
     *
     * @return boolean|object Returns WP_Error object on error, true on success and false by default
     */
    public function delete_activity( $activity_id ) {
        $activity = $this->get_activity( $activity_id );

        if ( empty( $activity ) ) {
            return new \WP_Error( 'erp_deals_delete_activity', __( 'Invalid activity', 'erp-pro' ) );
        }

        if ( !$this->is_user_can_delete_activity( $this->current_user_id, $activity ) ) {
            return new \WP_Error( 'erp_deals_delete_activity', __( 'You do not have permission to delete this activity', 'erp-pro' ) );
        }

        // The forceDelete method in Query Builder returns 0 or 1. Eloquent models don't
        $deleted = ActivityModel::where( 'id', $activity_id )->forceDelete();

        if ( $deleted ) {
            /**
             * Action Hook - after delete an activity
             *
             * @since 1.0.0
             *
             * @param object $activity Eloquent Activity model
             */
            do_action( 'erp_deals_delete_activity', $activity );

            return true;
        }

        return false;
    }

    /**
     * Check edit permission for an activity
     *
     * Site Admin  -> can edit any activity
     * CRM Manager -> can edit any activity
     * CRM Agent   -> can not edit others activities | can edit assigned by other
     * Public      -> cannot edit
     *
     * @since 1.0.0
     *
     * @param int        $user_id  WP User id
     * @param int|object $activity Activity id or Eloquent Activity model
     *
     * @return boolean
     */
    public function is_user_can_edit_activity( $user_id, $activity ) {
        if ( !is_object( $activity ) ) {
            $activity = $this->get_activity( $activity );
        }

        // Site Admin and CRM Manager can edit
        if ( current_user_can( 'administrator' ) || erp_crm_is_current_user_manager() ) {
            return true;

        // agents can edit own and assigned by other
        } else if ( erp_crm_is_current_user_crm_agent() && ( $user_id === absint( $activity->assigned_to_id ) ) ) {
            return true;
        }

        return false;
    }

    /**
     * Check delete permission for an activity
     *
     * Site Admin  -> can delete any activity
     * CRM Manager -> can delete any activity
     * CRM Agent   -> can delete own activity | can not delete created by other
     * Public      -> cannot delete
     *
     * @since 1.0.0
     *
     * @param int        $user_id  WP User id
     * @param int|object $activity Activity id or Eloquent Activity model
     *
     * @return boolean
     */
    public function is_user_can_delete_activity( $user_id, $activity ) {
        if ( !is_object( $activity ) ) {
            $activity = $this->get_activity( $activity );
        }

        // Site Admin and CRM Manager can delete
        if ( current_user_can( 'administrator' ) || erp_crm_is_current_user_manager() ) {
            return true;

        // agents can only delete activity created by him/herself
        } else if ( erp_crm_is_current_user_crm_agent() && ( $user_id === absint( $activity->created_by ) ) ) {
            return true;
        }

        return false;
    }

    /**
     * Deal participants/people other than primary contact and company
     *
     * @since 1.0.0
     *
     * @param int $deal_id
     *
     * @return array
     */
    public function get_deal_participants( $deal_id ) {
        $participants = [];

        $people = ParticipantModel::select( 'people_id', 'people_type' )->where( 'deal_id', $deal_id )->get();

        foreach ( $people as $i => $participant ) {
            if ( 'contact' === $participant->people_type ) {
                $participants[$i] = Helpers::get_people_by_id( $participant->people_id, 'contact' );
                $participants[$i]['people_type'] = 'contact';
            } else {
                $participants[$i] = Helpers::get_people_by_id( $participant->people_id, 'company' );
                $participants[$i]['people_type'] = 'company';
            }
        }

        return $participants;
    }

    /**
     * Add agents to a deal
     *
     * @since 1.0.0
     *
     * @param int   $deal_id
     * @param array $agent_ids
     *
     * @return boolean
     */
    public function add_agents( $deal_id, $agent_ids ) {
        // ids must be in array
        if ( !is_array( $agent_ids ) ) {
            return new \WP_Error( 'error_removing_agents', __( 'Invalid agent id format', 'erp-pro' ) );
        }

        $deal = $this->get_deal( $deal_id );


        if ( $deal ) {
            $agents = [];

            foreach ( $agent_ids as $id ) {
                $agents[] = new AgentModel( [
                    'deal_id'   => $deal->id,
                    'agent_id'  => $id,
                    'added_by'  => $this->current_user_id
                ] );
            }

            if ( !empty( $agents ) ) {
                $agents = $deal->agents()->saveMany( $agents );

                /**
                 * Action Hook - after inserting deal agents
                 *
                 * @since 1.0.0
                 *
                 * @param object $deal   Eloquent Deal model
                 * @param array  $agents Array of Eloquent Agent models
                 */
                do_action( 'erp_deals_add_agents', $deal, $agents );

                return true;
            }
        }

        return false;
    }

    /**
     * Remove agents from a deal
     *
     * @since 1.0.0
     *
     * @param int   $deal_id
     * @param array $agent_ids
     *
     * @return boolean|object Returns WP_Error object on error, true on success and false by default
     */
    public function remove_agents( $deal_id, $agent_ids ) {
        // ids must be in array
        if ( !is_array( $agent_ids ) ) {
            return new \WP_Error( 'error_removing_agents', __( 'Invalid agent id format', 'erp-pro' ) );
        }

        $deal = $this->get_deal( $deal_id );

        if ( $deal ) {
            $deleted = $deal->agents()->whereIn( 'agent_id', $agent_ids )->delete();

            if ( $deleted ) {
                /**
                 * Action Hook - after deleting deal agents
                 *
                 * @since 1.0.0
                 *
                 * @param object $deal   Eloquent Deal model
                 * @param array  $agents Removed agent ids
                 */
                do_action( 'erp_deals_remove_agents', $deal, $agent_ids );

                return true;
            }
        }

        return false;
    }

    /**
     * Get deal notes
     *
     * @since 1.0.0
     *
     * @param int   $deal_id
     * @param array $args
     *
     * @return object Eloquent Note Collection object
     */
    public function get_deal_notes( $deal_id, $args = [] ) {
        $query = NoteModel::where( 'deal_id', $deal_id );

        if ( !empty( $args['created_by'] ) ) {
            $query = $query->where( 'created_by', $args['created_by'] );
        }

        return $query->get();
    }

    /**
     * Get a single note
     *
     * @since 1.0.0
     *
     * @param int $note_id
     *
     * @return object Eloquent Note model
     */
    public function get_note( $note_id ) {
        return NoteModel::where( 'id', $note_id )->first();
    }

    /**
     * Save deal note
     *
     * @since 1.0.0
     *
     * @param array Note data
     *
     * @return object Retuns eloquent Note model on success and WP_Error on failure
     */
    public function save_deal_note( $data ) {
        if ( !isset( $data['id'] ) ) {
            $data['id'] = 0;
        }

        $note = NoteModel::firstOrNew( [ 'id' => $data['id'] ] );

        $current_data = [
            'deal_id'       => $note->deal_id,
            'note'          => $note->note,
            'is_sticky'     => $note->is_sticky,
        ];

        $values = wp_parse_args( $data, $current_data );

        // validations
        if ( empty( $data['id'] ) && empty( $data['deal_id'] ) ) {
            return new \WP_Error( 'erp_deals_save_note', __( 'Invalid deal id', 'erp-pro' ) );
        }

        $deal = $this->get_deal( $values['deal_id'] );
        if ( empty( $deal ) ) {
            return new \WP_Error( 'erp_deals_save_note', __( 'Invalid deal id', 'erp-pro' ) );
        }

        if ( empty( $data['id'] ) && empty( $values['note'] ) ) {
            return new \WP_Error( 'erp_deals_save_note', __( 'Note content should not be empty', 'erp-pro' ) );
        }

        // sticky property
        if ( filter_var( $data['is_sticky'], FILTER_VALIDATE_BOOLEAN ) && empty( $note->is_sticky ) ) {
            $values['is_sticky'] = 1;

        } else if ( isset( $data['is_sticky'] ) && !filter_var( $data['is_sticky'], FILTER_VALIDATE_BOOLEAN ) ) {
            $values['is_sticky'] = 0;
        }

        // store
        if ( $note->exists ) {
            $is_new = false;
            $note->update( $values );

        } else {
            $is_new = true;
            $values['created_by'] = $this->current_user_id;
            $note->setRawAttributes( $values, true );
            $note->save();
        }

        // throw error on failure
        if ( empty( $note->id ) ) {
            return new \WP_Error( 'erp_deals_save_deal_note', __( 'Could not save deal note. Please try again.', 'erp-pro' ) );
        }

        /**
         * Action Hook - after create/update a note
         *
         * @since 1.0.0
         *
         * @param object  $deal         Eloquent Deal model
         * @param object  $note         Eloquent Note model containing new/updated note data
         * @param array   $current_data Old note data
         * @param boolean $is_new       Is new note or updating an existing one
         */
        do_action( 'erp_deals_save_note', $deal, $note, $current_data, $is_new );

        return $note;
    }

    /**
     * Delete deal note
     *
     * @since 1.0.0
     *
     * @param int $note_id
     *
     * @return boolean
     */
    public function delete_note( $note_id ) {
        $note = $this->get_note( $note_id );

        if ( empty( $note ) ) {
            return new \WP_Error( 'erp_deals_delete_note', __( 'Invalid note', 'erp-pro' ) );
        }

        if ( !$this->is_user_can_delete_note( $this->current_user_id, $note ) ) {
            return new \WP_Error( 'erp_deals_delete_note', __( 'You do not have permission to delete this note', 'erp-pro' ) );
        }

        $deleted = NoteModel::where( 'id', $note_id )->delete();

        if ( $deleted ) {
            /**
             * Action Hook - after delete an note
             *
             * @since 1.0.0
             *
             * @param object $note Eloquent Note model
             */
            do_action( 'erp_deals_delete_note', $note );

            return true;
        }

        return false;
    }

    /**
     * Check user permission to delete a note
     *
     * @since 1.0.0
     *
     * @param int        $user_id
     * @param int|object $note    note id or Eloquent Note model
     *
     * @return boolean
     */
    public function is_user_can_delete_note( $user_id, $note ) {
        if ( !is_object( $note ) ) {
            $note = $this->get_note( $note );
        }

        // Site Admin and CRM Manager can delete
        if ( current_user_can( 'administrator' ) || erp_crm_is_current_user_manager() ) {
            return true;

        // agents can only delete note created by him/herself
        } else if ( erp_crm_is_current_user_crm_agent() && ( $user_id === absint( $note->created_by ) ) ) {
            return true;
        }

        return false;
    }

    /**
     * Get all attachments belongs to a deal
     *
     * @since 1.0.0
     *
     * @param int $deal_id
     *
     * @return array
     */
    public function get_deal_attachments( $deal_id ) {
        $attachments = [];
        $deal_attachments = AttachmentModel::where( 'deal_id', $deal_id )->get();

        $agents = collect( Helpers::get_crm_agents() );
        $agents = $agents->keyBy( 'ID' )->toArray();

        foreach ( $deal_attachments as $i => $attachment ) {
            if ( array_key_exists( $attachment->added_by , $agents ) ) {
                $agent = $agents[ $attachment->added_by ];

                $meta_data = wp_prepare_attachment_for_js( $attachment->attachment_id );

                $attachments[ $i ] = [
                    'id'         => $attachment->attachment_id,
                    'filename'   => $meta_data['filename'],
                    'url'        => $meta_data['url'],
                    'type'       => $meta_data['type'],
                    'filesize'   => $meta_data['filesizeHumanReadable'],
                    'created_at' => $attachment->created_at->format( 'Y-m-d H:i:s' ),
                    'added_by'   => [
                        'id'        => $agent->ID,
                        'name'      => $agent->display_name,
                        'avatar'    => get_avatar_url( $agent->ID, [ 'size' => 48 ] ),
                        'link'      => add_query_arg( 'user_id', $agent->ID, self_admin_url( 'user-edit.php' ) )
                    ]
                ];
            }
        }

        return $attachments;
    }

    /**
     * Add deal attachment
     *
     * @since 1.0.0
     *
     * @param int    $deal_id
     * @param string $attachment_id
     *
     * @return object|boolean Retuns eloquent Attachment model on success and false on failure
     */
    public function add_attachment( $deal_id, $attachment_id ) {
        $deal = $this->get_deal( $deal_id );

        if ( $deal ) {
            $attachment = new AttachmentModel( [
                'attachment_id' => $attachment_id,
                'added_by' => $this->current_user_id
            ] );

            $attachment = $deal->attachments()->save( $attachment );

            if ( !empty( $attachment->id ) ) {
                /**
                 * Action Hook - after saving a deal attachment
                 *
                 * @since 1.0.0
                 *
                 * @param object $deal       Eloquent Deal model
                 * @param object $attachment Eloquent Attachment model
                 */
                do_action( 'erp_deals_add_attachment', $deal, $attachment );

                return $attachment;
            }
        }

        return false;
    }

    /**
     * Remove attachments from a deal
     *
     * This doesn't delete an attachment from upload folder.
     * It only detaches the attachments from a deal.
     *
     * @since 1.0.0
     *
     * @param int       $deal_id
     * @param int|array $attachment_ids
     *
     * @return boolean
     */
    public function remove_attachment( $deal_id, $attachment_ids ) {
        if ( !is_array( $attachment_ids ) ) {
            $attachment_ids = [ absint( $attachment_ids ) ];
        }

        $deal = $this->get_deal( $deal_id );

        if ( $deal ) {
            $deleted = $deal->attachments()->whereIn( 'attachment_id', $attachment_ids )->delete();

            if ( $deleted ) {
                /**
                 * Action Hook - after deleting deal attachments
                 *
                 * @since 1.0.0
                 *
                 * @param object $deal   Eloquent Deal model
                 * @param array  $agents Removed attachment ids
                 */
                do_action( 'erp_deals_remove_attachments', $deal, $attachment_ids );

                return true;
            }
        }

        return false;
    }

    /**
     * Send email
     *
     * @since 1.0.0
     *
     * @param int           $deal_id
     * @param int           $parent_id
     * @param string|array  $to
     * @param string        $subject
     * @param string        $message
     * @param array         $attachment_ids
     *
     * @return boolean|object Returns true on success WP_Error on error
     */
    public function send_email( $deal_id, $parent_id = 0, $to, $subject, $message, $attachment_ids = [] ) {
        $deal = $this->get_deal( $deal_id );

        if ( empty( $deal ) ) {
            return new \WP_Error( 'error_sending_mail', __( 'Invalid deal', 'erp-pro' ) );
        }

        if ( empty( $to ) ) {
            return new \WP_Error( 'error_sending_mail', __( 'No email address found', 'erp-pro' ) );

        } else if ( !is_array( $to ) ) {
            $to = [ $to ];
        }

        $attachments = [];

        if ( !empty( $attachment_ids ) && is_array( $attachment_ids ) ) {
            $upload_dirs = wp_upload_dir();

            foreach ( $attachment_ids as $attachment_id ) {
                $file = get_post_meta( $attachment_id, '_wp_attached_file', true );

                if ( !empty( $file ) ) {
                    $attachments[] = $upload_dirs['basedir'] . '/' . $file;
                }
            }
        }

        $sent_email_ids = [];
        $failed_addresses = [];

        foreach ( $to as $email_address ) {
            $people = erp_get_people_by( 'email', $email_address );

            $email_body = deal_shortcodes()->render_email( $people, $message );

            $hash = md5( uniqid( time() . $email_address ) );
            $message_id = $hash . '.' . $people->id . '.' . $this->current_user_id . '.r1@' . $_SERVER['HTTP_HOST'];

            $custom_headers = [
                "Message-ID" => "<{$message_id}>",
                "In-Reply-To" => "<{$message_id}>",
                "References" => "<{$message_id}>",
            ];

            $is_sent = erp_mail( $email_address, $subject, $email_body, '', $attachments, $custom_headers );

            if ( $is_sent ) {

                // this data will show in people(contact/company) single page
                $people_activity_data = [
                    'user_id'       => $people->id,
                    'created_by'    => $this->current_user_id,
                    'message'       => $email_body,
                    'type'          => 'email',
                    'email_subject' => $subject,
                ];

                $customer_feed_data = erp_crm_save_customer_feed_data( $people_activity_data );

                // save this customer activity reference in deal emails table
                $email = new EmailModel;
                $email->deal_id     = $deal->id;
                $email->cust_act_id = $customer_feed_data['id'];
                $email->hash        = $hash;

                if ( !empty( $parent_id ) ) {
                    $email->parent_id = $parent_id;
                }

                $email->save();

                $sent_email_ids[] = $email->id;

                /**
                 * Action Hook - after send email
                 *
                 * @since 1.0.0
                 *
                 * @param object $people
                 * @param string $subject
                 * @param string $message
                 * @param array  $attachments
                 */
                do_action( 'erp_deals_send_email', $people, $subject, $message, $attachments );

            } else {
                $failed_addresses[] = $email_address;
            }
        }

        if ( !empty( $failed_addresses ) ) {
            if ( count( $failed_addresses ) > 1 ) {
                $msg = sprintf( __( 'Could not send email to these addresses %s', 'erp-pro' ), implode( ', ' , $failed_addresses ) );
            } else {
                $msg = sprintf( __( 'Could not send email to %s', 'erp-pro' ), $failed_addresses[0] );
            }

            return new \WP_Error( 'error_sending_mail', $msg );
        }

        // let's return the newly sent emails with details
        $prefix = DB::instance()->db->prefix;
        $emails = DB::table( 'erp_crm_deals_emails as e' )
            ->select(
                'e.id', 'e.deal_id', 'e.cust_act_id', 'e.hash', 'e.parent_id',
                'ca.user_id', 'pep.email', 'ca.message', 'ca.email_subject', 'ca.created_by', 'ca.created_at'
            )
            ->leftJoin( "{$prefix}erp_crm_customer_activities as ca", 'e.cust_act_id', '=', 'ca.id' )
            ->leftJoin( "{$prefix}erp_peoples as pep", 'ca.user_id', '=', 'pep.id' )
            ->whereIn( 'e.id', $sent_email_ids )
            ->get();

        return $emails;
    }

    /**
     * Save deal competitor
     *
     * @since 1.0.0
     *
     * @param array    $data
     *
     * @return object Eloquent Competitor model on success and WP_Error on failure
     */
    public function save_competitor( $data ) {
        // validations
        if ( empty( $data['deal_id'] ) ) {
            return new \WP_Error( 'error_save_competitor', __( 'Invalid deal', 'erp-pro' ) );
        }

        if ( empty( $data['competitor_name'] ) ) {
            return new \WP_Error( 'error_save_competitor', __( 'Competitor name is required', 'erp-pro' ) );
        }

        if ( !empty( $data['website'] ) && !filter_var( $data['website'], FILTER_VALIDATE_URL ) ) {
            return new \WP_Error( 'error_save_competitor', __( 'Invalid URL', 'erp-pro' ) );
        }

        // check if the related deal exists or not
        $deal = $this->get_deal( $data['deal_id'] );

        if ( empty( $deal->id ) ) {
            return new \WP_Error( 'error_save_competitor', __( 'Invalid deal', 'erp-pro' ) );
        }

        if ( !isset( $data['id'] ) ) {
            $data['id'] = 0;
        }

        $competitor = $deal->competitors()->firstOrNew( [ 'id' => $data['id'] ] );

        $current_data = [
            'deal_id'           => $competitor->deal_id,
            'competitor_name'   => $competitor->competitor_name,
            'website'           => $competitor->website,
            'strengths'         => $competitor->strengths,
            'weaknesses'        => $competitor->weaknesses,
        ];

        $values = wp_parse_args( $data, $current_data );

        // store data
        if ( $competitor->exists ) {
            $is_new = false;
            $competitor->update( $values );

        } else {
            $is_new = true;
            $values['created_by'] = $this->current_user_id;
            $competitor->setRawAttributes( $values, true );
            $competitor->save();
        }

        /**
         * Action Hook - after create/update deal competitor
         *
         * @since 1.0.0
         *
         * @param object  $competitor   Eloquent Activity model containing new/updated competitor data
         * @param array   $current_data Old competitor data
         * @param boolean $is_new       Is new competitor or updating an existing competitor
         */
        do_action( 'erp_deals_save_competitor', $competitor, $current_data, $is_new );

        return $competitor;
    }

    /**
     * Delete an activity
     *
     * @since 1.0.0
     *
     * @param int $competitor_id
     *
     * @return boolean|object Returns WP_Error object on error, true on success and false by default
     */
    public function delete_competitor( $competitor_id ) {
        $competitor = CompetitorModel::where( 'id', $competitor_id )->get();

        if ( empty( $competitor ) ) {
            return new \WP_Error( 'erp_deals_delete_competitor', __( 'Invalid competitor', 'erp-pro' ) );
        }

        if ( !$this->is_user_can_delete_competitor( $this->current_user_id, $competitor ) ) {
            return new \WP_Error( 'erp_deals_delete_competitor', __( 'You do not have permission to delete this competitor', 'erp-pro' ) );
        }

        $deleted = CompetitorModel::where( 'id', $competitor_id )->delete();

        if ( $deleted ) {
            /**
             * Action Hook - after delete an competitor
             *
             * @since 1.0.0
             *
             * @param object $competitor Eloquent Activity model
             */
            do_action( 'erp_deals_delete_competitor', $competitor );

            return true;
        }

        return false;
    }

    /**
     * Check delete permission for a competitor
     *
     * Site Admin  -> can delete any competitor
     * CRM Manager -> can delete any competitor
     * CRM Agent   -> can delete own competitor | can not delete created by other
     * Public      -> cannot delete
     *
     * @since 1.0.0
     *
     * @param int        $user_id  WP User id
     * @param int|object $competitor Activity id or Eloquent Activity model
     *
     * @return boolean
     */
    public function is_user_can_delete_competitor( $user_id, $competitor ) {
        if ( !is_object( $competitor ) ) {
            $competitor = $this->get_competitor( $competitor );
        }

        // Site Admin and CRM Manager can delete
        if ( current_user_can( 'administrator' ) || erp_crm_is_current_user_manager() ) {
            return true;

        // agents can only delete competitor created by him/herself
        } else if ( erp_crm_is_current_user_crm_agent() && ( $user_id === absint( $competitor->created_by ) ) ) {
            return true;
        }

        return false;
    }

    /**
     * Save Pipeline
     *
     * @since 1.0.0
     *
     * @param array $data
     *
     * @return object Eloquent Pipeline model or WP_Error object
     */
    public function save_pipeline( $data ) {
        if ( !isset( $data['id'] ) ) {
            $data['id'] = 0;
        }

        // creating pipeline needs at least one stage
        if ( empty( $data['id'] ) && empty( $data['stage'] ) ) {
            return new \WP_Error( 'erp_deals_save_pipeline', __( 'Creating a pipeline requires at least one stage', 'erp-pro' ) );

        } else if ( !empty( $data['stage'] ) ) {
            $stage = $data['stage'];
            unset( $data['stage'] );
        }

        $pipeline = PipelineModel::firstOrNew( [ 'id' => $data['id'] ] );

        $current_data = [
            'title' => $pipeline->title,
        ];

        $values = wp_parse_args( $data, $current_data );

        // validations
        if ( empty( $values['title'] ) ) {
            return new \WP_Error( 'erp_deals_save_pipeline', __( 'Title cannot be empty', 'erp-pro' ) );
        }

        // store
        if ( $pipeline->exists ) {
            $pipeline->update( $values );

        } else {
            $pipeline->setRawAttributes( $values, true );
            $pipeline->save();
        }

        // throw error on failure
        if ( empty( $pipeline->id ) ) {
            return new \WP_Error( 'erp_deals_save_pipeline', __( 'Could not save pipeline. Please try again.', 'erp-pro' ) );

        } else if ( empty( $values['id'] ) ) {
            // save the first stage
            $stage['pipeline_id'] = $pipeline->id;
            $stage = $this->save_stage( $stage );

            if ( is_wp_error( $stage ) ) {
                // delete pipeline that we've just created
                $pipeline->delete();

                return new \WP_Error( 'erp_deals_save_pipeline', sprintf(
                    __( 'Stage', 'erp-pro' ) . ': ' . $stage->get_error_message()
                ) );

            } else {
                $pipeline->stages;
            }
        }

        return $pipeline;
    }

    /**
     * Delete a pipeline stage
     *
     * @since 1.0.0
     *
     * @param id $pipeline_id       Stage id to delete
     * @param id $transfer_to_stage Stage id to which all deals under deleting pipeline will be transferred
     *
     * @return boolean
     */
    public function delete_pipeline( $pipeline_id, $transfer_to_stage_id = 0 ) {
        $pipeline = PipelineModel::find( $pipeline_id );

        // Is pipeline exists?
        if ( empty( $pipeline ) ) {
            return new \WP_Error( 'erp_deals_delete_pipeline', __( 'Invalid pipeline', 'erp-pro' ) );
        }

        // at least one stage must be exists in the system
        $total_pipelines = PipelineModel::count();

        if ( $total_pipelines < 2 ) {
            return new \WP_Error( 'erp_deals_delete_pipeline', __( 'You cannot delete the last pipeline of the system', 'erp-pro' ) );
        }

        // get deal count
        $deals = $pipeline->deals;
        $deals_count = $deals->count();

        // If pipeline has one or more deals, another stage must be provided to which its deals will be transferred
        if ( $deals_count > 0 && empty( $transfer_to_stage_id ) ) {
            return new \WP_Error( 'erp_deals_delete_pipeline', __( 'This pipeline has one or more deals. Please provide another stage id to transfer', 'erp-pro' ) );

        } else if ( $deals_count ) {
            // Check transfer_to_stage_id is exists or not
            $transfer_to_stage = PipelineStageModel::find( $transfer_to_stage_id );

            if ( empty( $transfer_to_stage ) ) {
                return new \WP_Error( 'erp_deals_delete_pipeline', __( 'Invalid pipeline stage to transfer deals', 'erp-pro' ) );
            }

            // transfer the deals
            $deals->each( function ( $deal ) use ( $transfer_to_stage_id ) {
                deals()->save_deal( [ 'id' => $deal->id, 'stage_id' => $transfer_to_stage_id ] );
            } );
        }

        // delete pipeline
        return $pipeline->delete();
    }

    /**
     * Reorder pipeline stages
     *
     * @since 1.0.0
     *
     * @param array $stages
     *
     * @return void
     */
    public function reorder_stages( $stages ) {
        foreach ( $stages as $stage ) {
            PipelineStageModel::where( 'id', $stage['id'] )->update( [ 'order' => $stage['order'] ] );
        }
    }

    /**
     * Save pipeline stage
     *
     * @since 1.0.0
     *
     * @param array $data
     *
     * @return object Eloquent PipelineStage model or WP_Error object
     */
    public function save_stage( $data ) {
        if ( !isset( $data['id'] ) ) {
            $data['id'] = 0;
        }

        $stage = PipelineStageModel::firstOrNew( [ 'id' => $data['id'] ] );

        $current_data = [
            'title'         => $stage->title,
            'pipeline_id'   => $stage->pipeline_id,
            'probability'   => $stage->probability,
            'is_rotting_on' => $stage->is_rotting_on,
            'rotting_after' => $stage->rotting_after,
            'life_stage'    => $stage->life_stage,
            'order'         => $stage->order,
        ];

        $values = wp_parse_args( $data, $current_data );

        // validations
        if ( empty( $values['title'] ) ) {
            return new \WP_Error( 'erp_deals_save_stage', __( 'Title cannot be empty', 'erp-pro' ) );
        }

        $pipeline = PipelineModel::find( $values['pipeline_id'] );
        if ( empty( $pipeline ) ) {
            return new \WP_Error( 'erp_deals_save_stage', __( 'Invalid pipeline', 'erp-pro' ) );
        }

        // Set order for new stage
        if ( empty( $values['id'] ) ) {
            $stage_count = $pipeline->stages->count();
            $values['order'] = $stage_count;
        }

        // store
        if ( $stage->exists ) {
            $stage->update( $values );

        } else {
            $stage->setRawAttributes( $values, true );
            $stage->save();
        }

        // throw error on failure
        if ( empty( $stage->id ) ) {
            return new \WP_Error( 'erp_deals_save_stage', __( 'Could not save pipeline stage. Please try again.', 'erp-pro' ) );
        }

        return $stage;
    }

    /**
     * Delete a pipeline stage
     *
     * @since 1.0.0
     *
     * @param id $stage_id          Stage id to delete
     * @param id $transfer_to_stage Stage id to which all deals under deleting stage will be transferred
     *
     * @return boolean
     */
    public function delete_stage( $stage_id, $transfer_to_stage_id = 0 ) {
        $stage = PipelineStageModel::find( $stage_id );

        // Is stage exists?
        if ( empty( $stage ) ) {
            return new \WP_Error( 'erp_deals_delete_stage', __( 'Invalid pipeline stage', 'erp-pro' ) );
        }

        // at least one stage must be exists in a pipeline
        $total_stages = PipelineStageModel::where( 'pipeline_id', $stage->pipeline_id )->count();

        if ( $total_stages < 2 ) {
            return new \WP_Error( 'erp_deals_delete_stage', __( 'At least one stage must be exists in a pipeline', 'erp-pro' ) );
        }

        // get deal count
        $deals = $stage->deals;
        $deals_count = $deals->count();

        // If stage has one or more deals, another stage must be provided to which its deals will be transferred
        if ( $deals_count > 0 && empty( $transfer_to_stage_id ) ) {
            return new \WP_Error( 'erp_deals_delete_stage', __( 'This stage has one or more deals. Please provide another stage id to transfer', 'erp-pro' ) );

        } else if ( $deals_count ) {
            // Check transfer_to_stage_id is exists or not
            $transfer_to_stage = PipelineStageModel::find( $transfer_to_stage_id );

            if ( empty( $transfer_to_stage ) ) {
                return new \WP_Error( 'erp_deals_delete_stage', __( 'Invalid pipeline stage to transfer deals', 'erp-pro' ) );
            }

            // transfer the deals
            $deals->each( function ( $deal ) use ( $transfer_to_stage_id ) {
                deals()->save_deal( [ 'id' => $deal->id, 'stage_id' => $transfer_to_stage_id ] );
            } );
        }

        // delete stage
        $is_deleted = $stage->delete();

        // reorder order indices
        $stages = PipelineStageModel::where( 'pipeline_id', $stage->pipeline_id )->orderBy( 'order', 'asc' )->get();

        $reorder_stages = [];
        foreach ( $stages as $i => $stage ) {
            $reorder_stages[] = [ 'id' => $stage->id, 'order' => $i ];
        }

        $this->reorder_stages( $reorder_stages );

        // return data
        return $is_deleted;
    }

    /**
     * Save activity type
     *
     * @since 1.0.0
     *
     * @param array $data
     *
     * @return object Eloquent ActivityType model or WP_Error object
     */
    public function save_activity_type( $data ) {
        if ( !isset( $data['id'] ) ) {
            $data['id'] = 0;
        }

        $type = ActivityTypeModel::where( [ 'id' => $data['id'] ] )->withTrashed()->first()
                ?: new ActivityTypeModel( [ 'id' => $data['id'] ] );


        if ( $type->trashed() && !empty( $data['restore'] ) ) {
            // restore and set order
            $type->forceFill( [
                'order'         => ActivityTypeModel::count(),
                'deleted_at'    => NULL
            ] )->save();

            // restore all trashed activities under this type
            ActivityModel::where( 'type', $data['id'] )->restore();

            return $type;
        }

        $current_data = [
            'title' => $type->title,
            'icon'  => $type->icon,
            'order' => $type->order
        ];

        $values = wp_parse_args( $data, $current_data );

        // validations
        if ( empty( $values['title'] ) ) {
            return new \WP_Error( 'erp_deals_save_type', __( 'Title cannot be empty', 'erp-pro' ) );
        }

        if ( empty( $values['icon'] ) ) {
            return new \WP_Error( 'erp_deals_save_type', __( 'Icon cannot be empty', 'erp-pro' ) );
        }

        // Set order for new stage
        if ( empty( $values['id'] ) ) {
            $values['order'] = ActivityTypeModel::count();
        }

        // store
        if ( $type->exists ) {
            $type->update( $values );

        } else {
            $type->setRawAttributes( $values, true );
            $type->save();
        }

        // throw error on failure
        if ( empty( $type->id ) ) {
            return new \WP_Error( 'erp_deals_save_type', __( 'Could not save activity type. Please try again.', 'erp-pro' ) );
        }

        return $type;
    }

    /**
     * Reorder activity types
     *
     * @since 1.0.0
     *
     * @param array $types
     *
     * @return void
     */
    public function reorder_activity_types( $types ) {
        foreach ( $types as $type ) {
            $this->save_activity_type( $type );
        }
    }

    /**
     * Trash an activity type
     *
     * @since 1.0.0
     *
     * @param int $activity_type_id
     *
     * @return object Eloquent ActivityType model
     */
    public function trash_activity_type( $activity_type_id ) {
        $type = ActivityTypeModel::find( $activity_type_id );

        if ( empty( $type ) ) {
            return new \WP_Error( 'erp_deals_trash_type', __( 'Could not trash activity type. Please try again.', 'erp-pro' ) );
        }

        $type->delete();

        // trash all activities under this type
        ActivityModel::where( 'type', $activity_type_id )->delete();

        return $type;
    }

    /**
     * Save lost reason
     *
     * @since 1.0.0
     *
     * @param array $data
     *
     * @return object Eloquent LostReason model or WP_Error object
     */
    public function save_lost_reason( $data ) {
        if ( !isset( $data['id'] ) ) {
            $data['id'] = 0;
        }

        $lost_reason = LostReasonModel::firstOrNew( [ 'id' => $data['id'] ] );

        $current_data = [
            'reason' => $lost_reason->reason,
        ];

        $values = wp_parse_args( $data, $current_data );

        // validations
        if ( empty( $values['reason'] ) ) {
            return new \WP_Error( 'erp_deals_save_lost_reason', __( 'Lost reason cannot be empty', 'erp-pro' ) );
        }

        // store
        if ( $lost_reason->exists ) {
            $lost_reason->update( $values );

        } else {
            $lost_reason->setRawAttributes( $values, true );
            $lost_reason->save();
        }

        // throw error on failure
        if ( empty( $lost_reason->id ) ) {
            return new \WP_Error( 'erp_deals_save_lost_reason', __( 'Could not save lost reason. Please try again.', 'erp-pro' ) );
        }

        return $lost_reason;
    }

    /**
     * Delete a lost reason
     *
     * @since 1.0.0
     *
     * @param int $lost_reason_id
     *
     * @return boolean
     */
    public function delete_lost_reason( $lost_reason_id ) {
        $lost_reason = Helpers::get_lost_reason( $lost_reason_id );

        if ( empty( $lost_reason ) ) {
            return new \WP_Error( 'erp_deals_delete_lost_reason', __( 'Invalid lost_reason', 'erp-pro' ) );
        }

        $deleted = LostReasonModel::where( 'id', $lost_reason_id )->delete();

        if ( $deleted ) {
            return true;
        }

        return false;
    }

    /**
     * Get deal emails
     *
     * @since 1.0.0
     *
     * @param int $deal_id
     *
     * @return array
     */
    public function get_deal_emails( $deal_id ) {
        $prefix = DB::instance()->db->prefix;
        $emails = DB::table( 'erp_crm_deals_emails as e' )
            ->select(
                'e.id', 'e.deal_id', 'e.cust_act_id', 'e.hash', 'e.parent_id',
                'ca.user_id', 'pep.email', 'ca.message', 'ca.email_subject', 'ca.created_by', 'ca.created_at'
            )
            ->leftJoin( "{$prefix}erp_crm_customer_activities as ca", 'e.cust_act_id', '=', 'ca.id' )
            ->leftJoin( "{$prefix}erp_peoples as pep", 'ca.user_id', '=', 'pep.id' )
            ->where( 'e.deal_id', $deal_id )
            ->get();

        return $emails;
    }
}

/**
 * Class instance
 *
 * @since 1.0.0
 *
 * @return object
 */
function deals() {
    return Deals::instance();
}
