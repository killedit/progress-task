<template>
  <div class="container mt-5">
    <h2>Login</h2>
    <form @submit.prevent="handleLogin">
      <div class="mb-3">
        <label>Email</label>
        <input type="email" v-model="email" class="form-control" required />
      </div>
      <div class="mb-3">
        <label>Password</label>
        <input type="password" v-model="password" class="form-control" required />
      </div>
      <button class="btn btn-primary">Login</button>
      <p class="text-danger mt-2" v-if="error">{{ error }}</p>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { login } from '../services/AuthService'
import { useRouter } from 'vue-router'
import { isAuthenticated, user } from '../stores/auth'

const email = ref('')
const password = ref('')
const error = ref('')
const router = useRouter()

const handleLogin = async () => {
  try {
    const response = await axios.post('http://127.0.0.1:8007/api/login', {
      email: email.value,
      password: password.value
    })

    // Save the user globally
    user.value = response.data.user
    isAuthenticated.value = true

    // Optionally store token if your backend returns it
    // localStorage.setItem('token', response.data.token)

    router.push('/')
    // window.location.reload()
  } catch (err) {
    error.value = 'Invalid credentials'
    console.error(err)
  }
}
</script>
