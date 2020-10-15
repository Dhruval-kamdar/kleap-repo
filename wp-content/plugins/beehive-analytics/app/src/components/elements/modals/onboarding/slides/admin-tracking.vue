<template>
	<div :id="`${modal}-admin-tracking`" class="sui-modal-slide" data-modal-size="md">
		<div class="sui-box">
			<div class="sui-box-header sui-flatten sui-content-center sui-spacing-top--60">
				<figure v-if="!$vars.whitelabel.hide_branding" class="sui-box-banner" aria-hidden="true">
					<image-tag src="onboarding/setup.png" :alt="$i18n.settings.labels.admin_tracking" />
				</figure>
				<button
					class="sui-button-icon sui-button-float--right onboarding-dismiss"
					@click="$emit('dismiss')"
				>
					<i class="sui-icon-close sui-md" aria-hidden="true"></i>
					<span class="sui-screen-reader-text">{{ $i18n.dialogs.close }}</span>
				</button>
				<button
					class="sui-button-icon sui-button-float--left"
					:data-modal-slide="prev"
					data-modal-slide-intro="back"
				>
					<i class="sui-icon-chevron-left sui-md" aria-hidden="true"></i>
					<span class="sui-screen-reader-text">{{ $i18n.dialogs.go_back }}</span>
				</button>
				<h3 class="sui-box-title sui-lg">{{ $i18n.settings.labels.admin_tracking }}</h3>
				<p class="sui-description">{{ $i18n.settings.descriptions.admin_tracking }}</p>
			</div>
			<div class="sui-box-body sui-box-body-toggle">
				<div class="beehive-onboarding-toggle">
					<label for="beehive-onboarding-admin-tracking" class="sui-toggle">
						<input v-model="trackAdmin" type="checkbox" id="beehive-onboarding-admin-tracking" value="1" />
						<span class="sui-toggle-slider"></span>
					</label>
					<label
						for="beehive-onboarding-admin-tracking"
						class="sui-toggle-label"
					>{{ $i18n.settings.labels.admin_tracking_enable }}</label>
				</div>
			</div>
			<div class="sui-box-footer sui-flatten sui-content-center sui-spacing-bottom--50">
				<button
					:data-modal-slide="next"
					data-modal-slide-intro="next"
					role="button"
					class="sui-button"
					@click="$emit('submitForm')"
				>{{ $i18n.dialogs.continue }}</button>
			</div>
		</div>
	</div>
</template>

<script>
import ImageTag from '@/components/elements/image-tag'

export default {
	name: 'AdminTracking',

	components: { ImageTag },

	props: {
		next: {
			type: String,
			required: true
		},
		prev: {
			type: String,
			required: true
		},
		modal: {
			type: String,
			required: true
		}
	},

	computed: {
		/**
		 * Computed model object to get admin tracking option.
		 *
		 * @since 3.2.4
		 *
		 * @returns {boolean}
		 */
		trackAdmin: {
			get() {
				return this.getOption('track_admin', 'general')
			},
			set(value) {
				this.setOption('track_admin', 'general', value)
			}
		}
	}
}
</script>
