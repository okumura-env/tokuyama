<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';  // Vue Routerの使用
import PartnerForm from '@/Components/Partials/Partners/Form.vue';// Formコンポーネントのインポート

// ルーターインスタンスの取得
const router = useRouter();

// 業者の作成成功時のハンドラ
const handlePartnerStored = () => {
    // 作成後、一覧ページにリダイレクト
    router.push({ name: 'partners.index' });
};
</script>

<template>
  <div>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      業者作成
    </h2>
    <a
      href="javascript:history.back();"
      class="text-blue-500 hover:text-blue-700"
    >
      戻る
    </a>
    
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <!-- Formコンポーネントを使用し、新規作成モードを指定 -->
          <PartnerForm :isEditMode="false" @partnerStored="handlePartnerStored" />
        </div>
      </div>
    </div>
  </div>
</template>
