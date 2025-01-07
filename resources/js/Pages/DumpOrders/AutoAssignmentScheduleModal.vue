<script setup>
import { ref } from "vue";
import AutoAssignmentScheduleForm from "@/Components/AutoAssignmentScheduleForm.vue";
import Modal from "@/Components/Commons/Modal.vue";

const props = defineProps({
    isAutoAssignmentModalOpen : Boolean,
    dateData : Array,
});

const emit = defineEmits(["close","refetch"]);

const closeAutoAssignmentModal = () => {
    emit("close");
};

const handleSuccess = () => {
    emit("refetch");
    closeAutoAssignmentModal(); 
};

</script>

<template>
  <Modal
    :isModalOpen = "isAutoAssignmentModalOpen"
    title = "配車登録" 
    @close="closeAutoAssignmentModal"
  >
    <AutoAssignmentScheduleForm
      :dateData="dateData"
      @success="handleSuccess"
     />
  </Modal>
</template>
