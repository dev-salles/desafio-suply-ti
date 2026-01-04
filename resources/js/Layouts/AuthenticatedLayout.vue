<script setup>
import { computed } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Toast from '@/Components/Toast.vue';
import { Link, usePage } from '@inertiajs/vue3';

// Lógica para capturar as mensagens Flash do Laravel com segurança
const page = usePage();

// O uso do ?. evita erros caso a propriedade flash ou success não existam no momento
const flashSuccess = computed(() => page.props.flash?.success);
const flashError = computed(() => page.props.flash?.error);
</script>

<template>
    <div class="min-h-screen bg-gray-100">
        <nav class="border-b border-gray-100 bg-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between items-center">
                    <div class="flex">
                        <Link :href="route('trechos.index')">
                            <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800" />
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <header class="bg-white shadow" v-if="$slots.header">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <main>
            <slot />
        </main>

        <div class="fixed top-5 right-5 z-[9999] flex flex-col gap-3 pointer-events-none">
            <Toast 
                v-if="$page.props.flash.success" 
                :message="$page.props.flash.success" 
                type="success" 
                :key="$page.props.flash.success"
            />

            <Toast 
                v-if="$page.props.flash.error" 
                :message="$page.props.flash.error" 
                type="error" 
                :key="$page.props.flash.error"
            />
        </div>
    </div>
</template>