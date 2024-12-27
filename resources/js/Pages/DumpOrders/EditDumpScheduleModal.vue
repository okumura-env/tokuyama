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

const closeEditModal = () => {
    emit("close");
};

const fetchDumpSchedules = () => {
    emit("refetch");
};

</script>

<template>
  <Modal
    :isModalOpen = "isEditModalOpen" 
    @close="closeEditModal"
  >
    <DumpScheduleForm
        :isEditMode = "isEditMode"
        :clickedData = "clickedData"
        @close="closeEditModal"
        @refetch="fetchDumpSchedules"
    />
  </Modal>
</template>
