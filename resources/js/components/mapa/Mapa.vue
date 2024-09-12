<script setup>
import {GoogleMap, Polygon, AdvancedMarker} from 'vue3-google-map';
import {ref, onMounted, inject} from 'vue';
import axios from 'axios';

const municipios = ref([]);
const selectedCity = ref('');
const center = ref(null);
const polygons = ref([]); // Lista de polígonos
const advancedMarkers = ref([]); // Lista de marcadores
const mapLoaded = ref(false); // Controla quando o mapa deve ser carregado
const events = inject('events');
onMounted(async () => {
    await carregarMunicipios();
    events.on('recarrega_mapa', carregarCoordenadas);
});

const carregarMunicipios = async () => {
    try {
        const response = await axios.get('https://servicodados.ibge.gov.br/api/v1/localidades/estados/51/municipios');
        municipios.value = response.data;
    } catch (error) {
        console.error('Erro ao carregar municípios:', error);
    }
};

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


const carregarCoordenadas = async () => {

    try {
        const response = await axios.get(`/mapa/getByCidade/${selectedCity.value}`);
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

                // Calcula o position do polígono
                const position = calcularPosition(path);

                if (coordenada.imovel_id !== null) {
                    if(coordenada.imovel.pessoa){
                        const markerOptions = {
                            position: position,
                            title: 'Imóvel COM morador',
                        };
                        const pinOptions = {
                            background: '#00ff00',
                            glyphColor:'#00ff00',
                        };
                        const customData = {
                            coordenada_id: coordenada.id,
                            imovel_id: coordenada.imovel_id
                        }
                        advancedMarkers.value.push({options: markerOptions, pinOptions: pinOptions, customData: customData});
                        return {
                            paths: path,
                            strokeColor: '#00ff00',
                            strokeOpacity: 0.8,
                            strokeWeight: 2,
                            fillColor: '#00ff00',
                            fillOpacity: 0.35,
                        };
                    }else {
                        const markerOptions = {
                            position: position,
                            title: 'Imóvel SEM morador',
                        };
                        const pinOptions = {
                            background: '#0d6efd',
                            glyphColor:'#0d6efd',
                        };
                        const customData = {
                            coordenada_id: coordenada.id,
                            imovel_id: coordenada.imovel_id
                        }
                        advancedMarkers.value.push({
                            options: markerOptions,
                            pinOptions: pinOptions,
                            customData: customData
                        });
                        return {
                            paths: path,
                            strokeColor: '#0d6efd',
                            strokeOpacity: 0.8,
                            strokeWeight: 2,
                            fillColor: '#0d6efd',
                            fillOpacity: 0.35,
                        };
                    }
                } else {
                    const markerOptions = {
                        position: position,
                        title: 'Sem imóvel cadastrado',
                    };
                    const pinOptions = {
                        background: '#D3D3D3',
                        glyphColor:'#D3D3D3',
                    };
                    const customData = {
                        coordenada_id: coordenada.id,
                    }
                    advancedMarkers.value.push({options: markerOptions, pinOptions: pinOptions, customData: customData});
                    return {
                        paths: path,
                        strokeColor: '#D3D3D3',
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillColor: '#D3D3D3',
                        fillOpacity: 0.35,
                    };
                }



            });

            mapLoaded.value = true; // Carregar o mapa
        }
    } catch (error) {
        console.error('Erro ao carregar coordenadas:', error);
    }
};


// Função para ser chamada ao clicar em um AdvancedMarker
const handleMarkerClick = (marker_id) => {
    events.emit('popup', {
        title: 'Vincular coordenada a Imóvel',
        component: 'vincula-coord-imovel',
        data: marker_id,
        size: 'md',
        id: 'vinculacao'
    });
};
</script>



<template>
    <div>
        <p>Selecione uma cidade da lista abaixo para visualizar as coordenadas no mapa.</p>

        <label class="form-label">Selecione uma cidade:</label>
        <select v-model="selectedCity" @change="carregarCoordenadas" class="form-select mb-3">
            <option value="" disabled>Escolha uma cidade para Importar</option>
            <option v-for="municipio in municipios" :key="municipio.id" :value="municipio.nome">
                {{ municipio.nome }}
            </option>
        </select>

        <div v-if="mapLoaded">
            <GoogleMap
                api-key="AIzaSyBx1KWiOM70BALJjUC5QI0jrQC0QdEc7Jo"
                mapId="SATELLITE"
                style="width: 100%; height: 650px"
                :center="center"
                :zoom="15"
            >
                <Polygon
                    v-for="(polygon, index) in polygons"
                    :key="index"
                    :options="polygon"
                />
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
