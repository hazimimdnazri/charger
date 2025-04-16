import { createRouter, createWebHistory } from 'vue-router'
import SessionView from '../views/SessionView.vue'
import LoginComponent from '../components/LoginComponent.vue'
import SessionsComponent from '../components/SessionsComponent.vue'
import HomeView from '../views/HomeView.vue'
import ChargerView from '../views/ChargerView.vue'
import CustomersComponent from '../components/admin/AdminCustomerComponent.vue'
const routes = [
	{
		path: '/',
		name: 'Home',
		component: HomeView,
		meta: {
			requiresAuth: true,
		},
	},
	{
		path: '/chargers',
		name: 'Charger',
		component: ChargerView,
		meta: {
			requiresAuth: true,
		},
	},
	{
		path: '/customers',
		name: 'Customers',
		component: CustomersComponent,
	},
	{
		path: '/sessions',
		name: 'Sessions',
		component: SessionsComponent,
	},
	{
		path: '/session/:id',
		name: 'Session',
		component: SessionView,
	},
	{
		path: '/login',
		name: 'Login',
		component: LoginComponent,
	},
]

const router = createRouter({
	history: createWebHistory(import.meta.env.BASE_URL),
	routes,
})

router.beforeEach((to, from, next) => {
	if (to.matched.some((record) => record.meta.requiresAuth)) {
		const authData = localStorage.getItem('auth_data')
		if (!authData) {
			next({ name: 'Login' })
		}
	}
	next()
})

export default router
