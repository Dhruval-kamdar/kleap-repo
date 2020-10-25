<template>
	<select
			:class="{ 'sui-select-sm': isSmall }"
			class="sui-select"
			:id="id"
			:aria-labelledby="labelId"
			:aria-describedby="descriptionId"
			:disabled="disabled"
	>
		<option></option>
	</select>
</template>

<script>
	export default {
		name: 'SuiSelect2',

		props: [ 'id', 'options', 'value', 'isSmall', 'labelId', 'descriptionId', 'placeholder', 'disabled' ],

		mounted() {
			this.initSelect2();
		},

		watch: {
			options: function( options ) {
				this.initSelect2( options );
			},

			value: function( value ) {
				jQuery( '#' + this.id )
					.val( value )
					.trigger( 'change' )
			},
		},

		destroyed: function() {
			this.destroySelect2();
		},

		methods: {
			initSelect2( options ) {
				let self = this;

				options = options || this.options;

				jQuery( '#' + this.id ).SUIselect2( {
					placeholder: this.placeholder,
					dropdownCssClass: 'sui-select-dropdown',
					data: options,
				} ).on( 'change', function() {
					self.$emit( 'input', this.value )
				} ).val( this.value ).trigger( 'change' );
			},

			destroySelect2() {
				jQuery( '#' + this.id ).off().SUIselect2( 'destroy' );
			}
		}
	}
</script>
