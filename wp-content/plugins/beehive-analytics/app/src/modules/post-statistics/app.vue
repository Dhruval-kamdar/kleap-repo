<template>
	<div class="beehive-post-stats-wrap">
		<div class="sui-row">
			<section-column
				v-for="(section, index) in sections.slice(0,3)"
				:key="index"
				:id="section.key"
				:label="section.label"
				:desc="section.desc"
				:screen-reader="section.screenReader"
				:stats="stats[section.key]"
				:trend-type="getTrendType(stats[section.key])"
				:value="getValue(stats[section.key], section.key)"
			/>
		</div>
		<div class="sui-row">
			<section-column
				v-for="(section, index) in sections.slice(3,6)"
				:key="index"
				:id="section.key"
				:label="section.label"
				:desc="section.desc"
				:screen-reader="section.screenReader"
				:trend-type="getTrendType(stats[section.key], section.key)"
				:trend-value="getTrendValue(stats[section.key], section.key)"
				:value="getValue(stats[section.key], section.key)"
			/>
		</div>
	</div>
</template>

<script>
import { RestGet } from '@/helpers/api'
import SectionColumn from './components/section-column'

export default {
	name: 'App',

	components: { SectionColumn },

	data() {
		return {
			stats: {
				users: {},
				pageviews: {},
				sessions: {},
				page_sessions: {},
				average_sessions: {},
				bounce_rates: {}
			},
			sections: [
				{
					key: 'users',
					label: this.$i18n.post.labels.users,
					desc: this.$i18n.post.descriptions.users,
					screenReader: this.$i18n.post.descriptions.screen_users
				},
				{
					key: 'pageviews',
					label: this.$i18n.post.labels.pageviews,
					desc: this.$i18n.post.descriptions.pageviews,
					screenReader: this.$i18n.post.descriptions.screen_pageviews
				},
				{
					key: 'sessions',
					label: this.$i18n.post.labels.sessions,
					desc: this.$i18n.post.descriptions.sessions,
					screenReader: this.$i18n.post.descriptions.screen_sessions
				},
				{
					key: 'page_sessions',
					label: this.$i18n.post.labels.page_sessions,
					desc: this.$i18n.post.descriptions.page_sessions,
					screenReader: this.$i18n.post.descriptions
						.screen_page_sessions
				},
				{
					key: 'average_sessions',
					label: this.$i18n.post.labels.average_sessions,
					desc: this.$i18n.post.descriptions.average_sessions,
					screenReader: this.$i18n.post.descriptions
						.screen_average_sessions
				},
				{
					key: 'bounce_rates',
					label: this.$i18n.post.labels.bounce_rates,
					desc: this.$i18n.post.descriptions.bounce_rates,
					screenReader: this.$i18n.post.descriptions
						.screen_bounce_rates
				}
			]
		}
	},

	mounted() {
		// Get the stats.
		if (this.$moduleVars.post > 0 && this.canGetStats) {
			this.getStats()
		}
	},

	computed: {
		canGetStats() {
			return this.$moduleVars.can_get_stats
		}
	},

	methods: {
		getStats() {
			RestGet({
				path: 'stats/post',
				params: {
					id: this.$moduleVars.post
				}
			}).then(response => {
				if (response.success && response.data && response.data.stats) {
					this.stats = response.data.stats
				} else {
					this.stats = false
				}
			})
		},

		getTrendType(stats, type) {
			let trendType = 'none'

			if (stats.trend) {
				const trend = stats.trend

				if (trend === 0) {
					trendType = 'equal'
				} else if (trend < 0) {
					trendType = 'down'
				} else if (trend > 0) {
					trendType = 'up'
				}

				// Bounce rate is opposite.
				if ('bounce_rates' === type) {
					if (trend < 0) {
						trendType = 'up'
					} else if (trend > 0) {
						trendType = 'down'
					}
				}
			}

			return trendType
		},

		getValue(stats, type) {
			let value = 0

			if (stats.value) {
				value = stats.value
			}

			// Bounce rate require %.
			if ('bounce_rates' === type) {
				value = value + '%'
			}

			return value
		},

		getTrendValue(stats, type) {
			if (stats.trend) {
				return Math.abs(this.stats[type].trend)
			}

			return '-'
		}
	}
}
</script>

<style lang="scss">
@import 'styles/main';
</style>
