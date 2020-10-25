<template>
	<div class="sui-form-field">
		<label v-if="label" :for="id" class="sui-label">{{ label }}</label>
		<sui-select2
			:id="id"
			:options="getAccounts"
			v-model="account"
			:placeholder="getPlaceholder"
			:disabled="isEmpty"
		/>
		<span v-if="description" class="sui-description">{{ description }}</span>
		<sui-notice
			v-if="showErrorNotice"
			type="error"
			:message="sprintf( $i18n.settings.notices.no_accounts, 'https://analytics.google.com/analytics/web/' )"
		/>
	</div>
</template>

<script>
import SuiNotice from '@/components/sui/sui-notice'
import SuiSelect2 from '@/components/sui/sui-select2'

export default {
	name: 'GoogleAccounts',

	components: { SuiSelect2, SuiNotice },

	props: {
		id: String,
		label: String,
		description: String,
		context: String,
		showError: {
			type: Boolean,
			default: true
		}
	},

	computed: {
		account: {
			get() {
				return this.getOption('account_id', 'google', '')
			},
			set(value) {
				this.setOption('account_id', 'google', value)
			}
		},

		getAccounts() {
			let options = []

			this.getProfiles.forEach(function(profile) {
				options.push({
					id: profile.id,
					text:
						profile.url +
						' (' +
						profile.name +
						' - ' +
						profile.property +
						')'
				})
			})

			return options
		},

		showErrorNotice() {
			return this.showError && this.isEmpty && this.isLoggedIn
		},

		getPlaceholder() {
			if (this.isEmpty) {
				return this.$i18n.settings.placeholders.no_website
			} else {
				return this.$i18n.settings.placeholders.select_website
			}
		},

		isEmpty() {
			return this.getProfiles.length <= 0
		},

		getProfiles() {
			return this.$store.state.helpers.google.profiles
		},

		isLoggedIn() {
			return this.$store.state.helpers.google.logged_in
		}
	}
}
</script>
