<script setup>
import { ref, watch, computed } from "vue";

const props = defineProps({
    isModalOpen : Boolean,
    title : String,
});

const emit = defineEmits(["close"]);

const closeModal = () => {
    emit("close");
};


</script>
<template>
    <v-dialog
    :model-value="props.isModalOpen"
    max-width="600px"
    :persistent="false"
    @click:outside="closeModal"
  >
    <v-card class="modern-card">
      <v-card-title class="modern-card-title text-h6">
        {{ props.title }}
      </v-card-title>
      <v-card-text>
        <slot></slot>
      </v-card-text>
      <v-card-actions>
        <v-btn color="secondary" 
               text 
               @click="closeModal" class="cancel-btn">
          閉じる
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
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
