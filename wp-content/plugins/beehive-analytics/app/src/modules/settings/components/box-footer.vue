<template>
	<div class="sui-box-footer">
		<div class="sui-actions-right">
			<button
				@click="submitHandler"
				:class="loadingClass"
				type="button"
				class="sui-button sui-button-blue"
				aria-live="polite"
				:disabled="disableSubmit"
			>
				<span class="sui-button-text-default">
					<i class="sui-icon-save" aria-hidden="true"></i>
					{{ saveButtonText }}
				</span>
				<span class="sui-button-text-onload">
					<i class="sui-icon-loader sui-loading" aria-hidden="true"></i>
					{{ savingButtonText }}
				</span>
			</button>
		</div>
	</div>
</template>

<script>
export default {
	name: 'BoxFooter',

	props: {
		saveText: {
			type: String
		},
		savingText: {
			type: String
		},
		submitMessage: {
			type: String
		},
		tab: {
			type: String,
			required: true
		},
		disableSubmit: {
			type: Boolean,
			default: false
		}
	},

	data() {
		return {
			processing: false
		}
	},

	computed: {
		loadingClass() {
			return {
				'sui-button-onload-text': this.processing
			}
		},

		saveButtonText() {
			return this.saveText || this.$i18n.settings.buttons.save_changes
		},

		savingButtonText() {
			return this.savingText || this.$i18n.settings.buttons.saving_changes
		}
	},

	methods: {
		submitHandler() {
			this.$emit('formSubmit', this.tab)
		}
	}
}
</script>
