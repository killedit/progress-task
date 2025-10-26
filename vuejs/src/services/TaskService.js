import axios from 'axios'
import { authHeader } from './AuthService'

const API_URL = 'http://127.0.0.1:8007/api';

axios.defaults.baseURL = API_URL;
axios.interceptors.request.use(config => {
  const token = localStorage.getItem('token');
  if (token) config.headers.Authorization = `Bearer ${token}`;
  return config;
});

export async function getTasks(page = 1) {
  const response = await axios.get(`${API_URL}/tasks?page=${page}`, {
    headers: authHeader(),
    timeout: 10000,
  })
  return response.data
}

export async function createTask(task) {
	const response = await axios.post(`${API_URL}/tasks`, task, {
		headers: authHeader()
	})
	return response.data
}

export async function updateTask(id, taskData) {
	const response = await axios.put(`${API_URL}/tasks/${id}`, taskData, {
		headers: authHeader()
	})
	return response.data
}

export async function deleteTask(id) {
	const response = await axios.delete(`${API_URL}/tasks/${id}`, {
		headers: authHeader()
	})
	return response.data
}

export async function toggleCompleteTask(id) {
	const response = await axios.patch(`${API_URL}/tasks/${id}/complete`, {}, {
		headers: authHeader()
	})
	return response.data
}