import ExampleComponent from './Components/Example.vue';
import PartnerIndex from './Pages/Partners/Index.vue';
import PartnerCreate from './Pages/Partners/Create.vue';
import { createRouter, createWebHistory } from 'vue-router';

const routes = [
    {
        path: "/test",
        component: ExampleComponent,
        name:'home',
    },
    {
        path: '/partners',
        name: 'partners.index',
        component: PartnerIndex,
    },
    {
        path: '/partners/create',
        name: 'partners.create',
        component: PartnerCreate,
    },
];

const router = createRouter({
    routes, // short for `routes: routes`
    history: createWebHistory(),
  })

export default router;