<template>
	<button
		type="button"
		@click="refreshData"
		:class="{ 'sui-button-onload-text': refreshing }"
		class="sui-button sui-button-ghost sui-tooltip"
		aria-live="polite"
		:data-tooltip="$i18n.settings.tooltips.refresh"
	>
		<span class="sui-button-text-default">
			<i class="sui-icon-refresh" aria-hidden="true"></i>
			{{ $i18n.settings.buttons.refresh }}
		</span>
		<span class="sui-button-text-onload">
			<i class="sui-icon-loader sui-loading" aria-hidden="true"></i>
			{{ $i18n.settings.buttons.refreshing }}
		</span>
	</button>
</template>

<script>
import { RestGet } from '@/helpers/api'

export default {
	name: 'RefreshButton',

	data() {
		return {
			refreshing: false
		}
	},

	methods: {
		refreshData() {
			this.refreshing = true

			RestGet({
				path: 'actions',
				params: {
					action: 'refresh',
					network: this.isNetwork() ? 1 : 0
				}
			}).then(response => {
				this.refreshing = false

				if (response.success && response.data) {
					this.$root.$emit('showTopNotice', {
						message: response.data.message
					})
				} else {
					this.$root.$emit('showTopNotice', {
						type: 'error',
						message: response.data.message
					})
				}
			})
		}
	}
}
</script>
