<template>
  <div v-if="visible" class="fixed top-5 right-5 z-[9999] bg-green-500 text-white p-4 rounded shadow-lg">
    {{ message }}
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const props = defineProps(['message', 'type']);
const visible = ref(true);

onMounted(() => {
    // Some após 3 segundos
    setTimeout(() => visible.value = false, 3000);
});
</script>

<style scoped>
/* Mantive o Slide, mas otimizado para a direita */
.slide-enter-active, .slide-leave-active { 
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); 
}
.slide-enter-from { 
    transform: translateX(120%); 
    opacity: 0; 
}
.slide-leave-to { 
    transform: translateX(120%); 
    opacity: 0; 
}
</style>