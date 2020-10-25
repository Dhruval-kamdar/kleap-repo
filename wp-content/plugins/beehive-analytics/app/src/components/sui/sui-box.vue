<template>
	<div class="sui-box" :class="getLoadingClass">
		<div class="sui-box-header" v-if="title">
			<h2 class="sui-box-title">
				<i :class="`sui-icon-${titleIcon}`" aria-hidden="true" v-if="titleIcon"></i>
				{{ title }}
			</h2>
			<div class="sui-actions-left" v-if="hasLeftActions">
				<slot name="headerLeft"></slot>
			</div>
			<div class="sui-actions-right" v-if="hasRightActions">
				<slot name="headerRight"></slot>
			</div>
		</div>

		<div :class="getBodyClass" v-if="hasBodySlot">
			<slot name="body"></slot>
		</div>

		<slot name="outside"></slot>

		<div class="sui-box-footer" v-if="hasFooterSlot">
			<slot name="footer"></slot>
		</div>

		<img
			v-if="image1x"
			:src="image1x"
			:srcset="getSrcSet"
			:alt="imageAlt"
			class="sui-image sui-image-center"
			aria-hidden="true"
		/>
	</div>
</template>

<script>
export default {
	name: 'SuiBox',

	props: {
		title: {
			type: String,
			required: false
		},

		titleIcon: {
			type: String,
			default: ''
		},

		bodyClass: {
			type: String,
			default: ''
		},

		image1x: {
			type: String,
			default: ''
		},

		image2x: {
			type: String,
			default: ''
		},

		imageAlt: {
			type: String,
			default: ''
		},

		loading: {
			type: Boolean,
			default: false
		}
	},

	computed: {
		hasLeftActions() {
			return !!this.$slots.headerLeft
		},

		hasRightActions() {
			return !!this.$slots.headerRight
		},

		hasBodySlot() {
			return !!this.$slots.body
		},

		hasFooterSlot() {
			return !!this.$slots.footer
		},

		getLoadingClass() {
			return {
				'beehive-loading': this.loading
			}
		},

		getBodyClass() {
			return {
				'sui-box-body': true,
				[this.bodyClass]: this.bodyClass
			}
		},

		getSrcSet() {
			let tag = ''

			if (this.image1x && this.image2x) {
				tag = this.image1x + ' 1x, ' + this.image2x + ' 2x'
			}

			return tag
		}
	}
}
</script>
