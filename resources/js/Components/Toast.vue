<template>
  <transition name="slide">
    <div v-if="show" :class="['toast', type === 'success' ? 'bg-green-500' : 'bg-red-500']">
      <div class="flex items-center gap-2 text-white font-medium p-4 rounded-lg shadow-xl">
        <span>{{ message }}</span>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const props = defineProps(['message', 'type']);
const show = ref(true);

onMounted(() => {
  setTimeout(() => show.value = false, 4000); // Some após 4 segundos
});
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