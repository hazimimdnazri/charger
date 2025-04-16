<template>
	<div class="container mt-5">
		<div class="row justify-content-center">
			<div class="col-md-6">
				<div class="card">
					<div class="card-header">
						<h3 class="text-center">Login</h3>
					</div>
					<div class="card-body">
						<form @submit.prevent="handleLogin">
							<div class="mb-3">
								<label for="email" class="form-label">Email address</label>
								<input
									type="email"
									class="form-control"
									id="email"
									v-model="email"
									required
									placeholder="Enter your email"
								/>
							</div>
							<div class="mb-3">
								<label for="password" class="form-label">Password</label>
								<input
									type="password"
									class="form-control"
									id="password"
									v-model="password"
									required
									placeholder="Enter your password"
								/>
							</div>
							<div class="mb-3 form-check">
								<input
									type="checkbox"
									class="form-check-input"
									id="remember"
									v-model="rememberMe"
								/>
								<label class="form-check-label" for="remember">Remember me</label>
							</div>
							<div class="d-grid">
								<button type="submit" class="btn btn-primary">Login</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useToast } from 'vue-toastification'
import { useRouter } from 'vue-router'

const router = useRouter()
const toast = useToast()
const email = ref('')
const password = ref('')
const rememberMe = ref(false)

const handleLogin = () => {
	axios
		.post(
			'http://localhost:3001/api/auth/login',
			{
				email: email.value,
				password: password.value,
			},
			{
				headers: {
					'Content-Type': 'application/json',
					Accept: 'application/json',
				},
			},
		)
		.then((response) => {
			if (response.status === 200 && response.data.token) {
				let authData = {
					token: response.data.token,
					user_id: response.data.user_id,
					customer_id: response.data.customer_id,
					role: response.data.role,
					charger_id: response.data.charger_id,
				}

				localStorage.setItem('auth_data', JSON.stringify(authData))

				toast.success('Login successful!')
				router.push('/')
			}
		})
		.catch((error) => {
			if (error.response?.status === 401) {
				toast.error('Invalid email or password')
			} else {
				toast.error('An error occurred during login')
			}
			console.log(error.response.data)
		})
}
</script>
