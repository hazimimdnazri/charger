<template>
	<div class="modal fade" :class="{ 'show d-block': visible }" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">{{ editingId ? 'Edit' : 'Add' }} Charger</h5>
					<button type="button" class="btn-close" @click="close"></button>
				</div>
				<div class="modal-body text-start">
					<div class="mb-3">
						<label class="form-label">Charger Name</label>
						<input type="text" class="form-control" v-model="form.name" />
					</div>
					<div class="mb-3">
						<label class="form-label">Status</label>
						<select class="form-select" v-model="form.isActive">
							<option :value="true">Active</option>
							<option :value="false">Inactive</option>
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" @click="close">Cancel</button>
					<button type="button" class="btn btn-primary" @click="submit">
						{{ editingId ? 'Update' : 'Save' }}
					</button>
				</div>
			</div>
		</div>
	</div>
	<div v-if="visible" class="modal-backdrop fade show"></div>
</template>

<script>
import { ref, watch } from 'vue'

export default {
	props: {
		visible: Boolean,
		charger: Object,
	},
	emits: ['close', 'submit'],
	setup(props, { emit }) {
		const form = ref({ name: '', isActive: true })
		const editingId = ref(null)

		watch(
			() => props.charger,
			(newVal) => {
				if (newVal) {
					editingId.value = newVal.id
					form.value = { ...newVal }
				} else {
					editingId.value = null
					form.value = { name: '', isActive: true }
				}
			},
		)

		const close = () => emit('close')
		const submit = () =>
			emit('submit', {
				id: editingId.value,
				data: form.value,
			})

		return {
			form,
			editingId,
			close,
			submit,
		}
	},
}
</script>
