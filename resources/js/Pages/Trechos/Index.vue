<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    trechos: Object,
});

const formatarData = (dataString) => {
    if (!dataString) return '';
    const data = new Date(dataString);
    // Usamos o 'pt-BR' para garantir o formato dia/mês/ano
    return data.toLocaleDateString('pt-BR', {
        timeZone: 'UTC' // Importante para não alterar o dia devido ao fuso horário
    });
};
</script>

<template>
    <Head title="Listagem de Trechos" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Trechos Rodoviários</h2>
                <Link :href="route('trechos.create')" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Novo Trecho
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">UF</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rodovia</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">KM Inicial</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">KM Final</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="trecho in trechos.data" :key="trecho.id">
                                <td class="px-6 py-4">{{ formatarData(trecho.data_referencia) }}</td>
                                <td class="px-6 py-4">{{ trecho.uf?.sigla }}</td>
                                <td class="px-6 py-4">BR-{{ trecho.rodovia?.nome || 'N/A' }}</td>
                                <td class="px-6 py-4">{{ trecho.km_inicial }}</td>
                                <td class="px-6 py-4">{{ trecho.km_final }}</td>
                                <td class="px-6 py-4 text-right">
                                    <button class="text-indigo-600 hover:text-indigo-900 mr-2">Editar</button>
                                    <button class="text-red-600 hover:text-red-900">Excluir</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="mt-6">
                        <Pagination :links="trechos.links" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>