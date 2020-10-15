<template>
	<div :id="`${modal}-google-tracking`" class="sui-modal-slide" data-modal-size="md">
		<div class="sui-box">
			<div class="sui-box-header sui-flatten sui-content-center sui-spacing-top--60">
				<figure v-if="!$vars.whitelabel.hide_branding" class="sui-box-banner" aria-hidden="true">
					<image-tag src="onboarding/tracking.png" :alt="$i18n.onboarding.labels.add_tracking_id" />
				</figure>
				<button class="sui-button-icon sui-button-float--right" @click="$emit('dismiss')">
					<i class="sui-icon-close sui-md" aria-hidden="true"></i>
					<span class="sui-screen-reader-text">{{ $i18n.dialogs.close }}</span>
				</button>
				<button
					data-modal-replace="beehive-onboarding-setup-account"
					data-modal-close-focus="beehive-wrap"
					data-modal-replace-mask="false"
					class="sui-button-icon sui-button-float--left"
				>
					<i class="sui-icon-chevron-left sui-md" aria-hidden="true"></i>
					<span class="sui-screen-reader-text">{{ $i18n.dialogs.go_back }}</span>
				</button>
				<h3 class="sui-box-title sui-lg">{{ $i18n.onboarding.labels.add_tracking_id }}</h3>
				<p
					class="sui-description"
					v-html="sprintf( $i18n.onboarding.descriptions.tracking_id, 'https://support.google.com/analytics/answer/2763052?hl=en' )"
				></p>
			</div>
			<div class="sui-box-body">
				<div :class="{ 'sui-form-field-error': showError }" class="sui-form-field">
					<label for="beehive-onboarding-tracking-code" class="sui-label">{{ inputLabel }}</label>
					<tracking-id
						id="beehive-onboarding-tracking-code"
						v-model="trackingId"
						context="onboarding"
						@validation="handleValidation"
					/>
					<span v-if="showError" class="sui-error-message">{{ $i18n.settings.errors.tracking_id }}</span>
				</div>
			</div>
			<div class="sui-box-footer sui-flatten sui-content-center sui-spacing-bottom--50">
				<button
					role="button"
					class="sui-button"
					:disabled="disableContinue"
					@click="saveCode"
				>{{ $i18n.onboarding.buttons.save_code }}</button>
			</div>
		</div>
	</div>
</template>

<script>
import ImageTag from '@/components/elements/image-tag'
import TrackingId from '@/modules/settings/components/settings/fields/tracking-id'

export default {
	name: 'TrackingForm',

	props: {
		next: {
			type: String,
			default: 'slide-admin-tracking'
		},
		modal: {
			type: String,
			required: true
		}
	},

	components: { TrackingId, ImageTag },

	data() {
		return {
			valid: true,
			showError: false
		}
	},

	computed: {
		/**
		 * Computed model object to get tracking ID.
		 *
		 * @since 3.2.4
		 *
		 * @returns {string}
		 */
		trackingId: {
			get() {
				return this.getOption('code', 'tracking', '')
			},
			set(value) {
				this.setOption('code', 'tracking', value)
			}
		},

		/**
		 * Get the input label text.
		 *
		 * @since 3.2.4
		 *
		 * @returns {string}
		 */
		inputLabel() {
			if (this.isNetwork()) {
				return this.$i18n.onboarding.labels.network_tracking_id
			} else {
				return this.$i18n.onboarding.labels.tracking_id
			}
		},

		/**
		 * Check if we need to disable continue.
		 *
		 * @since 3.2.4
		 *
		 * @returns {boolean}
		 */
		disableContinue() {
			return !this.trackingId
		}
	},

	methods: {
		/**
		 * Handle the validation event from the component.
		 *
		 * @param {object} data Custom data.
		 *
		 * @since 3.2.4
		 */
		handleValidation(data) {
			if ('onboarding' === data.context) {
				this.valid = data.valid

				if (data.valid) {
					this.showError = false
				}
			}
		},

		/**
		 * Save the code or show the validation error.
		 *
		 * @since 3.2.4
		 */
		saveCode() {
			if (this.valid) {
				this.showError = false
				this.slideNext()
			} else {
				this.showError = true
			}
		},

		/**
		 * Slide to next slide.
		 *
		 * @since 3.2.4
		 */
		slideNext() {
			SUI.slideModal(this.next, null, 'next')
		}
	}
}
</script>
