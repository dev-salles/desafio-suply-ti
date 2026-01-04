<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref, onMounted, nextTick } from "vue"; // Adicionei o nextTick
import "leaflet/dist/leaflet.css";
import {
    LMap,
    LTileLayer,
    LPolyline,
    LMarker,
    LPopup,
} from "@vue-leaflet/vue-leaflet";

const props = defineProps({
    trecho: Object,
});

const zoom = ref(13);
const center = ref([-15.7801, -47.9292]); // Brasília como fallback
const rotaCoordenadas = ref([]);
const map = ref(null);

onMounted(async () => {
    console.log("DADOS BRUTOS DA API:", props.trecho.geo); // ABRA O F12 E VEJA ISSO

    if (props.trecho.geo && props.trecho.geo.geometry) {
        const rawCoords = props.trecho.geo.geometry.coordinates;
        
        // Verifica se é um MultiLineString ou LineString simples
        // GeoJSON de rodovias as vezes vem em arrays aninhados [[[lng, lat]]]
        const processedCoords = Array.isArray(rawCoords[0][0]) 
            ? rawCoords[0] 
            : rawCoords;

        const coords = processedCoords.map(coord => {
            // Latitude deve ser negativa (aprox -15 a -30 para o Brasil)
            // Longitude deve ser negativa (aprox -40 a -60 para o Brasil)
            const lat = coord[1];
            const lng = coord[0];
            
            return [lat, lng];
        });

        rotaCoordenadas.value = coords;

        if (coords.length > 0) {
            center.value = coords[0];
            
            await nextTick();
            setTimeout(() => {
                if (map.value && map.value.leafletObject) {
                    map.value.leafletObject.invalidateSize();
                    // O fitBounds é o que vai te tirar do oceano e te levar pro MS
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
