<template>
	<sui-box
		:title="$i18n.statistics.labels.visitors"
		title-icon="profile-male"
		aria-live="polite"
		:loading="loading"
		body-class="beehive-spacing-bottom--0"
	>
		<template v-slot:body>
			<sui-notice
				v-if="canGetStats && isEmpty"
				:message="$i18n.statistics.notices.google_no_data"
				type="info"
			/>

			<sui-notice
				v-else-if="!canGetStats && !isLoggedIn"
				:message="sprintf( $i18n.statistics.notices.google_not_linked, $vars.urls.settings )"
				type="error"
			/>
		</template>

		<template v-slot:outside>
			<p class="beehive-loading-text" v-if="loading">
				<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
				{{ $i18n.statistics.labels.fetching_data }}
			</p>

			<chart-visitors :stats="stats" :periods="periods" :compare="compare" />
		</template>
	</sui-box>
</template>

<script>
import SuiBox from '@/components/sui/sui-box'
import SuiNotice from '@/components/sui/sui-notice'
import ChartVisitors from './../components/chart-visitors'

export default {
	name: 'Visitors',

	props: ['stats', 'compare', 'loading', 'periods'],

	data() {
		return {
			bodyClass: 'test'
		}
	},

	components: {
		SuiBox,
		SuiNotice,
		ChartVisitors
	},

	computed: {
		isLoggedIn() {
			return this.$store.state.helpers.google.logged_in
		},

		isEmpty() {
			return Object.keys(this.stats).length <= 0
		},

		canGetStats() {
			return this.$moduleVars.can_get_stats > 0
		}
	},

	methods: {}
}
</script>
