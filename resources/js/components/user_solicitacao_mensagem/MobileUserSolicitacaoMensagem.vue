<template>
    <div v-if="!loading" class="">
        <div class="cabecalho">
            <div class="info-container">
                <div class="mt-1 mb-1">
                    <i class="fa fa-city"></i>
                    Cidade: {{ solicitacao.imovel.loteamento.cidade.nome }}
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fa fa-map-marker-alt"></i>
                        Loteamento: {{ solicitacao.imovel.loteamento.nome }}
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-2">
                    <div>
                        <i class="fa fa-border-all"></i>
                        Quadra: {{ solicitacao.imovel.quadra }}
                    </div>
                    <div>
                        <i class="fa fa-location-crosshairs"></i>
                        Lote: {{ solicitacao.imovel.lote }}
                    </div>
                </div>
                <div class="info-linha mt-2">
                    <strong>Solicitante:</strong> {{ solicitacao.usuario.name }}
                </div>
                <div class="info-linha">
                    <strong>Data de Início:</strong> {{ formatarData(solicitacao.created_at) }}
                </div>
            </div>
        </div>

        <div class="chat-container">
            <div v-for="mensagem in solicitacao.mensagens" :key="mensagem.id">
                <div class="mensagem-esquerda" v-if="parseInt(props.user_id) !== parseInt(mensagem.usuario_id)">
                    <div class="conteudo-mensagem">
                        <small>{{ mensagem.usuario.name }}</small>
                        <p>{{ mensagem.texto }}</p>
                    </div>
                </div>
                <div class="mensagem-direita" v-else>
                    <div class="conteudo-mensagem">
                        <small>{{ mensagem.usuario.name }}</small>
                        <p v-html="mensagem.texto"></p>
                        <div v-if="mensagem.arquivos.length > 0">
                            <div v-for="arquivo in mensagem.arquivos" :key="arquivo.id" class="arquivo-container">
                                <p><strong>Arquivo:</strong> {{ arquivo.nome_arquivo }}</p>
                                <a v-if="arquivo.hash" :href="`/storage/${arquivo.hash}`" target="_blank">
                                    <i class="fa fa-file-download"></i> Baixar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Campo de envio de mensagem -->
            <hr>
            <form @submit.prevent="enviarMensagem" class="form-envio">
                <div class="linha-mensagem">
                    <textarea v-model="texto" placeholder="Digite sua mensagem" class="campo-mensagem form-label"></textarea>

                </div>

                <div class="linha-documento">
                    <!-- Campo de seleção para o tipo de arquivo -->
                    <label class="botao-enviar">
                        <i class="fa fa-paperclip"></i>
                        <input type="file" :accept="acceptAttribute" @change="atualizarNumeroArquivos">
                    </label>
                    <select v-model="tipo_arquivo_id" class="form-select" required>
                        <option selected value="0">Escolher documento</option>
                        <option v-for="documento in documentos" :key="documento.id" :value="documento.id">
                            {{ documento.nome }}
                        </option>
                    </select>
                    <button :disabled="(arquivoSelecionado && tipo_arquivo_id === 0) || (!arquivoSelecionado && texto === '')"  type="submit" class="botao-enviar"><i class="fa fa-paper-plane"></i></button>
                    <!-- Nome do arquivo selecionado -->

                </div>
                <div v-if="nomeArquivo" class="arquivo-selecionado">
                    <p>Documento: {{ nomeArquivo }}</p>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import {inject, ref, onMounted} from "vue";

export default {
    setup(props, {emit}) {
        const events = inject('events');
        const solicitacao = ref(null);
        const texto = ref('');
        const loading = ref(true);
        const arquivos_envio = ref(0);
        const acceptAttribute = ref(['pdf', 'doc', 'docx', 'xls', 'xlsx', 'png', 'jpg'].join(",."));
        const tipo_arquivo_id = ref(0);
        const arquivoSelecionado = ref(null);
        const documentos = ref([]); // Armazena a lista de documentos
        const nomeArquivo = ref(null);
        const carregarMensagens = async () => {
            try {
                const response = await axios.get(`/user/mensagem_solicitacao/chat/${props.data}`);
                solicitacao.value = response.data;
                loading.value = false;
            } catch (error) {
                console.error('Erro ao carregar mensagens:', error);
            }
        };

        const enviarMensagem = async () => {
            let formData = new FormData();
            formData.append('texto', texto.value);
            formData.append('solicitacao_id', props.data);
            formData.append('novo_status', 2);
            formData.append('tipo_arquivo_id', tipo_arquivo_id.value);

            if (arquivoSelecionado.value) {
                formData.append("arquivo", arquivoSelecionado.value);
            }
            try {
                await axios.post('/admin/mensagem_solicitacao/', formData);
                texto.value = '';
                events.emit('notification', {type: 'success', message: 'Mensagem enviada com sucesso.'});
            } catch (error) {
                console.error('Erro ao enviar a mensagem:', error);
                events.emit('notification', {type: 'error', message: 'Erro ao enviar a mensagem.'});
            }
        };

        const carregarDocumentos = async () => {
            try {
                const response = await axios.get('/user/mensagem_solicitacao/all_documentos');
                documentos.value = response.data;
            } catch (error) {
                console.error('Erro ao carregar documentos:', error);
            }
        };

        const formatarData = (dataISO) => {
            const data = new Date(dataISO);
            const dia = String(data.getDate()).padStart(2, '0');
            const mes = String(data.getMonth() + 1).padStart(2, '0'); // Janeiro é 0
            const ano = data.getFullYear();
            return `${dia}/${mes}/${ano}`;
        };

        const atualizarNumeroArquivos = (event) => {
            arquivos_envio.value = event.target.files.length;
            if (arquivos_envio.value > 0) {
                nomeArquivo.value = event.target.files[0].name; // Armazena o nome do arquivo selecionado
            } else {
                nomeArquivo.value = ''; // Limpa o nome do arquivo se nenhum arquivo for selecionado
            }
        };

        onMounted(async () => {
            await carregarDocumentos();
            await carregarMensagens()
        });

        return {
            solicitacao,
            texto,
            nomeArquivo,
            arquivoSelecionado,
            tipo_arquivo_id,
            arquivos_envio,
            acceptAttribute,
            loading,
            documentos,
            formatarData,
            enviarMensagem,
            props,
            atualizarNumeroArquivos,
        };
    },

    props: {
        user_id: {default: null},
        data: {default: null},
        readOnly: {default: false},
    },
};
</script>

<style scoped>
.cabecalho {
    padding: 10px;
    background-color: #f8f9fa;
    border-bottom: 1px solid #ccc;
    margin-bottom: 15px;
    border-radius: 5px;
    box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.1);
}


.info-container {
    font-size: 14px;
}

.info-linha {
    margin-bottom: 8px;
}

.chat-container {
    padding: 10px;
    display: flex;
    flex-direction: column;
}

.mensagem-esquerda {
    margin-right: auto;
    max-width: 85%;
    margin-bottom: 10px;
    display: flex;
    flex-direction: row;
    align-items: flex-start;
}

.mensagem-direita {
    margin-left: auto;
    max-width: 85%;
    margin-bottom: 10px;
    display: flex;
    flex-direction: row-reverse;
    align-items: flex-start;
}

.conteudo-mensagem {
    padding: 10px;
    border-radius: 10px;
    word-wrap: break-word;
    max-width: 100%;
    font-weight: normal;
    background-color: #e9e9e9;
}

.mensagem-direita .conteudo-mensagem {
    background-color: #dff6f0;
}

.arquivo-container {
    margin-top: 8px;
    display: flex;
    flex-direction: column;
}

.form-envio {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.linha-mensagem {
    display: flex;
    align-items: center;
    gap: 10px;
}

.campo-mensagem {
    flex-grow: 1;
    padding: 10px;
    border-radius: 5px;
    font-weight: normal !important;
    border: 1px solid #ccc;
}

.botao-enviar {
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.linha-documento {
    display: flex;
    align-items: center;
    gap: 10px;
}

.form-select {
    flex-grow: 1;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

.upload-label {
    display: flex;
    align-items: center;
    padding: 10px;
    background-color: #007bff;
    border-radius: 5px;
    cursor: pointer;
}

.upload-label input[type="file"] {
    display: none;
}

input[type="file"] {
    display: none;
}
</style>

