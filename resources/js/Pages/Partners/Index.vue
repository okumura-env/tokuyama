<script setup>
// import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { onMounted, ref } from "vue";
// import Pagination from "@/Components/Pagination.vue";

// パートナーのデータを格納するリスト
const partners = ref([]);
// ページネーション情報
const pagination = ref({});

// パートナーのデータをAPIから取得する関数
const fetchPartners = async (page = 1) => {
  try {
    // API呼び出しでページ番号に基づいてデータを取得
    const response = await axios.get(`/api/partners?page=${page}`);
    // 取得したデータをパートナーリストに格納
    partners.value = response.data.data;
    // ページネーション情報を格納
    pagination.value = response.data.meta;
  } catch (error) {
    console.error(error); // エラーハンドリング
  }
};

// // ページ変更時に呼び出される関数
// const changePage = (link) => {
//   if (link.url) {
//     const page = new URL(link.url).searchParams.get("page");
//     fetchPartners(page); // 新しいページのデータを取得
//   }
// };

// コンポーネントの初期化時にパートナーのデータを取得
onMounted(() => {
  fetchPartners();
});
</script>


<template>
    <AuthenticatedLayout>
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          協力業者一覧
        </h2>
      </template>
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <section class="p-6 text-gray-600 body-font">
              <div class="mb-4 flex justify-end">
                <router-link v-bind:to="{ name: 'partners.create'}">
                    <div class="text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded transition ease-in-out duration-150">
                        <button type="button" class="btn btn-secondary">登録</button>
                    </div>
                </router-link>
              </div>
              <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-no-wrap">
                  <thead>
                    <tr class="text-gray-900 bg-gray-100">
                      <th class="px-4 py-3 title-font tracking-wider font-medium text-sm">
                        業者名
                      </th>
                 
                      <th class="px-4 py-3 title-font tracking-wider font-medium text-sm">
                        色
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="partner in partners" :key="partner.id" class="border-b">
                      <td class="px-4 py-3">
                        <router-link v-bind:to="{ name: 'partners.show', params: { id: partner.id },}">
                          {{ partner.name }}
                        </router-link>
                      </td>
                      <td class="px-4 py-3">
                        <div style="width:50px; height:20px; background: #03c7b1 ;"></div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              
            </section>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>
