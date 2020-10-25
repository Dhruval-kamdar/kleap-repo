<template>
	<div :id="`${modal}-prosites`" class="sui-modal-slide" data-modal-size="md">
		<div class="sui-box">
			<div class="sui-box-header sui-flatten sui-content-center sui-spacing-top--60">
				<figure v-if="!$vars.whitelabel.hide_branding" class="sui-box-banner" aria-hidden="true">
					<image-tag src="onboarding/prosites.png" :alt="$i18n.settings.labels.prosites_settings" />
				</figure>
				<button class="sui-button-icon sui-button-float--right" @click="$emit('dismiss')">
					<i class="sui-icon-close sui-md" aria-hidden="true"></i>
					<span class="sui-screen-reader-text">{{ $i18n.dialogs.close }}</span>
				</button>
				<button
					class="sui-button-icon sui-button-float--left"
					:data-modal-slide="prev"
					data-modal-slide-intro="back"
				>
					<i class="sui-icon-chevron-left sui-md" aria-hidden="true"></i>
					<span class="sui-screen-reader-text">{{ $i18n.dialogs.go_back }}</span>
				</button>
				<h3 class="sui-box-title sui-lg">{{ $i18n.settings.labels.prosites_settings }}</h3>
				<p class="sui-description">{{ $i18n.settings.descriptions.prosites_settings }}</p>
			</div>
			<div class="sui-box-body">
				<span class="sui-settings-label">{{ $i18n.settings.labels.analytics_settings }}</span>
				<span class="sui-description">{{ $i18n.settings.descriptions.analytics_settings }}</span>
				<label
					v-for="(level, index) in psLevels"
					:for="`beehive-onboarding-ps-level-${index}`"
					:key="index"
					class="sui-checkbox sui-checkbox-sm"
				>
					<input
						v-model="psSettings"
						type="checkbox"
						:id="`beehive-onboarding-ps-level-${index}`"
						:value="index"
					/>
					<span aria-hidden="true"></span>
					<span>{{ level.name }}</span>
				</label>
				<hr />
				<span class="sui-settings-label">{{ $i18n.settings.labels.dashboard_analytics }}</span>
				<span class="sui-description">{{ $i18n.settings.descriptions.dashboard_analytics }}</span>
				<label
					v-for="(level, index) in psLevels"
					:for="`beehive-onboarding-dashboard-ps-level-${index}`"
					:key="index"
					class="sui-checkbox sui-checkbox-sm"
				>
					<input
						v-model="psDashboard"
						type="checkbox"
						:id="`beehive-onboarding-dashboard-ps-level-${index}`"
						:value="index"
					/>
					<span aria-hidden="true"></span>
					<span>{{ level.name }}</span>
				</label>
			</div>
			<div class="sui-box-footer sui-flatten sui-content-center sui-spacing-bottom--50">
				<button
					:data-modal-slide="next"
					data-modal-slide-intro="next"
					role="button"
					class="sui-button"
				>{{ $i18n.dialogs.continue }}</button>
			</div>
		</div>
	</div>
</template>

<script>
import ImageTag from '@/components/elements/image-tag'
import TrackingId from '@/modules/settings/components/settings/fields/tracking-id'

export default {
	name: 'ProSites',

	components: { TrackingId, ImageTag },

	props: {
		next: {
			type: String,
			required: true
		},
		prev: {
			type: String,
			required: true
		},
		modal: {
			type: String,
			required: true
		}
	},

	data() {
		return {
			psLevels: this.$moduleVars.ps_levels || {}
		}
	},

	computed: {
		/**
		 * Computed model object to get Pro Sites levels for settings.
		 *
		 * @since 3.2.4
		 *
		 * @returns {array}
		 */
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

		/**
		 * Computed model object to get Pro Sites levels for analytics.
		 *
		 * @since 3.2.4
		 *
		 * @returns {array}
		 */
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
		}
	}
}
</script>
