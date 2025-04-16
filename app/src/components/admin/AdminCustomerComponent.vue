<template>
	<TopbarComponent />

	<div class="col-md-12 container mt-3">
		<div class="d-flex justify-content-between align-items-center mb-1">
			<h2>Customers List</h2>
		</div>
		<table class="table table-bordered table-hover">
			<thead class="table-dark">
				<tr>
					<th>ID</th>
					<th>Name</th>
					<th>City</th>
					<th>Phone Number</th>
					<th>Customer Charger ID</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="customer in customers" :key="customer.id">
					<td>{{ customer.id }}</td>
					<td>{{ customer.first_name }} {{ customer.last_name }}</td>
					<td>{{ customer.city }}</td>
					<td>{{ customer.phone_mobile }}</td>
					<td>
						<router-link :to="`/session/${customer.customer_chargers[0].id}`">{{
							customer.customer_chargers[0].id
						}}</router-link>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</template>

<script>
import TopbarComponent from '@/components/layouts/TopbarComponent.vue'
import { useToast } from 'vue-toastification'
import { onMounted, ref } from 'vue'
import axios from 'axios'

export default {
	components: { TopbarComponent },
	setup() {
		const customers = ref([])
		const API_BASE = 'http://localhost:3001/api'
		const isLoading = ref(false)
		const toast = useToast()

		onMounted(async () => {
			try {
				const authData = JSON.parse(localStorage.getItem('auth_data'))
				const response = await axios.get(`${API_BASE}/customers`, {
					headers: { Authorization: `Bearer ${authData.token}` },
				})
				customers.value = response.data
			} catch (error) {
				toast.error(
					'Failed to load customers: ' +
						(error.response?.data?.message || 'Server error'),
				)
			} finally {
				isLoading.value = false
			}
		})

		const handleAddCustomer = () => {
			console.log('Add Customer')
		}

		const handleEditCustomer = (customer) => {
			console.log('Edit Customer', customer)
		}

		const handleDeleteCustomer = (customerId) => {
			console.log('Delete Customer', customerId)
		}

		return {
			customers,
			isLoading,
			handleAddCustomer,
			handleEditCustomer,
			handleDeleteCustomer,
		}
	},
}
</script>
