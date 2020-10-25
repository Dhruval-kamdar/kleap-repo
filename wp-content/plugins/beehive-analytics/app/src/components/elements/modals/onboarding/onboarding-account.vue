<template>
	<div class="sui-modal" :class="modalSizeClass">
		<div
			role="dialog"
			:id="modal"
			class="sui-modal-content sui-content-fade-out"
			aria-live="polite"
			aria-modal="true"
		>
			<!-- Google account form modal -->
			<google-account
				v-if="isLoggedIn"
				:modal="modal"
				:next="secondSlide"
				@dismiss="dismissOnboarding"
			/>
			<google-auth-form
				v-else
				:page="page"
				:modal="modal"
				:next="secondSlide"
				@dismiss="dismissOnboarding"
			/>

			<!-- ProSites settings modal -->
			<pro-sites
				v-if="isProSitesReady"
				:modal="modal"
				:next="`${modal}-admin-tracking`"
				:prev="firstSlide"
				@dismiss="dismissOnboarding"
			/>

			<!-- Admin tracking settings modal -->
			<admin-tracking
				:modal="modal"
				:next="`${modal}-finishing`"
				:prev="previousOfThird"
				@dismiss="dismissOnboarding"
				@submitForm="submitOnboarding"
			/>

			<!-- Finishing modal -->
			<finishing :modal="modal" />
		</div>
	</div>
</template>

<script>
import ProSites from './slides/pro-sites'
import Finishing from './slides/finishing'
import AdminTracking from './slides/admin-tracking'
import GoogleAccount from './slides/google-account'
import GoogleAuthForm from './slides/google-auth-form'

export default {
	name: 'OnboardingAccount',

	components: {
		ProSites,
		Finishing,
		AdminTracking,
		GoogleAccount,
		GoogleAuthForm
	},

	props: {
		page: {
			type: String,
			default: 'settings'
		}
	},

	data() {
		return {
			modal: 'beehive-onboarding-setup-account'
		}
	},

	mounted() {
		// Setup modal.
		SUI.modalDialog()

		// Open modal.
		this.openModal()
	},

	updated() {
		// Reinit modal.
		SUI.modalDialog()

		// Open again.
		this.openModal()
	},

	computed: {
		/**
		 * Get the first slide ID.
		 *
		 * @since 3.2.4
		 *
		 * @returns {string}
		 */
		firstSlide() {
			return this.isLoggedIn
				? this.modal + '-google-account'
				: this.modal + '-google-auth'
		},

		/**
		 * Get the second slide ID.
		 *
		 * @since 3.2.4
		 *
		 * @returns {string}
		 */
		secondSlide() {
			return this.isProSitesReady
				? this.modal + '-prosites'
				: this.modal + '-admin-tracking'
		},

		/**
		 * Get the ID of previous slide of third.
		 *
		 * @since 3.2.4
		 *
		 * @returns {string}
		 */
		previousOfThird() {
			return this.isProSitesReady
				? this.modal + '-prosites'
				: this.firstSlide
		},

		/**
		 * Get the modal size.
		 *
		 * @since 3.2.4
		 *
		 * @returns {string}
		 */
		modalSizeClass() {
			return {
				'sui-modal-md': this.isLoggedIn,
				'sui-modal-lg': !this.isLoggedIn
			}
		},

		/**
		 * Check if current user is logged in with Google.
		 *
		 * @since 3.2.4
		 *
		 * @returns {boolean}
		 */
		isLoggedIn() {
			return this.$store.state.helpers.google.logged_in
		},

		/**
		 * Check if Pro Sites is active.
		 *
		 * @since 3.2.4
		 *
		 * @returns {boolean}
		 */
		isProSitesReady() {
			let levels = this.$moduleVars.ps_levels || {}

			return (
				this.isNetwork() &&
				this.isMultisite() &&
				Object.keys(levels).length > 0
			)
		}
	},

	methods: {
		dismissOnboarding() {
			// Dismiss onboarding.
			this.setOption('onboarding_done', 'misc', 1)

			this.saveOptions()

			this.closeModal()
		},

		async submitOnboarding() {
			// Dismiss onboarding.
			this.setOption('onboarding_done', 'misc', 1)

			// Save settings.
			let success = await this.saveOptions()

			// Emit onboarding complete event.
			this.$root.$emit('onboardingComplete', success)

			// Close modal.
			this.closeModal()
		},

		openModal() {
			let modal = jQuery('#' + this.modal).parent()

			// Open only if not already open.
			if (!modal.hasClass('sui-active')) {
				SUI.openModal(this.modal, 'beehive-wrap')
			}
		},

		closeModal() {
			SUI.closeModal()

			// Temporary fix to remove non scrollable class from the html.
			document.body.parentNode.classList.remove('sui-has-modal')
		}
	}
}
</script>
