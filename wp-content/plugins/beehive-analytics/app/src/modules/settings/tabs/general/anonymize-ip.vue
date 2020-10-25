<template>
	<div class="sui-box-settings-row">
		<div class="sui-box-settings-col-1">
			<span class="sui-settings-label">{{ $i18n.settings.labels.ip_anonymization }}</span>
			<span
				class="sui-description"
				v-html="sprintf( $i18n.settings.descriptions.ip_anonymization, 'https://support.google.com/analytics/answer/2763052?hl=en' )"
			></span>
		</div>
		<div class="sui-box-settings-col-2">
			<label for="beehive-settings-anonymize" class="sui-toggle">
				<input
					v-model="anonymizeIP"
					type="checkbox"
					id="beehive-settings-anonymize"
					value="1"
					aria-controls="beehive-settings-anonymize-content"
				/>
				<span class="sui-toggle-slider"></span>
			</label>
			<label
				for="beehive-settings-anonymize"
				class="sui-toggle-label"
			>{{ $i18n.settings.labels.ip_anonymization_enable }}</label>
			<div
				v-if="showForceOption"
				class="sui-border-frame sui-toggle-content"
				tabindex="0"
				id="beehive-settings-anonymize-content"
				:aria-label="$i18n.settings.labels.ip_anonymization_force"
			>
				<label
					for="beehive-settings-force-anonymize"
					class="sui-label"
				>{{ $i18n.settings.labels.ip_anonymization_force_network }}</label>
				<label for="beehive-settings-force-anonymize" class="sui-checkbox sui-checkbox-sm">
					<input
						v-model="forceAnonymize"
						type="checkbox"
						id="beehive-settings-force-anonymize"
						value="1"
					/>
					<span aria-hidden="true"></span>
					<span>{{ $i18n.settings.labels.ip_anonymization_force_subsite }}</span>
				</label>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	name: 'AnonymizeIP',

	computed: {
		anonymizeIP: {
			get() {
				return this.getOption('anonymize', 'general')
			},
			set(value) {
				this.setOption('anonymize', 'general', value)
			}
		},

		forceAnonymize: {
			get() {
				return this.getOption('force_anonymize', 'general')
			},
			set(value) {
				this.setOption('force_anonymize', 'general', value)
			}
		},

		showForceOption() {
			return this.isNetwork() && this.anonymizeIP
		}
	}
}
</script>
