<template>
	<div class="sui-modal sui-modal-md">
		<div
			role="dialog"
			class="sui-modal-content sui-content-fade-in"
			aria-modal="true"
			:id="modal"
			:aria-labelledby="`${modal}-title`"
			:aria-describedby="`${modal}-desc`"
		>
			<div class="sui-box">
				<div class="sui-box-header sui-flatten sui-content-center sui-spacing-top--60">
					<figure class="sui-box-banner" aria-hidden="true">
						<image-tag src="welcome/welcome-3.2.5.png" :alt="$i18n.onboarding.labels.config_by_role" />
					</figure>

					<button
						class="sui-button-icon sui-button-float--right"
						@click="dismissModal"
						:id="`${modal}-close`"
					>
						<i class="sui-icon-close sui-md" aria-hidden="true"></i>
						<span class="sui-screen-reader-text">{{ $i18n.dialogs.close }}</span>
					</button>

					<h3
						:id="`${modal}-title`"
						class="sui-box-title sui-lg"
					>{{ $i18n.onboarding.labels.give_settings_access }}</h3>

					<p
						:id="`${modal}-desc`"
						class="sui-description"
					>{{ $i18n.onboarding.descriptions.give_settings_access }}</p>
				</div>

				<div class="sui-box-body">
					<div class="sui-form-field">
						<h6
							class="sui-settings-label sui-dark beehive-default-size"
						>{{ $i18n.onboarding.labels.config_by_role }}</h6>
						<p
							class="sui-description"
							ref="settingsLink"
							v-html="$i18n.onboarding.descriptions.config_by_role"
						></p>
					</div>
				</div>

				<div class="sui-box-footer sui-flatten sui-content-center sui-spacing-bottom--50">
					<button
						type="button"
						class="sui-button"
						@click="dismissModal"
					>{{ $i18n.onboarding.buttons.got_it }}</button>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import ImageTag from '@/components/elements/image-tag'

export default {
	name: 'Welcome325',

	components: { ImageTag },

	data() {
		return {
			modal: 'beehive-welcome-325-modal',
			closeFocus: 'beehive-wrap'
		}
	},

	mounted() {
		// Initialize modal.
		SUI.modalDialog()

		// Open modal.
		this.openModal()
	},

	methods: {
		/**
		 * Add the selected user to the list.
		 *
		 * We will not update the changes in db.
		 *
		 * @since 3.2.5
		 *
		 * @return {void}
		 */
		dismissModal() {
			// Close the modal after processing.
			this.closeModal()

			// Dismiss flag.
			this.setOption('show_welcome', 'misc', 0)

			//
			this.saveOptions()
		},

		/**
		 * Open the current modal.
		 *
		 * Open the SUI initialized modal using the modal ID.
		 *
		 * @since 3.2.5
		 */
		openModal() {
			SUI.openModal(this.modal, this.closeFocus, this.modal + '-close')
		},

		/**
		 * Close the current modal.
		 *
		 * SUI will close the active modal.
		 *
		 * @since 3.2.5
		 */
		closeModal() {
			SUI.closeModal()
		}
	}
}
</script>
