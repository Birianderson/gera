<template>
    <div>
        <label class="form-label">Selecione uma cidade:</label>
        <select v-model="selectedCity" class="form-select mb-3">
            <option value="" disabled>Escolha uma cidade para Importar</option>
            <option v-for="municipio in municipios" :key="municipio.id" :value="municipio.nome">
                {{ municipio.nome }}
            </option>
        </select>

        <label class="file-upload-label" :class="{ disabled: !selectedCity }">
            {{ title }}
            <input type="file" @change="uploadFile" class="file-upload-input" :disabled="!selectedCity" accept=".xlsx, .xls, .csv, .kml" />
            <i v-if="status === 'idle'" class="fa fa-upload"></i>
            <div v-else-if="status === 'pending'" class="spinner-border text-light" role="status"></div>
            <i v-else-if="status === 'success'" class="fa fa-check-circle text-white"></i>
            <i v-else-if="status === 'error'" class="fa fa-exclamation-circle text-warning"></i>
        </label>
    </div>
</template>

<script setup>
import {inject, ref, onMounted} from 'vue'
import axios from 'axios'

const events = inject('events');
const file = ref(null)
const municipios = ref([]);
const selectedCity = ref('');
const status = ref('idle'); // Status inicial é 'idle'

// Receber os atributos dinâmicos (título e URL)
const props = defineProps({
    title: {
        type: String,
        default: 'Upload File',
    },
    uploadUrl: {
        type: String,
        required: true,
    }
});

// Carregar os municípios ao montar o componente
onMounted(async () => {
    await carregarMunicipio();
});

const carregarMunicipio = async () => {
    try {
        const response = await axios.get('https://servicodados.ibge.gov.br/api/v1/localidades/estados/51/municipios');
        municipios.value = response.data;
    } catch (error) {
        console.error('Erro ao carregar municípios:', error);
        events.emit('notification', {
            type: 'error',
            message: 'Erro ao carregar municípios.'
        });
    }
};

const uploadFile = async (event) => {
    file.value = event.target.files[0]

    // Verifique se o arquivo é do tipo KML ou outro tipo permitido
    const allowedTypes = ['application/vnd.ms-excel', 'text/csv', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.google-earth.kml+xml'];
    if (!allowedTypes.includes(file.value.type)) {
        console.error('Tipo de arquivo não suportado');
        events.emit('notification', {
            type: 'error',
            message: 'Tipo de arquivo não suportado.'
        });
        return;
    }

    const formData = new FormData()
    formData.append('file', file.value)
    formData.append('cidade', selectedCity.value)

    status.value = 'pending'; // Status 'pending' enquanto o upload está em andamento

    try {
        const response = await axios.post(props.uploadUrl, formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        })
        console.log('Upload successful', response.data)
        status.value = 'success'; // Status 'success' após o upload bem-sucedido
    } catch (error) {
        console.error('Error uploading file', error)
        status.value = 'error'; // Status 'error' se ocorrer um erro no upload
    }
};
</script>

<style scoped>
.file-upload-label {
    display: inline-block;
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s;
}

.file-upload-label.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.file-upload-input {
    display: none;
}

.spinner-border {
    width: 20px;
    height: 20px;
    border-width: 2px;
    margin-left: 10px;
}

.fa {
    margin-left: 10px;
}
</style>
