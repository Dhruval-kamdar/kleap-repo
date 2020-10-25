<template>
	<fragment>
		<p
			v-if="isNetwork()"
			class="sui-description"
			style="margin: 0 0 10px;"
		>{{ $i18n.settings.descriptions.connect_google_network }}</p>
		<p
			v-else
			class="sui-description"
			style="margin: 0 0 10px;"
		>{{ $i18n.settings.descriptions.connect_google_single }}</p>

		<p style="margin-top: 0;">
			<a
				type="button"
				:href="$moduleVars.google.login_url"
				target="_blank"
				class="sui-button sui-button-lg beehive-connect-google-btn"
			>
				<i class="sui-icon-google-connect" aria-hidden="true"></i>
				{{ $i18n.settings.labels.connect_google }}
			</a>
		</p>

		<div :class="{ 'sui-form-field-error': errors.includes( 'accessCode' ) }" class="sui-form-field">
			<label
				:for="`google-${context}-access-code`"
				:id="`google-${context}-access-code-label`"
				class="sui-label"
			>{{ $i18n.settings.labels.access_code }}</label>

			<input
				v-model="accessCode"
				type="text"
				:placeholder="$i18n.settings.placeholders.access_code"
				:id="`google-${context}-access-code`"
				class="sui-form-control"
				:aria-labelledby="`google-${context}-access-code-label`"
				:aria-describedby="`google-${context}-access-code-error`"
			/>

			<p
				v-if="errors.includes( 'accessCode' )"
				:id="`google-${context}-access-code-error`"
				class="sui-error-message"
			>{{ $i18n.settings.errors.access_code }}</p>

			<p style="margin: 10px 0 0;">
				<button
					@click="exchangeCode"
					:class="{ 'sui-button-onload-text': processing }"
					type="button"
					class="sui-button sui-button-blue"
					aria-live="polite"
				>
					<span class="sui-button-text-default">{{ $i18n.settings.buttons.authorize }}</span>
					<span class="sui-button-text-onload">
						<i class="sui-icon-loader sui-loading" aria-hidden="true"></i>
						{{ $i18n.settings.buttons.authorizing }}
					</span>
				</button>
			</p>
		</div>
	</fragment>
</template>

<script>
import { RestGet } from '@/helpers/api'

export default {
	name: 'GooogleConnect',

	props: {
		context: {
			type: String,
			default: 'settings'
		}
	},

	data() {
		return {
			errors: [],
			processing: false,
			accessCode: ''
		}
	},

	methods: {
		validateForm: function() {
			this.errors = []

			if (!this.accessCode) {
				this.errors.push('accessCode')
			}
		},

		exchangeCode() {
			this.validateForm()

			if (this.errors.length === 0) {
				this.processing = true
				RestGet({
					path: 'auth/exchange-code',
					params: {
						access_code: this.accessCode,
						client_id: this.$moduleVars.google.client_id,
						network: this.isNetwork() ? 1 : 0
					}
				}).then(response => {
					if (response.success) {
						this.processStatus(true)
					} else {
						this.processStatus(false)
					}
					this.processing = false
				})
			}
		},

		processStatus(success) {
			if (success) {
				this.$root.$emit('googleLoggedIn')
			}
			this.$root.$emit('googleConnectProcessed', {
				type: 'simple',
				success: success,
				context: this.context
			})
		}
	}
}
</script>
