<template>
    <div>
      <h1>Vehicle Types</h1>
      <ul>
        <li v-for="vehicle in vehicles" :key="vehicle.id">{{ vehicle.type_name }}</li>
      </ul>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import { ref, onMounted } from 'vue';
  
  export default {
    setup() {
      const vehicles = ref([]);
  
      onMounted(async () => {
        const response = await axios.get('/api/vehicle-types');
        vehicles.value = response.data.data;
      });
  
      return { vehicles };
    },
  };
  </script>
  