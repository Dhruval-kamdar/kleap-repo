<template>
	<div :class="noticeClass">
		<div class="sui-notice-content">
			<div class="sui-notice-message">
				<i class="sui-notice-icon sui-icon-info sui-md" aria-hidden="true"></i>
				<p v-html="message"></p>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	name: 'SuiNotice',
	props: {
		type: {
			type: String,
			default: 'success'
		},
		message: {
			type: String,
			required: true
		}
	},

	mounted() {
		SUI.notice()
	},

	computed: {
		noticeClass() {
			return {
				'sui-notice': true,
				'sui-notice-info': this.type === 'info',
				'sui-notice-error': this.type === 'error',
				'sui-notice-success': this.type === 'success',
				'sui-notice-warning': this.type === 'warning'
			}
		}
	}
}
</script>
