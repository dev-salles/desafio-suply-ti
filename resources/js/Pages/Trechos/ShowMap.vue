<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref, onMounted, nextTick } from "vue";
import "leaflet/dist/leaflet.css";
import {
    LMap,
    LTileLayer,
    LPolyline,
    LMarker,
    LPopup,
} from "@vue-leaflet/vue-leaflet";
import axios from "axios"; // Importação necessária para busca direta

const props = defineProps({
    trecho: Object,
});

const zoom = ref(13);
const center = ref([-15.7801, -47.9292]); // Brasília como fallback
const rotaCoordenadas = ref([]);
const map = ref(null);
const carregandoMapa = ref(false); // Estado para feedback visual

onMounted(async () => {
    let geoData = props.trecho.geo;

    // LÓGICA DE FALLBACK: Se o dado geográfico não veio do banco, busca direto no navegador (Client-side)
    if (!geoData || !geoData.geometry) {
        carregandoMapa.value = true;
        try {
            console.log("Buscando GeoJSON via navegador (contornando bloqueio do servidor)...");
            const response = await axios.get("https://servicos.dnit.gov.br/sgplan/apigeo/snv/getGeoJson", {
                params: {
                    uf: props.trecho.uf?.sigla,
                    br: props.trecho.rodovia?.nome || props.trecho.rodovia_id
                }
            });
            geoData = response.data;
        } catch (error) {
            console.error("Erro na busca direta ao DNIT:", error);
        } finally {
            carregandoMapa.value = false;
        }
    }

    // PROCESSAMENTO DE COORDENADAS
    if (geoData && geoData.geometry) {
        const rawCoords = geoData.geometry.coordinates;
        
        // Algumas rodovias vêm como MultiLineString (arrays triplos). 
        // O flat(1) garante que tenhamos uma lista simples de pontos [lng, lat]
        const processedCoords = Array.isArray(rawCoords[0][0]) 
            ? rawCoords.flat(1) 
            : rawCoords;

        const coords = processedCoords.map(coord => {
            // Inverte de [Longitude, Latitude] para [Latitude, Longitude] (padrão Leaflet)
            return [coord[1], coord[0]];
        });

        rotaCoordenadas.value = coords;

        if (coords.length > 0) {
            center.value = coords[0];
            
            await nextTick();
            setTimeout(() => {
                if (map.value && map.value.leafletObject) {
                    map.value.leafletObject.invalidateSize();
                    // Ajusta o enquadramento do mapa para mostrar toda a rodovia
                    map.value.leafletObject.fitBounds(coords, { padding: [20, 20] });
                }
            }, 300);
        }
    }
});
</script>

<template>
    <Head title="Visualizar Mapa" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Mapa da Rodovia:
                    BR - {{ trecho.rodovia?.nome || trecho.rodovia_id }}
                </h2>
                <Link
                    :href="route('trechos.index')"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition "
                >
                Voltar
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6"
                >
                    <div
                        class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm"
                    >
                        <div class="p-3 bg-blue-50 rounded">
                            <strong>UF:</strong> {{ trecho.uf?.sigla }}
                        </div>
                        <div class="p-3 bg-blue-50 rounded">
                            <strong>KM Inicial:</strong> {{ trecho.km_inicial }}
                        </div>
                        <div class="p-3 bg-blue-50 rounded">
                            <strong>KM Final:</strong> {{ trecho.km_final }}
                        </div>
                    </div>

                    <div v-if="carregandoMapa" class="mb-4 p-3 bg-blue-50 border-l-4 border-blue-500 text-blue-700 text-sm animate-pulse flex items-center">
                        <svg class="animate-spin h-4 w-4 mr-3 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Buscando traçado da rodovia diretamente no servidor do DNIT para contornar bloqueios...
                    </div>

                    <div
                        style="height: 600px; width: 100%"
                        class="rounded-lg border-2 border-gray-200 shadow-inner overflow-hidden"
                    >
                        <l-map
                            ref="map"
                            v-model:zoom="zoom"
                            :center="center"
                            :use-global-leaflet="false"
                        >
                            <l-tile-layer
                                url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
                                layer-type="base"
                                name="OpenStreetMap"
                            ></l-tile-layer>

                            <l-polyline
                                v-if="rotaCoordenadas.length > 0"
                                :lat-lngs="rotaCoordenadas"
                                color="#ef4444"
                                :weight="6"
                                :opacity="0.9"
                            >
                                <l-popup
                                    >Trecho da
                                    {{ trecho.rodovia?.nome }}</l-popup
                                >
                            </l-polyline>
                        </l-map>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
