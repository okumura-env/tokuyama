<script setup>
import { ref } from "vue";
import MonthlyJetpackOrderForm from "@/Components/MonthlyJetpackOrderForm.vue";
import Modal from "@/Components/Commons/Modal.vue";

const props = defineProps({
    isCreateModalOpen : Boolean,
    scheduleData : Object,
});

const emit = defineEmits(["close","refetch"]);

const isEditMode = ref(false);

const closeCreateModal = () => {
    emit("close");
};

const handleSuccess = () => {
    emit("refetch");
    closeCreateModal(); 
};

</script>

<template>
  <Modal
    :isModalOpen = "isCreateModalOpen" 
    title = "ジェットパックオーダー月間登録"
    @close="closeCreateModal"
  >
   <MonthlyJetpackOrderForm
    @success="handleSuccess"
   
    />
  </Modal>

</template>
