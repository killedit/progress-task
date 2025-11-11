<template>
	<div class="notifications">
		<div v-for="n in notificationsState.list" :key="n.id" :class="['notification', n.type]">
			{{ n.message }}
		</div>
	</div>
</template>

<script setup>
import { notificationsState } from '../utils/notifications.js'
</script>

<style>
.notifications {
	position: fixed;
	bottom: 20px;
	left: 20px;
	display: flex;
	flex-direction: column;
	gap: 10px;
	z-index: 9999;
}

.notification {
	padding: 10px 15px;
	border-radius: 5px;
	color: white;
	font-weight: bold;
	box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.notification.success {
	background-color: #28a745;
}

.notification.error {
	background-color: #dc3545;
}

.notification.info {
	background-color: #17a2b8;
}
</style>