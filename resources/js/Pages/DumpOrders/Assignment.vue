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
  formData.append("dates", JSON.stringify(dates.value));

  try {
    // ファイルをアップロード
    const response = await axios.post("/api/dump-orders/import", formData, {
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

// 1番目の区画: 業務の優先度(task_priority) を取得する関数
const getTaskPriority = (dateId, vehicleId) => {
  const order = orders.value.find(order => order.date_id === dateId && order.vehicle_id === vehicleId);

  if (!order || !order.dumpSchedule || !order.dumpSchedule.date_vehicle || !order.dumpSchedule.date_vehicle.task_priority) {
    return "-"; // デフォルトメッセージ
  }

  return order.dumpSchedule.date_vehicle.task_priority;
};

// 2番目以降の区画: オーダーのタイトル(titles) を取得する関数
const ProcessOrderTitlesAndNumberByDateAndVehicle = (dateId, vehicleId) => {
  // フィルタリング
  const localFilteredOrders = orders.value.filter(order => order.date_id === dateId && order.vehicle_id === vehicleId);

  // マッチするデータがない場合
  if (!localFilteredOrders || localFilteredOrders.length === 0) {
    return Array(4).fill("-"); // データがない場合でも4区画を埋める
  }

  // sort順で並び替え
  const sortedOrders = localFilteredOrders.sort((a, b) => {
    const sortA = a.dumpSchedule?.sort || 0;
    const sortB = b.dumpSchedule?.sort || 0;
    return sortA - sortB;
  });

    // 区画に対応するタイトルを生成
    const result = Array(4).fill("-");
  sortedOrders.forEach(order => {
    const sort = order.dumpSchedule?.sort || 0;
    const title = order.dumpSchedule?.dump_order_category_title || "";
    const boilerNumber = order.boiler_number || "";
    const fullTitle = `${boilerNumber} ${title}`.trim();

    // ソート値に応じた区画にタイトルを配置
    if (sort >= 1 && sort <= 4) {
      result[sort - 1] = fullTitle;
    }
  });

  return result;
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
              <div class="grid-container">
                <!-- 1番目の区画にtask_priorityを表示 -->
                <div class="grid-item">
                  {{ getTaskPriority(date.id, vehicle.id) }}
                </div>
                <!-- 2番目以降の区画にboiler_numberとtitleを表示 -->
                <div v-for="(title, index) in ProcessOrderTitlesAndNumberByDateAndVehicle(date.id, vehicle.id)" :key="index" class="grid-item">
                  {{ title }}
                </div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  

  
<style scoped>
/* グリッドレイアウトのスタイル */
  .grid-container {
    display: grid;
    grid-template-columns: repeat(5, 1fr); /* 5列に分割 */
    gap: 5px; /* 区画間の間隔 */
  }

  .grid-item {
    border: 1px solid #ddd;
    padding: 5px;
    text-align: center;
    background-color: #f9f9f9; /* 背景色 */
    font-size: 12px; /* サイズ調整 */
  }

/* テーブルの基本スタイル */
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
  