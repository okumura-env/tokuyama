import './bootstrap';
import router from './routes';
import { createApp } from 'vue/dist/vue.esm-bundler';

const app = createApp({})
app.use(router)
app.mount('#app');