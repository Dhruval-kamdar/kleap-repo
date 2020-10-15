<template>
	<div
		:id="`${modal}-google-auth`"
		class="sui-modal-slide sui-active sui-loaded"
		data-modal-size="lg"
	>
		<div class="sui-box">
			<div class="sui-box-header sui-flatten sui-content-center sui-spacing-top--60">
				<figure v-if="!$vars.whitelabel.hide_branding" class="sui-box-banner" aria-hidden="true">
					<image-tag src="onboarding/welcome.png" :alt="$i18n.onboarding.labels.auth_form_alt" />
				</figure>
				<button class="sui-button-icon sui-button-float--right" @click="$emit('dismiss')">
					<i class="sui-icon-close sui-md" aria-hidden="true"></i>
					<span class="sui-screen-reader-text">{{ $i18n.dialogs.close }}</span>
				</button>
				<h3 class="sui-box-title sui-lg">{{ sprintf( $i18n.onboarding.labels.welcome, pluginName ) }}</h3>
				<p
					class="sui-description"
					v-if="isNetwork()"
				>{{ $i18n.onboarding.descriptions.welcome_network }}</p>
				<p class="sui-description" v-else>{{ $i18n.onboarding.descriptions.welcome_single }}</p>
			</div>
			<div class="sui-box-body" :class="{ 'sui-content-center': showSimpleConnect }">
				<sui-notice
					v-if="showSimpleError"
					type="error"
					:message="sprintf( $i18n.onboarding.notices.google_connect_failed, 'https://premium.wpmudev.org/docs/wpmu-dev-plugins/beehive/#set-up-api-project', 'https://premium.wpmudev.org/get-support/' )"
				/>
				<sui-notice
					v-if="showApiError"
					type="error"
					:message="sprintf( $i18n.onboarding.notices.google_api_failed, 'https://premium.wpmudev.org/docs/wpmu-dev-plugins/beehive/#set-up-api-project', 'https://premium.wpmudev.org/get-support/' )"
				/>
				<div v-if="showSimpleConnect" class="beehive-google-setup-connect">
					<div class="sui-form-field">
						<a
							type="button"
							:href="$moduleVars.google.login_url"
							class="sui-button sui-button-lg beehive-connect-google-btn"
						>
							<i class="sui-icon-google-connect" aria-hidden="true"></i>
							{{ $i18n.settings.labels.connect_google }}
						</a>
						<span class="sui-description sui-description-sm">
							{{ $i18n.onboarding.labels.why_connect }}
							<span
								class="beehive-icon-tooltip sui-tooltip sui-tooltip-right sui-tooltip-constrained"
								:data-tooltip="$i18n.onboarding.descriptions.why_connect"
							>
								<i class="sui-icon-info" aria-hidden="true"></i>
							</span>
						</span>
					</div>
				</div>
				<div v-else class="sui-side-tabs sui-tabs">
					<div data-tabs>
						<div class="active">{{ $i18n.settings.labels.connect_google }}</div>
						<div>{{ $i18n.settings.labels.google_api }}</div>
					</div>
					<div data-panes>
						<div class="sui-tab-boxed beehive-google-setup-connect active">
							<google-connect context="onboarding"></google-connect>
						</div>
						<div class="sui-tab-boxed">
							<google-api context="onboarding" :page="page"></google-api>
						</div>
					</div>
				</div>
			</div>
			<div class="sui-box-footer sui-flatten sui-content-center sui-spacing-bottom--50">
				<span class="beehive-modal-forward-link sui-block-content-center">
					<a
						href="#"
						@click.prevent
						data-modal-replace="beehive-onboarding-setup-tracking"
						data-modal-close-focus="beehive-wrap"
						data-modal-replace-mask="false"
					>{{ $i18n.onboarding.labels.google_tracking_id }}</a>
				</span>
			</div>
		</div>
	</div>
</template>

<script>
import SuiNotice from '@/components/sui/sui-notice'
import ImageTag from '@/components/elements/image-tag'
import GoogleApi from '@/modules/settings/components/settings/forms/google-api'
import GoogleConnect from '@/modules/settings/components/settings/forms/google-connect'

export default {
	name: 'AuthForm',

	components: { SuiNotice, GoogleApi, GoogleConnect, ImageTag },

	props: {
		page: {
			type: String,
			default: 'settings'
		},
		next: {
			type: String,
			required: true
		},
		modal: {
			type: String,
			required: true
		}
	},

	data() {
		return {
			error: false,
			errorType: 'connect',
			pluginName: this.$vars.plugin.name
		}
	},

	mounted() {
		SUI.suiTabs()
	},

	created() {
		// On form process.
		this.$root.$on('googleConnectProcessed', data => {
			if ('onboarding' === data.context) {
				this.error = !data.success
				this.errorType = data.type
			}
		})
	},

	computed: {
		/**
		 * Computed model function to get tracking ID.
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
		 * Check if we need to show simple error message.
		 *
		 * @since 3.2.4
		 *
		 * @returns {boolean}
		 */
		showSimpleError() {
			return this.error && 'simple' === this.errorType
		},

		/**
		 * Check if we need to show API error message.
		 *
		 * @since 3.2.4
		 *
		 * @returns {boolean}
		 */
		showApiError() {
			return this.error && 'api' === this.errorType
		},

		/**
		 * Check if we can show simple connect method.
		 *
		 * @since 3.2.4
		 *
		 * @returns {boolean}
		 */
		showSimpleConnect() {
			if (!this.$moduleVars.google) {
				return false
			}

			let netWorkSetup = this.$moduleVars.google.network_setup
			let netWorkLoggedIn = this.$moduleVars.google.network_logged_in
			let netWorkLoginMethod = this.$moduleVars.google
				.network_login_method

			return (
				this.isMultisite() &&
				this.isSubsite() &&
				netWorkSetup &&
				netWorkLoggedIn &&
				'api' === netWorkLoginMethod
			)
		}
	}
}
</script>
