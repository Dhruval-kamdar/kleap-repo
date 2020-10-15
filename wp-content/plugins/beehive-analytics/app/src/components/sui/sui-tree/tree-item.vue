<template>
	<li role="treeitem" :aria-selected="isSelected" :aria-disabled="isDisabled">
		<div class="sui-tree-node">
			<label :for="`${tree}-${parent}-${child}`" class="sui-node-checkbox">
				<input type="checkbox" :id="`${tree}-${parent}-${child}`" v-model="checked" />
				<span aria-hidden="true"></span>
				<span>{{ $i18n.trees.select }}</span>
			</label>

			<span class="sui-node-text">{{ title }}</span>

			<button v-if="hasChildren" data-button="expander">
				<span aria-hidden="true"></span>
				<span class="sui-screen-reader-text">{{ $i18n.trees.open_close }}</span>
			</button>
		</div>

		<ul v-if="hasChildren" role="group">
			<tree-item
				v-for="childData in children"
				:key="childData.name"
				:tree="tree"
				:data="data"
				:child="childData.name"
				:parent="child"
				:title="childData.title"
				:children="getChildren(childData)"
				:selected-items="selectedItems"
				:disabled-items="disabledItems"
				@itemSelect="itemSelect"
			/>
		</ul>
	</li>
</template>

<script>
export default {
	name: 'TreeItem',

	props: {
		children: Array,
		child: String,
		parent: {
			type: String,
			default: ''
		},
		tree: {
			type: String,
			required: true
		},
		title: {
			type: String,
			required: true
		},
		data: {
			type: Object,
			default: {}
		},
		disabledItems: {
			type: Array
		},
		selectedItems: {
			type: Array
		}
	},

	data() {
		return {
			checked: this.isSelected
		}
	},

	watch: {
		checked(checked) {
			// Emit checked event.
			this.emitChange(
				{
					name: this.child,
					children: this.hasChildren ? this.children : []
				},
				checked
			)
		}
	},

	computed: {
		hasChildren() {
			return this.children.length > 0
		},

		isSelected() {
			return this.selectedItems && this.selectedItems.includes(this.child)
		},

		isDisabled() {
			return this.disabledItems && this.disabledItems.includes(this.child)
		}
	},

	methods: {
		getChildren(data) {
			return data.children || []
		},

		emitChange(child, checked) {
			const self = this

			// Recursive checks.
			if (child.children && child.children.length > 0) {
				child.children.forEach(function(child) {
					self.emitChange(child, checked)
				})
			}

			// Emit the current child click.
			this.$emit('itemSelect', {
				tree: this.tree,
				item: child.name,
				checked: checked,
				data: this.data
			})
		},

		itemSelect(data) {
			// Emit the current child click.
			this.$emit('itemSelect', data)
		}
	}
}
</script>
