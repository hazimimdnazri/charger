<template>
	<TopbarComponent />

	<div class="container mt-3">
		<div class="d-flex justify-content-between align-items-center mb-1">
			<h2>Chargers List</h2>
			<button class="btn btn-primary" @click="handleAddCharger">Add Charger</button>
		</div>
		<table class="table table-bordered table-hover">
			<thead class="table-dark">
				<tr>
					<th>ID</th>
					<th>Name</th>
					<th>Status</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="charger in chargers" :key="charger.id">
					<td>{{ charger.id }}</td>
					<td>{{ charger.name }}</td>
					<td>
						<span :class="['badge', charger.isActive ? 'bg-success' : 'bg-danger']">
							{{ charger.isActive ? 'Active' : 'Inactive' }}
						</span>
					</td>
					<td>
						<button
							class="btn btn-sm btn-primary me-2"
							@click="handleEditCharger(charger)"
						>
							Edit
						</button>
						<button
							class="btn btn-sm btn-danger"
							@click="handleDeleteCharger(charger.id)"
						>
							Delete
						</button>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<ChargerModal
		:visible="modalVisible"
		:charger="selectedCharger"
		@close="modalVisible = false"
		@submit="handleModalSubmit"
	/>
</template>

<script>
import TopbarComponent from '@/components/layouts/TopbarComponent.vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { useToast } from 'vue-toastification'
import { onMounted, ref } from 'vue'
import ChargerModal from '@/components/admin/ChargerModal.vue'

export default {
	name: 'AdminChargerComponent',
	components: {
		TopbarComponent,
		ChargerModal,
	},
	setup() {
		const router = useRouter()
		const toast = useToast()
		const chargers = ref([])
		const modalVisible = ref(false)
		const selectedCharger = ref(null)
		const API_BASE = 'http://localhost:3001/api'
		const isLoading = ref(false)

		onMounted(async () => {
			try {
				const authData = JSON.parse(localStorage.getItem('auth_data'))
				const response = await axios.get(`${API_BASE}/chargers`, {
					headers: {
						Authorization: `Bearer ${authData.token}`,
					},
				})
				chargers.value = response.data
			} catch (error) {
				toast.error(
					'Failed to load chargers: ' + (error.response?.data?.message || 'Server error'),
				)
			}
		})

		const handleAddCharger = () => {
			selectedCharger.value = null
			modalVisible.value = true
		}

		const handleEditCharger = (charger) => {
			selectedCharger.value = charger
			modalVisible.value = true
		}

		const handleModalSubmit = async ({ id, data }) => {
			try {
				const authData = JSON.parse(localStorage.getItem('auth_data'))
				const url = id ? `${API_BASE}/chargers/${id}` : `${API_BASE}/chargers`
				const method = id ? 'put' : 'post'

				await axios[method](url, data, {
					headers: {
						Authorization: `Bearer ${authData.token}`,
					},
				})

				modalVisible.value = false
				fetchChargers()
				toast.success(`Charger ${id ? 'updated' : 'added'} successfully`)
			} catch (error) {
				toast.error(
					'Operation failed: ' + (error.response?.data?.message || 'Server error'),
				)
			}
		}

		const fetchChargers = async () => {
			isLoading.value = true
			try {
				const authData = JSON.parse(localStorage.getItem('auth_data'))
				const response = await axios.get(`${API_BASE}/chargers`, {
					headers: {
						Authorization: `Bearer ${authData.token}`,
					},
				})
				chargers.value = response.data
			} catch (error) {
				toast.error(
					'Failed to load chargers: ' + (error.response?.data?.message || 'Server error'),
				)
			} finally {
				isLoading.value = false
			}
		}

		const handleDeleteCharger = async (chargerId) => {
			if (!window.confirm('Are you sure you want to delete this charger?')) return

			try {
				const authData = JSON.parse(localStorage.getItem('auth_data'))
				await axios.delete(`${API_BASE}/chargers/${chargerId}`, {
					headers: {
						Authorization: `Bearer ${authData.token}`,
					},
				})

				fetchChargers()
				toast.success('Charger deleted successfully')
			} catch (error) {
				toast.error('Delete failed: ' + (error.response?.data?.message || 'Server error'))
			}
		}

		const getAuthHeader = () => {
			const authData = JSON.parse(localStorage.getItem('auth_data'))
			return { Authorization: `Bearer ${authData?.token}` }
		}

		const handleApiError = (error, defaultMessage = 'Operation failed') => {
			toast.error(`${defaultMessage}: ${error.response?.data?.message || 'Server error'}`)
		}

		return {
			chargers,
			modalVisible,
			selectedCharger,
			handleAddCharger,
			handleEditCharger,
			handleModalSubmit,
			fetchChargers,
			handleDeleteCharger,
			getAuthHeader,
			handleApiError,
			isLoading,
		}
	},
}
</script>
