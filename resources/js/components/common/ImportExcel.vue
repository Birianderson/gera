<template>
    <div>
        <label class="form-label">Selecione uma cidade:</label>
        <select v-model="selectedCity" @change="enableUpload" class="form-select mb-3">
            <option value="" disabled>Escolha uma cidade para Importar</option>
            <option v-for="municipio in municipios" :key="municipio.id" :value="municipio.nome">
                {{ municipio.nome }}
            </option>
        </select>

        <label class="file-upload-label" :class="{ disabled: !selectedCity }">
            {{ title }}
            <input type="file" @change="uploadFile" class="file-upload-input" :disabled="!selectedCity" />
            <i class="fa fa-upload"></i>
        </label>
    </div>
</template>

<script setup>
import { inject, ref, onMounted } from 'vue'
import axios from 'axios'

const events = inject('events');
const file = ref(null)
const municipios = ref([]);
const selectedCity = ref('');

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

    const formData = new FormData()
    formData.append('file', file.value)
    formData.append('cidade', selectedCity.value)  // Adicionar a cidade selecionada ao FormData

    try {
        const response = await axios.post(props.uploadUrl, formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        })
        console.log('Upload successful', response.data)
    } catch (error) {
        console.error('Error uploading file', error)
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
</style>
