<?php
namespace WeDevs\ERP\CRM\Deals\Models;

use WeDevs\ERP\Framework\Model;

/**
 * Attachment model
 *
 * @since 1.0.0
 */
class Attachment extends Model {

    public $timestamps  = true;
    protected $table    = 'erp_crm_deals_attachments';
    protected $fillable = [
        'deal_id', 'attachment_id', 'added_by'
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
