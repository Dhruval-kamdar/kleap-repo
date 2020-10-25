<template>
	<div class="sui-box">
		<box-header :title="$i18n.settings.titles.settings"></box-header>
		<div class="sui-box-body">
			<admin-tracking v-if="canShowAdminTracking"></admin-tracking>
			<anonymize-i-p v-if="canShowAnonymizeIP"></anonymize-i-p>
			<advertising></advertising>
			<pro-sites v-if="canShowProSites"></pro-sites>
		</div>
		<box-footer tab="general" @formSubmit="saveSettings"></box-footer>
	</div>
</template>

<script>
import ProSites from './general/pro-sites'
import Advertising from './general/advertising'
import AnonymizeIP from './general/anonymize-ip'
import BoxFooter from './../components/box-footer'
import BoxHeader from './../components/box-header'
import AdminTracking from './general/admin-tracking'

export default {
	name: 'General',

	components: {
		ProSites,
		BoxHeader,
		BoxFooter,
		AnonymizeIP,
		Advertising,
		AdminTracking
	},

	computed: {
		canShowAdminTracking: function() {
			return this.isNetwork() || !this.isMultisite()
		},

		canShowAnonymizeIP: function() {
			if (this.isNetworkWide() && !this.isNetwork()) {
				if (
					this.getOption('anonymize', 'general', false, true) &&
					this.getOption('force_anonymize', 'general', false, true)
				) {
					return false
				}
			}

			return true
		},

		canShowProSites: function() {
			return this.isNetwork() && this.isMultisite()
		}
	},

	methods: {
		saveSettings(tab) {
			if ('general' === tab) {
				this.$emit('saveSettings', {})
			}
		}
	}
}
</script>
