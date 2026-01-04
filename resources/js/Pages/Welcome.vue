<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Início - Gestão Rodoviária" />
    
    <div class="relative flex items-top justify-center min-h-screen bg-gray-100 sm:items-center py-4 sm:pt-0">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-center pt-8 sm:pt-0 mb-8">
                <h1 class="text-4xl font-bold text-gray-800 text-center">Sistema de Trechos Rodoviários</h1>
            </div>

            <div class="bg-white overflow-hidden shadow sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div class="p-6 border-2 border-gray-100 rounded-xl hover:border-blue-500 transition-all flex flex-col justify-between">
                        <div>
                            <div class="text-blue-600 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8">
                                    <path d="M11.47 3.84a.75.75 0 011.06 0l8.69 8.69a.75.75 0 101.06-1.06l-8.689-8.69a2.25 2.25 0 00-3.182 0l-8.69 8.69a.75.75 0 001.061 1.06l8.69-8.69z" />
                                    <path d="M12 5.432l8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 01-.75-.75v-4.5a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75V21a.75.75 0 01-.75.75H5.625a1.875 1.875 0 01-1.875-1.875v-6.198a2.29 2.29 0 00.091-.086L12 5.43z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold mb-2 text-blue-600">Painel</h3>
                            <p class="text-gray-600 mb-6">Visualize, edite e exclua os trechos cadastrados no sistema.</p>
                        </div>
                        <div>
                            <Link 
                                :href="route('trechos.index')" 
                                class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors font-semibold"
                            >
                                Acessar Painel
                            </Link>
                        </div>
                    </div>

                    <div class="p-6 border-2 border-gray-100 rounded-xl hover:border-green-500 transition-all flex flex-col justify-between">
                        <div>
                            <div class="text-green-600 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8">
                                    <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold mb-2 text-green-600">Área do Usuário</h3>
                            <p class="text-gray-600 mb-6">Acesse sua conta para gerenciar dados e estatísticas.</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <Link :href="route('login')" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition-colors font-semibold">
                                Acessar conta
                            </Link>
                            <Link :href="route('register')" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition-colors font-semibold">
                                Criar conta
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-8 text-center pt-6">
                <p class="text-sm text-gray-500 flex items-center justify-center gap-2">
                    <span>Desenvolvido como desafio técnico para</span>
                    <span class="font-bold text-gray-800">Suply TI</span>
                    <span class="text-gray-300">|</span>
                    <a 
                        href="https://github.com/dev-salles" 
                        target="_blank"
                        class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 transition-colors font-medium underline underline-offset-4"
                    >
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" /></svg>
                        Davi Sales Barcelos
                    </a>
                </p>
            </div>
        </div>
    </div>
</template>