<script setup>
import { GoogleMap, Polygon, AdvancedMarker, CustomMarker } from 'vue3-google-map';
import { ref, onMounted, inject } from 'vue';
import axios from 'axios';
import { Loader } from '@googlemaps/js-api-loader';

// Configurando o loader da API do Google Maps
const loader = new Loader({
    apiKey: 'AIzaSyBx1KWiOM70BALJjUC5QI0jrQC0QdEc7Jo', // Substitua pelo seu
    version: 'weekly',
    libraries: ['places', 'geometry', 'marker'],
});

const apiPromise = loader.load(); // Carrega a API

const center = ref(null);
const polygons = ref([]); // Lista de polígonos
const advancedMarkers = ref([]); // Lista de marcadores
const userMarker = ref(null); // Marcador da localização do usuário
const mapLoaded = ref(false); // Controla quando o mapa deve ser carregado
const userLocation = ref(null); // Localização do usuário
const verificationProgress = ref(0); // Progresso de verificação
const loading = ref(false); // Controla a exibição do spinner
const verified = ref(false); // Controle se todas as verificações foram bem-sucedidas

const events = inject('events');
const props = defineProps({
    data: { default: true },
});

onMounted(async () => {
    events.on('recarrega_mapa', carregarCoordenadas);
    await carregarCoordenadas(); // Carregar as coordenadas ao montar o componente
    getUserLocation(); // Captura a localização do usuário ao montar o componente
});

// Função para capturar a localização do usuário
const getUserLocation = () => {
    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(position => {
            userLocation.value = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            // Atualiza o marcador da localização do usuário dinamicamente
            userMarker.value = {
                position: { lat: userLocation.value.lat, lng: userLocation.value.lng },
                title: 'Sua Localização',
            };
        }, (error) => {
            console.error('Erro ao obter localização do usuário:', error);
        }, {
            enableHighAccuracy: true, // Maior precisão, mas consome mais bateria
            maximumAge: 0, // Não guarda em cache a localização anterior
            timeout: 5000 // Tempo limite para tentar pegar a localização
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
        const response = await axios.get(`/user/mapa/getByHash/${props.data}`);
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
                    title: 'Clique para verificar a Localização',
                };
                const pinOptions = {
                    background: '#0d6efd',
                    glyphColor: '#ffffff',
                    borderColor: '#0d6efd'
                };
                const customData = {
                    imovel_id: coordenada.imovel_id,
                    polygon: path,
                };

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

// Função para iniciar a verificação de localização ao clicar no marcador
const handleMarkerClick = (markerData) => {
    loading.value = true; // Inicia o spinner
    verificationProgress.value = 0; // Reseta o progresso
    startVerification(markerData.polygon, markerData.imovel_id); // Inicia o processo de verificação
};

// Função para verificar a localização do usuário 10 vezes
const startVerification = (polygonPath, imovelId) => {
    let attempts = 0;
    let successes = 0;

    const interval = setInterval(() => {
        attempts++;
        verificationProgress.value = Math.round((attempts / 10) * 100);

        // Verifica a localização em relação ao imóvel
        const isInside = verifyLocation(polygonPath);

        if (isInside) {
            successes++;
        } else {
            clearInterval(interval); // Cancela o loop se o usuário estiver fora
            loading.value = false;
            alert('Você está fora deste imóvel.');
            return;
        }

        // Se atingiu 10 tentativas ou todas as verificações forem bem-sucedidas
        if (attempts === 10 || successes === 10) {
            clearInterval(interval);
            loading.value = false;

            // Se todas as tentativas forem bem-sucedidas
            if (successes === 10) {
                verified.value = true;
                submitData(imovelId);
            } else {
                alert('Você está fora deste imóvel.');
            }
        }
    }, 1000);
};

// Função para verificar se o usuário está dentro do polígono
const verifyLocation = (polygonPath) => {
    if (userLocation.value && google.maps.geometry) {
        const userLatLng = new google.maps.LatLng(userLocation.value.lat, userLocation.value.lng);
        const polygon = new google.maps.Polygon({ paths: polygonPath });

        return google.maps.geometry.poly.containsLocation(userLatLng, polygon);
    }
    return false;
};

// Função para enviar o ID do imóvel
const submitData = async (imovelId) => {
    try {
        await axios.post('/user/solicitacoes/aprovado', {
            imovel_id: imovelId,
        }).then(response => {
            window.location.href = '/user/solicitacoes'; // Redireciona após o login
        })
            .catch(error => {
                console.error('Erro durante o login', error);
            });
        alert('Verificação bem-sucedida e dados enviados!');
    } catch (error) {
        console.error('Erro ao enviar dados:', error);
    }
};
</script>

<template>
    <div>
        <div v-if="loading" class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Verificando...</span>
            </div>
            <p>{{ verificationProgress }}% Verificado</p>
        </div>

        <div v-else>
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
                <!-- Marcador de localização do usuário -->
                <CustomMarker v-if="userLocation" :options="{ position: userLocation, anchorPoint: 'BOTTOM_CENTER' }">
                    <div style="text-align: center;">
                        <div style="font-size: 1.125rem">Sua localização</div>
                        <i class="fa fa-circle-user" style="font-size: 32px; color: red;"></i>
                    </div>
                </CustomMarker>
            </GoogleMap>
        </div>
    </div>
</template>
Z
