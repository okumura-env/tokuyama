<script setup>
import { ref } from "vue";
import DumpScheduleForm from "@/Components/DumpScheduleForm.vue";
import Modal from "@/Components/Commons/Modal.vue";

const props = defineProps({
    isEditModalOpen : Boolean,
    clickedData : Object,
});

const emit = defineEmits(["close","refetch"]);

const isEditMode = ref(true);
const title = ref("ダンプスケジュール編集");

const closeEditModal = () => {
    emit("close");
};

const handleSuccess = () => {
    emit("refetch");
    closeEditModal();
};

</script>

<template>
  <Modal
    :isModalOpen = "isEditModalOpen" 
    :title = "title"
    @close="closeEditModal"
  >
    <DumpScheduleForm
        :isEditMode = "isEditMode"
        :clickedData = "clickedData"
        @success="handleSuccess"
    />
  </Modal>
</template>
