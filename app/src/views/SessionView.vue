<script setup>
import AdminSessionComponent from '@/components/admin/AdminSessionComponent.vue'
import { computed } from 'vue'

const authData = JSON.parse(localStorage.getItem('auth_data') || '{}')
const isAdmin = computed(() => authData.role === 'admin')
</script>

<template>
	<main>
		<AdminSessionComponent />
	</main>
</template>
