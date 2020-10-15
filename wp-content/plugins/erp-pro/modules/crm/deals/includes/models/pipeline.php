<?php
namespace WeDevs\ERP\CRM\Deals\Models;

use WeDevs\ERP\Framework\Model;

/**
 * Pipeline model
 *
 * @since 1.0.0
 */
class Pipeline extends Model {

    public $timestamps  = false;
    protected $table    = 'erp_crm_deals_pipelines';
    protected $fillable = [ 'title' ];

    /**
     * Relation to PipelineStage model
     *
     * @since 1.0.0
     *
     * @return object
     */
    public function stages() {
        return $this->hasMany( 'WeDevs\ERP\CRM\Deals\Models\PipelineStage', 'pipeline_id' );
    }

    /**
     * Relation to Deal model
     *
     * @since 1.0.0
     *
     * @return object
     */
    public function deals() {
        return $this->hasManyThrough(
            'WeDevs\ERP\CRM\Deals\Models\Deal', 'WeDevs\ERP\CRM\Deals\Models\PipelineStage',
            'pipeline_id', 'stage_id', 'id'
        );
    }
}
