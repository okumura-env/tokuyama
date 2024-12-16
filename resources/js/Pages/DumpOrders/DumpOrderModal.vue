<script setup>
import { ref } from "vue";

const emit = defineEmits(["close"]);

const formComponentRef = ref();
const closeOrderModal = () => {
    formComponentRef.value.emptyForm();
    emit("close");
};

</script>
<template>
    <Teleport to="body">
        <div class="modal" id="modal-1" aria-hidden="true">
            <div
                class="modal__overlay"
                tabindex="-1"
                data-micromodal-close
                @click.self="closeOrderModal"
            >
                <div
                    class="modal__container w-2/3"
                    role="dialog"
                    aria-modal="true"
                    aria-labelledby="modal-1-title"
                >
                    <header class="modal__header">
                        <h2 class="modal__title" id="modal-1-title">
                            モーダルタイトル
                        </h2>
                        <button @click="closeOrderModal">✖</button>
                    </header>
                    <main class="modal__content" id="modal-1-content">
                        <div class="container px-5 py-8 mx-auto">
                            <div class="lg:w-2/3 w-full mx-auto overflow-auto">
                            </div>
                        </div>
                    </main>
                    <footer class="modal__footer">
                        <div class="flex justify-center space-x-4 p-2 w-full">
                            <a
                                class="text-white bg-emerald-500 border-0 py-2 px-8 focus:outline-none hover:bg-emerald-600 rounded text-lg"
                                v-on:click="closeOrderModal"
                            >
                                Close
                            </a>
                        </div>
                    </footer>
                </div>
            </div>
        </div>
    </Teleport>
</template>

<style scoped>
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
}
.modal__container {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    min-width: 300px;
    max-width: 500px;
    text-align: center;
}
</style>
