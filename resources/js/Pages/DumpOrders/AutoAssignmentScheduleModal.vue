<script setup>
import { ref } from "vue";
import AutoAssignmentScheduleForm from "@/Components/AutoAssignmentScheduleForm.vue";
import Modal from "@/Components/Commons/Modal.vue";

const props = defineProps({
    isAutoAssignmentModalOpen : Boolean,
});

const emit = defineEmits(["close"]);

const closeAutoAssignmentModal = () => {
    emit("close");
};

</script>

<template>
  <Modal
    :isModalOpen = "isAutoAssignmentModalOpen"
    title = "配車登録" 
    @close="closeAutoAssignmentModal"
  >
    <AutoAssignmentScheduleForm />
  </Modal>
</template>
