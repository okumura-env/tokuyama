<script setup>
import { ref } from "vue";

const props = defineProps({
    dateData : Array,
});

const emit = defineEmits(["success"]);

const currentAction = ref("");

const fujiData = ref({
    dateData: props.dateData,
    vehicleCount: "",
});

const ruleData = ref({
    dateData: props.dateData,
    selectedRule: "未選択",
});

 //富士のオーダーの保存処理
const registerFujiSchedule = async() => {
    const response = await axios.post("/api/dump-orders/fuji/store",fujiData.value);
    emit("success");
};

//  //ルールに基づくオーダーの保存処理
//  const registerMcmRuledSchedule = async() => {
//     const response = await axios.post("/api/dump-orders/mcm-rule/store",ruleData.value);
//     emit("success");
// };

const setAction = (action) => {
    currentAction.value = action;
};

const handleSubmit = () => {
    if (ruleData.value.selectedRule === '未選択') {
        alert('ルールを選択してください。');
        return;
      }

      if (currentAction.value === 'mcmCoal') {
        registerMcmRuledSchedule();
      } else if (currentAction.value === 'ten') {
        tenRegister();
      } else {
        console.error('無効なアクションです:', currentAction.value);
      }
}

 //ルールに基づくオーダーの保存処理
 const registerMcmRuledSchedule = async() => {
    console.log('MCM石炭処理実行:', ruleData.value.selectedRule);
    const response = await axios.post("/api/dump-orders/mcm-rule/store",ruleData.value);
    emit("success");
};

 //ルールに基づくオーダーの保存処理
 const tenRegister = async() => {
    console.log('転処理実行:', ruleData.value.selectedRule);
    const response = await axios.post("/api/dump-orders/ten/store",ruleData.value);
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

        <form  @submit.prevent="handleSubmit"
            class="form-container">
            <!-- 選択したMCMルール -->
            <v-select
                label="ルール"
                :items="['未選択','ルール1','ルール2','ルール3','ルール4','ルール5','ルール6','ルール7','ルール8','ルール9']"
                v-model="ruleData.selectedRule"
                outlined
                class="form-input"
                ></v-select>

            <div class="form-actions">
                <!-- MCM石炭登録ボタン -->
                <v-btn type="submit" @click="setAction('mcmCoal')" class="mcm-coal-register-button">
                    MCM石炭
                </v-btn>

                 <!-- 転登録ボタン -->
                <v-btn type="submit" @click="setAction('ten')" class="ten-register-button">
                    転
                </v-btn>
            </div>

        
           
        
    </form>
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

/* ボタン間のスペースを調整 */
.form-actions {
  display: flex; /* 横並びに配置 */
  gap: 16px; /* ボタン間のスペースを指定 */
}

.submit-button,
.mcm-coal-register-button,
.ten-register-button {
  background-color: #002c5e; /* ブランド基調色 */
  color: #ffffff;
  font-weight: bold;
}

.submit-button:hover,
.mcm-coal-register-button:hover,
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
