import { Line, mixins } from 'vue-chartjs'

export default {
	extends: Line,

	mixins: [mixins.reactiveProp],

	props: {
		id: {
			type: String,
			required: true,
		},
		chartData: {
			type: Object,
			default: {},
		},
		options: {
			type: Object,
			default: {},
		},
	},

	created() {
		this.$root.$on('updateLineChart', (data) => {
			if (data.chart === this.id) {
				// Update the options.
				this.$data._chart.options = this.options
				// Update the chart for new data.
				return this.$data._chart.update();
			}
		});
	},

	mounted() {
		this.renderChart(this.chartData, this.options)
	},
}
