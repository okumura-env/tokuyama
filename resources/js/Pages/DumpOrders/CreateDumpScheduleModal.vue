<script setup>
import DumpScheduleForm from "@/Components/DumpScheduleForm.vue";
import Modal from "@/Components/Commons/Modal.vue";

const props = defineProps({
    isEditMode : Boolean,
    isCreateModalOpen : Boolean,
});

const emit = defineEmits(["close"]);

const closeCreateModal = () => {
    emit("close");
};

</script>

<template>
  <Modal
    :isModalOpen = "isCreateModalOpen" 
    @close="closeCreateModal"
  >
    <DumpScheduleForm
        @close="closeCreateModal"
    />
  </Modal>
</template>
