<template>
	<div class="sui-modal sui-modal-md">
		<div
			role="dialog"
			:id="modal"
			class="sui-modal-content sui-content-fade-out"
			aria-live="polite"
			aria-modal="true"
		>
			<!-- Tracking form modal -->
			<tracking-form :modal="modal" :next="secondSlide" @dismiss="dismissOnboarding" />

			<!-- ProSites settings modal -->
			<pro-sites
				v-if="isProSitesReady"
				:modal="modal"
				:next="`${modal}-admin-tracking`"
				:prev="`${modal}-google-tracking`"
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
import TrackingForm from './slides/tracking-form'
import AdminTracking from './slides/admin-tracking'

export default {
	name: 'OnboardingTracking',

	components: { ProSites, Finishing, TrackingForm, AdminTracking },

	data() {
		return {
			modal: 'beehive-onboarding-setup-tracking'
		}
	},

	mounted() {
		SUI.modalDialog()
	},

	computed: {
		secondSlide() {
			return this.isProSitesReady
				? this.modal + '-prosites'
				: this.modal + '-admin-tracking'
		},

		previousOfThird() {
			return this.isProSitesReady
				? this.modal + '-prosites'
				: this.modal + '-google-tracking'
		},

		isLoggedIn() {
			return this.$store.state.helpers.google.logged_in
		},

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
