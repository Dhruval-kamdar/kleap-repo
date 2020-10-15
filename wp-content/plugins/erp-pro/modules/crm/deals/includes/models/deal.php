<?php
namespace WeDevs\ERP\CRM\Deals\Models;

use WeDevs\ERP\Framework\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Deal model
 *
 * @since 1.0.0
 */
class Deal extends Model {

    use SoftDeletes;

    public $timestamps  = true;
    protected $dates    = [ 'deleted_at' ];
    protected $table    = 'erp_crm_deals';
    protected $fillable = [
        'title', 'stage_id', 'contact_id', 'company_id', 'created_by',
        'owner_id', 'value', 'currency', 'expected_close_date', 'won_at', 'lost_at',
        'lost_reason_id', 'lost_reason', 'lost_reason_comment'
    ];

    /**
     * Readable query scope for deals model
     *
     * @since 1.0.0
     *
     * @param object  $query   Query Builder
     * @param integer $deal_id
     *
     * @return object
     */
    public function scopeReadable( $query, $deal_id = 0, $withTrashed = false ) {
        $prefix  = $query->getQuery()->getConnection()->db->prefix;
        $deal_tbl = $prefix . 'erp_crm_deals';

        // deal read permission
        if ( !( current_user_can( 'administrator' ) || erp_crm_is_current_user_manager() ) ) {

            // selected columns
            $query->select( $deal_tbl . '.*' );

            // add left join statement
            $query->leftJoin( "{$prefix}erp_crm_deals_agents as agent", $deal_tbl . '.id', '=', 'agent.deal_id' );

            // add where clause statement
            $query->where( function ( $_query ) use( $deal_tbl ) {
                $current_user_id = get_current_user_id();

                // and (deal.owner_id = 102 or agent.agent_id = 102)
                $_query->where( $deal_tbl . '.owner_id',  $current_user_id )
                       ->orWhere( 'agent.agent_id', $current_user_id );
            } );
        }

        if ( $deal_id ) {
            $query->where( $deal_tbl . '.id', $deal_id );
        }

        if ( $withTrashed ) {
            $query->withTrashed();
        }

        return $query;
    }

    /**
     * Relation to PipelineStage model
     *
     * @since 1.0.0
     *
     * @return object
     */
    public function pipeline_stage() {
        return $this->belongsTo( 'WeDevs\ERP\CRM\Deals\Models\PipelineStage', 'stage_id' );
    }

    /**
     * Relation to Activity model
     *
     * @since 1.0.0
     *
     * @return object
     */
    public function activities() {
        return $this->hasMany( 'WeDevs\ERP\CRM\Deals\Models\Activity', 'deal_id' );
    }

    /**
     * Relation to LostReason model
     *
     * @since 1.0.0
     *
     * @return object
     */
    public function lost_reason() {
        return $this->belongsTo( 'WeDevs\ERP\CRM\Deals\Models\LostReason', 'deal_id' );
    }

    /**
     * Relation to StageHistory model
     *
     * @since 1.0.0
     *
     * @return object
     */
    public function stage_histories() {
        return $this->hasMany( 'WeDevs\ERP\CRM\Deals\Models\StageHistory', 'deal_id' );
    }

    /**
     * Relation to Participant model
     *
     * @since 1.0.0
     *
     * @return object
     */
    public function participants() {
        return $this->hasMany( 'WeDevs\ERP\CRM\Deals\Models\Participant', 'deal_id' );
    }

    /**
     * Relation to Agent model
     *
     * @since 1.0.0
     *
     * @return object
     */
    public function agents() {
        return $this->hasMany( 'WeDevs\ERP\CRM\Deals\Models\Agent', 'deal_id' );
    }

    /**
     * Relation to Note model
     *
     * @since 1.0.0
     *
     * @return object
     */
    public function notes() {
        return $this->hasMany( 'WeDevs\ERP\CRM\Deals\Models\Note', 'deal_id' );
    }

    /**
     * Relation to Email model
     *
     * @since 1.0.0
     *
     * @return object
     */
    public function emails() {
        return $this->hasMany( 'WeDevs\ERP\CRM\Deals\Models\Email', 'deal_id' );
    }

    /**
     * Relation to Attachment model
     *
     * @since 1.0.0
     *
     * @return object
     */
    public function attachments() {
        return $this->hasMany( 'WeDevs\ERP\CRM\Deals\Models\Attachment', 'deal_id' );
    }

    /**
     * Relation to Competitor model
     *
     * @since 1.0.0
     *
     * @return object
     */
    public function competitors() {
        return $this->hasMany( 'WeDevs\ERP\CRM\Deals\Models\Competitor', 'deal_id' );
    }
}
