import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
import 'bootstrap/dist/css/bootstrap.min.css'

const app = createApp(App)

app.use(createPinia())
app.use(router)

router.afterEach((to) => {
    document.title = to.meta.title ?? import.meta.env.VITE_APP_TITLE
})

app.mount('#app')
