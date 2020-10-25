<template>
	<sui-box
		:title="$i18n.dashboard.titles.gtm"
		titleIcon="gtm"
		:image1x="images[1]"
		:image2x="images[2]"
		:imageAlt="$i18n.dashboard.labels.coming_soon"
	>
		<template v-slot:headerLeft>
			<span
				class="sui-tag sui-tag-beta"
				style="background-color: #17A8E3;"
			>{{ $i18n.dashboard.labels.coming_soon }}</span>
		</template>

		<template v-slot:body>
			<p>{{ $i18n.dashboard.descriptions.gtm }}</p>
		</template>
	</sui-box>
</template>

<script>
import { imageUrl } from '@/helpers/utils'
import SuiBox from '@/components/sui/sui-box'

export default {
	name: 'ComingSoonWidget',

	components: { SuiBox },

	data() {
		return {
			images: {
				1: this.$vars.whitelabel.hide_branding
					? ''
					: imageUrl('characters/beehive-butterfly.png'),
				2: this.$vars.whitelabel.hide_branding
					? ''
					: imageUrl('characters/beehive-butterfly@2x.png')
			}
		}
	}
}
</script>
