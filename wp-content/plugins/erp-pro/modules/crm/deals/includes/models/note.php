<?php
namespace WeDevs\ERP\CRM\Deals\Models;

use WeDevs\ERP\Framework\Model;

/**
 * Note model
 *
 * @since 1.0.0
 */
class Note extends Model {

    public $timestamps  = true;
    protected $table    = 'erp_crm_deals_notes';
    protected $fillable = [ 'deal_id', 'note', 'is_sticky', 'created_by' ];

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
