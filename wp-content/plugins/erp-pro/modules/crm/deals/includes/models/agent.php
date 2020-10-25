<?php
namespace WeDevs\ERP\CRM\Deals\Models;

use WeDevs\ERP\Framework\Model;

/**
 * Agent model
 *
 * @since 1.0.0
 */
class Agent extends Model {

    public $timestamps  = true;
    protected $table    = 'erp_crm_deals_agents';
    protected $fillable = [ 'deal_id', 'agent_id', 'added_by' ];

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

}
