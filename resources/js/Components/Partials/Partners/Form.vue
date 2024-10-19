<script setup>
import { reactive, ref, watch } from 'vue';
import axios from 'axios';  // HTTPリクエスト用
// import { useErrorHandler } from '@/composables/useErrorHandler';  // エラーハンドリング

// プロパティの定義
const props = defineProps({
  isEditMode: Boolean,  // 編集モードか新規作成モードかを区別
  partner: Object,  // 編集モードの場合は既存の業者データ
});

// イベントの発行
const emits = defineEmits(['partnerStored', 'partnerUpdated']);

// エラーハンドリング
// const { errors, handleError, resetError } = useErrorHandler();

// フォームの初期化
const form = reactive({
  name: props.isEditMode && props.partner ? props.partner.name : '',  // 編集モードなら既存の値、そうでなければ空
  color: props.isEditMode && props.partner ? props.partner.color : '',  // 編集モードなら既存の値
});

// 入力変更時にエラーをリセット
// watch(() => form.name, () => resetError('name'));
// watch(() => form.color, () => resetError('color'));

// 新規業者の保存
const storePartner = async () => {
  try {
    await axios.post('/api/partners', form);  // APIにデータを送信
    emits('partnerStored');  // 作成成功時にイベント発行
  } catch (error) {
    handleError(error);  // エラー処理
  }
};

// 業者の更新
const updatePartner = async () => {
  try {
    await axios.put(`/api/partners/${props.partner.id}`, form);  // 更新リクエスト
    emits('partnerUpdated');  // 更新成功時にイベント発行
  } catch (error) {
    handleError(error);  // エラー処理
  }
};
</script>

<template>
  <form @submit.prevent="props.isEditMode ? updatePartner() : storePartner()">
    <section class="text-gray-600 body-font relative">
      <div class="container px-5 py-8 mx-auto">
        <div class="lg:w-1/2 md:w-2/3 mx-auto">
          <div class="flex flex-wrap -m-2">
            <!-- 業者名の入力欄 -->
            <div class="p-2 w-full">
              <div class="relative">
                <label for="name" class="leading-7 text-sm text-gray-600">
                  業者名<span class="text-red-500">（必須）</span>
                </label>
                <input
                  type="text"
                  id="name"
                  v-model="form.name"
                  class="w-full bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
                />
                <!-- <span v-if="errors.name" class="text-red-500 text-sm">{{ errors.name }}</span> -->
              </div>
            </div>

            <!-- 色の入力欄 -->
            <div class="p-2 w-full">
              <div class="relative">
                <label for="color" class="leading-7 text-sm text-gray-600">
                  色（任意）
                </label>
                <input
                  type="color"
                  id="color"
                  v-model="form.color"
                  class="w-full bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
                />
                <!-- <span v-if="errors.color" class="text-red-500 text-sm">{{ errors.color }}</span> -->
              </div>
            </div>

            <!-- 送信ボタン -->
            <div class="p-2 w-full">
              <button class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">
                {{ props.isEditMode ? '更新' : '作成' }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>
  </form>
</template>
