<template>
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<div class="container-fluid">
			<a class="navbar-brand" href="#">EV Charger</a>
			<button
				class="navbar-toggler"
				type="button"
				data-bs-toggle="collapse"
				data-bs-target="#navbarNav"
				aria-controls="navbar-nav"
				aria-expanded="false"
				aria-label="Toggle navigation"
			>
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav ms-auto">
					<li class="nav-item">
						<a class="nav-link" aria-current="page" href="/">Home</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#" @click.prevent="handleLogout">Logout</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>
</template>

<script>
import { useRouter } from 'vue-router'
import axios from 'axios'
import { useToast } from 'vue-toastification'

export default {
	name: 'TopbarComponent',
	setup() {
		const router = useRouter()
		const toast = useToast()

		const handleLogout = () => {
			const authData = JSON.parse(localStorage.getItem('auth_data'))
			const config = {
				headers: {
					Authorization: `Bearer ${authData?.token}`,
				},
			}
			axios
				.post('http://localhost:3001/api/auth/logout', {}, config)
				.then((response) => {
					if (response.status === 200) {
						localStorage.removeItem('auth_data')
						toast.success('Logged out successfully')
						router.push('/login')
					}
				})
				.catch((error) => {
					toast.error(
						'Logout failed: ' + (error.response?.data?.message || 'Unknown error'),
					)
				})
		}

		return { handleLogout }
	},
}
</script>
