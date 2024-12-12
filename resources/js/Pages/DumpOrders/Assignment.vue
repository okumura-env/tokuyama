<script setup>
import { ref, onMounted, computed } from "vue";
import { useDisplay } from "vuetify";
import axios from "axios";
import useDataApi from "../../Composables/useDataApi";

const drawer = ref(false);
const clipped = ref(false);
const { smAndDown } = useDisplay();
const isDesktop = computed(() => !smAndDown.value);
const menuItems = [{ title: "ホーム" }, { title: "設定" }];

// データ定義
const fileInput = ref(null);
const selectedFile = ref(null);

/**
 * 車両の取得
 */
const { data:vehicles , fetchData:fetchVehicles } = useDataApi(
    "/api/vehicles",
    (data) => data.filter(vehicle => vehicle.id <= 34)
  );

/**
 * 日付の取得
 */
const { data:dates , fetchData:fetchDates } = useDataApi(
  "/api/dates",
  (data) => data.filter(date => date.id >= 7)
);
  
/**
 * ダンプスケジュールの取得
 */
const { data:schedules , fetchData:fetchDumpSchedules } = useDataApi("/api/dump-schedules");

/**
 * ファイル選択トリガー
 */
const triggerFileSelect = () => {
  fileInput.value?.click(); // ?.演算子を使用して安全にアクセス
};

/**
 * ファイル選択時の処理
 * @param {Event} event
 * 
 */
const handleFileSelect = (event) => {
  selectedFile.value = event.target.files[0];
  console.log("選択されたファイル:", selectedFile.value);
};

/**
 * データのインポート
 */
const importData = async () => {
  if (!selectedFile.value) {
    alert("ファイルを選択してください！");
    return;
  }

  /**
   * FormDataオブジェクトの生成
   */
  const formData = new FormData();
  formData.append("file", selectedFile.value);
  formData.append("dates", JSON.stringify(dates.value));

  try {
    // ファイルをアップロード
    const response = await axios.post("/api/dump-orders/import", formData, {
      headers: { "Content-Type": "multipart/form-data" },
    });
    console.log("インポート成功:", response.data);

    // インポート後のデータ再取得
    try {
      await fetchDumpSchedules();
      console.log("ダンプオーダーの再取得が成功しました:");
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

/**
 * ダンプオーダーの業務の優先度(task_priority)を取得する関数
 * 1番目の区画に表示
 * @param {number} dateId
 * @param {number} vehicleId
 * @returns {string}
 */
const getTaskPriority = (dateId, vehicleId) => {
  const schedule = schedules.value.find(schedules => schedules.date_id === dateId && schedules.vehicle_id === vehicleId);

  if (!schedule || !schedule.dateVehicle || !schedule.dateVehicle.task_priority) {
    return "-"; // デフォルトメッセージ
  }

  return schedule.dateVehicle.task_priority;
};

/**
 * 該当日付と車両に対応するダンプオーダーを取得し、
 * オーダーのタイトル(titles)とボイラー番号(boiler_number)を取得し、
 * 並べ替えまで行う関数
 * 2番目以降の区画に表示
 * @param {number} dateId
 * @param {number} vehicleId
 * @returns {string[]}
 * 
 */
// 2番目以降の区画: オーダーのタイトル(titles) を取得する関数
const ProcessOrderTitlesAndNumberByDateAndVehicle = (dateId, vehicleId) => {
  // 該当日付と車両に対応するダンプスケジュールを取得
  const localFilteredSchedules = schedules.value.filter(schedule => schedule.date_id === dateId && schedule.vehicle_id === vehicleId);
  
  // マッチするデータがない場合
  if (!localFilteredSchedules || localFilteredSchedules.length === 0) {
    return Array(4).fill("-"); // データがない場合でも4区画を埋める
  }

    // sort順で並び替え
    const sortedSchedules = localFilteredSchedules.sort((a, b) => {
    const sortA = a.sort || "";
    const sortB = b.sort || "";
    return sortA - sortB;
  });

    // 区画に対応するタイトル、ボイラー番号、ソート値を取得
    const result = Array(4).fill("-");
    sortedSchedules.forEach(schedule => {
      const sort = schedule.sort|| "";
      const title = schedule.dump_order_category_title|| "";
      const boilerNumber = schedule.dumpOrder?.boiler_number || "";
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
  fetchDumpSchedules();
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
  