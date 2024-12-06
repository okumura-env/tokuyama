<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

// データ定義
const vehicles = ref([]);
const dates = ref([]);
const orders = ref([]);
const fileInput = ref(null);
const selectedFile = ref(null);

// 車両データの取得
const fetchVehicles = async () => {
  try {
    const { data } = await axios.get("/api/vehicles");
    vehicles.value = data.data.filter(vehicle => vehicle.id <= 34);
  } catch (error) {
    console.error("車両データの取得に失敗しました", error);
  }
};

// 日付データの取得
const fetchDates = async () => {
  try {
    const { data } = await axios.get("/api/dates");
    dates.value = data.data.filter(date => date.id >= 7);
  } catch (error) {
    console.error("日付データの取得に失敗しました", error);
  }
};

// ダンプオーダーの取得
const fetchDumpOrders = async () => {
  try {
    const { data } = await axios.get("/api/dump-orders");
    orders.value = data.data;
  } catch (error) {
    console.error("ダンプオーダーの取得に失敗しました", error);
  }
};

// ファイル選択トリガー
const triggerFileSelect = () => {
  fileInput.value?.click(); // ?.演算子を使用して安全にアクセス
};

// ファイル選択ハンドラー
const handleFileSelect = (event) => {
  selectedFile.value = event.target.files[0];
  console.log("選択されたファイル:", selectedFile.value);
};

// データインポート
const importData = async () => {
  if (!selectedFile.value) {
    alert("ファイルを選択してください！");
    return;
  }

  const formData = new FormData();
  formData.append("file", selectedFile.value);

  try {
    // ファイルをアップロード
    const response = await axios.post("/api/import-dump-orders", formData, {
      headers: { "Content-Type": "multipart/form-data" },
    });
    await fetchDumpOrders(); // 再取得
    console.log("インポート成功:", response.data);

    // インポート後のデータ再取得
    try {
      await fetchDumpOrders();
      console.log("ダンプオーダーの再取得が成功しました:", orders.value);
    } catch (fetchError) {
      console.error("ダンプオーダーの再取得に失敗しました:", fetchError);
      alert("インポートは成功しましたが、データの再取得に失敗しました。");
    }

    alert("データが正常にインポートされました！");
  } catch (error) {
    console.error("インポートに失敗しました", error);
    alert("インポートに失敗しました");
  }
};

// 1マスごと（同一日付・同一車両）のオーダーを取得する関数
const getOrderTitle = (dateId, vehicleId) => {
  // フィルタリング
  const localFilteredOrders = orders.value.filter(order => order.date_id === dateId && order.vehicle_id === vehicleId);

  // マッチするデータがない場合
  if (!localFilteredOrders || localFilteredOrders.length === 0) {
    return "データなし";
  }

  // dumpScheduleの存在をチェックし、タイトルを取得
  const titles = localFilteredOrders.map(order => {
    const dumpSchedule = order.dumpSchedule;
    if (!dumpSchedule || !dumpSchedule.dump_order_category_title) {
      console.warn("dumpScheduleが見つかりません");
      return "不明";
    }
    return dumpSchedule.dump_order_category_title;
  });

  return titles.join(", ");
};

// マウント時に初期データを取得
onMounted(() => {
  fetchVehicles();
  fetchDates();
  fetchDumpOrders();
});
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
              <div>
                <p v-if="!orders || orders.length === 0">読み込み中...</p>
                <p v-else>{{ getOrderTitle(date.id, vehicle.id) }}</p>
              </div>
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
  