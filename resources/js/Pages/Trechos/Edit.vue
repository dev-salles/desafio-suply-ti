<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, Link } from "@inertiajs/vue3"; // Importação do Link corrigida!
import { ref, watch, onMounted } from "vue";
import axios from "axios";

const props = defineProps({
    trecho: Object,
    ufs: Array,
});

const rodovias = ref([]);

const carregandoRodovias = ref(false);

const form = useForm({
    data_referencia: props.trecho.data_referencia
        ? props.trecho.data_referencia.split("T")[0]
        : "",
    uf_id: props.trecho.uf_id,
    rodovia_id: props.trecho.rodovia_id,
    tipo: props.trecho.tipo,
    km_inicial: props.trecho.km_inicial,
    km_final: props.trecho.km_final,
});

// Função para buscar rodovias (mesma lógica do Create)
const buscarRodovias = async (ufId) => {
    if (!ufId) return;
    carregandoRodovias.value = true; // Ativa o estado de carregamento
    try {
        const uf = props.ufs.find((u) => u.id === ufId);
        if (uf) {
            const response = await axios.get(`/api/rodovias/${uf.sigla}`);
            let lista = Array.isArray(response.data)
                ? response.data
                : response.data[0].split(",");
            
            // DICA: Use o .map(r => r.trim()) para garantir que não existam espaços escondidos
            rodovias.value = lista.map(r => r.trim());
        }
    } catch (error) {
        console.error("Erro ao buscar rodovias:", error);
    } finally {
        carregandoRodovias.value = false; // Desativa o carregamento
    }
};

onMounted(async () => {
    if (form.uf_id) {
        await buscarRodovias(form.uf_id);
        form.rodovia_id = props.trecho.rodovia_id;
    }
});

watch(
    () => form.uf_id,
    (newId) => buscarRodovias(newId)
);

const submit = () => {
    form.put(route("trechos.update", props.trecho.id), {
        onSuccess: () => {
            console.log("Trecho atualizado com sucesso!");
        },
        onError: (errors) => {
            console.error("Erros na validação:", errors);
        },
    });
};
</script>

<template>
    <Head title="Editar Trecho" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800">
                    Editar Trecho: {{ trecho.id }}
                </h2>
                <Link
                    :href="route('trechos.index')"
                    class="px-4 py-2 bg-gray-500 text-white rounded-lg cursor-pointer"
                >
                    Cancelar
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6"
                >
                    <form
                        @submit.prevent="submit"
                        class="grid grid-cols-1 md:grid-cols-2 gap-6"
                    >
                        <div>
                            <label
                                class="block font-medium text-sm text-gray-700"
                                >Data de Referência</label
                            >
                            <input
                                type="date"
                                v-model="form.data_referencia"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500"
                                required
                            />
                            <div
                                v-if="form.errors.data_referencia"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ form.errors.data_referencia }}
                            </div>
                        </div>

                        <div>
                            <label
                                class="block font-medium text-sm text-gray-700"
                                >UF</label
                            >
                            <select
                                v-model="form.uf_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500"
                                required
                            >
                                <option value="" disabled>
                                    Selecione a UF
                                </option>
                                <option
                                    v-for="uf in ufs"
                                    :key="uf.id"
                                    :value="uf.id"
                                >
                                    {{ uf.sigla }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label
                                class="block font-medium text-sm text-gray-700"
                            >
                                Rodovia
                                {{
                                    carregandoRodovias ? "(Carregando...)" : ""
                                }}
                            </label>
                            <select
                                v-model="form.rodovia_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500"
                                :disabled="carregandoRodovias || !form.uf_id"
                                required
                            >
                                <option value="" disabled>
                                    {{
                                        !form.uf_id
                                            ? "Selecione uma UF primeiro"
                                            : carregandoRodovias
                                            ? "Buscando BRs..."
                                            : "Selecione a Rodovia"
                                    }}
                                </option>
                                <option
                                    v-for="br in rodovias"
                                    :key="br"
                                    :value="br"
                                >
                                    {{ br }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label
                                class="block font-medium text-sm text-gray-700"
                                >Tipo de Trecho</label
                            >
                            <select
                                v-model="form.tipo"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500"
                                required
                            >
                                <option value="A">Tipo A</option>
                                <option value="B">Tipo B</option>
                                <option value="Y">Tipo Y</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">
                                Geralmente 'B' para buscas de rotas específicas.
                            </p>
                        </div>

                        <div>
                            <label
                                class="block font-medium text-sm text-gray-700"
                                >KM Inicial</label
                            >
                            <input
                                type="number"
                                step="0.1"
                                v-model="form.km_inicial"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500"
                                placeholder="Ex: 0.00"
                                required
                            />
                        </div>

                        <div>
                            <label
                                class="block font-medium text-sm text-gray-700"
                                >KM Final</label
                            >
                            <input
                                type="number"
                                step="0.1"
                                v-model="form.km_final"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500"
                                placeholder="Ex: 9.90"
                                required
                            />
                        </div>

                        <div
                            class="md:col-span-2 flex items-center justify-end mt-4"
                        >
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-md font-medium disabled:opacity-50 cursor-pointer"
                            >
                                <span v-if="form.processing"
                                    >Processando...</span
                                >
                                <span v-else>Atualizar Trecho</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
