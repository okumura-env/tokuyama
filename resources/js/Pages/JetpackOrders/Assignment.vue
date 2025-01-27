<script setup>
import { ref, computed, onMounted } from "vue";
import { useRoute } from "vue-router";
import useDataApi from "@/Composables/useDataApi";
import axios from "axios";

// ルートから日付を取得
const route = useRoute();
const date = route.query.date;
const dateId = route.params.dateId;

// データの状態
const tableData = ref([]); // テーブルデータの初期状態（空のセル）
const dispatchedOrders = ref([]); // 配車済みオーダー
const undispatchedOrders = ref([]); // 未配車オーダー

// ドラッグ中のアイテム
const draggingItem = ref(null);

// データ取得
const { data: vehicles, fetchData: fetchVehicles } = useDataApi("/api/vehicles");
const { data: workers, fetchData: fetchWorkers } = useDataApi("/api/workers");

// 日付フォーマット
const formattedDate = computed(() => {
  const dateObj = new Date(date);
  return `${dateObj.getMonth() + 1}月${dateObj.getDate()}日`;
});

const dayOfWeek = computed(() => {
  const dateObj = new Date(date);
  return ["日", "月", "火", "水", "木", "金", "土"][dateObj.getDay()];
});

// APIから日毎のジェットパックのオーダーを取得
const fetchJetpackOrdersByDate = async (dateId) => {
  try {
    console.log(dateId);
    const response = await axios.get(`/api/jetpack-orders-by-date/${dateId}`);
    const JetpackOrdersByDate = response.data.data;
    dispatchedOrders.value = JetpackOrdersByDate.filter((order) => order.status);
    undispatchedOrders.value = JetpackOrdersByDate.filter((order) => !order.status);
  } catch (error) {
    console.error("Error fetching unassigned dispatches:", error);
  }
};

// テーブルの初期化（空の行データを作成）
const initializeTableData = () => {
  tableData.value = vehicles.value.map((vehicle) => ({
    vehicle_number: vehicle.number,
    selectedWorkerId: "",
    workerDetail: "",
    startTime: "",
    // destinationを3つ分用意し回数もそれぞれに紐づけられるようにする
    destination1: "",
    rounds1: "",
    destination2: "",
    rounds2: "",
    destination3: "",
    rounds3: "",
    jetpack_order1_id: null,
    jetpack_order2_id: null,
    jetpack_order3_id: null,
    notes: "",
  }));
};

// 未配車リストでドラッグを開始するときのハンドラー
// "from: 'undispatched'" を付与
const handleDragStartFromUndispatched = (dispatch) => {
  draggingItem.value = {
    id: dispatch.id,
    name: dispatch.jetpack_destination_name,
    from: "undispatched", // 未配車から来たことがわかるように
  };
};

// テーブル上のセル（すでに何かが入力されているセル）でドラッグを開始するときのハンドラー
// "from: 'table'" と、セル位置を記録
const handleDragStartFromTable = (name, rowIndex, colIndex) => {
  // セルが空の場合はドラッグしない
  if (!name) return;
  draggingItem.value = {
    id,
    name,
    from: "table",
    rowIndex,
    colIndex,
  };
};

// ドロップ処理
const handleDrop = (rowIndex, colIndex) => {
  if (draggingItem.value) {
    // 1) 未配車から来た場合は未配車リストから削除
    if (draggingItem.value.from === "undispatched") {
      undispatchedOrders.value = undispatchedOrders.value.filter(
        (order) => order.id !== draggingItem.value.id
      );
    }
    // 2) すでにテーブル上のセルにあった場合は、ドラッグ元を空にする
    else if (draggingItem.value.from === "table") {
      const { rowIndex: fromRow, colIndex: fromCol } = draggingItem.value;
      if (fromCol === 0) {
        tableData.value[fromRow].destination1 = "";
        tableData.value[fromRow].jetpack_order1_id = null; // <-- IDも消す
      } else if (fromCol === 1) {
        tableData.value[fromRow].destination2 = "";
        tableData.value[fromRow].jetpack_order2_id = null;
      } else if (fromCol === 2) {
        tableData.value[fromRow].destination3 = "";
        tableData.value[fromRow].jetpack_order3_id = null;
      }
    }

    // 3) ドロップ先のセルへ値をセット
    const droppedName = draggingItem.value.name;
    const droppedId = draggingItem.value.id || null;

    if (colIndex === 0) {
      tableData.value[rowIndex].destination1 = draggingItem.value.name;
      tableData.value[rowIndex].jetpack_order1_id = droppedId;
    } else if (colIndex === 1) {
      tableData.value[rowIndex].destination2 = draggingItem.value.name;
      tableData.value[rowIndex].jetpack_order2_id = droppedId;
    } else if (colIndex === 2) {
      tableData.value[rowIndex].destination3 = draggingItem.value.name;
      tableData.value[rowIndex].jetpack_order3_id = droppedId;
    }

    // ドラッグが完了したらリセット
    draggingItem.value = null;
  }
};

// 配車内容を保存するボタン動作（サンプル）
const saveAdjustments = () => {
  // ここは既存機能を想定（例）
  console.log("配車内容保存:", tableData.value);
};

// コンポーネントマウント時にデータ取得
onMounted(async () => {
  await fetchJetpackOrdersByDate(dateId);
  initializeTableData();
});
</script>

<template>
  <div class="dispatch-adjustment">
    <header>
      <h2>{{ formattedDate }}（{{ dayOfWeek }}）</h2>
      <router-link to="/jetpack-schedules/index" class="back-btn"
        >戻る</router-link
      >
    </header>

    <div class="layout">
      <!-- 未配車一覧 -->
      <aside class="unassigned-list">
        <div class="unassigned-list-border">
          <div class="unassigned-list-header">
            <h3>未配車一覧</h3>
            <button class="assign-btn">登録</button>
          </div>
          <div class="dispatch-card-container">
            <div
              class="dispatch-card"
              v-for="dispatch in undispatchedOrders"
              :key="dispatch.id"
              draggable="true"
              @dragstart="handleDragStartFromUndispatched(dispatch)"
            >
              <div class="dispatch-card-header">
                {{ dispatch.jetpack_destination_name }}
              </div>
            </div>
          </div>
        </div>
      </aside>

      <!-- 配車調整テーブル -->
      <main class="adjustment-table">
        <div class="table-wrapper">
          <table>
            <thead>
              <tr>
                <th>番号</th>
                <th>車両番号</th>
                <th>運転手</th>
                <th>運転手備考</th>
                <th class="narrow-column">始業開始時間</th>
                <th>搬入先</th>
                <th class="narrow-column">回数</th>
                <th>搬入先</th>
                <th class="narrow-column">回数</th>
                <th>搬入先</th>
                <th class="narrow-column">回数</th>
                <th>その他備考</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(row, index) in tableData" :key="index">
                <td>{{ index + 1 }}</td>
                <td>{{ row.vehicle_number }}</td>
                <td>
                  <select
                    class="form-control"
                    v-model="row.selectedWorkerId"
                  >
                    <option
                      v-for="worker in workers"
                      :value="worker.id"
                      :key="worker.id"
                    >
                      {{ worker.name }}
                    </option>
                  </select>
                </td>
                <td>
                  <input
                    type="text"
                    v-model="row.workerDetail"
                    placeholder="運転手備考を入力"
                  />
                </td>
                <td class="narrow-column">
                  <input type="time" v-model="row.startTime" placeholder="時間" />
                </td>

                <!-- 1つ目の搬入先 -->
                <td
                  @dragover.prevent
                  @drop="handleDrop(index, 0)"
                  :draggable="row.destination1 !== ''"
                  @dragstart="handleDragStartFromTable(row.destination1, index, 0)"
                >
                  <input
                    type="text"
                    v-model="row.destination1"
                    placeholder="搬入先を入力"
                    draggable="false"
                  />
                </td>
                <td class="narrow-column">
                  <input
                    type="number"
                    v-model="row.rounds1"
                    placeholder=""
                  />
                </td>

                <!-- 2つ目の搬入先 -->
                <td
                  @dragover.prevent
                  @drop="handleDrop(index, 1)"
                >
                  <input
                    type="text"
                    v-model="row.destination2"
                    placeholder="搬入先を入力"
                    :draggable="row.destination2 !== ''"
                    @dragstart="handleDragStartFromTable(row.destination2, index, 1)"
                  />
                </td>
                <td class="narrow-column">
                  <input
                    type="number"
                    v-model="row.rounds2"
                    placeholder=""
                  />
                </td>

                <!-- 3つ目の搬入先 -->
                <td
                  @dragover.prevent
                  @drop="handleDrop(index, 2)"
                  :draggable="row.destination3 !== ''"
                  @dragstart="handleDragStartFromTable(row.destination3, index, 2)"
                >
                  <input
                    type="text"
                    v-model="row.destination3"
                    placeholder="搬入先を入力"
                    draggable="false"
                  />
                </td>
                <td class="narrow-column">
                  <input
                    type="number"
                    v-model="row.rounds3"
                    placeholder=""
                  />
                </td>

                <td>
                  <input
                    type="text"
                    v-model="row.notes"
                    placeholder="その他備考"
                  />
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <button @click="saveAdjustments">確定</button>
      </main>
    </div>
  </div>
</template>

<style scoped>
.dispatch-adjustment {
  display: flex;
  flex-direction: column;
  padding: 16px;
  height: 100vh; /* 画面全体の高さを確保 */
}

header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 16px;
}

.layout {
  display: flex;
  height: calc(100% - 64px); /* ヘッダー分を引いた高さ */
}

.unassigned-list {
  width: 20%;
  margin-right: 16px;
  height: 100%;
}

.unassigned-list-border {
  border: 2px solid #ddd;
  padding: 8px;
  border-radius: 8px;
  background-color: #f9f9f9;
  overflow-y: auto;
  height: 100%; /* テーブルと同じ高さ */
}

.unassigned-list-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 8px;
}

.dispatch-card-container {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.dispatch-card {
  border: 2px solid #ddd;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.dispatch-card-header {
  color: #000;
  padding: 8px;
  font-weight: bold;
  text-align: center;
  background-color: #f0f0f0;
}

.adjustment-table {
  width: 80%;
}

.table-wrapper {
  overflow-x: auto; /* 横スクロール可能に設定 */
}

table {
  width: 100%;
  border-collapse: collapse;
  min-width: 1200px; /* 横スクロールを確保するための最小幅 */
}

th,
td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: center;
}

th.narrow-column,
td.narrow-column {
  width: 60px;
  max-width: 60px;
  text-align: center;
}

button {
  padding: 8px 16px;
  background-color: #28a745;
  color: white;
  border: none;
  cursor: pointer;
}

button:hover {
  background-color: #218838;
}

.assign-btn {
  padding: 4px 8px;
  font-size: 12px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.assign-btn:hover {
  background-color: #0056b3;
}
</style>
