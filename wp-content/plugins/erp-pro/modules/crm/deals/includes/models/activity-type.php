<?php
namespace WeDevs\ERP\CRM\Deals\Models;

use WeDevs\ERP\Framework\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Activity model
 *
 * @since 1.0.0
 */
class ActivityType extends Model {

    use SoftDeletes;

    public $timestamps  = false;
    protected $dates    = [ 'deleted_at' ];
    protected $table    = 'erp_crm_deals_activity_types';
    protected $fillable = [ 'title', 'icon', 'order' ];

    /**
     * Relation to Activity model
     *
     * @since 1.0.0
     *
     * @return object
     */
    public function activities() {
        return $this->hasMany( 'WeDevs\ERP\CRM\Deals\Models\Activity', 'type' );
    }
}
