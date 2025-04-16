<template>
	<TopbarComponent />

	<div class="container mt-5">
		<div class="d-flex justify-content-between align-items-center mb-1">
			<h4>Charger {{ chargerId }} Sessions</h4>
			<button class="btn btn-success" @click="startNewSession">Start New Session</button>
		</div>

		<template v-if="sessions.length">
			<div class="col-md-12 mb-3" v-for="session in sessions" :key="session.id">
				<div class="card">
					<div class="card-header bg-primary text-white">
						<h5 class="card-title mb-0">Session {{ session.id }} Status</h5>
					</div>
					<div class="card-body">
						<div class="d-flex align-items-center justify-content-between">
							<div>
								<h6 class="mb-2">Current Status</h6>
								<p class="mb-0">{{ session.status.toUpperCase() }}</p>
							</div>
							<div>
								<button
									@click="stopSession()"
									class="btn"
									:class="{
										'btn-danger': session.status !== 'completed',
										'btn-secondary': session.status === 'completed',
									}"
									:disabled="session.status === 'completed'"
								>
									Disconnect
								</button>
							</div>
						</div>
						<div class="mt-3">
							<div class="progress" style="height: 25px">
								<div
									class="progress-bar"
									role="progressbar"
									:style="{ width: session.soc_percent + '%' }"
									:aria-valuenow="session.soc_percent"
									aria-valuemin="0"
									aria-valuemax="100"
								>
									{{ session.soc_percent }}%
								</div>
							</div>
						</div>
						<div class="mt-3">
							<p class="mb-3">
								<strong>Last Updated:</strong> {{ session.soc_updated_at }}
							</p>
							<div class="d-flex justify-content-around mb-1">
								<div>
									<strong>Total Charged Amount:</strong>
									{{ session.total_charge_amount }}
								</div>
								<div>
									<strong>Total Charged Time:</strong>
									{{ session.total_charge_duration }}
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</template>

		<div v-else class="col-md-12 mb-3 mt-5">
			<div class="card">
				<div class="card-body text-center">
					<p class="mb-0">No past sessions, click on Start New Session</p>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import TopbarComponent from '@/components/layouts/TopbarComponent.vue'
import { ref, onMounted, onUnmounted } from 'vue'
import { useToast } from 'vue-toastification'
import axios from 'axios'
import { useRouter } from 'vue-router'
export default {
	components: { TopbarComponent },

	setup() {
		const router = useRouter()
		const API_BASE = 'http://localhost:3001/api'
		const toast = useToast()
		const sessions = ref([])
		const chargerId = router.currentRoute.value.params.id
		const intervalId = ref(null)

		const fetchSessions = async () => {
			try {
				const authData = JSON.parse(localStorage.getItem('auth_data'))
				const response = await axios.get(`${API_BASE}/charger/${chargerId}/sessions`, {
					headers: { Authorization: `Bearer ${authData.token}` },
				})
				sessions.value = response.data.sort(
					(a, b) => new Date(b.created_at) - new Date(a.created_at),
				)
			} catch (error) {
				toast.error(
					'Failed to load customers: ' +
						(error.response?.data?.message || 'Server error'),
				)
			}
		}

		onMounted(() => {
			fetchSessions()
			intervalId.value = setInterval(fetchSessions, 5000)
		})

		onUnmounted(() => {
			if (intervalId.value) {
				clearInterval(intervalId.value)
			}
		})

		const startNewSession = async () => {
			try {
				const authData = JSON.parse(localStorage.getItem('auth_data'))
				const response = await axios.post(
					`${API_BASE}/charger/start`,
					{
						customer_charger_id: chargerId,
					},
					{
						headers: { Authorization: `Bearer ${authData.token}` },
					},
				)
				toast.success('New session started successfully')
			} catch (error) {
				toast.error(
					'Failed to start new session: ' +
						(error.response?.data?.message || 'Server error'),
				)
			}
		}

		const stopSession = async () => {
			try {
				const authData = JSON.parse(localStorage.getItem('auth_data'))
				const response = await axios.post(
					`${API_BASE}/charger/stop`,
					{
						customer_charger_id: chargerId,
					},
					{
						headers: { Authorization: `Bearer ${authData.token}` },
					},
				)
				toast.success('Session stopped successfully')
			} catch (error) {
				toast.error(
					'Failed to stop session: ' + (error.response?.data?.message || 'Server error'),
				)
			}
		}

		return { sessions, chargerId, startNewSession, stopSession }
	},
}
</script>
