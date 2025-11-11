<template>
    <div id="app">
        <router-view />
        <Notifications /> <!-- always present -->
    </div>
</template>

<script setup>
import Notifications from './components/Notifications.vue'
</script>
