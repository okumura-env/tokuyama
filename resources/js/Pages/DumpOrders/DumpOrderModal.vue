<script setup>
import { ref, watch } from "vue";
import axios from "axios";
import useDataApi from "@/Composables/useDataApi";

const props = defineProps({
    isModalOpen: Boolean,
    dateVehicleData: Object,
});

const emit = defineEmits(["close"]);

const formData = ref({
    date: "", // 日付文字列
    date_id: "",
    vehicle_id: "",
    dump_schedule_id: "1",// 仮の初期値
    dump_order_category_id: "",
    dump_order_category_title_id: "",
    boiler_number: "",
    status: true,
    is_preloaded: false,
    note: "",
});

watch(
  () => props.dateVehicleData,
  (newData) => {
    if (newData) {
      formData.value.date = newData.date?.date || "";
      formData.value.date_id = newData.date?.id || "";
      formData.value.vehicle_id = newData.vehicle?.id || "";
    }
  },
  { immediate: true } // 初期値設定のために即時実行
);

const filteredTitles = ref([]);
watch(
  () => formData.value.dump_order_category_id,
  (newCategoryId) => {
    if (newCategoryId) {
      filteredTitles.value = dumpOrderCategoryTitles.value.filter(
        (title) => title.dump_order_category_id === newCategoryId
      );
    } else {
      filteredTitles.value = dumpOrderCategoryTitles.value;
    }
  }
);

const registerOrder = async() => {
    const response = await axios.post("/api/dump-orders", formData.value);
    console.log("登録ボタンが押されました");
    emit("close");
};

// データ取得
const { data: vehicles, fetchData: fetchVehicles } = useDataApi("/api/vehicles");
const { data: dumpOrderCategories, fetchData: fetchDumpOrderCategories } = useDataApi("/api/dump-order-categories");
const { data: dumpOrderCategoryTitles, fetchData: fetchDumpOrderCategoryTitles } = useDataApi("/api/dump-order-category-titles");


</script>
<template>
    <Teleport to="body">
        <div class="modal" id="modal-1" v-show="isModalOpen">
            <div
                class="modal__overlay"
                tabindex="-1"
                @click.self="emit('close')"
            >
                <div
                    class="modal__container w-2/3"
                    role="dialog"
                    aria-modal="true"
                    aria-labelledby="modal-1-title"
                >
                    <header class="modal__header">
                        <h2 class="modal__title" id="modal-1-title">
                            受注登録
                        </h2>
                        <button @click="emit('close')" class="close-button">✖</button>
                    </header>
                    <main class="modal__content" id="modal-1-content">
                        <div class="container px-5 py-8 mx-auto">
                            <div class="lg:w-2/3 w-full mx-auto overflow-auto">
                                <form @submit.prevent="registerOrder" class="form-container">
                                    <div class="form-group">
                                        <label for="date" class="form-label">日付</label>
                                        <input
                                            id = "date"
                                            name = "date"
                                            v-model="formData.date"
                                            type="date"
                                            class="form-input"
                                        />
                                    </div>
                                    <div class="form-group">
                                        <label for="vehicles" class="form-label">車両</label>
                                        <select
                                                id = "vehicles"
                                                name = "vehicles"
                                                class="form-input"
                                                v-model="formData.vehicle_id"
                                            >
                                            <option
                                                v-for="vehicle in vehicles"
                                                :key="vehicle.id"
                                                :value="vehicle.id"
                                            >
                                                {{ vehicle.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="dump-order-categories" class="form-label">カテゴリー</label>
                                        <select
                                                id = "dump-order-categories"
                                                name = "dump-order-categories"
                                                class="form-input"
                                                v-model="formData.dump_order_category_id"
                                            >
                                            <option
                                                v-for="dumpOrderCategory in dumpOrderCategories"
                                                :key="dumpOrderCategory.id"
                                                :value="dumpOrderCategory.id"
                                            >
                                                {{ dumpOrderCategory.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="dump-order-category-titles" class="form-label">タイトル</label>
                                        <select
                                                id = "dump-order-category-titles"
                                                name = "dump-order-category-titles"
                                                class="form-input"
                                                v-model="formData.dump_order_category_title_id"
                                            >
                                            <option
                                                v-for="dumpOrderCategoryTitle in filteredTitles"
                                                :key="dumpOrderCategoryTitle.id"
                                                :value="dumpOrderCategoryTitle.id"
                                            >
                                                {{ dumpOrderCategoryTitle.title }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="boiler-number" class="form-label">ボイラー番号</label>
                                        <select 
                                            id = "boiler-number"
                                            name = "boiler-number"
                                            class="form-input"
                                            v-model="formData.boiler_number">
                                            <option value="1">1</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">ステータス</label>
                                        <div class="form-radio-group">
                                            <label for="status_unassigned">
                                                <input 
                                                    type="radio" 
                                                    id="status_unassigned" 
                                                    name="status" 
                                                    v-model="formData.status" 
                                                    :value="false"
                                                />
                                                未配車
                                            </label>
                                            <label for="status_assigned">
                                                <input 
                                                    type="radio" 
                                                    id="status_assigned" 
                                                    name="status" 
                                                    v-model="formData.status" 
                                                    :value="true"
                                                />
                                                配車済み
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="is_preloaded" class="form-label">積み込み</label>
                                        <input
                                            id="is_preloaded"
                                            name="is_preloaded"
                                            v-model="formData.is_preloaded"
                                            type="checkbox"
                                            class="form-checkbox"
                                            :true-value = true
                                            :false-value = false
                                        />
                                    </div>
                                    <div class="form-group">
                                        <label for="note" class="form-label">備考</label>
                                        <textarea
                                            id="note"
                                            name="note"
                                            v-model="formData.note"
                                            class="form-textarea"
                                            rows="4"
                                        ></textarea>
                                    </div>
                                  
                                    <div class="form-actions">
                                        <button
                                            type="submit"
                                            class="submit-button"
                                        >
                                            登録
                                        </button>
                                    </div>
                                    <div class="flex justify-center mt-4">
                                        <button
                                            type="button"
                                            @click="emit('close')"
                                            class="cancel-button"
                                        >
                                            Close
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </main>
                    <footer class="modal__footer">
                        <div class="flex justify-center space-x-4 p-2 w-full">
                            <a
                                class="text-white bg-emerald-500 border-0 py-2 px-8 focus:outline-none hover:bg-emerald-600 rounded text-lg"
                                v-on:click="emit('close')"
                            >
                                Close
                            </a>
                        </div>
                    </footer>
                </div>
            </div>
        </div>
    </Teleport>
</template>

<style scoped>
/* モーダルのスタイル */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: flex-start; /* 上部と間隔を作るためにflex-startに変更 */
    padding: 20px; /* ヘッダーとの間隔を確保 */
}

.modal__container {
  width: 150%;
  max-height: 90vh;
  margin-top: 48px; /* ヘッダーとの間隔を調整 */
  padding: 50px;
  background: white;
  border-radius: 10px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  overflow-y: auto;
}

.modal__header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.close-button {
  background: none;
  border: none;
  font-size: 1.25rem;
  cursor: pointer;
}

.form-container {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-label {
  font-weight: bold;
}

.form-input,
.form-textarea,
.form-checkbox {
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.form-actions {
  display: flex;
  justify-content: space-between;
}

.submit-button {
  background-color: #007bff;
  color: white;
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.cancel-button {
  background-color: #ccc;
  color: black;
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.submit-button:hover {
  background-color: #0056b3;
}

.cancel-button:hover {
  background-color: #999;
}
</style>
