import Vue from 'vue'
import Router from 'vue-router'
import General from './tabs/general'
import Tracking from './tabs/tracking'
import Permissions from './tabs/permissions'

Vue.use(Router);

export default new Router({
	linkActiveClass: 'current',
	routes: [
		{
			path: '/',
			redirect: '/tracking',
		},
		{
			path: '/tracking',
			name: 'Tracking',
			component: Tracking
		},
		{
			path: '/general',
			name: 'General',
			component: General
		},
		{
			path: '/permissions',
			name: 'Permissions',
			component: Permissions
		},
	]
})
