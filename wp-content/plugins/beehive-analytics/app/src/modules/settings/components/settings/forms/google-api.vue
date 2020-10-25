<template>
	<fragment>
		<div class="sui-form-field">
			<span
				class="sui-description"
				v-html="sprintf( $i18n.settings.descriptions.google_setup, 'https://premium.wpmudev.org/docs/wpmu-dev-plugins/beehive/#set-up-api-project' )"
			></span>
		</div>
		<div :class="{ 'sui-form-field-error': errors.includes( 'clientId' ) }" class="sui-form-field">
			<label
				:for="`google-${context}-client-id`"
				:id="`google-${context}-client-id-label`"
				class="sui-label"
			>{{ $i18n.settings.labels.client_id }}</label>
			<input
				v-model="clientId"
				type="text"
				:id="`google-${context}-client-id`"
				class="sui-form-control"
				:aria-labelledby="`google-${context}-client-id-label`"
				:aria-describedby="`google-${context}-client-id-error`"
				:placeholder="$i18n.settings.placeholders.client_id"
			/>
			<span
				v-if="errors.includes( 'clientId' )"
				:id="`google-${context}-client-id-error`"
				class="sui-error-message"
			>{{ $i18n.settings.errors.client_id }}</span>
		</div>

		<div
			:class="{ 'sui-form-field-error': errors.includes( 'clientSecret' ) }"
			class="sui-form-field"
		>
			<label
				:for="`google-${context}-client-secret`"
				:id="`google-${context}-client-secret-label`"
				class="sui-label"
			>{{ $i18n.settings.errors.client_secret }}</label>
			<input
				v-model="clientSecret"
				type="text"
				:id="`google-${context}-client-secret`"
				class="sui-form-control"
				:aria-labelledby="`google-${context}-client-secret-label`"
				:aria-describedby="`google-${context}-client-secret-error`"
				:placeholder="$i18n.settings.placeholders.client_secret"
			/>
			<span
				v-if="errors.includes( 'clientSecret' )"
				:id="`google-${context}-client-secret-error`"
				class="sui-error-message"
			>{{ $i18n.settings.errors.client_secret }}</span>
		</div>

		<div class="sui-form-field">
			<button
				@click="authorize"
				:class="{ 'sui-button-onload-text': processing }"
				type="button"
				class="sui-button sui-button-blue"
				aria-live="polite"
			>
				<span class="sui-button-text-default">{{ $i18n.settings.buttons.authorize }}</span>
				<span class="sui-button-text-onload">
					<i class="sui-icon-loader sui-loading" aria-hidden="true"></i>
					{{ $i18n.settings.buttons.processing }}
				</span>
			</button>
		</div>
	</fragment>
</template>

<script>
import { RestGet } from '@/helpers/api'

export default {
	name: 'GoogleApi',

	props: {
		context: {
			type: String,
			default: 'settings'
		},
		page: {
			type: String,
			default: 'settings'
		}
	},

	data() {
		return {
			errors: [],
			processing: false,
			clientId: this.getOption('client_id', 'google', ''),
			clientSecret: this.getOption('client_secret', 'google', '')
		}
	},

	methods: {
		validateForm: function() {
			this.errors = []

			if (!this.clientId) {
				this.errors.push('clientId')
			}

			if (!this.clientSecret) {
				this.errors.push('clientSecret')
			}
		},

		authorize() {
			this.validateForm()

			if (this.errors.length === 0) {
				this.processing = true
				RestGet({
					path: 'auth/auth-url',
					params: {
						client_id: this.clientId,
						client_secret: this.clientSecret,
						network: this.isNetwork() ? 1 : 0,
						context: this.page,
						modal: this.context === 'onboarding' ? 1 : 0
					}
				}).then(response => {
					if (response.success && response.data.url) {
						window.location.href = response.data.url
					} else {
						this.processing = false
						this.processError()
					}
				})
			}
		},

		showError() {
			this.$root.$emit('showTopNotice', {
				type: 'error',
				dismiss: true,
				message: this.sprintf(
					this.$i18n.settings.notices.auth_failed,
					'https://premium.wpmudev.org/get-support/'
				)
			})
		},

		processError() {
			this.$root.$emit('googleConnectProcessed', {
				type: 'api',
				success: false,
				context: this.context,
				page: this.page
			})
		}
	}
}
</script>
