<script setup>
import { ref, watch } from "vue";
import useDataApi from "@/Composables/useDataApi";
import { useRouter  } from "vue-router";

// データ取得
const { data: destinations, fetchData: fetchDestinations } = useDataApi("/api/jetpack-destinations");
const { data: dates, fetchData: fetchDates } = useDataApi("/api/dates");
const { data: jetpackOrders, fetchData: fetchJetpackOrders } = useDataApi("/api/jetpack-orders");

// Vue Router
const router = useRouter();// ルーターインスタンスを取得

// オーダーのカウント処理
const orderCounts = ref({});
const countOrders = () => {
  const counts = {};
  jetpackOrders.value.forEach((order) => {
    const key = `${order.date_id}_${order.jetpack_destination_id}`;
    counts[key] = (counts[key] || 0) + 1;
  });
  orderCounts.value = counts;
};

// jetpackOrdersが変化したときにcountOrdersを実行
watch(jetpackOrders, () => {
  if (jetpackOrders.value.length > 0) {
    countOrders();
  }
});

// 曜日が週末かを判定
const isWeekend = (day) => day === "土" || day === "日";

// 登録処理
const handleRegister = (destination, date) => {
  // 必要に応じて API 呼び出しを追加
};

// 配車処理
const handleDispatch = (destination, date) => {
  router.push({ name:'jetpack-assignment' ,params:{ dateId: date.id }}); // 指定されたルートに移動
  
  // 必要に応じて API 呼び出しを追加
};

</script>

<template>
  <div class="order-management">
    <header>
      <h2>※ジェットパック月間オーダー管理画面</h2>
    </header>
    <table>
      <thead>
        <tr>
          <th>日付</th>
          <th v-for="destination in destinations" :key="destination.id">
            {{ destination.name }}
          </th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="date in dates" :key="date.date">
          <td :class="{ weekend: isWeekend(date.day) }">
            {{ date.date }} ({{ date.day }})
          </td>
          <td
            v-for="destination in destinations"
            :key="`cell-${date.date}-${destination.id}`"
          >
          {{ orderCounts?.[`${date.id}_${destination.id}`] || 0 }}
          </td>
          <td>
            <button @click="handleRegister(destination, date)">登録</button>
            <button @click="handleDispatch(destination, date)">配車</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<style scoped>
.order-management {
  padding: 16px;
  font-family: Arial, sans-serif;
}

header {
  text-align: right;
  margin-bottom: 16px;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th,
td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: center;
}

th {
  background-color: #f4f4f4;
}

.weekend {
  background-color: #ffecec;
}

button {
  margin: 0 4px;
  padding: 6px 12px;
  cursor: pointer;
}

button:hover {
  background-color: #f0f0f0;
}
</style>
