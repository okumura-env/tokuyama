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
                <form @submit.prevent="isEditMode ? updateSchedule() : registerSchedule()" 
                      class="form-container">
                       <!-- 日付 -->
                        <v-text-field
                        label="日付"
                        readonly
                        outlined
                        class="form-input">
                            {{ isEditMode ? scheduleData.date?.date : scheduleData.date }}
                        </v-text-field>
                
                        <!-- 車両 -->
                        <v-col cols="12">
                                <v-text-field
                                    label="車両"
                                    readonly
                                    outlined
                                    class="form-input"
                                >{{ getVehicle(formData.vehicle_id) }}</v-text-field>
                        </v-col>

                    <!-- カテゴリー -->
                        <v-select
                        label="カテゴリー"
                        :items="dumpOrderCategories"
                        item-title="name"
                        item-value="id"
                        v-model="formData.dump_order_category_id"
                        outlined
                        class="form-input"
                        >                            
                        </v-select>

                        <!-- タイトル -->
                        <v-col cols="12">
                                    <v-select
                                        label="タイトル"
                                        :items="filteredTitles"
                                        item-title="title"
                                        item-value="id"
                                        v-model="formData.dump_order_category_title_id"
                                        outlined
                                        class="form-input"
                                    ></v-select>
                        </v-col>

                        <!-- ボイラー番号 -->
                        <v-select
                        label="ボイラー番号"
                        :items="['未選択', '1', '5', '6']"
                        v-model="formData.boiler_number"
                        outlined
                        class="form-input"
                        ></v-select>

                        <!-- ステータス -->
                        <v-radio-group
                        v-model="formData.status"
                        label="ステータス"
                        class="form-radio-group"
                        >
                            <v-radio label="未配車" :value="false"></v-radio>
                            <v-radio label="配車済み" :value="true"></v-radio>
                        </v-radio-group>

                        <!-- 積み込み -->
                        <v-checkbox
                        label="積み込み"
                        v-model="formData.is_preloaded"
                        true-value="true"
                        false-value="false"
                        class="form-checkbox"
                        ></v-checkbox>

                        <!-- 備考 -->
                        <v-textarea
                        label="備考"
                        v-model="formData.note"
                        rows="4"
                        outlined
                        class="form-textarea"
                        ></v-textarea>

                   <!-- アクションボタン -->
                    <div class="form-actions">
                        <v-btn type="submit" class="submit-button">
                            {{ isEditMode ? "更新" : "登録" }}
                        </v-btn>
                    </div>
                </form>
            </div>
        </div>
    </main>
</template>

<style scoped>
/* カード内部のフォーム全体デザイン */
.form-container {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.form-input {
  margin-bottom: 1rem;
}

.form-radio-group {
  margin-bottom: 1rem;
}

.form-checkbox {
  margin-bottom: 1rem;
}

.submit-button {
  background-color: #002c5e; /* ブランド基調色 */
  color: #ffffff;
  font-weight: bold;
}

.submit-button:hover {
  background-color: #001d43;
  transition: background-color 0.3s ease;
}

@media (max-width: 600px) {
  .submit-button {
    font-size: 14px;
  }
}
</style>
