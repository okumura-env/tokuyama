<script setup>
import { ref } from "vue";
import DumpScheduleForm from "@/Components/DumpScheduleForm.vue";
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
    title = "ダンプスケジュール登録"
    @close="closeCreateModal"
  >
    <DumpScheduleForm
        :isEditMode = "isEditMode"
        :scheduleData = "scheduleData"
        @success="handleSuccess"
    />
  </Modal>
</template>
