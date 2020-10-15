<template>
	<div class="sui-box-settings-col-2">
		<div :class="{ 'sui-form-field-error': error }" class="sui-form-field beehive-margin-bottom--10">
			<label
				for="beehive-settings-tracking-code-manual"
				class="sui-label"
			>{{ $i18n.settings.labels.tracking_id }}</label>
			<tracking-id
				id="beehive-settings-tracking-code-manual"
				v-model="trackingId"
				context="settings"
				@validation="onValidation"
			/>
			<span class="sui-description" v-html="getDescription"></span>
		</div>
		<sui-notice
			v-if="error"
			type="error"
			:message="sprintf( $i18n.settings.notices.invalid_tracking_id, 'https://support.google.com/analytics/answer/1032385?rd=1' )"
		/>
	</div>
</template>

<script>
import SuiNotice from '@/components/sui/sui-notice'
import TrackingId from './../../../components/settings/fields/tracking-id'

export default {
	name: 'ManualInput',

	components: { SuiNotice, TrackingId },

	props: {
		error: {
			type: Boolean,
			default: false
		}
	},

	computed: {
		trackingId: {
			get() {
				return this.getOption('code', 'tracking', '')
			},
			set(value) {
				this.setOption('code', 'tracking', value)
			}
		},

		getDescription() {
			// Descriptions.
			let desc = this.sprintf(
				this.$i18n.settings.descriptions.tracking_id_help,
				'https://support.google.com/analytics/answer/1032385?rd=1'
			)
			let autoDesc = this.$i18n.settings.descriptions
				.tracking_id_inherited

			// Tracking IDs.
			let trackingId = this.getOption('code', 'tracking', '')
			let networkTrackingId = this.getOption('code', 'tracking', '', true)

			// Automatic tracking IDs.
			let networkAutoTrackingId = this.getOption(
				'auto_track',
				'misc',
				'',
				true
			)
			// Auto tracking flag.
			let networkAutoTracking = this.getOption(
				'auto_track',
				'google',
				false,
				true
			)

			if (
				trackingId ||
				!this.isSubsite() ||
				!this.isNetworkWide() ||
				this.isNetwork()
			) {
				return desc
			} else if (
				networkTrackingId ||
				(networkAutoTracking && networkAutoTrackingId)
			) {
				return autoDesc
			} else {
				return desc
			}
		}
	},

	methods: {
		onValidation(data) {
			// Emit to parent.
			this.$emit('validation', data)
		}
	}
}
</script>
