<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Início - Gestão Rodoviária" />
    
    <div class="relative flex items-top justify-center min-h-screen bg-gray-100 sm:items-center py-4 sm:pt-0">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-center pt-8 sm:justify-start sm:pt-0 mb-8">
                <h1 class="text-4xl font-bold text-gray-800">Sistema de Trechos Rodoviários</h1>
            </div>

            <div class="bg-white overflow-hidden shadow sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div class="p-6 border-2 border-gray-100 rounded-xl hover:border-blue-500 transition-all flex flex-col justify-between">
                        <div>
                            <h3 class="text-lg font-bold mb-2 text-blue-600">📊 Dashboard de Trechos</h3>
                            <p class="text-gray-600 mb-6">Visualize, edite e exclua os trechos cadastrados no sistema.</p>
                        </div>
                        <div>
                            <Link :href="route('trechos.index')" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full text-center md:w-auto transition-colors">
                                Acessar Listagem
                            </Link>
                        </div>
                    </div>

                    <div class="p-6 border-2 border-gray-100 rounded-xl hover:border-green-500 transition-all flex flex-col justify-between">
                        <div>
                            <h3 class="text-lg font-bold mb-2 text-green-600">➕ Novo Cadastro</h3>
                            <p class="text-gray-600 mb-6">Adicione novos trechos rodoviários de forma rápida e organizada.</p>
                        </div>
                        <div>
                            <Link :href="route('trechos.create')" class="inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 w-full text-center md:w-auto transition-colors">
                                Cadastrar Trecho
                            </Link>
                        </div>
                    </div>

                </div>
            </div>

            <div class="flex justify-center mt-4 sm:items-center sm:justify-between text-gray-400 text-sm italic">
                Desenvolvido como desafio técnico da Suply TI - 2026
            </div>
        </div>
    </div>
</template>