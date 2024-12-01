<template>
    <div>
      <h1>ダンプ配車画面</h1>
      <table>
        <thead>
          <tr>
            <th>車両名</th>
            <th v-for="date in dates" :key="date.id">{{ date.date }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="vehicle in vehicles" :key="vehicle.id">
            <td>{{ vehicle.name }}</td>
            <td v-for="date in dates" :key="date.id">
              <div></div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  import { ref, onMounted } from "vue";
  import axios from "axios";
  
  export default {
    setup() {
      const vehicles = ref([]);
      const dates = ref([]);
  
      const fetchVehicles = async () => {
        try {
          const response = await axios.get("/api/vehicles");
          vehicles.value = response.data.data.filter(vehicle => vehicle.id <= 34);
        } catch (error) {
          console.error("車両データの取得に失敗しました", error);
        }
      };
  
      const fetchDates = async () => {
        try {
          const response = await axios.get("/api/dates");
          dates.value = response.data.data.filter(date => date.id <= 6);
        } catch (error) {
          console.error("日付データの取得に失敗しました", error);
        }
      };
  
      onMounted(() => {
        fetchVehicles();
        fetchDates();
      });
  
      return {
        vehicles,
        dates,
      };
    },
  };
  </script>
  
  <style scoped>
  table {
    width: 100%;
    border-collapse: collapse;
  }
  
  th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
  }
  
  th {
    background-color: #f4f4f4;
  }
  </style>
  