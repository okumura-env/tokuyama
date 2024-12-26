<script setup>
import { ref } from "vue";
import DumpScheduleForm from "@/Components/DumpScheduleForm.vue";
import Modal from "@/Components/Commons/Modal.vue";

const props = defineProps({
    isEditModalOpen : Boolean,
});

const emit = defineEmits(["close"]);

const isEditMode = ref(true);

const closeEditModal = () => {
    emit("close");
};

</script>

<template>
  <Modal
    :isModalOpen = "isEditModalOpen" 
    @close="closeEditModal"
  >
    <DumpScheduleForm
        :isEditMode = "isEditMode"
        @close="closeEditModal"
    />
  </Modal>
</template>
