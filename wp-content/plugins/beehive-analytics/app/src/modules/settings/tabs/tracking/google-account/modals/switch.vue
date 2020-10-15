<template>
	<div class="sui-modal sui-modal-sm">
		<div
			role="dialog"
			id="beehive-google-switch-confirm"
			class="sui-modal-content sui-content-fade-out"
			aria-modal="true"
			aria-labelledby="beehive-google-switch-confirm-title"
			aria-describedby="beehive-google-switch-confirm-description"
		>
			<div class="sui-box">
				<div class="sui-box-header sui-flatten sui-content-center sui-spacing-top--60">
					<button class="sui-button-icon sui-button-float--right" data-modal-close>
						<i class="sui-icon-close sui-md" aria-hidden="true"></i>
						<span class="sui-screen-reader-text">{{ $i18n.dialogs.close }}</span>
					</button>
					<h3
						id="beehive-google-switch-confirm-title"
						class="sui-box-title sui-lg"
					>{{ $i18n.settings.labels.switch_profile }}</h3>
					<p id="beehive-google-switch-confirm-description" class="sui-description">
						{{ $i18n.settings.descriptions.switch_one }}
						<br />
						{{ $i18n.settings.descriptions.switch_second }}
					</p>
				</div>
				<div class="sui-box-footer sui-flatten sui-content-center">
					<button class="sui-button sui-button-ghost" data-modal-close>{{ $i18n.dialogs.cancel }}</button>
					<button
						@click="switchAccount"
						:class="buttonClass"
						type="button"
						class="sui-button"
						aria-live="polite"
					>
						<span class="sui-button-text-default">
							<i class="sui-icon-logout" aria-hidden="true"></i>
							{{ $i18n.settings.labels.switch_profile }}
						</span>
						<span class="sui-button-text-onload">
							<i class="sui-icon-loader sui-loading" aria-hidden="true"></i>
							{{ $i18n.settings.buttons.logging_out }}
						</span>
					</button>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import { RestGet } from '@/helpers/api'

export default {
	name: 'SwitchModal',

	data() {
		return {
			processing: false
		}
	},

	mounted() {
		SUI.modalDialog()
	},

	computed: {
		buttonClass: function() {
			return {
				'sui-button-ghost': !this.processing,
				'sui-button-onload-text': this.processing
			}
		}
	},

	methods: {
		switchAccount() {
			this.processing = true

			RestGet({
				path: 'auth/logout',
				params: {
					network: this.isNetwork() ? 1 : 0
				}
			}).then(response => {
				this.$root.$emit('googleLoggedOut')

				this.processing = false

				this.closeModal()

				if (response.success) {
					this.$root.$emit('showTopNotice', {
						message: this.$i18n.settings.notices.logged_out
					})
				}
			})
		},

		closeModal() {
			SUI.closeModal()

			// Temporary fix to remove non scrollable class from the html.
			document.body.parentNode.classList.remove('sui-has-modal')
		}
	}
}
</script>
