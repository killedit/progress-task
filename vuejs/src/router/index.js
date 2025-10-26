import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/Home.vue';
import Login from '../views/Login.vue';
import Register from '../views/Register.vue';
import Tasks from '../views/Tasks.vue'
import TaskForm from '../views/TaskForm.vue'

const routes = [
  { path: '/', name: 'Home', component: Home },
  { path: '/login', name: 'Login', component: Login },
  { path: '/register', name: 'Register', component: Register },
  { path: '/tasks', name: 'Tasks', component: Tasks, meta: { requiresAuth: true }},
  { path: '/tasks/create', component: TaskForm },
  { path: '/tasks/:id/edit', component: TaskForm, props: true },
  { path: '/register', name:'Register', component: Register },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
