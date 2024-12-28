<script setup>
import { ref } from "vue";
import DumpScheduleForm from "@/Components/DumpScheduleForm.vue";
import Modal from "@/Components/Commons/Modal.vue";

const props = defineProps({
    isEditModalOpen : Boolean,
    scheduleData : Object,
});

const emit = defineEmits(["close","refetch"]);

const isEditMode = ref(true);

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
    title = "ダンプスケジュール編集"
    @close="closeEditModal"
  >
    <DumpScheduleForm
        :isEditMode = "isEditMode"
        :scheduleData = "scheduleData"
        @success="handleSuccess"
    />
  </Modal>
</template>
