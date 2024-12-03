<script>
import { ref, onMounted } from "vue";
import axios from "axios";
import useDataApi from "../../Composables/useDataApi";

export default {
  setup() {
    const vehicles = ref([]);
    const dates = ref([]);
    const fileInput = ref(null);
    const selectedFile = ref(null);

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

    const triggerFileSelect = () => {
      fileInput.value.click(); // ファイル入力要素をプログラム的にクリック
    };
    const handleFileSelect = (event) => {
      selectedFile.value = event.target.files[0]; // 選択されたファイルを取得
      console.log("選択されたファイル:", selectedFile.value);
    };

    const importData = async () => {
      if (!selectedFile.value) {
        alert("ファイルを選択してください！");
        return;
      }

      const formData = new FormData();
      formData.append("file", selectedFile.value);

      try {
        const response = await axios.post("/api/import-dump-orders", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        });
        console.log("インポート成功:", response.data);
        alert("データが正常にインポートされました！");
      } catch (error) {
        console.error("インポートに失敗しました", error);
        alert("インポートに失敗しました");
      }
    };

    onMounted(() => {
      fetchVehicles();
      fetchDates();
    });

    return {
      vehicles,
      dates,
      fileInput,
      triggerFileSelect,
      handleFileSelect,
      importData,
    };
  },
};
</script>

<template>
    <div>
      <h1>ダンプ配車画面</h1>

      <div>
        <button @click="triggerFileSelect">ファイルを選択</button>
          <input type="file" ref="fileInput" @change="handleFileSelect" style="display: none;" />
        <button @click="importData">インポート</button>
      </div>

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
  