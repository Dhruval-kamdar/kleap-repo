<template>
	<fragment>
		<info-bar></info-bar>
		<account-selection></account-selection>
		<logout-modal></logout-modal>
		<switch-modal></switch-modal>
	</fragment>
</template>

<script>
import InfoBar from './sections/info-bar'
import LogoutModal from './modals/logout'
import SwitchModal from './modals/switch'
import AccountSelection from './sections/account-selection'

export default {
	name: 'AccountData',

	components: {
		InfoBar,
		SwitchModal,
		LogoutModal,
		AccountSelection
	}
}
</script>
