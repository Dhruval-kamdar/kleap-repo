<template>
	<sui-tree
		:id="`beehive-permissions-tree-${type}-${role}`"
		:items="tree"
		:selected-items="getReports()"
		:data="data"
		@itemSelect="handleChange"
	/>
</template>

<script>
import SuiTree from '@/components/sui/sui-tree/tree'

export default {
	name: 'ReportTree',

	props: {
		role: String,
		type: String,
		title: String,
		items: {
			type: Array,
			required: true
		}
	},

	components: { SuiTree },

	data() {
		return {
			data: {
				type: this.type,
				role: this.role
			},
			tree: [
				{
					name: this.type,
					title: this.title,
					children: this.items
				}
			] // It should be an array.
		}
	},

	methods: {
		getReports() {
			let reports = this.getOption(this.role, 'reports', {})

			return reports[this.type] || []
		},

		setReports(selected) {
			let reports = this.getOption(this.role, 'reports', {})

			reports[this.type] = selected

			this.setOption(this.role, 'reports', reports)
		},

		/**
		 * Handle checkbox click event.
		 *
		 * @param {object} data Data.
		 */
		handleChange(data) {
			// Only if required data is found.
			if (
				data.item &&
				data.data.type === this.type &&
				data.data.role === this.role
			) {
				if (data.checked) {
					this.setSelected(data.item)
				} else {
					this.removeSelected(data.item)
				}
			}
		},

		/**
		 * Set selected item to the reports.
		 *
		 * @param {string} report Report item.
		 */
		setSelected(report) {
			let reports = this.getReports()

			if (!reports.includes(report)) {
				reports.push(report)
			}

			this.setReports(reports)
		},

		/**
		 * Remove unselected items from the reports.
		 *
		 * @param {string} report Report item.
		 */
		removeSelected(report) {
			let reports = this.getReports()

			if (reports.includes(report)) {
				let index = reports.indexOf(report)

				if (index !== -1) {
					reports.splice(index, 1)
				}
			}

			this.setReports(reports)
		},

		getTotalSelected() {
			return this.$moduleVars.report_tree || {}
		}
	}
}
</script>
