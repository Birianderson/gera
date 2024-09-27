<template>
    <div class="documentos-container">
        <div v-if="loading" class="loading-spinner">
            <p>Carregando...</p>
        </div>
        <div v-else>
            <!-- Mostrar todos os tipos de documentos -->
            <div v-for="documentoTipo in allDocumentos" :key="documentoTipo.id" class="documento-card row">
                <a class="documento-info col-10" :href="`/user/pessoa/documentos/${documentoTipo.id}`">

                <div><strong>Documento:</strong> {{ documentoTipo.nome }}</div>

                    <div v-if="ultimoDocumento(documentoTipo.id)">
                        <div class="mt-2 mb-2">
                            <strong>Status:</strong>
                            <span class="ms-1 text-white rounded-3 p-2" :class="statusClass(ultimoDocumento(documentoTipo.id).status)">
                            {{ getStatusTexto(ultimoDocumento(documentoTipo.id).status) }}</span>
                        </div>
                    </div>

                    <!-- Mostrar a opção de enviar novo documento se não houver nenhum documento enviado -->
                    <div v-else>
                        <p>Nenhum documento enviado ainda.</p>
                        <label class="custom-file-upload">
                            <i class="fa fa-cloud-upload"></i> Escolher arquivo
                            <input type="file" @change="handleFileChange(documentoTipo.id, $event)" />
                        </label>
                    </div>
                </a>
                <div class="col-2 align-content-center text-primary"><i class="fa fa-arrow-alt-circle-right "></i> </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';

export default {
    setup() {
        const allDocumentos = ref([]);
        const documentos = ref([]);
        const arquivosSelecionados = ref([]);
        const loading = ref(true);
        const nomesArquivosSelecionados = ref({});

        const carregarDocumentos = async () => {
            try {
                // Carrega os documentos já enviados pelo usuário
                const response = await axios.get('/user/pessoa/meus_documentos');
                documentos.value = response.data || [];

                // Carrega todos os documentos disponíveis
                const responsePendentes = await axios.get('/user/pessoa/all_documentos');
                allDocumentos.value = responsePendentes.data || [];

                loading.value = false;
            } catch (error) {
                console.error('Erro ao carregar documentos:', error);
            }
        };

        // Função para encontrar o último documento enviado de um tipo específico
        const ultimoDocumento = (tipoDocumentoId) => {
            const documentosDoTipo = documentos.value.filter(doc => doc.tipo_arquivo_id === tipoDocumentoId);
            if (documentosDoTipo.length > 0) {
                // Retornar o último documento com base no array filtrado
                return documentosDoTipo[documentosDoTipo.length - 1];
            }
            return null; // Se nenhum documento do tipo for encontrado
        };

        const handleFileChange = (tipoDocumentoId, event) => {
            if (event && event.target && event.target.files.length > 0) {
                const file = event.target.files[0];
                arquivosSelecionados.value.push({ tipoDocumentoId, file });
                nomesArquivosSelecionados.value[tipoDocumentoId] = file.name;
            } else {
                console.error('Nenhum arquivo foi selecionado ou evento inválido.');
            }
        };

        const uploadDocumentos = async () => {
            const formData = new FormData();

            arquivosSelecionados.value.forEach(({ tipoDocumentoId, file }) => {
                formData.append('arquivo[]', file);
                formData.append('tipo_arquivo_id[]', tipoDocumentoId);
            });

            try {
                await axios.post('/user/pessoa/upload_documentos', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                });
                alert('Documentos enviados com sucesso!');
                // Limpar os arquivos selecionados após o envio
            } catch (error) {
                console.error('Erro ao enviar documentos:', error);
            }
        };

        const statusClass = (status) => {
            return status === 'R'
                ? 'bg-danger'
                : status === 'P'
                    ? 'bg-warning'
                    : status === 'A'
                        ? 'bg-success'
                        : 'bg-secondary';
        };

        const getStatusTexto = (status) => {
            switch (status) {
                case 'A':
                    return 'Aprovado';
                case 'R':
                    return 'Reprovado';
                case 'P':
                    return 'Em Avaliação';
                default:
                    return 'Desconhecido';
            }
        };

        onMounted(() => {
            carregarDocumentos();
        });

        return {
            allDocumentos,
            documentos,
            loading,
            arquivosSelecionados,
            statusClass,
            getStatusTexto,
            handleFileChange,
            uploadDocumentos,
            ultimoDocumento,
        };
    },
};
</script>

<style scoped>
.documentos-container {
    padding: 20px;
    max-width: 100%;
    margin: 0 auto;
}

.loading-spinner {
    text-align: center;
}

.documento-card {
    background-color: white;
    border-radius: 8px;
    margin-bottom: 15px;
    padding: 15px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.documento-info {
    font-size: 16px;
    color: #212529  ;
}

.reprovado {
    color: #d9534f;
}

.em-avaliacao {
    color: #f0ad4e;
}

.aprovado {
    color: #198754;
}

.novo-documento {
    margin-top: 20px;
    padding: 15px;
    background-color: #fff;
    border-radius: 8px;
}

.custom-file-upload {
    display: inline-block;
    padding: 10px 20px;
    cursor: pointer;
    background-color: #007bff;
    color: white;
    border-radius: 5px;
    font-size: 16px;
}

.btn.botao-enviar {
    margin-top: 20px;
    padding: 10px 20px;
    background-color: #2275D4 !important;
    color: white;
    font-size: 18px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    width: 100%;
    text-align: center;
}

.btn.botao-enviar:hover {
    background-color: #218838;
}
</style>
