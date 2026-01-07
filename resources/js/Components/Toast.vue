<template>
  <transition name="slide">
    <div v-if="visible" class="fixed top-5 right-5 z-[9999] pointer-events-none">
      <div 
        :class="[
          'relative overflow-hidden min-w-[300px] flex items-center gap-3 p-4 rounded-xl shadow-2xl text-white font-semibold pointer-events-auto',
          type === 'success' ? 'bg-emerald-600' : 'bg-rose-600'
        ]"
      >
        <div class="bg-white/20 p-1.5 rounded-lg">
          <svg v-if="type === 'success'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
          </svg>
          <svg v-else class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </div>

        <span class="pr-4">{{ message.split('|')[0] }}</span>

        <div class="absolute bottom-0 left-0 h-1 bg-black/20 w-full">
          <div class="h-full bg-white/40 progress-animation"></div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const props = defineProps(['message', 'type']);
const visible = ref(true);

onMounted(() => {
    // Definido 4 segundos para dar tempo do usuário ler
    setTimeout(() => {
        visible.value = false;
    }, 4000);
});
</script>

<style scoped>
/* 1. Animação de Entrada e Saída */
.slide-enter-active { 
    transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275); 
}
.slide-leave-active { 
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); 
}

.slide-enter-from { 
    transform: translateX(120%) scale(0.7); 
    opacity: 0; 
}
.slide-leave-to { 
    transform: translateX(120%) opacity(0); 
}

/* 2. Animação da Barra de Progresso */
.progress-animation {
  animation: shrink 4s linear forwards;
}

@keyframes shrink {
  from { width: 100%; }
  to { width: 0%; }
}
</style>