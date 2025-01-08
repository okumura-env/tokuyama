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
    <div class="form-wrapper">
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

        <div class="button-container">
            <!-- データ登録ボタン -->
            <v-btn @click="tenRegisterData" class="ten-register-button">
                転
            </v-btn>
        </div>
    </div>
</template>

<style scoped>
/* フォーム全体を囲むラッパー */
.form-wrapper {
  display: flex;
  flex-direction: column;
  gap: 4rem; /* 各フォーム間の間隔を設定 */
}

/* カード内部のフォーム全体デザイン */
.form-container {
  display: flex;
  flex-direction: column;
  gap: 0rem; /* フォーム項目とボタン間の適切な余白 */
}

.form-input {
    margin-bottom: 0; /* ボタンと不要な間隔を排除 */
}

.submit-button,
.ten-register-button {
  background-color: #002c5e; /* ブランド基調色 */
  color: #ffffff;
  font-weight: bold;
}

.submit-button:hover,
.ten-register-button:hover  {
  background-color: #001d43;
  transition: background-color 0.3s ease;
}

@media (max-width: 600px) {
  .submit-button {
    font-size: 14px;
  }
}
</style>
