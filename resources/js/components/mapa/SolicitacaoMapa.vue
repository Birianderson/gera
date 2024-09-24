<script setup>
import {GoogleMap, Polygon, AdvancedMarker} from 'vue3-google-map';
import {ref, onMounted, inject} from 'vue';
import axios from 'axios';
import {Loader} from '@googlemaps/js-api-loader';

// Configurando o loader da API do Google Maps
const loader = new Loader({
    apiKey: 'AIzaSyBx1KWiOM70BALJjUC5QI0jrQC0QdEc7Jo',
    version: 'weekly',
    libraries: ['places', 'geometry', 'marker'],
});

const apiPromise = loader.load(); // Carrega a API

const center = ref(null);
const polygons = ref([]); // Lista de polígonos
const advancedMarkers = ref([]); // Lista de marcadores
const mapLoaded = ref(false); // Controla quando o mapa deve ser carregado
const userLocation = ref(null); // Localização do usuário
const events = inject('events');
const props = defineProps({
    data: {default: true},
});

onMounted(async () => {
    events.on('recarrega_mapa', carregarCoordenadas);
    await carregarCoordenadas(); // Carregar as coordenadas ao montar o componente
    getUserLocation(); // Captura a localização do usuário ao montar o componente
});

// Função para capturar a localização do usuário
const getUserLocation = () => {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(position => {
            userLocation.value = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
        }, (error) => {
            console.error('Erro ao obter localização do usuário:', error);
        });
    } else {
        console.error('Geolocalização não suportada pelo navegador.');
    }
};

// Função para calcular a posição central de um polígono
const calcularPosition = (path) => {
    const numCoords = path.length;
    let sumLat = 0;
    let sumLng = 0;

    path.forEach(coord => {
        sumLat += coord.lat;
        sumLng += coord.lng;
    });

    return {
        lat: sumLat / numCoords,
        lng: sumLng / numCoords
    };
};

// Função para carregar as coordenadas e criar polígonos e marcadores
const carregarCoordenadas = async () => {
    try {
        const response = await axios.get(`/mapa/getByHash/${props.data}`);
        const coordenadas = response.data;

        if (coordenadas.length > 0) {
            const primeiraCoordenada = coordenadas[0];
            center.value = {
                lat: parseFloat(primeiraCoordenada.lat.split(',')[0]),
                lng: parseFloat(primeiraCoordenada.long.split(',')[0])
            };

            polygons.value = coordenadas.map(coordenada => {
                const latitudes = coordenada.lat.split(',').map(parseFloat);
                const longitudes = coordenada.long.split(',').map(parseFloat);

                const path = latitudes.map((lat, index) => ({
                    lat: lat,
                    lng: longitudes[index]
                }));

                // Calcula a posição central do polígono
                const position = calcularPosition(path);
                const markerOptions = {
                    position: position,
                    title: 'Imóvel SEM morador',
                };
                const pinOptions = {
                    background: '#0d6efd',
                    glyphColor: '#ffffff',
                };
                const customData = {
                    imovel_id: coordenada.imovel_id,
                    polygon: path, // Adiciona o caminho do polígono para verificação
                };

                // Adiciona o marcador à lista de marcadores avançados
                advancedMarkers.value.push({
                    options: markerOptions,
                    pinOptions: pinOptions,
                    customData: customData
                });

                // Retorna as opções do polígono
                return {
                    paths: path,
                    strokeColor: '#0d6efd',
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: '#0d6efd',
                    fillOpacity: 0.35,
                };
            });
            mapLoaded.value = true; // Sinaliza que o mapa foi carregado
        }
    } catch (error) {
        console.error('Erro ao carregar coordenadas:', error);
    }
};

// Função para ser chamada ao clicar em um AdvancedMarker
const handleMarkerClick = (markerData) => {
    if (userLocation.value && google.maps.geometry) {
        const userLatLng = new google.maps.LatLng(userLocation.value.lat, userLocation.value.lng);

        // Cria um objeto LatLng para cada ponto do polígono
        const polygonPath = markerData.polygon.map(coord => new google.maps.LatLng(coord.lat, coord.lng));

        const polygon = new google.maps.Polygon({paths: polygonPath});

        // Verifica se o usuário está dentro do polígono
        if (google.maps.geometry.poly.containsLocation(userLatLng, polygon)) {
            alert('Você está dentro deste imóvel!');
        } else {
            alert('Você está fora deste imóvel!');
        }
    } else {
        console.error('Geometria do Google Maps ou localização do usuário não disponível.');
    }
};
</script>

<template>
    <div>
        <div v-if="mapLoaded">
            <GoogleMap
                mapId="SATELLITE"
                :api-promise="apiPromise"
                style="width: 100%; height: 650px"
                :center="center"
                :zoom="15"
            >
                <!-- Renderiza os polígonos -->
                <Polygon
                    v-for="(polygon, index) in polygons"
                    :key="index"
                    :options="polygon"
                />
                <!-- Renderiza os marcadores avançados -->
                <AdvancedMarker
                    v-for="(marker, index) in advancedMarkers"
                    :key="index"
                    :options="marker.options"
                    :pin-options="marker.pinOptions"
                    @click="handleMarkerClick(marker.customData)"
                />
            </GoogleMap>
        </div>
    </div>
</template>
