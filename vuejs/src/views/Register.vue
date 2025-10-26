<template>
  <div class="container mt-5" style="max-width: 400px">
    <h2 class="mb-4">Register</h2>

    <form @submit.prevent="register">
      <div class="mb-3">
        <label>Name</label>
        <input v-model="form.name" type="text" class="form-control" autocomplete="off" required />
      </div>

      <div class="mb-3">
        <label>Email</label>
        <input name="register_email" v-model="form.email" type="email" class="form-control" autocomplete="new-email" required />
      </div>

      <div class="mb-3">
        <label>Password</label>
        <input name="register_password" v-model="form.password" type="password" class="form-control" autocomplete="new-password" required />
      </div>

      <div class="mb-3">
        <label>Confirm Password</label>
        <input v-model="form.password_confirmation" type="password" class="form-control" autocomplete="new-password" required />
      </div>

      <button type="submit" class="btn btn-primary w-100">Register</button>

      <p class="mt-3 text-center">
        Already have an account?
        <router-link to="/login">Login</router-link>
      </p>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { registerUser } from '../services/AuthService'

const router = useRouter()
const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
})

const register = async () => {
  try {
    const response = await registerUser(form.value)

    localStorage.setItem('token', response.data.access_token)
    localStorage.setItem('user', JSON.stringify(response.data.user))

    alert('Registration successful!')
    router.push('/')
  } catch (err) {
    console.error(err)
    alert('Registration failed. Please check your input.')
  }
}
</script>
