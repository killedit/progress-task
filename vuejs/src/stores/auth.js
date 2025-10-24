// /vuejs/src/stores/auth.js
import { ref } from 'vue'

// Initialize from localStorage to persist state across page refreshes
export const isAuthenticated = ref(!!localStorage.getItem('token'))
export const user = ref(JSON.parse(localStorage.getItem('user')) || null)

// Helper functions to manage auth state
export const setAuth = (userData, token) => {
  user.value = userData
  isAuthenticated.value = true
  localStorage.setItem('user', JSON.stringify(userData))
  localStorage.setItem('token', token)
}

export const clearAuth = () => {
  user.value = null
  isAuthenticated.value = false
  localStorage.removeItem('user')
  localStorage.removeItem('token')
}