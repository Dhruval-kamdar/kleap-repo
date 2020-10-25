<?php if ( version_compare( WPERP_VERSION, "1.4.0", '>=' ) ) : ?>
    <div class="wrap">
        <div class="erp-grid-container erp-deal-page" id="erp-deals" v-cloak>
            <div v-if="'deals' === urlQueries.section || 'dashboard' === urlQueries.section ">
                <overview :i18n="i18n"></overview>
            </div>

            <div v-if="'all-deals' === urlQueries.section">
                <pipeline-view
                    v-if="'pipeline' === view"
                    :i18n="i18n"
                    :pipeline="pipeline"
                    :filters.sync="filters"
                    :currency-symbol="currencySymbol"
                    :crm-agents="users.crmAgents"
                ></pipeline-view>

                <single-deal
                    v-if="'single-deal' === view"
                    :i18n="i18n"
                    :deal-id="urlQueries.id"
                ></single-deal>

                <new-deal-modal
                    :i18n="i18n"
                    :currency-symbol="currencySymbol"
                    :pipeline-stages="pipelineStages"
                    :users="users"
                ></new-deal-modal>

                <!-- there is another same element inside single-deal component -->
                <activity-modal v-if="'single-deal' !== view" :i18n="i18n" :users="users"></activity-modal>

                <lost-reason-modal :i18n="i18n"></lost-reason-modal>
            </div>

            <div v-if="'activities' === urlQueries.section">
                <activity-list :i18n="i18n"></activity-list>
            </div>
        </div>
    </div>
<?php else : ?>
    <div class="wrap">
    <div class="erp-grid-container erp-deal-page" id="erp-deals" v-cloak>
        <div v-if="'erp-deals' === urlQueries.page">
            <overview :i18n="i18n"></overview>
        </div>

        <div v-if="'erp-deals-admin-page' === urlQueries.page">
            <pipeline-view
                v-if="'pipeline' === view"
                :i18n="i18n"
                :pipeline="pipeline"
                :filters.sync="filters"
                :currency-symbol="currencySymbol"
                :crm-agents="users.crmAgents"
            ></pipeline-view>

            <single-deal
                v-if="'single-deal' === view"
                :i18n="i18n"
                :deal-id="urlQueries.id"
            ></single-deal>

            <new-deal-modal
                :i18n="i18n"
                :currency-symbol="currencySymbol"
                :pipeline-stages="pipelineStages"
                :users="users"
            ></new-deal-modal>

            <!-- there is another same element inside single-deal component -->
            <activity-modal v-if="'single-deal' !== view" :i18n="i18n" :users="users"></activity-modal>

            <lost-reason-modal :i18n="i18n"></lost-reason-modal>
        </div>

        <div v-if="'erp-deals-activities' === urlQueries.page">
            <activity-list :i18n="i18n"></activity-list>
        </div>
    </div>
</div>
<?php endif; ?>
