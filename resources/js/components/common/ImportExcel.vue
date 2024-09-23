<template>
    <div>
        <label class="form-label">Selecione uma cidade:</label>
        <select v-model="selectedCity" class="form-select mb-3" @change="carregarLoteamento">
            <option value="" disabled>Escolha uma cidade para Importar</option>
            <option v-for="municipio in municipios" :key="municipio.id" :value="municipio.id">
                {{ municipio.nome }}
            </option>
        </select>

        <label class="form-label">Selecione um loteamento:</label>
        <select :disabled="!loteamentos.length > 0" v-model="selectedLoteamento" class="form-select mb-3">
            <option value="" disabled>Escolha um loteamento</option>
            <option v-for="loteamento in loteamentos" :key="loteamento.id" :value="loteamento.id">
                {{ loteamento.nome }}
            </option>
        </select>

        <label class="file-upload-label" :class="{ disabled: !selectedLoteamento }">
            {{ title }}
            <input type="file" @change="uploadFile" class="file-upload-input" :disabled="!selectedLoteamento" accept=".xlsx, .xls, .csv, .kml" />
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
const loteamentos = ref([]);
const selectedCity = ref('');
const selectedLoteamento = ref('');
const status = ref('idle');

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
        const response = await axios.get('/admin/cidade/getAll');
        municipios.value = response.data;
    } catch (error) {
        console.error('Erro ao carregar municípios:', error);
        events.emit('notification', {
            type: 'error',
            message: 'Erro ao carregar municípios.'
        });
    }
};

// Carregar os loteamentos ao selecionar uma cidade
const carregarLoteamento = async () => {
    if (!selectedCity.value) return;

    try {
        const response = await axios.get(`/admin/loteamento/findLoteamentoByCidade/${selectedCity.value}`);
        loteamentos.value = response.data;
    } catch (error) {
        console.error('Erro ao carregar loteamentos:', error);
        events.emit('notification', {
            type: 'error',
            message: 'Erro ao carregar loteamentos.'
        });
    }
};

const uploadFile = async (event) => {
    file.value = event.target.files[0]

    const allowedTypes = ['application/vnd.ms-excel', 'text/csv', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.google-earth.kml+xml'];
    if (!allowedTypes.includes(file.value.type)) {
        console.error('Tipo de arquivo não suportado');
        events.emit('notification', {
            type: 'error',
            message: 'Tipo de arquivo não suportado.'
        });
        return;
    }

    const formData = new FormData();
    formData.append('file', file.value);
    formData.append('loteamento_id', selectedLoteamento.value);

    status.value = 'pending';

    try {
        const response = await axios.post(props.uploadUrl, formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });
        console.log('Upload successful', response.data);
        status.value = 'success';
    } catch (error) {
        console.error('Error uploading file', error);
        status.value = 'error';
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
