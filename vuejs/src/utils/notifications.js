import { reactive } from 'vue'

export const notificationsState = reactive({
	list: []
})

export const addNotification = (message, type = 'success', duration = 5000) => {
	const id = Date.now()
	notificationsState.list.push({ id, message, type })

	setTimeout(() => {
		const index = notificationsState.list.findIndex(n => n.id === id)
		if (index !== -1) notificationsState.list.splice(index, 1)
	}, duration)
}
