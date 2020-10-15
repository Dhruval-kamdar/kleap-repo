<?php
namespace WeDevs\ERP\CRM\Deals\Models;

use WeDevs\ERP\Framework\Model;

/**
 * Email model
 *
 * cust_act_id = id column in erp_crm_customer_activities table
 *
 * @since 1.0.0
 */
class Email extends Model {

    public $timestamps  = false;
    protected $table    = 'erp_crm_deals_emails';
    protected $fillable = [ 'deal_id', 'cust_act_id', 'hash', 'parent_id' ];

    /**
     * Relation to Deal model
     *
     * @since 1.0.0
     *
     * @return object
     */
    public function deals() {
        return $this->belongsTo( 'WeDevs\ERP\CRM\Deals\Models\Deal', 'deal_id' );
    }
}
