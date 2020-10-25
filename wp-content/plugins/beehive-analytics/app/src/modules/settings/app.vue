<template>
	<!-- Open sui-wrap -->
	<div class="sui-wrap" id="beehive-wrap">
		<sui-header :title="$i18n.settings.titles.settings">
			<template v-slot:right>
				<!-- Button to clear the cached data -->
				<refresh-button></refresh-button>
			</template>
		</sui-header>

		<div class="sui-row-with-sidenav">
			<div class="sui-sidenav">
				<ul class="sui-vertical-tabs sui-sidenav-hide-md">
					<router-link class="sui-vertical-tab" tag="li" to="/tracking">
						<a>{{ $i18n.settings.menus.tracking }}</a>
					</router-link>
					<router-link class="sui-vertical-tab" tag="li" to="/general" exact>
						<a>{{ $i18n.settings.menus.general }}</a>
					</router-link>
					<router-link v-if="showPermissions" class="sui-vertical-tab" tag="li" to="/permissions">
						<a>{{ $i18n.settings.menus.permissions }}</a>
					</router-link>
				</ul>
			</div>
			<router-view @saveSettings="saveSettings"></router-view>
		</div>

		<sui-footer></sui-footer>

		<!-- Onboarding start -->
		<onboarding-tracking v-if="showOnboarding"></onboarding-tracking>
		<onboarding-account v-if="showOnboarding"></onboarding-account>
		<!-- Onboarding end -->
	</div>
	<!-- Close sui-wrap -->
</template>

<script>
import SuiHeader from '@/components/sui/sui-header'
import SuiFooter from '@/components/sui/sui-footer'
import RefreshButton from './components/refresh-button'
import OnboardingAccount from '@/components/elements/modals/onboarding/onboarding-account'
import OnboardingTracking from '@/components/elements/modals/onboarding/onboarding-tracking'

export default {
	name: 'App',

	components: {
		SuiHeader,
		SuiFooter,
		RefreshButton,
		OnboardingTracking,
		OnboardingAccount
	},

	created() {
		// On Google login.
		this.$root.$on('googleLoggedIn', () => {
			this.$store.dispatch('helpers/updateGoogleLogin', {
				reInit: true,
				status: true
			})

			// Update profiles.
			this.$store.dispatch('helpers/updateGoogleProfiles', {
				reInit: true // Re load settings.
			})
		})

		// On Google logout.
		this.$root.$on('googleLoggedOut', () => {
			this.$store.dispatch('helpers/updateGoogleLogin', {
				reInit: true,
				status: false
			})
		})
	},

	mounted() {
		// Update API status.
		if (this.$store.state.helpers.google.logged_in) {
			this.$store.dispatch('helpers/updateGoogleApi', {})
		}
	},

	computed: {
		/**
		 * Check if we can show onboarding modal.
		 *
		 * @since 3.2.4
		 *
		 * @returns {boolean}
		 */
		showOnboarding() {
			return !this.getOption('onboarding_done', 'misc')
		},

		/**
		 * Check if we can show the permissions tab.
		 *
		 * If statistics and permissions settings are not allowed
		 * by network admin on multisite, hide permissions tab.
		 *
		 * @since 3.2.4
		 * @since 3.2.5 Added settings permissions.
		 *
		 * @returns {boolean}
		 */
		showPermissions() {
			// Only on multisite.
			if (this.isNetworkWide() && this.isSubsite()) {
				return (
					this.getOption(
						'overwrite_cap',
						'permissions',
						false,
						true
					) ||
					this.getOption(
						'overwrite_settings_cap',
						'permissions',
						false,
						true
					)
				)
			} else {
				return true
			}
		}
	},

	methods: {
		/**
		 * Save settings values using API.
		 *
		 * @param {object} data Custom data.
		 *
		 * @since 3.2.4
		 */
		async saveSettings(data) {
			// Save settings.
			let success = await this.saveOptions()

			if (success) {
				this.$root.$emit('showTopNotice', {
					message:
						data.message ||
						this.$i18n.settings.notices.changes_saved
				})
			} else {
				this.$root.$emit('showTopNotice', {
					dimiss: true,
					type: 'error',
					message: this.$i18n.settings.notices.changes_failed
				})
			}
		}
	}
}
</script>

<style lang="scss">
@import 'styles/main';
</style>
