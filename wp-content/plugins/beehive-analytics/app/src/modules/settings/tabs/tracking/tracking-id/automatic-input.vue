<template>
	<div class="sui-box-settings-col-2">
		<div class="sui-form-field beehive-margin-bottom--10">
			<label for="beehive-settings-tracking-code-auto" class="sui-label">
				{{ $i18n.settings.labels.tracking_id }}
				<span
					class="beehive-icon-tooltip sui-tooltip sui-tooltip-constrained"
					:data-tooltip="$i18n.settings.tooltips.tracking_only"
				>
					<i class="sui-icon-info" aria-hidden="true"></i>
				</span>
				<a
					@click="showManualForm"
					role="button"
					href="#"
					class="sui-label-link"
				>{{ $i18n.settings.labels.use_different_tracking }}</a>
			</label>
			<input
				v-model="trackingId"
				type="text"
				id="beehive-settings-tracking-code-auto"
				class="sui-form-control"
				:placeholder="$i18n.settings.placeholders.tracking_id"
				disabled
			/>
		</div>
		<sui-notice
			type="info"
			:message="sprintf( $i18n.settings.notices.automatic_tracking_enabled, '&lt;', '&gt;' )"
		></sui-notice>
	</div>
</template>

<script>
import SuiNotice from '@/components/sui/sui-notice'

export default {
	name: 'AutomaticInput',

	components: { SuiNotice },

	computed: {
		trackingId: {
			get() {
				return this.getOption('auto_track', 'misc', '')
			},
			set(value) {
				this.setOption('auto_track', 'misc', value)
			}
		}
	},

	methods: {
		showManualForm(event) {
			event.preventDefault()
			this.setOption('auto_track', 'google', false)
		}
	}
}
</script>
