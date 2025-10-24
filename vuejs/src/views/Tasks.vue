<template>
  <div class="tasks-container">
    <div class="tasks-header">
      <h2>Tasks</h2>
      <button @click="showCreateModal = true" class="btn-primary">Create Task</button>
    </div>

    <div v-if="loading" class="loading">Loading tasks...</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    
    <div v-else class="tasks-list">
      <div v-for="task in tasks" :key="task.id" class="task-card">
        <div class="task-header">
          <h3>{{ task.title }}</h3>
          <div class="task-actions">
            <button @click="editTask(task)" class="btn-edit">Edit</button>
            <button @click="deleteTask(task.id)" class="btn-delete">Delete</button>
            <button 
              v-if="task.status !== 'completed'"
              @click="markCompleted(task.id)" 
              class="btn-complete"
            >
              Complete
            </button>
          </div>
        </div>
        <p>{{ task.description }}</p>
        <div class="task-meta">
          <span>Status: {{ task.status }}</span>
          <span>Created by: {{ task.creator?.name }}</span>
          <span v-if="task.assigned_to">Assigned to: {{ task.assignedUser?.name }}</span>
        </div>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <div v-if="showCreateModal || showEditModal" class="modal">
      <div class="modal-content">
        <h3>{{ showEditModal ? 'Edit Task' : 'Create Task' }}</h3>
        <form @submit.prevent="handleSubmit">
          <div class="form-group">
            <label>Title:</label>
            <input v-model="currentTask.title" required>
          </div>
          <div class="form-group">
            <label>Description:</label>
            <textarea v-model="currentTask.description"></textarea>
          </div>
          <div class="modal-actions">
            <button type="button" @click="closeModal">Cancel</button>
            <button type="submit">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { getTasks, deleteTask, toggleCompleteTask } from '../services/TaskService'

import { ref, onMounted } from 'vue'

export default {
  name: 'Tasks',
  setup() {
    const tasks = ref([])
    const loading = ref(true)
    const error = ref(null)
    const showCreateModal = ref(false)
    const showEditModal = ref(false)
    const currentTask = ref({
      title: '',
      description: ''
    })

    const loadTasks = async () => {
      try {
        loading.value = true
        const response = await TaskService.getAllTasks()
        tasks.value = response.data
      } catch (err) {
        error.value = 'Failed to load tasks'
        console.error(err)
      } finally {
        loading.value = false
      }
    }

    const handleSubmit = async () => {
      try {
        if (showEditModal.value) {
          await TaskService.updateTask(currentTask.value.id, currentTask.value)
        } else {
          await TaskService.createTask(currentTask.value)
        }
        closeModal()
        loadTasks()
      } catch (err) {
        error.value = 'Failed to save task'
        console.error(err)
      }
    }

    const editTask = (task) => {
      currentTask.value = { ...task }
      showEditModal.value = true
    }

    const deleteTask = async (id) => {
      if (confirm('Are you sure you want to delete this task?')) {
        try {
          await TaskService.deleteTask(id)
          loadTasks()
        } catch (err) {
          error.value = 'Failed to delete task'
          console.error(err)
        }
      }
    }

    const markCompleted = async (id) => {
      try {
        await TaskService.markAsCompleted(id)
        loadTasks()
      } catch (err) {
        error.value = 'Failed to mark task as completed'
        console.error(err)
      }
    }

    const closeModal = () => {
      showCreateModal.value = false
      showEditModal.value = false
      currentTask.value = { title: '', description: '' }
    }

    onMounted(loadTasks)

    return {
      tasks,
      loading,
      error,
      showCreateModal,
      showEditModal,
      currentTask,
      handleSubmit,
      editTask,
      deleteTask,
      markCompleted,
      closeModal
    }
  }
}
</script>

<style scoped>
.tasks-container {
  padding: 20px;
}

.tasks-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.task-card {
  border: 1px solid #ddd;
  padding: 15px;
  margin-bottom: 10px;
  border-radius: 4px;
}

.task-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.task-actions button {
  margin-left: 10px;
}

.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-content {
  background: white;
  padding: 20px;
  border-radius: 4px;
  width: 500px;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  margin-bottom: 5px;
}

.form-group input,
.form-group textarea {
  width: 100%;
  padding: 8px;
}
</style>