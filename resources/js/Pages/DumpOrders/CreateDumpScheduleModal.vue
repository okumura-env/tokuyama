<script setup>
import { ref } from "vue";
import DumpScheduleForm from "@/Components/DumpScheduleForm.vue";
import Modal from "@/Components/Commons/Modal.vue";

const props = defineProps({
    isCreateModalOpen : Boolean,
    clickedData : Object,
});

const emit = defineEmits(["close","refetch"]);

const isEditMode = ref(false);
const title = ref("ダンプスケジュール登録");

const closeCreateModal = () => {
    emit("close");
};

const fetchDumpSchedules = () => {
    emit("refetch");
};

</script>

<template>
  <Modal
    :isModalOpen = "isCreateModalOpen" 
    :title = "title"
    @close="closeCreateModal"
  >
    <DumpScheduleForm
        :isEditMode = "isEditMode"
        :clickedData = "clickedData"
        @close="closeCreateModal"
        @refetch="fetchDumpSchedules"
    />
  </Modal>
</template>
