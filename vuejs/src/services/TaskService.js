import axios from 'axios'
import { authHeader } from './AuthService'

const API_URL = 'http://127.0.0.1:8007/api';

axios.defaults.baseURL = API_URL;
axios.interceptors.request.use(config => {
  const token = localStorage.getItem('token');
  if (token) config.headers.Authorization = `Bearer ${token}`;
  return config;
});

// export async function getTasks(page = 1) {
//   const response = await axios.get(`/tasks?page=${page}`);
//   return response.data;
// }

export const getTasks = async (page = 1, token = null) => {
  const headers = token ? { Authorization: `Bearer ${token}` } : {}
  const res = await axios.get(`${API_URL}/tasks?page=${page}`, { headers })
  return res.data
}

// export async function createTask(taskData) {
//   const response = await axios.post('/tasks', taskData);
//   return response.data;
// }

export const createTask = async (task) => {
  const response = await axios.post(`${API_URL}/tasks`, task, {
    headers: authHeader()
  })
  return response.data
}

export const updateTask = async (id, taskData) => {
  const response = await axios.put(`${API_URL}/tasks/${id}`, taskData, {
    headers: authHeader()
  })
  return response.data
}

export const deleteTask = async (id) => {
  const res = await axios.delete(`${API_URL}/tasks/${id}`, {
    // headers: { Authorization: `Bearer ${token}` }
  })
  return res.data
}

export const toggleCompleteTask = async (id) => {
  const res = await axios.patch(`${API_URL}/tasks/${id}/complete`, {}, {
    // headers: { Authorization: `Bearer ${token}` }
  })
  return res.data
}