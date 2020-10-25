<template>
	<div v-if="isProSitesReady" class="sui-box-settings-row">
		<div class="sui-box-settings-col-1">
			<span class="sui-settings-label">{{ $i18n.settings.labels.prosites_settings }}</span>
			<span class="sui-description">{{ $i18n.settings.descriptions.prosites_settings }}</span>
		</div>

		<div class="sui-box-settings-col-2">
			<span class="sui-settings-label">{{ $i18n.settings.labels.analytics_settings }}</span>
			<span class="sui-description">{{ $i18n.settings.descriptions.analytics_settings }}</span>
			<label
				v-for="(level, index) in levels"
				:for="`beehive-settings-ps-level-${index}`"
				:key="index"
				class="sui-checkbox sui-checkbox-sm"
			>
				<input
					v-model="psSettings"
					type="checkbox"
					:id="`beehive-settings-ps-level-${index}`"
					:value="index"
				/>
				<span aria-hidden="true"></span>
				<span>{{ level.name }}</span>
			</label>
			<hr />
			<span class="sui-settings-label">{{ $i18n.settings.labels.dashboard_analytics }}</span>
			<span class="sui-description">{{ $i18n.settings.descriptions.dashboard_analytics }}</span>
			<label
				v-for="(level, index) in levels"
				:for="`beehive-dashboard-ps-level-${index}`"
				:key="index"
				class="sui-checkbox sui-checkbox-sm"
			>
				<input
					v-model="psDashboard"
					type="checkbox"
					:id="`beehive-dashboard-ps-level-${index}`"
					:value="index"
				/>
				<span aria-hidden="true"></span>
				<span>{{ level.name }}</span>
			</label>
		</div>
	</div>
</template>

<script>
export default {
	name: 'ProSites',

	data() {
		return {
			levels: this.$moduleVars.ps_levels || {}
		}
	},

	computed: {
		psSettings: {
			get() {
				return this.getOption(
					'prosites_settings_level',
					'general',
					[],
					true
				)
			},
			set(value) {
				this.setOption(
					'prosites_settings_level',
					'general',
					value,
					true
				)
			}
		},

		psDashboard: {
			get() {
				return this.getOption(
					'prosites_analytics_level',
					'general',
					[],
					true
				)
			},
			set(value) {
				this.setOption(
					'prosites_analytics_level',
					'general',
					value,
					true
				)
			}
		},

		isProSitesReady() {
			return Object.keys(this.levels).length > 0
		}
	}
}
</script>
