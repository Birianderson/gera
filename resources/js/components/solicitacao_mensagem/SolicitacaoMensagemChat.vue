<template>
    <div v-if="!loading">
        <confirmation-popup></confirmation-popup>
        <div class="cabecalho mt-1">
            <h4>Atendimento de Solicitações</h4>
            <div class="w-100 px-5">
                <div class="row">
                    <div class="col-12 d-flex border-bottom border-top">
                        <div class="flex-fill py-1">
                            <strong>Localização:</strong>
                            {{ solicitacao.imovel.loteamento.cidade.nome }} -> {{ solicitacao.imovel.loteamento.nome }}
                            -> Quadra {{ solicitacao.imovel.quadra }} -> Lote {{ solicitacao.imovel.lote }}
                        </div>
                        <div class="flex-fill text-end py-1">
                            <strong>Data:</strong> {{ formatarDataHora(solicitacao.data) }}
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12 d-flex justify-content-between border-bottom">
                        <div class="flex-fill py-1">
                            <strong>Solicitante:</strong> {{ solicitacao.usuario.name }}
                        </div>
                        <div class="flex-fill py-1">
                            <strong>CPF:</strong> {{ solicitacao.usuario.cpf }}
                        </div>
                        <div class="flex-fill text-end py-1" v-if="!props.readOnly">
                            <strong>E-mail:</strong> {{ solicitacao.usuario.email }}
                        </div>
                    </div>
                </div>
            </div>
            <strong class="ms-2" style="font-size: 15px;">Situação:</strong>
            <div class="situacao-container"
                 style="display: inline-flex;  align-items: center; gap: 5px; margin-left: 10px;">
                <button v-for="status in statusList" :key="status.value" @click="mudarSituacao(status.value)"
                        :disabled="props.readOnly" :class="['btn btn-secondary', {
                        'btn-primary': status.value === solicitacao.status,
                    }]">
                    {{ status.label }}
                </button>
            </div>
        </div>
        <div class="chat-container card">
            <div v-for="mensagem in solicitacao.mensagens" :key="mensagem.id">
                <div class="mensagem-esquerda" v-if="parseInt(props.user_id) !== parseInt(mensagem.usuario_id)">
                    <div class="conteudo-mensagem">
                        <small>{{ mensagem.usuario.name }}</small>
                        <p v-html="mensagem.texto"></p>
                        <div v-if="mensagem.arquivos">
                            <div class="arquivo-container">
                                <p><strong>Documento:</strong> {{ mensagem.arquivos.tipo_arquivo.nome }}</p>
                                <p><strong>Arquivo:</strong> {{ mensagem.arquivos.nome }}</p>

                                <div class="mt-2 mb-2">
                                    <strong>Status:</strong>
                                    <span class="ms-1 text-white rounded-3 p-2 me-2"
                                          :class="statusClass( mensagem.arquivos.status)">
                                    {{ getStatusTexto(mensagem.arquivos.status) }}</span>
                                    <popup-button id="nova-pessoa" title="Nova Pessoa" :data="mensagem.arquivos.id" type="secondary"
                                                  component="arquivo-form" action="/pessoa/" size="xl">
                                        <i class="fa fa-pencil"></i>
                                    </popup-button>
                                </div>
                                <p v-if="mensagem.arquivos.descricao"><strong>Observação:</strong> {{ mensagem.arquivos.descricao }}</p>
                                <a v-if="mensagem.arquivos.hash" :href="`/storage/${mensagem.arquivos.hash}`"
                                   target="_blank">
                                    <i class="fa fa-file-download"></i> Download
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mensagem-direita" v-else>
                    <div class="conteudo-mensagem">
                        <small>{{ mensagem.usuario.name }}</small>
                        <p v-html="mensagem.texto"></p>
                    </div>
                </div>
            </div>
            <!-- Campo de envio de mensagem -->
            <hr>
            <form @submit.prevent="enviarMensagem" class="form-envio linha-documento">
                <textarea v-model="texto" placeholder="Digite sua mensagem" class="campo-mensagem form-label"></textarea>

                <div class="botoes">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-paper-plane"></i>Enviar
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>


<script>
import {inject, ref, onMounted, readonly} from "vue";
import moment from "moment";
import Popup from "../common/Popup.vue";

export default {
    components: {Popup},
    setup(props, {emit}) {
        const events = inject('events');
        let usuarioLogadoId = ref(null);
        let solicitacaoId = ref(null);
        const texto = ref('');
        const solicitacao = ref(null);
        const arquivosPermitidos = ['pdf', 'doc', 'docx', 'odt', 'xls', 'xlsx', 'zip', 'rar', 'png', 'jpg'];
        const acceptAttribute = ref('');
        const arquivos_envio = ref(0);
        const file = ref(null);
        let loading = ref(true);
        let statusList = ref([
            {value: 'A', label: 'Aprovado'},
            {value: 'P', label: 'Pendente'},
            {value: 'E', label: 'Em andamento'},
            {value: 'R', label: 'Cancelado'},
        ]);
        const arquivosDetalhes = ref([]);

        const mudarSituacao = async (novoStatus) => {
            const statusAtual = solicitacao.value.situacao;
            if (statusAtual === '4') {
                events.emit('notification', {
                    type: 'warning',
                    message: 'Solicitação cancelada. Não é possível mudar o status.'
                });
                return;
            }

            if (statusAtual === '1' && novoStatus === '3') {
                events.emit('notification', {
                    type: 'warning',
                    message: 'A solicitação pendente não pode ser concluída sem ser atendida.'
                });
                return;
            }

            if (statusAtual === '1' && novoStatus === '4') {
                events.emit('confirmation', {
                    title: 'Confirmação de mudança de status',
                    message: `Tem certeza de que deseja alterar o status de "${statusList.value.find(s => s.value === statusAtual).label}" para "${statusList.value.find(s => s.value === novoStatus).label}"? <strong>A ALTERAÇÃO NÃO PODERÁ SER REVERTIDA.</strong>`,
                    event: () => atualizaSituacoo(novoStatus),
                });
            } else if (statusAtual === '2' && novoStatus === '3') {
                events.emit('confirmation', {
                    title: 'Confirmação de mudança de status',
                    message: `Tem certeza de que deseja alterar o status de "${statusList.value.find(s => s.value === statusAtual).label}" para "${statusList.value.find(s => s.value === novoStatus).label}"? <strong>A ALTERAÇÃO NÃO PODERÁ SER REVERTIDA.</strong>`,
                    event: () => atualizaSituacoo(novoStatus),
                });
            } else {
                events.emit('notification', {
                    type: 'warning',
                    message: 'Mudança de status não permitida.'
                });
            }
        };

        const atualizaSituacoo = async (novaSituacao) => {
            try {
                const response = await axios.post('/admin/mensagem_solicitacao/mudar_situacao', {
                    solicitacao_id: props.data,
                    novo_status: novaSituacao
                });
                solicitacao.value.situacao = novaSituacao;
                events.emit('notification', {
                    type: 'success',
                    message: 'Status da solicitação atualizado com sucesso.'
                });
            } catch (error) {
                console.error('Erro ao mudar o status:', error);
                events.emit('notification', {
                    type: 'error',
                    message: 'Erro ao mudar o status da solicitação.'
                });
            }
        }


        const atualizarNumeroArquivos = () => {
            arquivos_envio.value = file.value.files.length;
            arquivosDetalhes.value = [];
            for (let i = 0; i < file.value.files.length; i++) {
                arquivosDetalhes.value.push({
                    file: file.value.files[i],
                    titulo: '',
                    descricao: ''
                });
            }
        };
        const carregarMensagens = async () => {
            try {
                let source = `/admin/mensagem_solicitacao/${solicitacaoId.value}`
                const response = await axios.get(source);
                solicitacao.value = response.data;
                console.log(solicitacao.value);
                setTimeout(() => {
                    loading.value = false;
                }, 50);
            } catch (error) {
                console.error('Erro ao carregar mensagens:', error);
            }
        }
        const enviarMensagem = async () => {
            let formData = new FormData();
            formData.append('texto', texto.value);
            formData.append('solicitacao_id', solicitacaoId.value);
            formData.append('novo_status', 2);
            for (let i = 0; i < arquivosDetalhes.value.length; i++) {
                let arquivoDetalhes = arquivosDetalhes.value[i];
                formData.append('arquivo[]', arquivoDetalhes.file);
                formData.append('titulo[]', arquivoDetalhes.titulo);
                formData.append('descricao[]', arquivoDetalhes.descricao);
            }
            try {
                const response = await axios.post('/admin/mensagem_solicitacao/create', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                });
                const novaMensagem = {
                    usuario_id: props.user_id, // ID do usuário logado
                    texto: texto.value,
                    usuario: {
                        name: 'Eu', // Nome do usuário logado (pode ser dinâmico se necessário)
                    },
                };
                // Adicion
                solicitacao.value.mensagens.push(novaMensagem);
                texto.value = '';
                arquivosDetalhes.value = [];
                arquivos_envio.value = 0;
                solicitacao.value.situacao = '2';
                events.emit('notification', {
                    type: 'success',
                    message: 'Mensagem enviada com sucesso.'
                });
            } catch (erro) {
                console.error('Erro ao enviar o formulário:', erro);
                events.emit('notification', {
                    type: 'error',
                    message: 'Não foi possível salvar o formulário, mensagem OBRIGATÓRIA.'
                });
            }
        }

        const formatarNome = (nome) => {
            if (!nome) return '';
            return nome
                .toLowerCase()
                .split(' ')
                .map(palavra => palavra.charAt(0).toUpperCase() + palavra.slice(1))
                .join(' ');
        }

        const removeArquivo = (index) => {
            arquivosDetalhes.value.splice(index, 1);
            arquivos_envio.value = arquivosDetalhes.value.length;
            if (arquivos_envio.value === 0) {
                file.value.value = '';
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
                    return 'Pendente';
                default:
                    return 'Desconhecido';
            }
        };

        const formatarDataHora = (data) => {
            return moment(data).format('DD/MM/YYYY HH:mm');
        }

        onMounted(async () => {
            console.log(props.user_id)
            events.on("status-arquivo", (dados) => {
                const { arquivoId, novoStatus, descricao } = dados;
                solicitacao.value.mensagens.forEach((mensagem) => {
                    if (mensagem.arquivos && mensagem.arquivos.id === arquivoId) {
                        console.log(descricao)
                        mensagem.arquivos.status = novoStatus;
                        mensagem.arquivos.descricao = descricao;
                    }
                });
                events.emit('notification', {
                    type: 'success',
                    message: 'Status do arquivo atualizado com sucesso.'
                });
            });
            solicitacaoId.value = props.data.solicitacaoId;
            await carregarMensagens();
            acceptAttribute.value = arquivosPermitidos.join(",.");
        });

        return {
            texto,
            usuarioLogadoId,
            arquivosPermitidos,
            acceptAttribute,
            arquivos_envio,
            file,
            solicitacaoId,
            solicitacao,
            loading,
            props,
            arquivosDetalhes,
            statusList,
            statusClass,
            getStatusTexto,
            mudarSituacao,
            enviarMensagem,
            formatarDataHora,
            carregarMensagens,
            onMounted,
            atualizarNumeroArquivos,
            formatarNome,
            removeArquivo
        }
    },

    props: {
        data: {default: null},
        user_id: {default: false}
    }

};
</script>

<style scoped>

.chat-container {
    padding: 10px 150px;
    width: 100%;
    max-width: 100%;
}

.conteudo-mensagem {
    max-width: 65rem;
    min-width: 20rem;
    border-radius: 10px;
    padding: 10px;
}


.mensagem-direita {
    display: flex;
    flex-direction: row-reverse;
    margin-bottom: 15px;
}

.mensagem-direita .conteudo-mensagem {
    background-color: #dff6f0;
    padding: 15px;
    max-width: 50rem;
    min-width: 20rem;
    display: flex;
    flex-direction: column;
    text-align: start;
}

.arquivo-container {

    background-color: transparent;
    margin-top: 10px;
    padding: 0;
}


.mensagem-esquerda .conteudo-mensagem {
    text-align: left;
    max-width: 50rem;
    background-color: #f0f0f0;
}

.mensagem-esquerda {
    border-radius: 10px;
    padding: 10px;
    display: flex;
    flex-direction: row;
}

input[type="file"] {
    display: none;
}


.table th,
.table td {
    padding: 2px !important;
}


.cabecalho {
    background-color: #f8f9fa;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
    box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.1);
}


.campo-mensagem {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc; /* Borda mais fina */
    font-weight: 300 !important; /* Remove o negrito com prioridade */
    font-size: 14px; /* Ajusta o tamanho da fonte */
    border-radius: 5px; /* Cantos arredondados (opcional) */
    outline: none;
    resize: none; /* Impede o redimensionamento, se necessário */
}

.campo-mensagem:focus {
    border-color: #888; /* Cor de borda ao focar */
}

.form-envio {
    display: flex;
    align-items: center;
    width: 100%;
}

.campo-mensagem {
    flex-grow: 1;
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
    margin-right: 10px; /* Espaço entre o campo de texto e os botões */
}

.botoes {
    display: flex;
    gap: 10px; /* Espaço entre o botão de anexo e o de envio */
    align-items: center;
}
</style>
