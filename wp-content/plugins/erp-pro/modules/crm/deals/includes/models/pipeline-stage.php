<?php
namespace WeDevs\ERP\CRM\Deals\Models;

use WeDevs\ERP\Framework\Model;

/**
 * PipelineStage model
 *
 * @since 1.0.0
 */
class PipelineStage extends Model {

    public $timestamps  = false;
    protected $table    = 'erp_crm_deals_pipeline_stages';
    protected $fillable = [
        'title', 'pipeline_id', 'probability',
        'is_rotting_on', 'rotting_after', 'life_stage', 'order'
    ];

    /**
     * Relation to Pipeline model
     *
     * @since 1.0.0
     *
     * @return object
     */
    public function pipeline() {
        return $this->belongsTo( 'WeDevs\ERP\CRM\Deals\Models\Pipeline', 'pipeline_id' );
    }

    /**
     * Relation to Deal model
     *
     * @since 1.0.0
     *
     * @return object
     */
    public function deals() {
        return $this->hasMany( 'WeDevs\ERP\CRM\Deals\Models\Deal', 'stage_id' );
    }
}
