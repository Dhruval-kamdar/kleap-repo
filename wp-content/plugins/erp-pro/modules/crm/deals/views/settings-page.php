<tr class="erp-grid-container erp-deal-settings-page" id="erp-deals-settings-page" v-cloak>
    <td colspan="2">
        <div v-if="isReady" id="erp-vertical-tab-settings">
            <div id="settings-sidebar">
                <ul class="settings-menu">
                    <li v-for="(tab, title) in settingsTabs" :class="[(tab === currentTab) ? 'active' : '']">
                        <a href="#" class="settings-tab-btn" @click.prevent="currentTab = tab">{{ title }}</a>
                    </li>
                </ul>
            </div>
            <div id="settings-content">
                <div v-if="'pipeline' === currentTab" :class="[doingAjax ? 'disabled' : '']">
                    <h1 class="tab-title">{{ i18n.customizeSalesStages }}</h1>

                    <div v-for="(pipeIndex, pipeline) in pipelines" class="settings-pipeline">
                        <div class="clearfix">
                            <h3 class="pipeline-title pull-left">{{ pipeline.title }}</h3>

                            <div class="pull-left button-group">
                                <button
                                    type="button"
                                    class="button button-small button-link"
                                    @click="addNewStage(pipeline)"
                                ><span class="dashicons dashicons-plus"></span> {{ i18n.addStage }}</button>

                                <button
                                    type="button"
                                    class="button button-small button-link"
                                    @click="openPipelineEditorModal(pipeline)"
                                ><span class="dashicons dashicons-edit"></span> {{ i18n.editPipeline }}</button>
                            </div>
                        </div>

                        <div class="postbox-inside" :id="'settings-pipeline-id-' + pipeline.id">
                            <div class="settings-step-progressbar margin-bottom-8">
                                <ul
                                    v-erp-sortable
                                    stop="updateStageOrder"
                                >
                                    <li
                                        v-for="(index, stage) in pipeline.stages | orderBy 'order'"
                                        :data-index="index"
                                        :data-stage-id="stage.id"
                                        :data-pipeline-index="pipeIndex"
                                        :data-pipeline-id="pipeline.id"
                                        @click="openStageEditorModal(stage)"
                                    >
                                        <span class="stage-title">
                                            <span>
                                                <span>{{ stage.title }}</span>
                                            </span>
                                        </span>
                                    </li>
                                </ul>
                            </div>


                        </div>
                    </div>

                    <button
                        type="button"
                        class="button button-primary"
                        @click="addNewPipeline"
                    >{{ i18n.addNewPipeline }}</button>
                </div>

                <div v-if="'activityTypes' === currentTab">
                    <settings-activity-types :i18n="i18n" :activity-types.sync="activityTypes"></settings-activity-types>
                </div>

                <div v-if="'lostReasons' === currentTab">
                    <settings-lost-reasons :i18n="i18n" :lost-reasons.sync="lostReasons"></settings-lost-reasons>
                </div>

            </div>
        </div>

        <div class="erp-deal-modal" id="deal-settings-stage" tabindex="-1">
            <div class="erp-deal-modal-dialog" role="document">
                <div class="erp-deal-modal-content">
                    <div class="erp-deal-modal-header">
                        <button type="button" class="erp-close" data-dismiss="erp-deal-modal" aria-label="Close" :disabled="doingAjax">
                            <span aria-hidden="true" :class="[doingAjax ? 'disabled': '']">×</span>
                        </button>
                        <h4 v-if="!showDeleteStageDialogue" class="erp-deal-modal-title">{{ editingStage.id ? i18n.editStage : i18n.addStage }}</h4>
                        <h4 v-else class="erp-deal-modal-title">{{ i18n.deleteStage }}</h4>
                    </div>

                    <div v-if="!showDeleteStageDialogue" class="erp-deal-modal-body" id="deal-settings-stage-body">
                        <div class="deal-row margin-bottom-20">
                            <div class="col-2 text-right">
                                <strong class="input-label">{{ i18n.stageName }}</strong>
                            </div>
                            <div :class="['col-4', stageTitleClass]">
                                <input
                                    class="erp-deal-input"
                                    type="text"
                                    v-model="editingStage.title"
                                    @focus="stageTitleClass = ''"
                                    required
                                >
                            </div>
                        </div>

                        <div class="deal-row">
                            <div class="col-2 text-right">
                                <strong>{{ i18n.lifeStage }}</strong>
                            </div>
                            <div class="col-4">
                                <ul class="no-margin">
                                    <li v-for="(lifeStage, title) in lifeStages">
                                        <label>
                                            <input type="radio" :value="lifeStage" v-model="editingStage.lifeStage"> {{ title }}
                                        </label>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="radio" :value="0" v-model="editingStage.lifeStage"> {{ i18n.doNotChange }}
                                        </label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div v-else class="erp-deal-modal-body" id="deal-settings-stage-body">
                        <p>
                            <?php printf( __( 'You are about to delete <strong>%s</strong> stage. Are you sure you want to do this?', 'erp-pro' ), '{{ editingStageSource.title }}' ); ?>
                        </p>

                        <p v-if="editingStage.dealCount && editingStage.dealCount >= 2">
                            <?php printf( __( 'There are <strong>%s deals</strong> in this stage right now.', 'erp-pro' ), '{{ editingStage.dealCount }}' ); ?><br>
                        </p>
                        <p v-if="editingStage.dealCount && editingStage.dealCount < 2">
                            <?php printf( __( 'There is <strong>%s deal</strong> in this stage right now.', 'erp-pro' ), '{{ editingStage.dealCount }}' ); ?><br>
                        </p>


                        <ul v-if="editingStage.dealCount" class="no-bottom-margin">
                            <li>
                                <?php _e( 'Please choose into which stage these deals should be moved after deletion.', 'erp-pro' ); ?>
                            </li>
                            <li v-for="stage in transferableStages | orderBy 'order'">
                                <label>
                                    <input type="radio" :value="stage.id" v-model="transferToStage"> {{ stage.title }}
                                </label>
                            </li>
                        </ul>
                    </div>

                    <div class="erp-deal-modal-footer">
                        <button
                            v-if="editingStage.id && !showDeleteStageDialogue && transferableStages.length"
                            type="button"
                            class="button button-danger pull-left"
                            :disabled="doingAjax"
                            @click="openDeleteStageDialogue"
                        >{{ i18n.delete }}</button>

                        <button
                            v-if="editingStage.id && showDeleteStageDialogue"
                            type="button"
                            class="button pull-left button-icon-only"
                            :disabled="doingAjax"
                            @click="showDeleteStageDialogue = false"
                        ><span class="dashicons dashicons-arrow-left-alt2"></span></button>

                        <button
                            type="button"
                            class="button button-link"
                            data-dismiss="erp-deal-modal"
                            :disabled="doingAjax"
                        >{{ i18n.cancel }}</button>

                        <button
                            v-if="!showDeleteStageDialogue"
                            type="button"
                            class="button button-primary"
                            @click="saveStage"
                            :disabled="doingAjax"
                        >{{ i18n.save }}</button>

                        <button
                            v-if="showDeleteStageDialogue"
                            type="button"
                            class="button button-danger"
                            @click="deleteStage"
                            :disabled="doingAjax || !transferableStages.length"
                        >{{ i18n.deleteThisStage }}</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="erp-deal-modal" id="deal-settings-pipeline" tabindex="-1">
            <div class="erp-deal-modal-dialog" role="document">
                <div class="erp-deal-modal-content">
                    <div class="erp-deal-modal-header">
                        <button type="button" class="erp-close" data-dismiss="erp-deal-modal" aria-label="Close" :disabled="doingAjax">
                            <span aria-hidden="true" :class="[doingAjax ? 'disabled': '']">×</span>
                        </button>
                        <h4 v-if="!showDeletePipelineDialogue" class="erp-deal-modal-title">{{ editingPipeline.id ? i18n.editPipeline : i18n.addPipeline }}</h4>
                        <h4 v-else class="erp-deal-modal-title">{{ i18n.deletePipeline }}</h4>
                    </div>

                    <div v-if="!showDeletePipelineDialogue" class="erp-deal-modal-body" id="deal-settings-pipeline-body">
                        <div class="deal-row">
                            <div class="col-2 text-right">
                                <strong class="input-label">{{ i18n.pipelineTitle }}</strong>
                            </div>
                            <div :class="['col-4', pipelineTitleClass ]">
                                <input
                                    class="erp-deal-input"
                                    type="text"
                                    v-model="editingPipeline.title"
                                    @focus="pipelineTitleClass = ''"
                                >
                            </div>
                        </div>

                        <div v-if="!editingPipeline.id" class="deal-row margin-top-20">
                            <div class="col-2 text-right">
                                <strong>{{ i18n.stage }}</strong>
                            </div>
                            <div class="col-4">
                                <span class="block-label margin-bottom-4">{{ i18n.name }}:</span>
                                <div :class="[stageTitleClass]">
                                    <input
                                        class="erp-deal-input margin-bottom-20"
                                        type="text"
                                        v-model="editingPipeline.stage.title"
                                        @focus="stageTitleClass = ''"
                                    >
                                </div>

                                <span class="block-label margin-bottom-4">{{ i18n.lifeStage }}:</span>
                                <ul class="no-margin">
                                    <li v-for="(lifeStage, title) in lifeStages">
                                        <label>
                                            <input type="radio" :value="lifeStage" v-model="editingPipeline.stage.lifeStage"> {{ title }}
                                        </label>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="radio" :value="0" v-model="editingPipeline.stage.lifeStage"> {{ i18n.doNotChange }}
                                        </label>
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </div>

                    <div v-else class="erp-deal-modal-body" id="deal-settings-pipeline-body">
                        <p>
                            <?php printf( __( 'You are about to delete <strong>%s</strong> pipeline. Are you sure you want to do this?', 'erp-pro' ), '{{ editingPipelineSource.title }}' ); ?>
                        </p>

                        <p v-if="editingPipeline.dealCount && editingPipeline.dealCount >= 2">
                            <?php printf( __( 'There are <strong>%s deals</strong> in this pipeline right now.', 'erp-pro' ), '{{ editingPipeline.dealCount }}' ); ?><br>
                        </p>
                        <p v-if="editingPipeline.dealCount && editingPipeline.dealCount < 2">
                            <?php printf( __( 'There is <strong>%s deal</strong> in this pipeline right now.', 'erp-pro' ), '{{ editingPipeline.dealCount }}' ); ?><br>
                        </p>

                        <ul v-if="editingPipeline.dealCount" class="no-bottom-margin pipeline-stage-list">
                            <li>
                                <?php _e( 'Please choose into which pipeline these deals should be moved after deletion.', 'erp-pro' ); ?>
                            </li>
                            <li class="pipeline-stage-list-item">
                                <select v-model="transferToStage">
                                    <optgroup v-for="pipeline in transferablePipelines()" :label="pipeline.title">
                                        <option v-for="stage in pipeline.stages" :value="stage.id">{{ stage.title }}</option>
                                    </optgroup>
                                </select>
                            </li>
                        </ul>
                    </div>

                    <div class="erp-deal-modal-footer">
                        <button
                            v-if="editingPipeline.id && !showDeletePipelineDialogue"
                            type="button"
                            class="button button-danger pull-left"
                            :disabled="doingAjax"
                            @click="openDeletePipelineDialogue"
                        >{{ i18n.delete }}</button>

                        <button
                            v-if="editingPipeline.id && showDeletePipelineDialogue"
                            type="button"
                            class="button pull-left button-icon-only"
                            :disabled="doingAjax"
                            @click="showDeletePipelineDialogue = false"
                        ><span class="dashicons dashicons-arrow-left-alt2"></span></button>

                        <button
                            type="button"
                            class="button button-link"
                            data-dismiss="erp-deal-modal"
                            :disabled="doingAjax"
                        >{{ i18n.cancel }}</button>

                        <button
                            v-if="!showDeletePipelineDialogue"
                            type="button"
                            class="button button-primary"
                            @click="savePipeline"
                            :disabled="doingAjax || showDeletePipelineDialogue"
                        >{{ i18n.save }}</button>

                        <button
                            v-if="showDeletePipelineDialogue"
                            type="button"
                            class="button button-danger"
                            @click="deletePipeline"
                            :disabled="doingAjax"
                        >{{ i18n.deleteThisPipeline }}</button>
                    </div>
                </div>
            </div>
        </div>
    </td>
</tr>
