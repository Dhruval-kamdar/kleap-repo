<template>
	<div class="sui-box-settings-row">
		<div class="sui-box-settings-col-1">
			<span class="sui-settings-label">{{ $i18n.settings.labels.account }}</span>
			<span
				v-if="isNetwork()"
				class="sui-description"
			>{{ $i18n.settings.descriptions.account_network }}</span>
			<span
				v-else-if="isSubsite()"
				class="sui-description"
			>{{ $i18n.settings.descriptions.account_subsite }}</span>
			<span v-else class="sui-description">{{ $i18n.settings.descriptions.account_single }}</span>
		</div>
		<div class="sui-box-settings-col-2">
			<sui-notice v-if="!isApiUp" type="error" :message="apiErrorMessage" />
			<account-data v-if="isLoggedIn"></account-data>
			<account-form v-else></account-form>
		</div>
	</div>
</template>

<script>
import SuiNotice from '@/components/sui/sui-notice'
import AccountData from './google-account/account-data'
import AccountForm from './google-account/account-form'

export default {
	name: 'GoogleAccount',

	components: { AccountData, AccountForm, SuiNotice },

	mounted: function() {
		this.showSuccessMessage()
	},

	computed: {
		isLoggedIn() {
			return this.$store.state.helpers.google.logged_in
		},

		isApiUp() {
			return this.$store.state.helpers.googleApi.status
		},

		apiErrorMessage() {
			return this.$store.state.helpers.googleApi.error
		}
	},

	methods: {
		showSuccessMessage() {
			if (this.getOption('google_auth_redirect_success', 'misc')) {
				this.setOption('google_auth_redirect_success', 'misc', 0)
				this.saveOptions()
				this.$root.$emit('showTopNotice', {
					dismiss: true,
					message: this.$i18n.settings.notices.google_account_success
				})
			}
		}
	}
}
</script>
