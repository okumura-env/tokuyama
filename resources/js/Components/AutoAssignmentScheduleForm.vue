<script setup>


</script>
<template>
    <form class="form-container">
        <!-- 富士の車両台数 -->
        <v-select
            label="富士の車両台数"
            :items="['未選択', '1', '2', '3']"
            
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
