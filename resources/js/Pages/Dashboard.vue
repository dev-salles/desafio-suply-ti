<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3'; // Importamos o Link aqui
</script>

<template>
    <Head title="Sucesso" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Status do Cadastro
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-12 text-center text-gray-900">
                        <div class="flex justify-center mb-4">
                            <div class="rounded-full bg-green-100 p-3">
                                <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>

                        <h3 class="text-2xl font-bold text-gray-800 mb-2">
                            Cadastro realizado com sucesso!
                        </h3>
                        
                        <p class="text-gray-600 mb-8">
                           Acesse a tela principal pelo botão abaixo.
                        </p>

                        <Link
                            :href="route('trechos.index')"
                            class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-white uppercase tracking-widest hover:bg-blue-700 transition ease-in-out duration-150 shadow-md"
                        >
                            Ir para o Início
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>