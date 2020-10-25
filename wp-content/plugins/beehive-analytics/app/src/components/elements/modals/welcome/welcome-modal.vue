<template>
	<welcome-325 v-if="'3.2.5' === current" />
</template>

<script>
import Welcome325 from './versions/welcome-325'

export default {
	name: 'WelcomeModal',

	components: { Welcome325 },

	data() {
		return {
			current: '3.2.5'
		}
	}
}
</script>
