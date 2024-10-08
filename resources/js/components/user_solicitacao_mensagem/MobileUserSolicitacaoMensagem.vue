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
                        <div v-if="mensagem.arquivos">
                            <div class="arquivo-container">
                                <p><strong>Documento:</strong> {{ mensagem.arquivos.tipo_arquivo.nome }}</p>
                                <p><strong>Arquivo:</strong> {{ mensagem.arquivos.nome }}</p>
                                <div class="mt-2 mb-2">
                                    <strong>Status:</strong>
                                    <span class="ms-1 text-white rounded-3 p-2" :class="statusClass( mensagem.arquivos.status)">
                                    {{ getStatusTexto( mensagem.arquivos.status) }}</span>
                                </div>
                                <a v-if="mensagem.arquivos.hash" :href="`/storage/${mensagem.arquivos.hash}`"
                                   target="_blank">
                                    <i class="fa fa-file-download"></i> Download Documento
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
                    <textarea v-model="texto" placeholder="Digite sua mensagem"
                              class="campo-mensagem form-label"></textarea>

                </div>

                <div class="linha-documento">
                    <!-- Campo de seleção para o tipo de arquivo -->
                    <label class="botao-enviar">
                        <i class="fa fa-paperclip"></i>
                        <input type="file" :accept="acceptAttribute" name="arquivo" id="arquivo"
                               @change="atualizarNumeroArquivos">
                    </label>
                    <select v-model="tipo_arquivo_id" class="form-select" required>
                        <option selected value="0">Escolher documento</option>
                        <option v-for="documento in documentosFiltrados" :key="documento.id" :value="documento.id">
                            {{ documento.nome }}
                        </option>
                    </select>
                    <button
                        :disabled="(arquivoSelecionado && tipo_arquivo_id === 0) || (!arquivoSelecionado && texto === '')"
                        type="submit" class="btn btn-primary"><i class="fa fa-paper-plane"></i></button>
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
import {inject, ref, onMounted, computed} from "vue";

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
        const nomeArquivo = ref('');
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
            const tipoArquivo = documentos.value.find(doc => doc.id === tipo_arquivo_id.value);
            const arquivoStatus = 'P'; // Status inicial definido como "Pendente"

            console.log(tipoArquivo)
            // Criando a nova mensagem localmente
            const novaMensagem = {
                usuario_id: solicitacao.value.usuario_id, // ID do usuário logado
                texto: texto.value,
                usuario: {
                    name: 'Eu', // Nome do usuário logado (pode ser dinâmico se necessário)
                },
                arquivos: {
                    nome: arquivoSelecionado.value.name, // Nome do arquivo
                    tipo_arquivo: {
                        nome: tipoArquivo.nome, // Nome do tipo de arquivo
                    },
                    status: arquivoStatus, // Status inicial do arquivo (Pendente)
                }
            };
            // Adicionando a nova mensagem localmente
            solicitacao.value.mensagens.push(novaMensagem);

            // Resetando os valores do formulário
            texto.value = '';
            tipo_arquivo_id.value = 0;
            arquivoSelecionado.value = null;
            nomeArquivo.value = '';
            try {
                await axios.post('/user/mensagem_solicitacao/', formData);
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

        const statusClass = (status) => {
            return status === 'R'
                ? 'bg-danger'
                : status === 'P'
                    ? 'bg-warning'
                    : status === 'A'
                        ? 'bg-success'
                        : 'bg-secondary';
        };
        const documentosFiltrados = computed(() => {
            // Filtra as mensagens que contêm arquivos
            const mensagensComArquivos = solicitacao.value.mensagens.filter(mensagem => mensagem.arquivos);

            // Mapeia os IDs dos tipos de arquivos que têm status A ou P
            const tiposArquivosComStatusAP = mensagensComArquivos
                .map(mensagem => mensagem.arquivos) // Extrai o objeto de arquivos
                .filter(arquivo => ['A', 'P'].includes(arquivo.status)) // Filtra apenas os arquivos com status A ou P
                .map(arquivo => arquivo.tipo_arquivo_id); // Mapeia para obter o ID do tipo de arquivo

            // Retorna os documentos que não estão na lista de tipos de arquivos com status A ou P
            return documentos.value.filter(documento => !tiposArquivosComStatusAP.includes(documento.id));
        });

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
        const atualizarNumeroArquivos = (event) => {
            arquivos_envio.value = event.target.files.length;
            if (arquivos_envio.value > 0) {
                arquivoSelecionado.value = event.target.files[0]; // Atualiza a variável com o arquivo selecionado
                nomeArquivo.value = arquivoSelecionado.value.name; // Armazena o nome do arquivo selecionado
            } else {
                nomeArquivo.value = ''; // Limpa o nome do arquivo se nenhum arquivo for selecionado
                arquivoSelecionado.value = null; // Limpa a variável do arquivo selecionado
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
            documentosFiltrados,
            formatarData,
            enviarMensagem,
            statusClass,
            getStatusTexto,
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

