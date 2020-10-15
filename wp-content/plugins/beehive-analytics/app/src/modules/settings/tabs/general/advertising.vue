<template>
	<div class="sui-box-settings-row">
		<div class="sui-box-settings-col-1">
			<span class="sui-settings-label">{{ $i18n.settings.labels.display_ad }}</span>
			<span
				class="sui-description"
				v-html="sprintf( $i18n.settings.descriptions.display_ad, 'https://support.google.com/analytics/answer/3450482?hl=en&ref_topic=3413645&rd=1' )"
			></span>
		</div>
		<div class="sui-box-settings-col-2">
			<label for="beehive-settings-advertising" class="sui-toggle">
				<input v-model="advertising" type="checkbox" id="beehive-settings-advertising" value="1" />
				<span class="sui-toggle-slider"></span>
			</label>
			<label
				for="beehive-settings-advertising"
				class="sui-toggle-label"
			>{{ $i18n.settings.labels.display_ad_enable }}</label>
			<sui-notice
				type="default"
				class="sui-toggle-content"
				:message="sprintf( $i18n.settings.notices.privacy_policy, 'https://support.google.com/analytics/answer/2700409' )"
			/>
		</div>
	</div>
</template>

<script>
import SuiNotice from '@/components/sui/sui-notice'

export default {
	name: 'Advertising',

	components: { SuiNotice },

	computed: {
		advertising: {
			get() {
				return this.getOption('advertising', 'general')
			},
			set(value) {
				this.setOption('advertising', 'general', value)
			}
		}
	}
}
</script>
