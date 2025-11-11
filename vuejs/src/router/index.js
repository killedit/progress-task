import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/Home.vue';
import Login from '../views/Login.vue';
import Register from '../views/Register.vue';
import Tasks from '../views/Tasks.vue'
import TaskForm from '../views/TaskForm.vue'

const routes = [
	{ path: '/', name: 'Home', component: Home, meta: { title: 'Progress Task Home' } },
	{ path: '/login', name: 'Login', component: Login, meta: { title: 'Progress Task Login' } },
	{ path: '/register', name: 'Register', component: Register, meta: { title: 'Progress Task Register' } },
	{ path: '/tasks/create', component: TaskForm, meta: { title: 'Progress Task Create Task' } },
	{ path: '/tasks/:id/edit', component: TaskForm, props: true, meta: { title: 'Progress Task Edit Task' } },
	{ path: '/register', name: 'Register', component: Register, meta: { title: 'Progress Task Register' } },
];

const router = createRouter({
	history: createWebHistory(),
	routes,
});

export default router;
