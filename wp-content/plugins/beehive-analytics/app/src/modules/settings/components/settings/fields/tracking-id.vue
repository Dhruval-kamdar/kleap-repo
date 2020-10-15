<template>
	<input
		type="text"
		:id="id"
		class="sui-form-control"
		:placeholder="$i18n.settings.placeholders.tracking_id"
		v-model="trackingId"
		@input="handleInput"
	/>
</template>

<script>
export default {
	name: 'TrackingId',

	props: {
		id: String,
		value: String,
		context: String
	},

	data() {
		return {
			trackingId: this.value
		}
	},

	methods: {
		validateId(id) {
			return /^ua-\d{4,9}-\d{1,4}$/i.test(id) || !id
		},

		handleInput(event) {
			this.$emit('input', this.trackingId)

			this.$emit('validation', {
				valid: this.validateId(this.trackingId),
				context: this.context
			})
		}
	}
}
</script>
