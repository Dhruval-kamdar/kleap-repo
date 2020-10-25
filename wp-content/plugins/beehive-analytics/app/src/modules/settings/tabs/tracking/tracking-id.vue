<template>
	<div class="sui-box-settings-row">
		<div class="sui-box-settings-col-1">
			<span v-if="isNetwork()" class="sui-settings-label">{{ $i18n.settings.labels.network_tracking }}</span>
			<span v-else class="sui-settings-label">{{ $i18n.settings.labels.tracking_statistics }}</span>

			<span
				v-if="isNetwork()"
				class="sui-description"
			>{{ $i18n.settings.descriptions.network_tracking }}</span>
			<span v-else class="sui-description">{{ $i18n.settings.descriptions.tracking_statistics }}</span>
		</div>
		<automatic-input v-if="showAutomatic"></automatic-input>
		<manual-input v-else @validation="validationHandler" :error="error"></manual-input>
	</div>
</template>

<script>
import ManualInput from './tracking-id/manual-input'
import AutomaticInput from './tracking-id/automatic-input'

export default {
	name: 'TrackingId',

	components: { AutomaticInput, ManualInput },

	props: {
		error: {
			type: Boolean,
			default: false
		}
	},

	computed: {
		showAutomatic() {
			let account = this.getOption('account_id', 'google')
			let autoTrack = this.getOption('auto_track', 'google')
			let autoTrackId = this.getOption('auto_track', 'misc')

			return account && autoTrack && autoTrackId && this.isLoggedIn
		},

		isLoggedIn() {
			return this.$store.state.helpers.google.logged_in
		}
	},

	methods: {
		validationHandler(data) {
			this.$emit('validation', data)
		}
	}
}
</script>
