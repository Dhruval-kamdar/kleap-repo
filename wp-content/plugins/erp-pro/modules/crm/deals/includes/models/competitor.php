<?php
namespace WeDevs\ERP\CRM\Deals\Models;

use WeDevs\ERP\Framework\Model;

/**
 * Competitor model
 *
 * @since 1.0.0
 */
class Competitor extends Model {

    public $timestamps  = true;
    protected $table    = 'erp_crm_deals_competitors';
    protected $fillable = [
        'deal_id', 'competitor_name', 'website', 'strengths', 'weaknesses', 'created_by'
    ];

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
