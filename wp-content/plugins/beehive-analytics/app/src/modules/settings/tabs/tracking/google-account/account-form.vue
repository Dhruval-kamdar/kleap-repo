<template>
	<fragment>
		<div v-if="showSimpleConnect" class="beehive-google-setup-connect">
			<div class="sui-form-field">
				<sui-notice
					type="info"
					:message="sprintf( $i18n.settings.notices.google_already_connected, $vars.urls.statistics )"
				></sui-notice>
				<a
					type="button"
					:href="$moduleVars.google.login_url"
					class="sui-button sui-button-lg beehive-connect-google-btn"
				>
					<i class="sui-icon-google-connect" aria-hidden="true"></i>
					{{ $i18n.settings.labels.connect_google }}
				</a>
			</div>
		</div>
		<div v-else class="sui-side-tabs sui-tabs">
			<div data-tabs>
				<div class="active">{{ $i18n.settings.labels.connect_google }}</div>
				<div>{{ $i18n.settings.labels.google_api }}</div>
			</div>
			<div data-panes>
				<div class="sui-tab-boxed beehive-google-setup-connect active">
					<google-connect context="settings"></google-connect>
				</div>
				<div class="sui-tab-boxed">
					<google-api context="settings"></google-api>
				</div>
			</div>
		</div>
	</fragment>
</template>

<script>
import SuiNotice from '@/components/sui/sui-notice'
import GoogleApi from './../../../components/settings/forms/google-api'
import GoogleConnect from './../../../components/settings/forms/google-connect'

export default {
	name: 'AccountForm',

	components: { GoogleConnect, GoogleApi, SuiNotice },

	mounted() {
		SUI.suiTabs()
	},

	created() {
		// On form process.
		this.$root.$on('googleConnectProcessed', data => {
			if ('settings' === data.context && 'simple' === data.type) {
				data.success
					? this.showConnectSuccess()
					: this.showConnectError()
			} else if (
				'settings' === data.context &&
				'api' === data.type &&
				!data.success
			) {
				this.showApiError()
			}
		})
	},

	computed: {
		showSimpleConnect() {
			if (!this.$moduleVars.google) {
				return false
			}

			let netWorkSetup = this.$moduleVars.google.network_setup
			let netWorkLoggedIn = this.$moduleVars.google.network_logged_in
			let netWorkLoginMethod = this.$moduleVars.google
				.network_login_method

			return (
				this.isMultisite() &&
				this.isSubsite() &&
				netWorkSetup &&
				netWorkLoggedIn &&
				'api' === netWorkLoginMethod
			)
		}
	},

	methods: {
		showApiError() {
			this.$root.$emit('showTopNotice', {
				type: 'error',
				dismiss: true,
				message: this.sprintf(
					this.$i18n.settings.notices.google_api_error,
					'https://premium.wpmudev.org/docs/wpmu-dev-plugins/beehive/#set-up-api-project',
					'https://premium.wpmudev.org/get-support/'
				)
			})
		},

		showConnectSuccess() {
			this.$root.$emit('showTopNotice', {
				dismiss: true,
				message: this.$i18n.settings.notices.google_connect_error
			})
		},

		showConnectError() {
			this.$root.$emit('showTopNotice', {
				type: 'error',
				dismiss: true,
				message: this.sprintf(
					this.$i18n.settings.notices.google_connect_success,
					'https://premium.wpmudev.org/docs/wpmu-dev-plugins/beehive/#set-up-api-project',
					'https://premium.wpmudev.org/get-support/'
				)
			})
		}
	}
}
</script>
