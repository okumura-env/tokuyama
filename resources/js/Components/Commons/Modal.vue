<script setup>
import {
  VDialog,
  VCard,
  VCardTitle,
  VCardText,
  VCardActions,
  VBtn,
} from "vuetify/components";


const props = defineProps({
    isModalOpen : Boolean,
    title : String,
});

const emit = defineEmits(["close"]);

const closeModal = () => {
    emit("close");
};


</script>
<template>
    <v-dialog
    :model-value="props.isModalOpen"
    max-width="600px"
    :persistent="false"
    @click:outside="closeModal"
  >
    <v-card class="modern-card">
      <v-card-title class="modern-card-title text-h6">
        {{ props.title }}
      </v-card-title>
      <v-card-text>
        <slot></slot>
      </v-card-text>
      <v-card-actions>
        <v-btn color="secondary" 
               text 
               @click="closeModal" class="cancel-btn">
          閉じる
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<style scoped>
@import "@css/modal.css";
</style>
