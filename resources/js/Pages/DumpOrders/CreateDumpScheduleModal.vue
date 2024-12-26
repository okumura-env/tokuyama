<script setup>
import { ref } from "vue";
import DumpScheduleForm from "@/Components/DumpScheduleForm.vue";
import Modal from "@/Components/Commons/Modal.vue";

const props = defineProps({
    isEditMode : Boolean,
    isCreateModalOpen : Boolean,
});

const emit = defineEmits(["close"]);

const isEditMode = ref(false);

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
        :isEditMode = "isEditMode"
        @close="closeCreateModal"
    />
  </Modal>
</template>
