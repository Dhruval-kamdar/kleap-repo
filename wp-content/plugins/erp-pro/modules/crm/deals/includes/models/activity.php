<?php
namespace WeDevs\ERP\CRM\Deals\Models;

use WeDevs\ERP\Framework\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Activity model
 *
 * @since 1.0.0
 */
class Activity extends Model {

    use SoftDeletes;

    public $timestamps  = true;
    protected $dates    = [ 'deleted_at' ];
    protected $table    = 'erp_crm_deals_activities';
    protected $fillable = [
        'type', 'title', 'deal_id', 'contact_id', 'company_id', 'created_by', 'assigned_to_id',
        'start', 'end', 'is_start_time_set', 'note', 'done_at', 'done_by'
    ];
    protected $non_fillable = [ 'id', 'created_at', 'updated_at', 'deleted_at' ];

    /**
     * Relation to Deal model
     *
     * @since 1.0.0
     *
     * @return object
     */
    public function deal() {
        return $this->belongsTo( 'WeDevs\ERP\CRM\Deals\Models\Deal', 'deal_id' );
    }

    /**
     * Relation to ActivityType model
     *
     * @since 1.0.0
     *
     * @return object
     */
    public function activity_type() {
        return $this->belongsTo( 'WeDevs\ERP\CRM\Deals\Models\ActivityType', 'type' );
    }

    /**
     * Join related tables to fetch contact, company and agent names
     *
     * @since 1.0.0
     *
     * @param object $query
     *
     * @return void
     */
    public function scopeWithNames( $query ) {
        $prefix  = $query->getQuery()->getConnection()->db->prefix;
        $columns = $query->getQuery()->columns;

        $select_fields = [];

        // prepare the columns of erp_crm_deals_activities table
        if ( empty( $columns ) ) {
            $select_fields[] = $prefix . 'erp_crm_deals_activities.*';
        } else {
            foreach ( $columns as $column ) {
                if ( in_array( $column , $this->fillable ) || in_array( $column , $this->non_fillable ) ) {
                    $select_fields[] = $prefix . 'erp_crm_deals_activities.' . $column;
                } else {
                    $select_fields[] = $column;
                }
            }
        }

        // Extra columns for names. For contact we need to
        // contact first and last names. That's why addSelect
        // is used
        $select_fields[] = 'wpu.display_name as assigned_to';
        $select_fields[] = 'comp.company as company';

        $query->leftJoin( "{$prefix}users as wpu", 'wpu.ID', '=', "{$prefix}erp_crm_deals_activities.assigned_to_id" )
              ->leftJoin( "{$prefix}erp_peoples as cont", 'cont.id', '=', "{$prefix}erp_crm_deals_activities.contact_id" )
              ->leftJoin( "{$prefix}erp_peoples as comp", 'comp.id', '=', "{$prefix}erp_crm_deals_activities.company_id" )
              ->select( $select_fields )
              ->addSelect( \WeDevs\ORM\Eloquent\Facades\DB::raw( "concat_ws( ' ', cont.first_name, cont.last_name ) contact" ) );
    }

    /**
     * Make sure user has permission to view the deal
     *
     * @since 1.0.0
     *
     * @param object $query
     *
     * @return void
     */
    public function scopeInAllReadableDeals( $query, $is_counting = false ) {
        $prefix   = $query->getQuery()->getConnection()->db->prefix;
        $deal_tbl = $prefix . 'erp_crm_deals';
        $act_tbl  = $prefix . 'erp_crm_deals_activities';

        $query->addSelect( $act_tbl . '.*' )
              ->addSelect( 'deal.title as deal_title' )
              ->leftJoin( "{$deal_tbl} as deal", 'deal.id', '=', "{$act_tbl}.deal_id" );

        // deal read permission
        if ( !( current_user_can( 'administrator' ) || erp_crm_is_current_user_manager() ) ) {

            // add left join statement
            $query->leftJoin( "{$prefix}erp_crm_deals_agents as agent", 'deal.id', '=', 'agent.deal_id' );

            // add where clause statement
            $query->where( function ( $_query ) use( $deal_tbl, $act_tbl ) {
                $current_user_id = get_current_user_id();

                // and (deal.owner_id = 102 or agent.agent_id = 102)
                $_query->where( 'deal.owner_id',  $current_user_id )
                       ->orWhere( $act_tbl . '.assigned_to_id', $current_user_id );
            } );
        }
        $query->whereNull( 'deal.won_at' );
        $query->whereNull( 'deal.lost_at' );
        $query->whereNull( 'deal.deleted_at' );

        if ( !$is_counting ) {
            $query->groupBy( $act_tbl . '.id' );
        }
    }
}
