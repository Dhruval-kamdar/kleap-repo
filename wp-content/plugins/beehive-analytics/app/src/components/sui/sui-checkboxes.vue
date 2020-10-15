<template>
	<sui-field
		:label="label"
		:labelType="labelClass"
		:topDescription="topDescription"
		:btmDescription="btmDescription"
	>

		<sui-checkbox
			:id="id + '-option-' + index"
			:label="option"
			:type="checkClass"
			v-for="(option, index) in options"
			:key="index"
		/>

	</sui-field>
</template>

<script>
import { Fragment } from 'vue-fragment'

import SuiField from './sui-field'
import SuiCheckbox from './sui-checkbox'

export default {
	name: 'SuiCheckboxes',

	props: {
		id: {
			type: String,
			required: true
		},

		options: {
			type: Array,
			default: null
		},

		checkClass: {
			default: null
		},

		label: {},

		labelClass: {},

		topDescription: {},

		btmDescription: {},
	},

	components: {
		Fragment,
		SuiField,
		SuiCheckbox,
	},

	computed: {},

	methods: {},
}
</script>