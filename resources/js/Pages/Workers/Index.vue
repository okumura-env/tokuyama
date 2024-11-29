<template>
    <div>
      <h1>Workers</h1>
      <ul>
        <li v-for="worker in workers" :key="worker.id">{{ worker.name }}</li>
      </ul>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import { ref, onMounted } from 'vue';
  
  export default {
    setup() {
      const workers = ref([]);
  
      onMounted(async () => {
        const response = await axios.get('/api/workers');
        workers.value = response.data.data;
      });
  
      return { workers };
    },
  };
  </script>
  