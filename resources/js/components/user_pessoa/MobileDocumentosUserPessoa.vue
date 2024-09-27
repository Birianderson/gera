<template>
    <div class="documentos-container">
        <div v-if="loading" class="loading-spinner">
            <p>Carregando...</p>
        </div>
        <div v-else>
            <!-- Mostrar documentos do usuário se existirem -->
            <div v-if="documentos.length > 0">
                <div v-for="documento in documentos" :key="documento.id" class="documento-card">
                    <div class="documento-info">
                        <div><strong>Documento:</strong> {{ documento.tipo_arquivo.nome }}</div>
                        <div class="mt-2 mb-2">
                            <strong>Status:</strong>
                            <span class="ms-1 text-white rounded-3 p-2" :class="statusClass(documento.status)">
                {{ getStatusTexto(documento.status) }}
            </span>
                        </div>
                        <div><strong>Download:</strong>
                            <a v-if="documento.hash" :href="`/storage/${documento.hash}`" target="_blank" class="ms-1">
                                <i class="fa fa-file-download"></i>
                            </a>
                        </div>

                        <!-- Permitir envio de novo documento somente se reprovado e não houver outro em avaliação -->
                        <div v-if="documento.status === 'R' && !existeDocumentoEmAvaliacao(documento.tipo_arquivo_id)" class="reprovado">
                            <p>Documento reprovado, envie outro.</p>
                            <div class="upload-container">
                                <label class="custom-file-upload">
                                    <i class="fas fa-upload"></i> Selecione um arquivo
                                    <input type="file" @change="(event) => handleFileChange(documento.tipo_arquivo_id, event)" />
                                </label>
                            </div>
                        </div>

                        <!-- Documento em avaliação, não permitir novo envio -->
                        <div v-else-if="documento.status === 'P'" class="mt-1 em-avaliacao">
                            <p>Documento em avaliação, aguarde...</p>
                        </div>

                        <!-- Documento em avaliação, não permitir novo envio -->
                        <div v-else-if="documento.status === 'R'" class="mt-1 em-avaliacao">
                            <p>Documento reprovado, envie outro para a Avaliação.</p>
                        </div>

                        <!-- Documento aprovado, não permitir novo envio -->
                        <div v-else-if="documento.status === 'A'" class="mt-1 aprovado">
                            <p>Documento aprovado!</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Mostrar todos documentos pendentes se "Meus Documentos" estiver vazio -->
            <div v-if="documentosPendentes.length > 0" class="documento-card">
                <div><strong>DOCUMENTO PENDENTE</strong></div>
                <div v-for="doc in documentosPendentes" :key="doc.id" class="upload-container">
                    <label>Nome: {{ doc.nome }}</label>
                    <label class="custom-file-upload mt-2">
                        <i class="fas fa-upload"></i> Selecione um arquivo
                        <input type="file" :multiple="false" @change="(event) => handleFileChange(doc.id, event)" />
                    </label>
                    <div v-if="nomesArquivosSelecionados[doc.id]" class="mt-2">
                        <strong>Arquivo Selecionado:</strong> {{ nomesArquivosSelecionados[doc.id] }}
                    </div>
                </div>
            </div>

            <div v-if="!documentosPendentes.length > 0" class="">
                <p>Sem documento pendente, aguarde a aprovação dos restantes...</p>
            </div>

            <!-- Botão de Enviar -->
            <button type="button" class="btn botao-enviar" :disabled="arquivosSelecionados.length === 0" @click="uploadDocumentos">Enviar</button>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';

export default {
    setup() {
        const documentos = ref([]);
        const documentosPendentes = ref([]);
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
                const allDocumentos = responsePendentes.data || [];
                console.log(allDocumentos);

                // Filtra os documentos pendentes com base no que já foi enviado pelo usuário
                documentosPendentes.value = allDocumentos.filter(doc =>
                    !documentos.value.some(d => d.tipo_arquivo_id === doc.id)
                );

                // Opcional: Se você quiser adicionar um log para verificar quais documentos pendentes estão sendo exibidos
                console.log('Documentos Pendentes:', documentosPendentes.value);

                loading.value = false;
            } catch (error) {
                console.error('Erro ao carregar documentos:', error);
            }
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

        const existeDocumentoEmAvaliacao = (tipoDocumentoId) => {
            console.log(tipoDocumentoId)
            return documentos.value.some(doc => doc.tipo_arquivo_id === tipoDocumentoId && doc.status === 'P');
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
            documentos,
            documentosPendentes,
            loading,
            arquivosSelecionados,
            existeDocumentoEmAvaliacao,
            statusClass,
            getStatusTexto,
            handleFileChange,
            nomesArquivosSelecionados,
            uploadDocumentos,
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

.upload-container {
    margin-top: 10px;
}

input[type="file"] {
    display: none;
}

.custom-file-upload {
    display: inline-block;
    padding: 10px 20px;
    cursor: pointer;
    background-color: #007bff;
    color: white;
    border-radius: 5px;
    font-size: 16px;
    text-align: center;
}

.custom-file-upload i {
    margin-right: 8px;
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
