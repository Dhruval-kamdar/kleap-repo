<template>
	<div
		:id="`${modal}-google-account`"
		class="sui-modal-slide sui-active sui-loaded"
		data-modal-size="md"
	>
		<div class="sui-box">
			<div class="sui-box-header sui-flatten sui-content-center sui-spacing-top--60">
				<figure v-if="!$vars.whitelabel.hide_branding" class="sui-box-banner" aria-hidden="true">
					<image-tag src="onboarding/setup.png" :alt="$i18n.onboarding.labels.google_account_setup" />
				</figure>
				<button class="sui-button-icon sui-button-float--right" @click="$emit('dismiss')">
					<i class="sui-icon-close sui-md" aria-hidden="true"></i>
					<span class="sui-screen-reader-text">{{ $i18n.dialogs.close }}</span>
				</button>
				<h3 class="sui-box-title sui-lg">{{ $i18n.onboarding.labels.google_account_setup }}</h3>
				<p class="sui-description">{{ $i18n.onboarding.descriptions.google_connect_success }}</p>
			</div>
			<div class="sui-box-body">
				<div class="beehive-box-border-bottom beehive-onboarding-google-profile-section">
					<google-accounts
						id="beehive-onboarding-google-account-id"
						:label="$i18n.onboarding.labels.choose_account"
						:show-error="false"
					/>
					<div v-if="showAutoTrack" class="sui-form-field">
						<label for="beehive-onboarding-google-auto-track" class="sui-checkbox sui-checkbox-sm">
							<input
								v-model="autoTrack"
								type="checkbox"
								id="beehive-onboarding-google-auto-track"
								value="1"
							/>
							<span aria-hidden="true"></span>
							<span>
								{{ $i18n.settings.labels.auto_detect_id }}
								<span
									class="sui-tooltip sui-tooltip-constrained"
									:data-tooltip="$i18n.settings.tooltips.tracking_id"
								>
									<i class="sui-icon-info" aria-hidden="true"></i>
								</span>
							</span>
						</label>
					</div>
				</div>
				<div class="sui-form-field">
					<span class="sui-label">{{ $i18n.onboarding.labels.display_statistics }}</span>
					<label
						v-for="(title, role) in availableRoles"
						:for="`beehive-onboarding-permissions-roles-${role}`"
						:key="role"
						class="sui-checkbox sui-checkbox-stacked"
					>
						<input
							v-model="roles"
							type="checkbox"
							:id="`beehive-onboarding-permissions-roles-${role}`"
							class="google-modal-roles"
							:value="role"
						/>
						<span aria-hidden="true"></span>
						<span>{{ title }}</span>
					</label>
				</div>
			</div>
			<div class="sui-box-footer sui-flatten sui-content-center sui-spacing-bottom--50">
				<button
					role="button"
					class="sui-button"
					:data-modal-slide="next"
					data-modal-slide-intro="next"
				>{{ $i18n.dialogs.continue }}</button>
			</div>
		</div>
	</div>
</template>

<script>
import ImageTag from '@/components/elements/image-tag'
import GoogleAccounts from '@/modules/settings/components/settings/fields/google-accounts'

export default {
	name: 'GoogleAccount',

	props: {
		next: {
			type: String,
			required: true
		},
		modal: {
			type: String,
			required: true
		}
	},

	components: { GoogleAccounts, ImageTag },

	data() {
		return {
			availableRoles: this.$moduleVars.roles,
			emptyAccounts: false
		}
	},

	computed: {
		/**
		 * Computed model to get the enabled roles.
		 *
		 * @since 3.2.4
		 *
		 * @returns {array}
		 */
		roles: {
			get() {
				return this.getOption('roles', 'permissions', [])
			},
			set(value) {
				this.setOption('roles', 'permissions', value)
			}
		},

		/**
		 * Computed model to get the auto tracking flag.
		 *
		 * @since 3.2.4
		 *
		 * @returns {boolean}
		 */
		autoTrack: {
			get() {
				return this.getOption('auto_track', 'google')
			},
			set(value) {
				this.setOption('auto_track', 'google', value)
			}
		},

		/**
		 * Check if auto tracking code can ne shown.
		 *
		 * @since 3.2.4
		 *
		 * @returns {boolean}
		 */
		showAutoTrack() {
			let autoTrack = this.getOption('auto_track', 'misc')

			return autoTrack && autoTrack !== ''
		}
	}
}
</script>
