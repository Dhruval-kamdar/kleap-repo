<?php
namespace WeDevs\ERP\CRM\Deals;

use \WeDevs\ORM\Eloquent\Facades\DB;
use \WeDevs\ERP\CRM\Deals\Models\Deal as DealModel;

/**
 * Statistics class
 *
 * @since 1.0.0
 */
class Statistics {

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
    }

    /**
     * The main caller function of this stat class
     *
     * @since 1.0.0
     *
     * @param array $filters
     *
     * @return array
     */
    public function get_overview( $filters = [] ) {
        $company_currency = erp_get_currency();

        return [
            'company_currency'          => $company_currency,
            'company_currency_symbol'   => erp_get_currency_symbol( $company_currency ),
            'deal_summery'              => $this->get_deal_summery(),
            'deals_progress_by_stages'  => $this->deals_progress_by_stages( $filters['deal_progress'] ),
            'activity_progress'         => $this->activity_progress( $filters['activity_progress'] ),
            'last_open_deals'           => $this->last_open_deals(),
            'last_won_deals'            => $this->last_won_deals()
        ];
    }

    /**
     * Deal summery overview data
     *
     * @since 1.0.0
     *
     * @return array
     */
    public function get_deal_summery() {
        $summery = [ 'last_month' => [], 'this_month' => [] ];

        $last_month_start   = date( 'Y-m-d 00:00:00', strtotime( 'first day of last month' ) );
        $this_month_start   = date( 'Y-m-d 00:00:00', strtotime( 'first day of this month' ) );
        $this_month_end     = current_time( 'mysql' );

        // last month
        $last_month_deals = DealModel::select( 'value', 'won_at', 'lost_at' )
                                ->where( 'created_at', '>=', $last_month_start )
                                ->where( 'created_at', '<', $this_month_start )
                                ->get();

        $summery['last_month']['new'] = [
            'total' => $last_month_deals->count(),
            'value' => $last_month_deals->sum( 'value' )
        ];

        $last_month_won = $last_month_deals->filter( function ( $deal ) {
            return !empty( $deal->won_at );
        } );

        $summery['last_month']['won'] = [
            'total' => $last_month_won->count(),
            'value' => $last_month_won->sum( 'value' )
        ];

        $last_month_lost = $last_month_deals->filter( function ( $deal ) {
            return !empty( $deal->lost_at );
        } );

        $summery['last_month']['lost'] = [
            'total' => $last_month_lost->count(),
            'value' => $last_month_lost->sum( 'value' )
        ];

        // this month
        $this_month_deals = DealModel::select( 'value', 'won_at', 'lost_at' )
                                ->where( 'created_at', '>=', $this_month_start )
                                ->where( 'created_at', '<=', $this_month_end )
                                ->get();

        $summery['this_month']['new'] = [
            'total' => $this_month_deals->count(),
            'value' => $this_month_deals->sum( 'value' )
        ];

        $this_month_won = $this_month_deals->filter( function ( $deal ) {
            return !empty( $deal->won_at );
        } );

        $summery['this_month']['won'] = [
            'total' => $this_month_won->count(),
            'value' => $this_month_won->sum( 'value' )
        ];

        $this_month_lost = $this_month_deals->filter( function ( $deal ) {
            return !empty( $deal->lost_at );
        } );

        $summery['this_month']['lost'] = [
            'total' => $this_month_lost->count(),
            'value' => $this_month_lost->sum( 'value' )
        ];

        return $summery;
    }

    /**
     * Deal progress report
     *
     * @since 1.0.0
     *
     * @param array $filters
     *
     * @return array Report of deal progress by pipeline stages
     */
    public function deals_progress_by_stages( $filters = [] ) {
        $prefix = DB::instance()->db->prefix;

        $defaults = [
            'pipeline_id' => 0,
            'agent_id'    => 0,
            'time'        => 'month'
        ];

        $filters = wp_parse_args( $filters, $defaults );

        if ( empty( $filters['pipeline_id'] ) ) {
            $pipeline = \WeDevs\ERP\CRM\Deals\Models\Pipeline::orderBy( 'id', 'asc' )->first();
        } else {
            $pipeline = \WeDevs\ERP\CRM\Deals\Models\Pipeline::find( $filters['pipeline_id'] );
        }

        $stages = $pipeline->stages()->orderBy( 'order', 'asc' )->get( [ 'id', 'title', 'order' ] )->toArray();

        $stage_ids = wp_list_pluck( $stages, 'id' );

        $history = DB::table( 'erp_crm_deals_stage_history as i' )
                     ->select(
                        'i.stage_id as id',
                        DB::raw( "count(i.deal_id) as deal_count" ),
                        DB::raw( "SUM(d.value) as total_value" ),
                        DB::raw( "SUM( TIMESTAMPDIFF(DAY, o.in, o.out) ) as total_days_to_reach" )
                     )
                     ->leftJoin( "{$prefix}erp_crm_deals_pipeline_stages as s", 'i.stage_id', '=', 's.id' )
                     ->leftJoin( "{$prefix}erp_crm_deals_stage_history as o", function ( $join ) {
                        $join->on( 'i.in', '=', 'o.out' )->where( 'i.deal_id', '=', DB::raw('o.deal_id') );
                     } )
                     ->leftJoin( "{$prefix}erp_crm_deals as d", 'i.deal_id', '=', 'd.id' )
                     ->whereIn( 'i.stage_id', $stage_ids )
                     ->whereNull( 'd.deleted_at' )
                     ->groupBy( 'i.stage_id' );


        if ( !empty( $filters['agent_id'] ) ) {
            $history->where( 'd.owner_id', $filters['agent_id'] );
        }

        switch ( $filters['time'] ) {
            case 'week':
                $first_day = date( 'Y-m-d 00:00:00', strtotime( 'monday this week' ) );
                break;

            case 'year':
                $first_day = date( 'Y-01-01 00:00:00', strtotime( current_time( 'mysql' ) ) );
                break;

            default:
                $first_day = date( 'Y-m-d 00:00:00', strtotime( 'first day of this month' ) );
                break;
        }

        $history->where( 'i.in', '>=', $first_day  );

        $history->where( function ( $query ) {
            $query->where( 'o.in', '<=', current_time( 'mysql' ) )
                  ->orWhereNull( 'o.in' );
        } );

        $history = $history->get()->toArray();

        foreach ( $stages as $i => $stage ) {
            $stage_history = array_filter( $history, function ( $item ) use ( $stage ) {
                return absint( $stage['id'] ) === absint( $item->id );
            });

            $stage_history = (array) array_pop( $stage_history );

            if ( empty( $stage_history ) ) {
                $stage_history = [
                    'deal_count' => 0,
                    'total_value' => 0.00,
                    'total_days_to_reach' => 0,
                ];
            }

            $stages[ $i ] = array_merge( $stage, $stage_history );
        }

        return $stages;
    }

    /**
     * Activity progress overview data
     *
     * @since 1.0.0
     *
     * @param array $filters
     *
     * @return array
     */
    public function activity_progress( $filters = [] ) {
        $prefix = DB::instance()->db->prefix;

        $defaults = [
            'agent_id'    => 0,
            'time'        => 'month'
        ];

        $filters = wp_parse_args( $filters, $defaults );

        $types = Helpers::get_activity_types();

        $activity_types = [];

        $types->each( function ( $type ) use ( &$activity_types, $filters ) {
            $query = $type->activities()->select( 'done_at' );

            switch ( $filters['time'] ) {
                case 'week':
                    $first_day = date( 'Y-m-d 00:00:00', strtotime( 'monday this week' ) );
                    break;

                case 'year':
                    $first_day = date( 'Y-01-01 00:00:00', strtotime( current_time( 'mysql' ) ) );
                    break;

                default:
                    $first_day = date( 'Y-m-d 00:00:00', strtotime( 'first day of this month' ) );
                    break;
            }

            if ( !empty( $filters['agent_id'] ) ) {
                $query->where( 'assigned_to_id', $filters['agent_id'] );
            }

            $query->where( 'start', '>=', $first_day  );

            $query->where( 'end', '<=', current_time( 'mysql' )  );

            $activities = $query->get();

            $total = $activities->count();

            $marked_as_done  = $activities->filter( function ( $activity ) {
                return !empty( $activity->done_at );
            } );

            $done = $marked_as_done->count();

            $activity_types[] = [
                'id'    => $type->id,
                'title' => $type->title,
                'order' => $type->order,
                'icon'  => $type->icon,
                'total' => $total,
                'done'  => $done,
            ];
        } );

        return $activity_types;
    }

    /**
     * Most recent open deals
     *
     * @since 1.0.0
     *
     * @return object Eloquent Collection object of Deal models
     */
    public function last_open_deals() {
        return DealModel::select( 'id', 'title', 'value', 'created_at' )
                   ->whereNull( 'won_at' )
                   ->whereNull( 'lost_at' )
                   ->orderBy( 'id', 'desc' )
                   ->take( 5 )
                   ->get();
    }

    /**
     * Most recent won deals
     *
     * @since 1.0.0
     *
     * @return object Eloquent Collection object of Deal models
     */
    public function last_won_deals() {
        return DealModel::select( 'id', 'title', 'value', 'created_at' )
                   ->whereNotNull( 'won_at' )
                   ->orderBy( 'id', 'desc' )
                   ->take( 5 )
                   ->get();
    }
}

/**
 * Class instance
 *
 * @since 1.0.0
 *
 * @return object
 */
function statistics() {
    return Statistics::instance();
}
