<script setup>
import SessionsComponent from '@/components/SessionsComponent.vue'
import AdminHomeComponent from '@/components/admin/AdminHomeComponent.vue'
import { computed } from 'vue'

const authData = JSON.parse(localStorage.getItem('auth_data') || '{}')
const isAdmin = computed(() => authData.role === 'admin')
</script>

<template>
	<main>
		<SessionsComponent v-if="!isAdmin" />
		<AdminHomeComponent v-else />
	</main>
</template>
