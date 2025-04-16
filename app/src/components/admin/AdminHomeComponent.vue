<template>
	<div class="container vh-100">
		<div class="row h-100 align-items-center justify-content-center">
			<div class="col-md-6">
				<div class="card shadow">
					<div class="card-body text-center p-5">
						<h1 class="card-title">EV Charger Monitor</h1>
						<div class="flex d-flex justify-content-center mt-4">
							<div class="flex-item">
								<a
									style="text-decoration: none"
									href="/customers"
									class="card-title"
									>| Customers |</a
								>
							</div>
							<div class="flex-item">
								<a style="text-decoration: none" href="/chargers" class="card-title"
									>| Chargers |</a
								>
							</div>
							<div class="flex-item">
								<a
									style="text-decoration: none"
									href="#"
									@click="handleLogout"
									class="card-title"
									>| Logout |</a
								>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<style scoped>
.card {
	background-color: white;
	border: none;
}
</style>

<script>
import { useRouter } from 'vue-router'
import axios from 'axios'
import { useToast } from 'vue-toastification'

export default {
	name: 'HomeComponent',
	setup() {
		const router = useRouter()
		const toast = useToast()

		const handleLogout = () => {
			const authData = JSON.parse(localStorage.getItem('auth_data'))
			const config = {
				headers: {
					Authorization: `Bearer ${authData.token}`,
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

		return {
			handleLogout,
		}
	},
}
</script>
