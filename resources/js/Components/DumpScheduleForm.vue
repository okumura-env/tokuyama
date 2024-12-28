<script setup>
import { ref, watch, computed } from "vue";
import axios from "axios";
import useDataApi from "@/Composables/useDataApi";

const props = defineProps({
    isEditMode : Boolean,
    scheduleData : Object,
});

const emit = defineEmits(["success"]);

// データ取得
const { data: vehicles, fetchData: fetchVehicles } = useDataApi("/api/vehicles");
const { data: dumpOrderCategories, fetchData: fetchDumpOrderCategories } = useDataApi("/api/dump-order-categories");
const { data: dumpOrderCategoryTitles, fetchData: fetchDumpOrderCategoryTitles } = useDataApi("/api/dump-order-category-titles");

const initialFormData = {
    date: "", // 日付文字列
    date_id: "",
    vehicle_id: "",
    dump_schedule_id: "1",// 仮の初期値
    dump_order_category_id: "",
    dump_order_category_title_id:"",
    boiler_number: "",
    status: true,
    is_preloaded:false,
    note: "",
};

const scheduleData = computed(() => {
    return props.scheduleData ? props.scheduleData : null;
});

const formData = ref({ ...initialFormData });

watch(
  () => scheduleData.value,
  (newData) => {
    if (newData) {
        console.log(!!newData.dumpOrder?.is_preloaded);
        if(props.isEditMode){
            formData.value.date = newData.date?.date || "";
            formData.value.date_id = newData.date_id || "";
            formData.value.vehicle_id = newData.vehicle_id || "";
            formData.value.dump_order_category_id = newData.dump_order_category_id || "";
            formData.value.dump_order_category_title_id = newData.dump_order_category_title_id || "";
            formData.value.boiler_number = newData.dumpOrder?.boiler_number || "";
            formData.value.status = 
                newData.dumpOrder?.status !== undefined && newData.dumpOrder?.status !== null
                ? !!newData.dumpOrder?.status
                : ""; // nullまたはundefinedの場合のみ空文字列を格納(0のときに空文字列が格納されないようにするため)
            formData.value.is_preloaded = 
                newData.dumpOrder?.is_preloaded !== undefined && newData.dumpOrder?.is_preloaded !== null
                ? !!newData.dumpOrder?.is_preloaded
                : ""; // nullまたはundefinedの場合のみ空文字列を格納(0のときに空文字列が格納されないようにするため)
            formData.value.note = newData.dumpOrder?.note || "";
    }else{
        console.log(newData);
        formData.value.date = newData.date || "";
        formData.value.date_id = newData.date_id || "";
        formData.value.vehicle_id = newData.vehicle_id || "";
    }
    }
  },
    { immediate: true }
);

const getVehicle = (vehicleId) => {
    const vehicle = vehicles.value.find((vehicle) => vehicle.id === vehicleId);
    return vehicle ? vehicle.name : "";
};

//カテゴリーを選ぶとタイトルが絞り込まれる
//dumpOrderCategoryTitlesにAPIからデータが入った瞬間・dump_order_category_idが変わった瞬間
//の両方でfilteredTitlesを更新する
const filteredTitles = ref([]);
watch(
  [() => formData.value.dump_order_category_id, () => dumpOrderCategoryTitles.value],
  ([newCategoryId, newTitles]) => {
    if (!newTitles || newTitles.length === 0) {
      filteredTitles.value = [];
      return;
    }
    if (newCategoryId) {
      filteredTitles.value = newTitles.filter(title => title.dump_order_category_id === newCategoryId);
    } else {
      filteredTitles.value = newTitles;
    }
  },
  { immediate: true }
);

//保存処理
const registerSchedule = async() => {
    const response = await axios.post("/api/dump-orders", formData.value);
    console.log("登録ボタンが押されました");
    emit("success");
};

//更新処理
const updateSchedule = async() => {
    const response = await axios.put(`/api/dump-orders/${scheduleDataData.value.id}`, formData.value);
    console.log("更新ボタンが押されました");
    emit("success");
};

</script>
<template>
    <main class="modal__content" id="modal-1-content">
        <div class="container px-5 py-8 mx-auto">
            <div class="lg:w-2/3 w-full mx-auto overflow-auto">
                <form @submit.prevent="isEditMode  ?  updateSchedule() : registerSchedule()" 
                        class="form-container">
                    <div class="form-group">
                        <label for="date" class="form-label">日付</label>
                        <div id="date" class="form-display">{{ isEditMode ? scheduleData.date?.date : scheduleData.date }}</div>
                    </div>
                    <div class="form-group">
                        <label for="vehicles" class="form-label">車両</label>
                        <div id="vehicles" class="form-display">{{ getVehicle(formData.vehicle_id) }}</div>
                  
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
                            <option value="">未選択</option>
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
                        {{
                            isEditMode
                                ? "更新"
                                : "登録"
                        }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</template>

<style scoped>
/* モーダルのスタイル */
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
