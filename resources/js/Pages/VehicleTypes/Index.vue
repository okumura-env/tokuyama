<template>
    <div>
      <h1>Vehicle Types</h1>
      <ul>
        <li v-for="vehicle_type in vehicle_types" :key="vehicle_type.id">{{ vehicle_type.type_name }}</li>
      </ul>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import { ref, onMounted } from 'vue';
  
  export default {
    setup() {
      const vehicle_types = ref([]);
  
      onMounted(async () => {
        const response = await axios.get('/api/vehicle-types');
        vehicle_types.value = response.data.data;
      });
  
      return { vehicle_types };
    },
  };
  </script>
  