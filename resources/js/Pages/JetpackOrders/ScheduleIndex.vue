<script setup>
import { ref, computed } from "vue";
import { useDisplay } from "vuetify";
import axios from "axios";
import useDataApi from "@/Composables/useDataApi";

const drawer = ref(false);
const clipped = ref(false);
const { smAndDown } = useDisplay();
const isDesktop = computed(() => !smAndDown.value);
const menuItems = [{ title: "ダンプ配車作成" , icon: ['fas', 'truck']},{ title: "ジェットパック配車作成", icon: ['fas', 'plane'] },{ title: "設定" , icon: ['fas', 'cog'] }];

// データ取得
const { data: dates, fetchData: fetchDates } = useDataApi("/api/dates");
const { data: destinationRules, fetchData: fetchJetpackDestinationRoutes } = useDataApi("/api/jetpack-destination-routes");
const { data: jetpackOrders, fetchData: fetchJetpackOrders } = useDataApi("/api/jetpack-orders");

// const destinationsForHeader = computed(() => [
//       { text: "日付", value: "date" }, // 固定列
//       ...(destinationRules.value || []).map((rule) => ({
//         text: rule.name, // 表示名
//         value: rule.name, // データキー (必要に応じてkeyフィールドを使う)
//       })),
//         { text: "操作", value: "操作", sortable: false }, // 一番右に操作列を追加
//     ]);

const computedItems = computed(() => {
  if (!dates.value || !jetpackOrders.value || !destinationRules.value) return [];

  // 各日付ごとにデータをまとめる
  return dates.value.map((dateItem) => {
    // 各日付の行データを初期化
    const item = { date: dateItem.date }; // 日付列を追加

    // 目的地ごとに集計
    destinationRules.value.forEach((rule) => {
      const count = jetpackOrders.value.filter(
        (order) =>
          order.date_id === dateItem.id &&
          order.jetpack_destination_route_id === rule.id
      ).length;

      // 該当目的地の列に集計値を設定
      item[rule.name] = count;
    });

     // 操作列にボタンを追加するためにplaceholderを設定
     item.actions = ""; // 操作列用

    return item;
  });
});

// 配車ボタンの処理関数
const handleDispatch = (item) => {
  console.log("配車ボタンが押されました", item);
  // ここで配車処理を実装する
};

</script>

<template>
    <v-app>
        <!-- サイドバー -->
        <v-navigation-drawer
            v-model="drawer"
            app
            :clipped="clipped"
            :permanent="isDesktop"
            class="sidebar"
        >
            <v-list>
                <v-list-item>
                    <v-list-item-title></v-list-item-title>
                </v-list-item>
                <v-list-item v-for="item in menuItems" :key="item.title">
                    <v-list-item-title> <font-awesome-icon :icon="item.icon" />{{ item.title }}</v-list-item-title>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>

        <!-- ヘッダー -->
        <v-app-bar app class="header">
            <v-btn icon @click="drawer = !drawer">
                <v-icon>{{ drawer ? 'mdi-menu-open' : 'mdi-menu' }}</v-icon>
            </v-btn> 
            <v-toolbar-title>トクヤマ海陸運送</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn text>ログアウト</v-btn>
        </v-app-bar>

        <!-- メインコンテンツ -->
        <v-main>
            <v-container>
                <!-- 表の追加 -->
                <v-data-table
                :items="computedItems"
                class="elevation-1"
                >
                    <template v-slot:top>
                        <v-toolbar flat>
                        <v-toolbar-title>ジェットパック月間オーダー管理画面</v-toolbar-title>
                        <v-spacer></v-spacer>
                        </v-toolbar>
                    </template>
                    <template v-slot:[`item.actions`]="{ item }">
                        <v-btn text color="primary" @click="handleDispatch(item)">配車</v-btn>
                    </template>
                </v-data-table>
            </v-container>
        </v-main>
    </v-app>
</template>

<style scoped>
/* ヘッダー */
.v-app-bar.v-toolbar {
  background-color: var(--v-primary-base);
  color: var(--v-on-primary);
}

.sidebar {
  background-color: var(--v-primary-base);
}

@media (prefers-color-scheme: dark) {
  .sidebar {
    background-color: #002c5e;
    color: #ffffff;
  }

  .v-app-bar.v-toolbar {
    background-color: #002c5e;

    color: #ffffff;
  }
}

/* テーブルの基本スタイル */
table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

th {
    background-color: #f4f4f4;
}
</style>
