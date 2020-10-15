<?php
namespace WeDevs\ERP\CRM\Deals\Models;

use WeDevs\ERP\Framework\Model;

/**
 * Participant model
 *
 * @since 1.0.0
 */
class Participant extends Model {

    public $timestamps  = true;
    protected $table    = 'erp_crm_deals_participants';
    protected $fillable = [ 'id', 'deal_id', 'people_id', 'people_type', 'added_by' ];

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
