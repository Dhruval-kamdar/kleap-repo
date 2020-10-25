<template>
	<div class="sui-border-frame google-account-selector">
		<google-accounts
			id="beehive-settings-google-account-id"
			:label="$i18n.settings.labels.choose_account"
			:description="$i18n.settings.descriptions.account_not_here"
		/>
		<div v-if="showAutoTrack" class="sui-form-field">
			<label for="beehive-settings-google-auto-track" class="sui-checkbox sui-checkbox-sm">
				<input v-model="autoTrack" type="checkbox" id="beehive-settings-google-auto-track" value="1" />
				<span aria-hidden="true"></span>
				<span>
					{{ $i18n.settings.labels.auto_detect_id }}
					<span
						class="sui-tooltip sui-tooltip-constrained"
						:data-tooltip="$i18n.settings.tooltips.tracking_id"
					>
						<i class="sui-icon-info" aria-hidden="true"></i>
					</span>
				</span>
			</label>
		</div>
	</div>
</template>

<script>
import GoogleAccounts from './../../../../components/settings/fields/google-accounts'

export default {
	name: 'AccountSelection',

	components: { GoogleAccounts },

	computed: {
		autoTrack: {
			get() {
				return this.getOption('auto_track', 'google')
			},
			set(value) {
				this.setOption('auto_track', 'google', value)
			}
		},

		showAutoTrack() {
			let autoTrack = this.getOption('auto_track', 'misc')

			return autoTrack && autoTrack !== ''
		}
	}
}
</script>
