<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { watch, ref } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';

defineProps({
    trechos: Object,
});


const page = usePage();
const mostrarMensagem = ref(false);

// Monitora a prop de flash.success
watch(() => page.props.flash.success, (novoValor) => {
    if (novoValor) {
        mostrarMensagem.value = true;
        
        // Opcional: Faz a mensagem sumir sozinha após 4 segundos
        setTimeout(() => {
            mostrarMensagem.value = false;
        }, 4000);
    }
}, { immediate: true });

const formatarData = (dataString) => {
    if (!dataString) return '';
    const data = new Date(dataString);
    // Usamos o 'pt-BR' para garantir o formato dia/mês/ano
    return data.toLocaleDateString('pt-BR', {
        timeZone: 'UTC' // Importante para não alterar o dia devido ao fuso horário
    });
};

const excluirTrecho = (id) => {
    if (confirm('Tem certeza que deseja excluir este trecho?')) {
        router.delete(route('trechos.destroy', id), {
            preserveScroll: true,
            onSuccess: () => {
                // Opcional: você pode disparar um som ou um log aqui
            }
        });
    }
};

</script>

<template>
    <Head title="Listagem de Trechos" />

    <AuthenticatedLayout>
        <template #header>
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <h1 class="text-2xl font-bold text-gray-800">Trechos Rodoviários</h1>

                <div class="flex items-center gap-3">
                    <Link :href="route('home')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                            Voltar para o Início
                    </Link>

                    <Link :href="route('trechos.create')" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        Adicionar Trecho
                    </Link>
                    <Link :href="route('logout')" method="post" as="button" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center gap-2 shadow-md font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Sair
                    </Link>
                </div>
            </div>
        </template>

        <Toast  v-if="$page.props.flash.success" :key="Math.random()" :message="$page.props.flash.success" type="success"></Toast>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
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
                            <tr v-for="trecho in trechos.data" :key="trecho.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4">{{ formatarData(trecho.data_referencia) }}</td>
                                <td class="px-6 py-4">{{ trecho.uf?.sigla }}</td>
                                <td class="px-6 py-4">BR-{{ trecho.rodovia?.nome || 'N/A' }}</td>
                                <td class="px-6 py-4">{{ trecho.km_inicial }}</td>
                                <td class="px-6 py-4">{{ trecho.km_final }}</td>
                                <td class="px-6 py-4 text-right space-x-3">
                                    <Link :href="route('trechos.edit', trecho.id)" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                        Editar
                                    </Link>
                                    <button @click="excluirTrecho(trecho.id)" class="text-red-600 hover:text-red-900 font-medium">
                                        Excluir
                                    </button>
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