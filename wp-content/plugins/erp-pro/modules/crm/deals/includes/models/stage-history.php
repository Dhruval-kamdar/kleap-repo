<?php
namespace WeDevs\ERP\CRM\Deals\Models;

use WeDevs\ERP\Framework\Model;

/**
 * StageHistory model
 *
 * @since 1.0.0
 */
class StageHistory extends Model {

    public $timestamps  = false;
    protected $table    = 'erp_crm_deals_stage_history';
    protected $fillable = [ 'deal_id', 'stage_id', 'in', 'out', 'in_amount', 'expected_close_date', 'modified_by' ];

}
