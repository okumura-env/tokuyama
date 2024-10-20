<!-- Partners/Edit.vue -->
<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router'; // Vue Routerの使用
import axios from 'axios'; // データ取得のためのaxios
import PartnerForm from '@/Components/Partials/Partners/Form.vue';// Formコンポーネントのインポート

// ルートとルーターの定義
const route = useRoute();
const router = useRouter();

// パートナーIDを取得
const partnerId = route.params.id;

// パートナー情報を保持するための変数
const partner = ref(null);

// パートナー情報を取得
const fetchPartner = async () => {
  try {
    const response = await axios.get(`/api/partners/${partnerId}`);
    partner.value = response.data.data;
  } catch (error) {
    console.error('パートナー情報の取得に失敗しました:', error);
  }
};

// コンポーネントのマウント時にデータ取得
onMounted(() => {
  fetchPartner();
});

// パートナー情報の更新後、一覧ページにリダイレクト
const handlePartnerUpdated = () => {
  router.push({ name: 'partners.show', params: { id: partner.id }});
};
</script>

<template>
  <div>
    <h1 class="text-2xl font-bold mb-4">パートナー編集</h1>
    <!-- フォームコンポーネントの呼び出し -->
    <PartnerForm
      v-if="partner"
      :partner="partner"
      :isEditMode="true"
      @partnerUpdated="handlePartnerUpdated"
    />
  </div>
</template>
