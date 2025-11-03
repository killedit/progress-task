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
        <input type="datetime-local" v-model="form.due_date" class="form-control" />
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

const route = useRoute()
const router = useRouter()

// determine if editing or creating
const taskId = route.params.id
const isEdit = ref(!!taskId)

const users = ref([])
const form = ref({
  title: '',
  description: '',
  assigned_to: '',
  due_date: '',
  is_completed: false,
})

onMounted(async () => {
  try {
    // Load users for dropdown
    const response = await getUsers()

console.log("TaskForm loaded users:", response.data);

    users.value = response.data

    // If editing, load task data
    if (isEdit.value) {
      const taskResponse = await getTask(taskId)
      Object.assign(form.value, taskResponse.data)
    }
  } catch (err) {
    console.error('Failed to load form data:', err)
  }
})

const handleSubmit = async () => {
  try {
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
