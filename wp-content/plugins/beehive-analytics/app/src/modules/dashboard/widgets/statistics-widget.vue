<template>
	<sui-box
		id="beehive-widget-stats"
		:title="$i18n.dashboard.titles.statistics"
		titleIcon="graph-line"
		aria-live="polite"
		:loading="loading"
	>
		<template v-slot:body>
			<p class="beehive-loading-text" v-if="loading">
				<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
				{{ $i18n.dashboard.labels.fetching_data }}
			</p>

			<p>{{ $i18n.dashboard.descriptions.statistics }}</p>

			<fragment v-if="!isLoggedIn && !canGetStats">
				<sui-notice
					v-if="isNetwork()"
					type="info"
					:message="$i18n.dashboard.notices.auth_required_network"
				/>
				<sui-notice v-else type="info" :message="$i18n.dashboard.notices.auth_required" />

				<a role="button" class="sui-button sui-button-blue" :href="$vars.urls.settings">
					<i class="sui-icon-wrench-tool" aria-hidden="true"></i>
					{{ $i18n.dashboard.labels.configure_account }}
				</a>

				<a
					role="button"
					class="sui-button sui-button-ghost"
					:href="$vars.urls.settings"
				>{{ $i18n.dashboard.labels.add_tracking_id }}</a>
			</fragment>

			<sui-notice v-else-if="isEmpty" :message="$i18n.dashboard.notices.no_data" type="info" />
		</template>

		<template v-slot:outside>
			<stats-list :stats="stats" v-if="canGetStats && !isEmpty" />
		</template>
	</sui-box>
</template>

<script>
import SuiBox from '@/components/sui/sui-box'
import StatsList from './statistics/stats-list'
import SuiNotice from '@/components/sui/sui-notice'

export default {
	name: 'StatisticsWidget',

	props: ['stats', 'loading'],

	components: {
		SuiBox,
		SuiNotice,
		StatsList
	},

	computed: {
		/**
		 * Check if the current user is logged in with Google.
		 *
		 * @since 3.2.4
		 *
		 * @returns {boolean}
		 */
		isLoggedIn() {
			return this.$store.state.helpers.google.logged_in
		},

		/**
		 * Check if current site can get stats.
		 *
		 * @since 3.2.4
		 *
		 * @returns {boolean}
		 */
		canGetStats() {
			return this.$moduleVars.can_get_stats || this.isLoggedIn
		},

		/**
		 * Check if stats are empty.
		 *
		 * @since 3.2.4
		 *
		 * @returns {boolean}
		 */
		isEmpty() {
			return Object.keys(this.stats).length <= 0
		}
	},

	methods: {}
}
</script>
