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
                        <p><strong>Tipo de Documento:</strong> {{ documento.tipo_documento }}</p>
                        <p><strong>Status:</strong> {{ getStatusTexto(documento.status) }}</p>

                        <!-- Documento reprovado, permitir reenvio -->
                        <div v-if="documento.status === 'R'" class="reprovado">
                            <p>Documento reprovado, envie outro.</p>
                            <div class="upload-container">
                                <label class="custom-file-upload">
                                    <i class="fas fa-upload"></i> Selecione um arquivo
                                    <input type="file" @change="handleFileChange(documento.tipo_documento)" />
                                </label>
                            </div>
                        </div>

                        <!-- Documento em avaliação, não permitir novo envio -->
                        <div v-else-if="documento.status === 'E'" class="em-avaliacao">
                            <p>Documento em avaliação, aguarde...</p>
                        </div>

                        <!-- Documento aprovado, não permitir novo envio -->
                        <div v-else-if="documento.status === 'A'" class="aprovado">
                            <p>Documento aprovado!</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mostrar todos documentos pendentes se "Meus Documentos" estiver vazio -->
            <div v-else class="novo-documento">
                <p><strong>Enviar documentos pendentes</strong></p>
                <div v-for="doc in documentosPendentes" :key="doc.id" class="upload-container">
                    <label>{{ doc.nome }}</label>
                    <label class="custom-file-upload">
                        <i class="fas fa-upload"></i> Selecione um arquivo
                        <input type="file" :multiple="false" @change="(event) => handleFileChange(doc.id, event)" />
                    </label>
                </div>
            </div>

            <!-- Botão de Enviar -->
            <button type="button" class="btn botao-enviar" @click="uploadDocumentos">Enviar</button>
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

        const carregarDocumentos = async () => {
            try {
                // Buscar "Meus Documentos"
                const response = await axios.get('/user/pessoa/meus_documentos');
                documentos.value = response.data || [];

                // Buscar todos os tipos de documentos
                const responsePendentes = await axios.get('/user/pessoa/all_documentos');
                const allDocumentos = responsePendentes.data || [];

                if (documentos.value.length === 0) {
                    documentosPendentes.value = allDocumentos;
                } else {
                    documentosPendentes.value = allDocumentos.filter(
                        (doc) => !documentos.value.some((d) => d.tipo_documento === doc.tipo_documento)
                    );
                }
                loading.value = false;
            } catch (error) {
                console.error('Erro ao carregar documentos:', error);
            }
        };

        const handleFileChange = (tipoDocumentoId, event) => {
            console.log(tipoDocumentoId);
            if (event && event.target && event.target.files.length > 0) {
                const file = event.target.files[0];
                arquivosSelecionados.value.push({ tipoDocumentoId, file });
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

        const getStatusTexto = (status) => {
            switch (status) {
                case 'A':
                    return 'Aprovado';
                case 'R':
                    return 'Reprovado';
                case 'E':
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
            getStatusTexto,
            handleFileChange,
            uploadDocumentos,
        };
    },
};

</script>

<style scoped>
.documentos-container {
    padding: 20px;
    background-color: #f9f9f9;
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
    color: #5cb85c;
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
    background-color: #28a745;
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
