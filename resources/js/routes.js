import ExampleComponent from './components/Example.vue';
import { createRouter, createWebHistory } from 'vue-router';

const routes = [
    {
        path: "/test",
        component: ExampleComponent,
        name:'home',
    },
];

const router = createRouter({
    routes, // short for `routes: routes`
    history: createWebHistory(),
  })

export default router;