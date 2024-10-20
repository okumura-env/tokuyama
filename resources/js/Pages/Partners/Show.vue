<script setup>
import { onMounted, ref } from "vue";
import { useRoute, useRouter } from "vue-router";

// ルート情報とルーターの初期化
const route = useRoute();
const router = useRouter();

// パートナーのデータを格納する変数
const partner = ref({});

// APIからデータを取得する非同期関数
const fetchPartner = async () => {
  try {
    // パートナーIDをルートから取得
    const partnerId = route.params.id;

    // APIを呼び出してデータを取得
    const response = await axios.get(`/api/partners/${partnerId}`);

    // 取得したデータを partner に保存
    partner.value = response.data.data;
  } catch (error) {
    console.error("データの取得に失敗しました:", error);
    // エラー時には一覧画面にリダイレクト
    router.push({ name: "partners.index" });
  }
};

// コンポーネントがマウントされたときにデータを取得
onMounted(fetchPartner);

// パートナーの削除処理
const deletePartner = async (id) => {
  if (confirm("削除してよろしいですか?")) {
    try {
      await axios.delete(`/api/partners/${id}`);
      alert("削除が完了しました");
      router.push({ name: "partners.index" });
    } catch (error) {
      console.error("削除に失敗しました:", error);
    }
  }
};
</script>

<template>
  <div>
    <h1>パートナー詳細</h1>
    <a
      href="javascript:history.back();"
      class="text-blue-500 hover:text-blue-700"
    >
      戻る
    </a>
    <div v-if="partner">
      <p><strong>ID:</strong> {{ partner.id }}</p>
      <p><strong>名前:</strong> {{ partner.name }}</p>
      <p><strong>色コード:</strong> <span :style="{color: partner.color}">{{ partner.color }}</span></p>
    </div>
    <div v-else>
      <p>データを読み込んでいます...</p>
    </div>

    <div class="mt-4">
      <button @click="deletePartner(partner.id)" class="bg-red-500 text-white p-2 rounded">削除</button>
    </div>
    <router-link v-bind:to="{ name: 'partners.edit', params: { id: partner.id }}">
        <div class="text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded transition ease-in-out duration-150">
            <button type="button" class="btn btn-secondary">編集</button>
        </div>
    </router-link>
  </div>
</template>

<style scoped>
/* 必要に応じてスタイルを調整 */
</style>
