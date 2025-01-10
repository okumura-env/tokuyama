<script setup>
import { ref, computed } from "vue";
import { useDisplay } from "vuetify";
import axios from "axios";
import useDataApi from "@/Composables/useDataApi";
import useModal from "@/Composables/useModal";
import CreateDumpScheduleModal from "@/Pages/DumpOrders/CreateDumpScheduleModal.vue";
import EditDumpScheduleModal from "@/Pages/DumpOrders/EditDumpScheduleModal.vue";
import AutoAssignmentScheduleModal from "@/Pages/DumpOrders/AutoAssignmentScheduleModal.vue";

const drawer = ref(false);
const clipped = ref(false);
const { smAndDown } = useDisplay();
const isDesktop = computed(() => !smAndDown.value);
const menuItems = [{ title: "ホーム" }, { title: "設定" }];
const isModalOpen = ref(false);
const dateVehicleData = ref({});

// データ定義
const fileInput = ref(null);
const selectedFile = ref(null);

// データ取得
const { data: vehicles, fetchData: fetchVehicles } = useDataApi("/api/vehicles");
const { data: dates, fetchData: fetchDates } = useDataApi("/api/dates");
const { data: schedules, fetchData: fetchDumpSchedules } = useDataApi("/api/dump-schedules");
const { data: mcmCoalUsageSchedules, fetchData: fetchMcmCoalUsageSchedules } = useDataApi("/api/mcm-coal-usage-schedules");

// フィルタリング処理
const filteredVehicles = computed(() =>
    vehicles.value.filter((vehicle) => vehicle.id <= 34)
);

const filteredMcmCoalUsageSchedules = computed(() =>
    mcmCoalUsageSchedules.value.filter((mcmCoalUsageSchedules) => mcmCoalUsageSchedules.id >= 7)
);

// 自動配車登録用の日付データ
const dateData = computed(() => {
    return dates.value
    .filter((date) => date.id >= 7) // IDが7以上のデータをフィルタリング
    .map((date, index) => ({
        ...date,
        mcmQuantity: filteredMcmCoalUsageSchedules.value[index]?.planned_amount,
      }));
    });

//日付を表示できる形に整形(例：12/4(月)(320))
const dateWithQuantities = computed(() => {
  return dates.value
    .filter((date) => date.id >= 7) // IDが7以上のデータをフィルタリング
    .map((date, index) => {
      const shortDayOfWeek = date.day_of_week.replace("曜日", ""); // "曜日" を省略
      const jsDate = new Date(date.date);
      const formattedDate = `${jsDate.getMonth() + 1}/${jsDate.getDate()}`; // 月/日形式に整形
      return `${formattedDate}(${shortDayOfWeek})(${filteredMcmCoalUsageSchedules.value[index]?.planned_amount})`;
    });
});

// モーダルの開閉ロジック
// 手動新規登録モーダル
const {     
          isModalOpen: isCreateModalOpen,
          openModal: openCreateModal,
          closeModal: closeCreateModal,
       } = useModal();

// 手動編集モーダル
const {     
          isModalOpen: isEditModalOpen,
          openModal: openEditModal,
          closeModal: closeEditModal,
       } = useModal();

// 自動配車用モーダル
const {     
    isModalOpen: isAutoAssignmentModalOpen,
    openModal: openAutoAssignmentModal,
    closeModal: closeAutoAssignmentModal,
} = useModal();
       
// 新規登録の場合
// createModalData.value = {date:2024-12-04, date_id: 8 , vehicle_id: 1};
// 編集の場合
// editModalData.value = {
//                          id: 1,
//                          date_id: 8,
//                          vehicle_id: 1,
//                          date:Object, 
//                          dateVehicle: Object, 
//                          dumpOrder:{
//                                      id: 2,
//                                      date_id: "8"
//                                      vehicle_id: "1"
//                                      dump_schedule_id: 2,
//                                      boiler_number: "1",
//                                      is_preloaded: 0,
//                                      status: "1",
//                                      note: "テスト",
//                                      vehicle_number: null,
//                                      },
//                          dump_order_category_title: "リデ"
//                          dump_order_category_id: 1,
//                          dump_order_category_title_id: 2,
//                          sort: 1,
//                          schedule_type: "orders",
//                        };
const editModalData = ref({});
const createModalData = ref({});
const clickCell = async(scheduleId,date,vehicleId) => {
    //scheduleIdがある場合は編集、ない場合は新規登録
    if(scheduleId){
      console.log("編集")
      openEditModal();
      const response = await axios.get(`api/dump-schedules/${scheduleId}`);
      editModalData.value = response.data.data;
      console.log(editModalData.value);
    }else{
        console.log("新規登録")
        openCreateModal();
        createModalData.value = {date:date.date, date_id: date.id, vehicle_id: vehicleId};
        console.log(createModalData.value);
    }
    
};

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
    formData.append("dates", JSON.stringify(dateData.value));

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
const getTaskPriority = (dateId, vehicleId, defaultPriority = "-") => {
    const schedule = schedules.value.find(
        (schedules) =>
            schedules.date_id === dateId && schedules.vehicle_id === vehicleId
    );
    return schedule?.dateVehicle?.task_priority || defaultPriority; 
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
// メイン関数：2番目以降の区画のオーダーのタイトル(titles)とボイラー番号を処理
const getScheduleSections = (dateId, vehicleId) => {
    // 該当日付と車両に対応するダンプスケジュールを取得
    const localFilteredSchedules = filteredSchedules(dateId, vehicleId);

    // データがない場合の処理
    if (!localFilteredSchedules.length === 0) {
        return Array(5).fill("-"); // データがない場合でも4区画を埋める
    }

    // ソート済みスケジュールの取得
    const sortedSchedules = sortSchedulesByOrder(localFilteredSchedules);

    // ソートされたスケジュールから区画データを作成
    return createSectionFromSchedules(sortedSchedules,5);
};

// サブ関数: フィルタリング処理
const filteredSchedules = (dateId, vehicleId) => {
    return schedules.value.filter(
        (schedule) =>
            schedule.date_id === dateId && schedule.vehicle_id === vehicleId
    );
};

// サブ関数: スケジュールをソート
const sortSchedulesByOrder = (schedules) => {
    return schedules.sort((a, b) => {
        const sortA = a.sort || "";
        const sortB = b.sort || "";
        return sortA - sortB;
    });
};

// サブ関数: スケジュールから区画データを作成
const createSectionFromSchedules = (schedules,sectionCount) => {
    const result = Array(sectionCount).fill("-");
    schedules.forEach((schedule) => {
        const sort = schedule.sort || "";
        const dumpScheduleId = schedule.id || "";
        const title = schedule.dump_order_category_title || "";
        const boilerNumber = schedule.dumpOrder?.boiler_number || "";
        const fullTitle = `${boilerNumber} ${title}`.trim();

        if (sort >= 1 && sort <= sectionCount) {
            result[sort - 1] = [fullTitle, dumpScheduleId];
        }
    });

    return result;
};

// ドラッグされている要素とドロップ先を追跡するための変数
const draggedItem = ref(null);
const dropTarget = ref(null);

// ドラッグ開始イベント
const handleDragStart = (event, item) => {
  draggedItem.value = item;
  event.dataTransfer.effectAllowed = "move";
};

// ドラッグオーバーイベント
const handleDragOver = (event, item) => {
  event.preventDefault(); // ドロップを許可
  dropTarget.value = item;
  // 入れ替え可能なドロップターゲットをハイライト
  if (event.target.classList.contains("grid-item")) {
    event.target.classList.add("highlight");
  }
};

// ドラッグリーブイベント
const handleDragLeave = (event) => {
  // ハイライトを解除
  if (event.target.classList.contains("grid-item")) {
    event.target.classList.remove("highlight");
  }
};

// ドロップイベント
const handleDrop = (event) => {
  event.preventDefault();

  if (draggedItem.value && dropTarget.value) {
    // 入れ替え処理
    const draggedContent = draggedItem.value.innerHTML;
    const dropContent = dropTarget.value.innerHTML;

    draggedItem.value.innerHTML = dropContent;
    dropTarget.value.innerHTML = draggedContent;
  }

  // ハイライトを解除
  if (dropTarget.value?.classList.contains("highlight")) {
    dropTarget.value.classList.remove("highlight");
  }

  // 追跡変数のリセット
  draggedItem.value = null;
  dropTarget.value = null;
};

// ドラッグ終了イベント
const handleDragEnd = (event) => {
  // ハイライトをすべて解除
  document.querySelectorAll(".highlight").forEach((el) => {
    el.classList.remove("highlight");
  });
  draggedItem.value = null;
  dropTarget.value = null;
};
</script>

<template>
    <v-app>
        <!-- サイドバー -->
        <v-navigation-drawer
            v-model="drawer"
            app
            :clipped="clipped"
            :permanent="isDesktop"
            class="sidebar"
        >
            <v-list>
                <v-list-item>
                    <v-list-item-title>メニュー</v-list-item-title>
                </v-list-item>
                <v-list-item v-for="item in menuItems" :key="item.title">
                    <v-list-item-title>{{ item.title }}</v-list-item-title>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>

        <!-- ヘッダー -->
        <v-app-bar app class="header">
            <v-btn icon @click="drawer = !drawer">
                <v-icon>{{ drawer ? 'mdi-menu-open' : 'mdi-menu' }}</v-icon>
            </v-btn> 
            <v-toolbar-title>トクヤマ海陸運送</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn text>ログアウト</v-btn>
        </v-app-bar>

        <!-- メインコンテンツ -->
        <v-main>
            <v-container>
                <h1>ダンプ配車作成</h1>

                <v-row>
                  <v-col cols="3" md="2">
                    <v-btn @click="openAutoAssignmentModal()" block color="primary">配車登録</v-btn>
                  </v-col>
                  <v-col cols="3" md="2">
                    <v-btn @click="triggerFileSelect" block>ファイルを選択</v-btn>
                  </v-col>
                  <v-file-input
                      type="file"
                      ref="fileInput"
                      @change="handleFileSelect"
                      style="display: none"
                  ></v-file-input>
                  <v-col cols="3" md="2">
                    <v-btn @click="importData" block color="primary">インポート</v-btn>
                  </v-col>
                </v-row>
                

                <!-- テーブル -->
                <v-simple-table class="mt-4">
                    <template v-slot:default>
                        <thead>
                            <tr>
                                <th>車両名/日付</th>
                                <th v-for="dateWithQuantity in dateWithQuantities">
                                    {{ dateWithQuantity }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="vehicle in filteredVehicles" :key="vehicle.id">
                                <td class="nowrap">{{ vehicle.name }}</td>
                                <td v-for="date in dateData"
                                   :key="date.id"
                                   
                                   >
                                    <div class="grid-container">
                                        <!-- 1番目の区画にtask_priorityを表示 -->
                                        <div class="grid-item">
                                            {{
                                                getTaskPriority(
                                                    date.id,
                                                    vehicle.id
                                                )
                                            }}
                                        </div>
                                        <!-- 2番目以降の区画にboiler_numberとtitleを表示 -->
                                        <div
                                            v-for="(
                                                scheduleTitleAndId, index
                                            ) in getScheduleSections(
                                                date.id,
                                                vehicle.id
                                            )"
                                            :key="index"
                                            @click="clickCell(scheduleTitleAndId[1],date,vehicle.id)"
                                            class="grid-item movable-item"
                                            draggable="true"
                                            @dragstart="handleDragStart($event, $event.target)"
                                            @dragover="handleDragOver($event, $event.target)"
                                            @dragleave="handleDragLeave"
                                            @drop="handleDrop"
                                            @dragend="handleDragEnd"
                                        >
                                            {{ scheduleTitleAndId[0] }}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </template>
                </v-simple-table>

                <!-- モーダル -->
                <CreateDumpScheduleModal  
                  :isCreateModalOpen = "isCreateModalOpen" 
                  :scheduleData = "createModalData"
                  @close="closeCreateModal" 
                  @refetch="fetchDumpSchedules"
                   />
                <EditDumpScheduleModal
                    :isEditModalOpen = "isEditModalOpen"
                    :scheduleData = "editModalData"
                    @close="closeEditModal"     
                    @refetch="fetchDumpSchedules"
                   />
                <AutoAssignmentScheduleModal
                    :isAutoAssignmentModalOpen = "isAutoAssignmentModalOpen"
                    :dateData = "dateData"
                    @close="closeAutoAssignmentModal" 
                    @refetch="fetchDumpSchedules"
                />
            </v-container>
        </v-main>
    </v-app>
</template>

<style scoped>
/* グリッドレイアウトのスタイル */
.grid-container {
    display: grid;
    grid-template-columns: repeat(6, 1fr); /* 5列に分割 */
    gap: 5px; /* 区画間の間隔 */
}

.grid-item {
    border: 1px solid #ddd;
    padding: 5px;
    text-align: center;
    font-size: 12px; /* サイズ調整 */
}

/* ハイライトのスタイル */
.highlight {
  background-color: #ffeb3b;
  border: 2px dashed #f57c00;
}

/* ヘッダー */
.v-app-bar.v-toolbar {
  background-color: var(--v-primary-base);
  color: var(--v-on-primary);
}

.sidebar {
  background-color: var(--v-primary-base);
}

@media (prefers-color-scheme: dark) {
  .sidebar {
    background-color: #1A3A86;
    color: #ffffff;
  }

  .v-app-bar.v-toolbar {
    background-color: #1A3A86;
    color: #ffffff;
  }
}

/* nowrap クラスの追加 */
.nowrap {
    white-space: nowrap;
}

/* テーブルの基本スタイル */
table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

th {
    background-color: #f4f4f4;
}
</style>
