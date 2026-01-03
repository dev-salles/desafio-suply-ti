<template>
  <transition name="slide">
    <div v-if="show" :class="['toast', type === 'success' ? 'bg-green-500' : 'bg-red-500']">
      <div class="flex items-center gap-2 text-white font-medium p-4 rounded-lg shadow-xl">
        <span>{{ mensagemLimpa }}</span>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { ref, watch, onMounted, computed } from 'vue';

const props = defineProps(['message', 'type']);
const show = ref(false);

// Esta propriedade computada remove tudo que vem após o "|"
const mensagemLimpa = computed(() => {
    if (!props.message) return '';
    return props.message.split('|')[0]; // Pega apenas a primeira parte
});

const triggerToast = () => {
    show.value = true;
    setTimeout(() => show.value = false, 4000);
};

watch(() => props.message, (newMsg) => {
    if (newMsg) {
        show.value = false;
        setTimeout(() => triggerToast(), 50);
    }
}, { immediate: true });
</script>

<style scoped>
.toast {
  position: fixed;
  top: 20px;
  right: 20px;
  z-index: 9999;
}
.slide-enter-active, .slide-leave-active { transition: all 0.3s ease; }
.slide-enter-from { transform: translateX(100%); opacity: 0; }
.slide-leave-to { transform: translateX(100%); opacity: 0; }
</style>