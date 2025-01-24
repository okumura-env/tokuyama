<script setup>
import { ref, watch, computed} from "vue";
import useDataApi from "@/Composables/useDataApi";

// const props = defineProps({
//     isEditMode : Boolean,
//     scheduleData : Object,
// });

// const emit = defineEmits(["success"]);

// データ取得
const { data: destinations, fetchData: fetchDestinations } = useDataApi("/api/jetpack-destinations");
const { data: dates, fetchData: fetchDates } = useDataApi("/api/dates");

const initialFormData = {
    jetpack_destination_id: "", // 日付文字列
    // schedule: [], // 日付と数値のセットを格納
};
// initialFormData.value.schedule = dates.value.map((d) => ({ date: d.date, value: "" }));

// dates の変更を監視して schedule を初期化
// watch(
//     dates,
//     (newDates) => {
//         if (newDates && newDates.length > 0) {
//             formData.value.schedule = newDates.map((d) => ({
//                 date: d.date,
//                 value: "",
//             }));
//         }
//     },
//     { immediate: true } // 初期化時にも実行
// );

// const scheduleData = computed(() => {
//     return props.scheduleData ? props.scheduleData : null;
// });

const formData = ref({ ...initialFormData });


// //保存処理
const registerSchedule = async() => {
//     const response = await axios.post("/api/dump-orders", formData.value);
//     console.log("登録ボタンが押されました");
//     emit("success");
};

// //更新処理
const updateSchedule = async() => {
//     const response = await axios.put(`/api/dump-orders/${scheduleData.value.id}`, formData.value);
//     console.log("更新ボタンが押されました");
//     emit("success");
};

</script>
<template>
   <main class="modal__content" id="modal-1-content">
        <div class="container px-5 py-8 mx-auto">
            <div class="lg:w-2/3 w-full mx-auto overflow-auto">
                <form @submit.prevent="isEditMode ? updateSchedule() : registerSchedule()" 
                      class="form-container">
                        <!-- 行き先 -->
                        <v-select
                          label="行き先"
                          :items="destinations"
                          item-title="name"
                          item-value="id"
                          v-model="formData.jetpack_destination_id"
                          outlined
                          class="form-input"
                        >                            
                        </v-select>

                        <!-- 日付とフォーム -->
                        <div class="schedule-form">
                            <h2 class="text-lg font-semibold mb-4">スケジュール</h2>
                            <div v-for="(date, index) in  dates" :key="index" class="date-row flex items-center mb-2">
                                <span class="date-label w-1/3">{{ date.date }}</span>
                                <v-text-field
                                  type="number"
                                  outlined
                                  class="flex-grow"
                                  placeholder="数値を入力"
                                ></v-text-field>
                            </div>
                        </div>                      
                   
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

.form-input .v-input__control {
  overflow: visible; /* 内容が切り取られないように設定 */
  text-overflow: ellipsis; /* 長いテキストが切れる場合に対応 */
  white-space: nowrap; /* テキストを1行に収める */
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
