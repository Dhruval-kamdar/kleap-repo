<template>
	<div class="sui-wrap" id="beehive-wrap">
		<sui-header :title="$i18n.dashboard.titles.dashboard">
			<template v-slot:right>
				<sui-calendar-range
					id="beehive-stats-datepicker"
					:periods="$vars.dates.periods"
					:start-date="$vars.dates.start_date"
					:end-date="$vars.dates.end_date"
					:selected-label="$vars.dates.selected_label"
					@periodChange="changePeriod"
				/>
			</template>
		</sui-header>
		<summary-widget :stats="stats.summary" :loading="loading" />
		<div class="sui-row">
			<div class="sui-col-md-6">
				<statistics-widget :stats="stats.list" :loading="loading" />
			</div>
			<div class="sui-col-md-6">
				<coming-soon-widget />
			</div>
		</div>
		<sui-footer></sui-footer>

		<!-- Onboarding start -->
		<onboarding-tracking v-if="showOnboarding"></onboarding-tracking>
		<onboarding-account v-if="showOnboarding"></onboarding-account>
		<!-- Onboarding end -->
		<!-- Welcome modal -->
		<welcome-modal v-if="showWelcome"></welcome-modal>
		<!-- welcome modal end -->
	</div>
</template>

<script>
import { RestGet } from '@/helpers/api'
import SuiHeader from '@/components/sui/sui-header'
import SuiFooter from '@/components/sui/sui-footer'
import SummaryWidget from './widgets/summary-widget'
import StatisticsWidget from './widgets/statistics-widget'
import ComingSoonWidget from './widgets/coming-soon-widget'
import SuiCalendarRange from '@/components/sui/sui-calendar-range'
import WelcomeModal from '@/components/elements/modals/welcome/welcome-modal'
import OnboardingAccount from '@/components/elements/modals/onboarding/onboarding-account'
import OnboardingTracking from '@/components/elements/modals/onboarding/onboarding-tracking'

export default {
	name: 'App',

	components: {
		SuiHeader,
		SuiFooter,
		WelcomeModal,
		SummaryWidget,
		StatisticsWidget,
		SuiCalendarRange,
		ComingSoonWidget,
		OnboardingAccount,
		OnboardingTracking
	},

	data() {
		return {
			loading: false,
			periods: [],
			dateStart: this.$vars.dates.start_date,
			dateEnd: this.$vars.dates.end_date,
			stats: {
				summary: {
					pageviews: {},
					page: {},
					searchEngine: {},
					medium: {},
					newUsers: {}
				},
				list: {}
			}
		}
	},

	mounted() {
		this.getSummary()
	},

	created() {
		// On Google login.
		this.$root.$on('googleLoggedIn', () => {
			this.setOption('google_auth_redirect_success', 'misc', 0)

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

		// On onboarding complete.
		this.$root.$on('onboardingComplete', success => {
			// Show account setup notice if all ok.
			if ('' !== this.getOption('account_id', 'google', '') && success) {
				this.$root.$emit('showTopNotice', {
					dismiss: true,
					message: this.sprintf(
						this.$i18n.dashboard.notices.account_setup,
						this.$vars.urls.statistics
					)
				})
			}

			// Update the stats.
			this.getSummary()
		})
	},

	computed: {
		/**
		 * Check if we can get the stats.
		 *
		 * @since 3.2.4
		 *
		 * @returns {boolean}
		 */
		canGetStats() {
			return this.$moduleVars.can_get_stats
		},

		/**
		 * Check if we current user is logged in with Google.
		 *
		 * @since 3.2.4
		 *
		 * @returns {boolean}
		 */
		isLoggedIn() {
			return this.$store.state.helpers.google.logged_in
		},

		/**
		 * Check if we can show the onboarding.
		 *
		 * @since 3.2.4
		 *
		 * @returns {boolean}
		 */
		showOnboarding() {
			return !this.getOption('onboarding_done', 'misc')
		},

		/**
		 * Check if we can show welcome modal.
		 *
		 * @since 3.2.5
		 *
		 * @returns {boolean}
		 */
		showWelcome() {
			// Do not conflict with onboarding.
			if (this.showOnboarding) {
				return false
			}

			if (
				this.isMultisite() &&
				this.isNetworkWide() &&
				this.isNetwork()
			) {
				return this.getOption('show_welcome', 'misc', false, true)
			} else if (!this.isMultisite() || !this.isNetworkWide()) {
				return this.getOption('show_welcome', 'misc')
			} else {
				return false
			}
		}
	},

	methods: {
		/**
		 * Get the summary data from the API.
		 *
		 * @since 3.2.4
		 */
		getSummary() {
			if (!this.canGetStats && !this.isLoggedIn) {
				return
			}

			this.loading = true

			RestGet({
				path: 'stats/summary',
				params: {
					from: this.dateStart,
					to: this.dateEnd,
					network: this.isNetwork() ? 1 : 0
				}
			}).then(response => {
				if (response.success && response.data && response.data.stats) {
					if (response.data.stats.summary) {
						this.stats.list = response.data.stats.summary
						this.setupSummary(response.data.stats)
					}
				}

				this.loading = false
			})
		},

		/**
		 * Setup the summary stats based on the response.
		 *
		 * @since 3.2.4
		 */
		setupSummary(stats) {
			this.stats.summary = {
				pageviews: stats.summary.pageviews,
				page: stats.page,
				searchEngine: stats.search_engine,
				medium: stats.medium,
				newUsers: stats.summary.new_users
			}
		},

		/**
		 * Change the period and update the stats.
		 *
		 * @since 3.2.4
		 */
		changePeriod(data) {
			// Set from and to dates.
			this.dateStart = data.startDate
			this.dateEnd = data.endDate

			// Make the API request.
			this.getSummary()
		}
	}
}
</script>

<style lang="scss">
@import 'styles/main';
</style>
