<template>
	<select
		:id="id"
		:class="getSelectClass"
		:aria-labelledby="id + '-label'"
		:disabled="disabled"
		ref="selector"
	>
		<fragment v-for="(name, value, index) in options" :key="index">
			<option :value="value">{{ name }}</option>
		</fragment>
	</select>
</template>

<script>
import { Fragment } from 'vue-fragment'

export default {
	name: 'SuiSelect',

	props: {
		id: {
			type: String,
			required: true,
		},

		wrapper: {
			type: Boolean,
			default: true
		},

		selectClass: {
			validator: function( value ) {
				return [ 'small' ].indexOf( value ) !== -1
			},
			default: null
		},

		options: {
			type: Object,
			default: () => ({})
		},
	},

	components: {
		Fragment
	},

	computed: {
		/**
		 * Get select class based on the props.
		 *
		 * @return {string}
		 */
		getSelectClass: function() {
			return {
				'sui-select-sm': this.selectClass === 'small',
			}
		},
	},

	methods: {},

	mounted() {
		SUI.suiSelect( jQuery( this.$refs.selector ) );
	},
}
</script>