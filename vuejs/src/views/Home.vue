<template>
	<div class="container mt-5">
		<h2 class="mb-4 text-left">Task List</h2>

		<!-- Navbar -->
		<nav class="navbar navbar-expand-md mb-3">
			<div class="collapse navbar-collapse" id="navbarSupportedContent">

				<a v-if="isAuthenticated" class="btn btn-success btn-sm me-2" href="`/tasks/create`" @click.prevent="addTask"><i class="bi bi-plus-circle"></i> + Add a new task</a>

				<div class="ms-auto d-flex align-items-center">

					<!-- Guest buttons -->
					<template v-if="!isAuthenticated">
						<a class="btn btn-sm btn-outline-primary me-2" href="/login">Login</a>
						<a class="btn btn-sm btn-primary" href="/register">Register</a>
					</template>

					<!-- Logged-in buttons -->
					<template v-else>
						<span class="me-3">Welcome, {{ user?.name || 'User' }}</span>
						<button class="btn btn-sm btn-outline-danger" @click="logout">Logout</button>
					</template>
				</div>
			</div>
		</nav>

		<!-- Task table -->
		<div v-if="loading" class="text-center">
			<p>Loading tasks...</p>
		</div>

		<table v-else class="table table-striped table-bordered">
			<thead>
				<tr>
					<th>ID</th>
					<th>Title</th>
					<th>Description</th>
					<th>Assigned To</th>
					<th>Created By</th>
					<th>Due Date</th>
					<th>Completed</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="task in tasks" :key="task.id">
					<td>{{ task.id }}</td>
					<td>{{ task.title }}</td>
					<td>{{ task.description }}</td>
					<td>{{ task.assigned_user?.name || task.assigned_to }}</td>
					<td>{{ task.created_by_user?.name || task.created_by }}</td>
					<td>{{ formatDate(task.due_date) }}</td>
					<td>
						<span v-if="task.is_completed" class="badge bg-success">Yes</span>
						<span v-else class="badge bg-secondary">No</span>
					</td>
					<td>
						<template v-if="isAuthenticated">
							<div class="action-buttons">
								<button class="btn btn-sm btn-warning" @click="editTask(task.id)">🖉 Edit</button>

								<button class="btn btn-sm btn-danger" @click="deleteTaskItem(task.id)">🗑 Delete</button>

								<button class="btn btn-sm" :class="task.is_completed ? 'btn-secondary' : 'btn-success'" @click="toggleComplete(task.id)">
									<span v-if="!task.is_completed">🗹 Completed</span>
									<span v-else>🗷 Incomplete</span>
								</button>
							</div>
						</template>
						<template v-else>
							<small class="text-muted">Login to manage</small>
						</template>
					</td>
				</tr>
			</tbody>
		</table>

		<!-- Pagination -->
		<nav v-if="pagination.last_page > 1" class="d-flex justify-content-center">
			<ul class="pagination">
				<li class="page-item" :class="{ disabled: !pagination.prev_page_url }">
				<button class="page-link" @click="changePage(pagination.current_page - 1)">Previous</button>
				</li>

				<li v-for="page in pagination.last_page" :key="page"
					class="page-item" :class="{ active: pagination.current_page === page }">
				<button class="page-link" @click="changePage(page)">{{ page }}</button>
				</li>

				<li class="page-item" :class="{ disabled: !pagination.next_page_url }">
				<button class="page-link" @click="changePage(pagination.current_page + 1)">Next</button>
				</li>
			</ul>
		</nav>

	</div>
</template>

<script setup>

const user = ref({})

import { ref, onMounted } from 'vue'
import { getTasks, deleteTask, toggleCompleteTask } from '../services/TaskService'
import { useRouter } from 'vue-router'
import { addNotification } from '../utils/notifications.js'

const TASKS_PER_PAGE = 10

const isAuthenticated = ref(false)

const tasks = ref([])
const pagination = ref({
	current_page: 1,
	last_page: 1,
	per_page: TASKS_PER_PAGE,
	total: 0,
	next_page_url: null,
	prev_page_url: null
})
const loading = ref(true)
const router = useRouter()

const loadTasks = async (page = 1) => {
	try {
		loading.value = true
		const data = await getTasks(page, TASKS_PER_PAGE)

		tasks.value = data.data
		pagination.value = {
			current_page: data.meta?.current_page ?? page,
			last_page: data.meta?.last_page ?? 1,
			per_page: data.meta?.per_page ?? TASKS_PER_PAGE,
			total: data.meta?.total ?? data.data.length,
			next_page_url: data.links?.next ?? null,
			prev_page_url: data.links?.prev ?? null
		}
	} catch (err) {
		console.error('Failed to load tasks:', err)
	} finally {
		loading.value = false
	}
}

const changePage = (page) => {
	const lastPage = pagination.value.last_page ?? 1
	if (page < 1 || page > lastPage) return
	loadTasks(page)
}

const formatDate = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return date.toLocaleString('en-GB', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    hour12: false
  })
}

const addTask = () => {
	router.push(`/tasks/create`)
}

const editTask = (id) => {
	router.push(`/tasks/${id}/edit`)
}

const deleteTaskItem = async (id) => {
	try {
		if (!confirm('Are you sure?')) return
		await deleteTask(id)
		
		addNotification('Task deleted successfully!', 'success')

		await loadTasks()
	} catch (err) {
		console.error('Failed to delete task:', err)
	}
}

const toggleComplete = async (id) => {
	try {
		await toggleCompleteTask(id)
		await loadTasks()
	} catch (err) {
		console.error('Failed to toggle complete:', err)
	}
}

const logout = () => {
	localStorage.removeItem('user')
	localStorage.removeItem('token')
	user.value = {}
	isAuthenticated.value = false

	tasks.value = []
	loadTasks()
	router.push('/')
}

onMounted(() => {
	const storedUser = localStorage.getItem('user')
	const storedToken = localStorage.getItem('token')

	user.value = storedUser ? JSON.parse(localStorage.getItem('user')) : {}
	isAuthenticated.value = !!storedToken

	loadTasks()
})

</script>


<style>
.container {
	max-width: 900px;
}

.action-buttons {
	display: flex;
	flex-wrap: nowrap;
	gap: 0.4rem;
	align-items: center;
	justify-content: flex-start;
}

.action-buttons .btn {
	white-space: nowrap;
}
</style>
