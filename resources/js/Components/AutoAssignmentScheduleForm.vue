<script setup>
import { ref } from "vue";

const props = defineProps({
    dateData : Array,
});

const emit = defineEmits(["success"]);

const fujiData = ref({
    dateData: props.dateData,
    vehicleCount: "",
});

const ruleData = ref({
    dateData: props.dateData,
    selectedRule: "",
});

 //富士のオーダーの保存処理
const registerFujiSchedule = async() => {
    const response = await axios.post("/api/dump-orders/fuji/store",fujiData.value);
    emit("success");
};

 //ルールに基づくオーダーの保存処理
 const registerMcmRuledSchedule = async() => {
    const response = await axios.post("/api/dump-orders/mcm-rule/store",ruleData.value);
    emit("success");
};

</script>
<template>
    <form  @submit.prevent="registerFujiSchedule()" 
           class="form-container">
        <!-- 富士の車両台数 -->
        <v-select
            label="富士の車両台数"
            :items="['未選択', '1', '2', '3']"
            v-model="fujiData.vehicleCount"
            outlined
            class="form-input"
            ></v-select>

        <!-- アクションボタン -->
        <div class="form-actions">
            <v-btn type="submit" class="submit-button">
                富士の登録
            </v-btn>
        </div>
    </form>

    <form  @submit.prevent="registerMcmRuledSchedule()" 
           class="form-container">
        <!-- 選択したMCMルール -->
        <v-select
            label="ルール"
            :items="['未選択','ルール1','ルール2','ルール3','ルール4','ルール5','ルール6','ルール7','ルール8','ルール9']"
            v-model="ruleData.selectedRule"
            outlined
            class="form-input"
            ></v-select>

        <!-- アクションボタン -->
        <div class="form-actions">
            <v-btn type="submit" class="submit-button">
                ルールの登録
            </v-btn>
        </div>
    </form>
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
