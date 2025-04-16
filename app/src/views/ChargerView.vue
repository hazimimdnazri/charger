<script setup>
import AdminChargerComponent from '@/components/admin/AdminChargerComponent.vue'
import { computed } from 'vue'

const authData = JSON.parse(localStorage.getItem('auth_data') || '{}')
const isAdmin = computed(() => authData.role === 'admin')
</script>

<template>
	<main>
		<AdminChargerComponent />
	</main>
</template>
