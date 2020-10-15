<?php
namespace WeDevs\ERP\CRM\Deals\Models;

use WeDevs\ERP\Framework\Model;

/**
 * Lost Reason model
 *
 * @since 1.0.0
 */
class LostReason extends Model {

    public $timestamps  = false;
    protected $table    = 'erp_crm_deals_lost_reasons';
    protected $fillable = [ 'reason' ];

    /**
     * Relation to Deal model
     *
     * @since 1.0.0
     *
     * @return object
     */
    public function deals() {
        return $this->hasMany( 'WeDevs\ERP\CRM\Deals\Models\Deals', 'lost_reason_id' );
    }
}
