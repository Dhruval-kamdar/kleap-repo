<template>
	<div class="sui-box">
		<box-header :title="$i18n.settings.titles.tracking"></box-header>
		<div class="sui-box-body">
			<div class="sui-box-settings-row">
				<div class="sui-box-settings-col-2">
					<p v-if="isSubsite()">
						{{ $i18n.settings.descriptions.tracking_subsite }}
					</p>
					<p v-else>{{ $i18n.settings.descriptions.tracking }}</p>
					<sui-notice
						type="info"
						:message="
							sprintf(
								$i18n.settings.notices.account_setup_both,
								$vars.urls.statistics
							)
						"
						v-if="showAccountTrackingNotice"
					/>
					<sui-notice
						type="info"
						:message="
							sprintf(
								$i18n.settings.notices.account_setup_login,
								$vars.urls.statistics
							)
						"
						v-else-if="showAccountNotice"
					/>
					<sui-notice
						type="info"
						:message="
							sprintf(
								$i18n.settings.notices.account_setup_tracking,
								'&#60;head&#62;',
								$vars.urls.statistics
							)
						"
						v-else-if="showTrackingNotice"
					/>
				</div>
			</div>
			<google-account></google-account>
			<tracking-id
				@validation="onValidation"
				:error="showError"
			></tracking-id>
		</div>
		<box-footer tab="tracking" @formSubmit="saveSettings"></box-footer>
	</div>
</template>

<script>
import TrackingId from './tracking/tracking-id'
import BoxHeader from './../components/box-header'
import BoxFooter from './../components/box-footer'
import SuiNotice from '@/components/sui/sui-notice'
import GoogleAccount from './tracking/google-account'

export default {
	name: 'Tracking',

	components: {
		SuiNotice,
		BoxHeader,
		BoxFooter,
		TrackingId,
		GoogleAccount,
	},

	data() {
		return {
			valid: true,
			showError: false,
			initialAccount: this.getOption('account_id', 'google', ''),
		}
	},

	computed: {
		/**
		 * Check if account id is changed from initial state.
		 *
		 * @since 3.2.4
		 */
		accountChanged() {
			let changed = false
			let account = this.getOption('account_id', 'google', '')

			// Only when not empty.
			if ('' !== account) {
				changed = this.initialAccount !== account
				this.initialAccount = account
			}

			return changed
		},

		/**
		 * Get the form submit message.
		 *
		 * If the Google account is changed, then show the
		 * account setup message.
		 *
		 * @since 3.2.4
		 */
		submitMessageText() {
			if (this.accountChanged) {
				return this.sprintf(
					this.$i18n.settings.notices.account_setup,
					this.$vars.urls.statistics
				)
			} else {
				return this.$i18n.settings.notices.changes_saved
			}
		},

		/**
		 * Check if we can show the connection notice.
		 *
		 * When everything is setup, appreciate the user.
		 *
		 * @since 3.2.7
		 */
		showAccountTrackingNotice() {
			return this.showAccountNotice && this.showTrackingNotice
		},

		/**
		 * Check if Google account is connected.
		 *
		 * @since 3.2.7
		 */
		showAccountNotice() {
			let account = this.getOption('account_id', 'google', '')
			let loggedIn = this.$store.state.helpers.google.logged_in

			return loggedIn && '' !== account
		},

		/**
		 * Check if tracking ID is setup.
		 *
		 * @since 3.2.7
		 */
		showTrackingNotice() {
			let trackId = this.getOption('code', 'tracking', '')
			let autoTrack = this.getOption('auto_track', 'google')
			let autoTrackId = this.getOption('auto_track', 'misc', '')

			return '' !== trackId || (autoTrack && '' !== autoTrackId)
		},
	},

	methods: {
		/**
		 * Save settings values using API.
		 *
		 * @param {string} tab Current tab.
		 *
		 * @since 3.2.4
		 */
		saveSettings(tab) {
			if ('tracking' === tab) {
				if (this.valid) {
					this.showError = false

					this.$emit('saveSettings', {
						message: this.submitMessageText,
					})
				} else {
					this.showError = true
				}
			}
		},

		/**
		 * On tracking code validation process.
		 *
		 * @param {object} data Custom data.
		 *
		 * @since 3.2.4
		 */
		onValidation(data) {
			this.valid = data.valid

			if (this.valid) {
				this.showError = false
			}
		},
	},
}
</script>
