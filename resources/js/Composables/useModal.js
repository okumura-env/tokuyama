import { ref } from "vue";

/**
 * モーダルの状態と操作を管理するカスタムフック
 */
export default function useModal() {
    const isModalOpen = ref(false);

    const openModal = () => {
        isModalOpen.value = true;
    }

    const closeModal = () => {
        isModalOpen.value = false;
    }
 

    return {
       isModalOpen,
       openModal,
       closeModal,

    };
}
