<template>
	<div class="container mt-5">
		<h2>{{ isEdit ? 'Edit Task' : 'Create New Task' }}</h2>

		<form @submit.prevent="handleSubmit">
			<div class="mb-3">
				<label>Title</label>
				<input type="text" v-model="form.title" class="form-control" required />
			</div>

			<div class="mb-3">
				<label>Description</label>
				<textarea v-model="form.description" class="form-control"></textarea>
			</div>

			<div class="mb-3">
				<label>Assign To</label>
				<select v-model="form.assigned_to" class="form-select">
					<option disabled value="">Select a user</option>
					<option v-for="user in users" :key="user.id" :value="user.id">
						{{ user.name }}
					</option>
				</select>
			</div>

			<div class="mb-3">
				<label>Due Date</label>
					<VueDatePicker
						v-model="form.due_date"
						:enable-time-picker="true"
						:is-24="true"
						:auto-apply="true"
						text-input
						:formats="{ input: 'dd/MM/yyyy HH:mm' }"
						input-class-name="dp-custom-input form-control"
						placeholder="DD/MM/YYYY HH:MM"
                	/>
			</div>

			<button type="submit" class="btn btn-primary">
				{{ isEdit ? 'Update' : 'Create' }}
			</button>
		</form>
	</div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { getUsers, getTask, createTask, updateTask } from '../services/TaskService'
import { VueDatePicker } from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'

const route = useRoute()
const router = useRouter()

const taskId = route.params.id
const isEdit = ref(!!taskId)

const users = ref([])
const form = ref({
	title: '',
	description: '',
	assigned_to: '',
	due_date: null,
	is_completed: false,
})



onMounted(async () => {
	try {
		const response = await getUsers()
		users.value = response.data

		if (isEdit.value) {
			const taskResponse = await getTask(taskId)
            const task = taskResponse.data

			// Convert due_date string to Date object if it exists
			if (task.due_date) {
				task.due_date = new Date(task.due_date)
			}

			Object.assign(form.value, task)
		}
	} catch (err) {
		console.error('Failed to load form data:', err)
	}
})

const handleSubmit = async () => {
	try {
        const payload = { ...form.value }

        // if (payload.due_date) {
		// 	payload.due_date = DateTime
		// 		.fromJSDate(payload.due_date)
		// 		.toUTC()
		// 		.toISO({ suppressMilliseconds: true });
		// }

		if (isEdit.value) {
			await updateTask(taskId, form.value)
			alert('Task updated successfully!')
		} else {
			await createTask(form.value)
			alert('Task created successfully!')
		}
		router.push('/')
	} catch (err) {
		console.error('Failed to save task:', err)
		alert('Failed to save task.')
	}
}
</script>
