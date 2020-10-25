<template>
    <div class="sui-circle-score" :data-score="value" ref="score"></div>
</template>

<script>
	export default {
		name: 'SuiScore',

		props: {
			value: {
				type: Number,
				required: true
			},
			nocolor: {
				type: Boolean,
				default: false
			},
		},

		mounted() {
			// Load score.
			jQuery( this.$refs.score ).each( function () {
				SUI.loadCircleScore( this );
			} );
		},
	}
</script>