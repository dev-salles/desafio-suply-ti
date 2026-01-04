<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import axios from "axios";

// Definimos as props explicitamente
const props = defineProps({
    ufs: {
        type: Array,
        default: () => [],
    },
});

const rodovias = ref([]);
const carregandoRodovias = ref(false);

const form = useForm({
    data_referencia: new Date().toISOString().split("T")[0],
    uf_id: "",
    rodovia_id: "",
    tipo: "B",
    km_inicial: "",
    km_final: "",
});

// Lógica da UF (Mantenha como está, apenas garanta que props.ufs existe)
watch(
    () => form.uf_id,
    async (ufId) => {
        rodovias.value = [];
        form.rodovia_id = "";

        if (!ufId) return;

        const ufSelecionada = props.ufs.find((u) => u.id === ufId);

        if (ufSelecionada) {
            carregandoRodovias.value = true;
            try {
                const response = await axios.get(
                    `/api/rodovias/${ufSelecionada.sigla}`
                );
                if (
                    Array.isArray(response.data) &&
                    response.data.length === 1 &&
                    response.data[0].includes(",")
                ) {
                    rodovias.value = response.data[0].split(",");
                } else {
                    rodovias.value = response.data;
                }
            } catch (error) {
                console.error("Erro na requisição:", error);
            } finally {
                carregandoRodovias.value = false;
            }
        }
    }
);

const submit = () => {
    form.post(route('trechos.store'), {
        onSuccess: () => {
            console.log("Salvo com sucesso!");
        },
        onError: (errors) => {
            // Se houver erro de validação, ele cai aqui
            console.error("Erro de validação:", errors);
        },
    });
};

</script>

<template>
    <Head title="Cadastrar Trecho" />

    <AuthenticatedLayout>
        <template #header>
            <div
                class="bg-white p-6 rounded-lg shadow-sm mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4"
            >
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Cadastrar Trecho Rodoviário
                </h2>
                <div class="flex items-center gap-3">
                    <Link
                        :href="route('trechos.index')"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2 cursor-pointer shadow-sm"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"
                            />
                        </svg>
                        Cancelar
                    </Link>

                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center gap-2 shadow-md font-medium cursor-pointer"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                            />
                        </svg>
                        Sair
                    </Link>
                </div>
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
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition ease-in-out duration-150"
                                :disabled="form.processing"
                            >
                                <span v-if="form.processing"
                                    >Processando...</span
                                >
                                <span v-else>Salvar Trecho</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
