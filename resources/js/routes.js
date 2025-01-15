import ExampleComponent from './Components/Example.vue';
import PartnerIndex from './Pages/Partners/Index.vue';
import PartnerCreate from './Pages/Partners/Create.vue';
import PartnerShow from './Pages/Partners/Show.vue';
import PartnerEdit from './Pages/Partners/Edit.vue';
import VehicleTypeIndex from './Pages/VehicleTypes/Index.vue';
import WorkerIndex from './Pages/Workers/Index.vue';
import VehicleIndex from './Pages/Vehicles/Index.vue';
import DumpAssignment from './Pages/DumpOrders/Assignment.vue';
import JetpackScheduleIndex from './Pages/JetpackOrders/ScheduleIndex.vue';
import { createRouter, createWebHistory } from 'vue-router';

const routes = [
    {
        path: "/test",
        component: ExampleComponent,
        name:'home',
    },
    {
        path: "/dump-assignment",
        component: DumpAssignment,
        name:'dump-assignment',
    },
    {
        path: "/jetpack-schedule-index",
        component: JetpackScheduleIndex,
        name:'jetpack-schedule-index',
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
    {
        path: '/partners/:id',
        name: 'partners.show',
        component: PartnerShow,
    },
    {
        path: '/partners/:id/edit',
        name: 'partners.edit',
        component: PartnerEdit,
    },
    {
        path: '/workers',
        name: 'workers.index',
        component: WorkerIndex,
    },
    { 
        path: '/vehicle-types',
        name: 'vehicle_type.index', 
        component: VehicleTypeIndex },
    {
        path: '/vehicles',
        name: 'vehicle.index', 
        component: VehicleIndex },




];

const router = createRouter({
    routes, // short for `routes: routes`
    history: createWebHistory(),
  })

export default router;