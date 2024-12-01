<template>
    <div>
      <h1>Vehicles</h1>
      <ul>
        <li v-for="vehicle in vehicles" :key="vehicle.id">
          {{ vehicle.name }} - {{ vehicle.code }}
        </li>
      </ul>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import { ref } from 'vue';
  
  export default {
    setup() {
      const vehicles = ref([]);
      axios.get('/api/vehicles').then((response) => {
        vehicles.value = response.data.data;
      });
      return { vehicles };
    },
  };
  </script>
  