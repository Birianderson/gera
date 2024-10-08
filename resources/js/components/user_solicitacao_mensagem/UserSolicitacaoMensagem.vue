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
                        'btn-primary': status.value === solicitacao.situacao,
                    }]">
                    {{ status.label }}
                </button>
            </div>
        </div>
        <div class="chat-container card">
            <div v-for="mensagem in solicitacao.mensagens" :key="mensagem.id">
                <div class="mensagem-esquerda" v-if="parseInt(props.user_id) !== parseInt(mensagem.usuario_id)">
                    <div class="conteudo-mensagem  shadow-sm">
                        <small>{{mensagem.usuario.name }}</small>
                        <p class="text-black">{{mensagem.texto }}</p>
                    </div>
                </div>
                <div class="mensagem-direita" v-else>
                    <div class="conteudo-mensagem shadow-sm">
                        <small>{{mensagem.usuario.name }}</small>
                        <p class="text-black" v-html="mensagem.texto"></p>
                        <div v-if="mensagem.arquivos.length > 0">
                            <div v-for="arquivo in mensagem.arquivos" :key="arquivo.id" class="arquivo-container pt-2 ">
                                <div class="file-info">
                                    <table class="table table-borderless">
                                        <tbody>
                                        <tr>
                                            <td><strong class="me-1">Arquivo:</strong> {{ arquivo.nome_arquivo }}
                                                <a v-if="arquivo.hash" :href="`/storage/${arquivo.hash}`"
                                                   target="_blank" class="ms-1">
                                                    <i class="fa fa-file-download"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <form id="frm" name="frm" method="post" action="/admin/mensagem_solicitacao/">
                <form-error></form-error>
                <div v-if="arquivos_envio > 0" class="mt-2 border-top p-2  ">
                    <div v-for="(arquivo, index) in arquivosDetalhes" :key="index"
                         class="arquivo-detalhes row mb-2 align-items-center d-flex mt-2">
                        <div class="col-3  align-items-center">
                            <p>{{ arquivo.file.name }}</p>
                        </div>
                        <div class="col-3">
                            <input :id="'nome_arquivo' - index" name="nome_arquivo[]" type="text" class="form-control"
                                   v-model="arquivo.titulo" placeholder="Título do arquivo">
                        </div>
                        <div class="col-5">
                            <input :id="'descricao_arquivo' - index" name="descricao_arquivo[]" type="text"
                                   class="form-control" v-model="arquivo.descricao" placeholder="Descrição do arquivo">
                        </div>
                        <div class="col-1 text">
                            <a href="#">
                                <i class="fa fa-trash" style="color: red" @click.prevent="removeArquivo"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="barra-escrita" v-if="!props.readOnly">
                    <div class="row">
                        <div class="col-11">
                            <textarea class="form-control" v-model="texto" style="border-radius: 5px;"
                                      placeholder="Digite sua mensagem" name="texto" id="texto"/>
                        </div>
                        <input type="hidden" name="solicitacao_id" :value="props.data">
                        <input type="hidden" name="novo_status" value="2">
                        <div class="col-1">
                            <div class="row">
                                <button type="button" class="btn botao-enviar mb-1"
                                        :disabled="solicitacao.situacao === '3' || solicitacao.situacao === '4'"
                                        @click="enviarMensagem">Enviar
                                </button>
                                <span class="btn botao-enviar"
                                      v-if="solicitacao.situacao !== '3' && solicitacao.situacao !== '4'">
                                    <label class="custom-file-upload"
                                           :class="{ 'disabled-label': solicitacao.situacao === '3' }">
                                        <i class="fa fa-paperclip"></i>
                                        <input :accept="acceptAttribute" multiple id="upload" ref="file" type="file"
                                               @change="atualizarNumeroArquivos"
                                               :disabled="solicitacao.situacao === '3'">
                                    </label>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>


<script>
import {inject, ref, onMounted, readonly} from "vue";
import moment from "moment";

export default {
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
            {value: '3', label: 'Concluído'},
            {value: '1', label: 'Pendente'},
            {value: '2', label: 'Em andamento'},
            {value: '4', label: 'Cancelado'},
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
                const response = await axios.get(`/user/mensagem_solicitacao/${props.data}`);
                solicitacao.value = response.data;
                loading.value = false;
            } catch (error) {
                console.error('Erro ao carregar mensagens:', error);
            }
        }
        const enviarMensagem = async () => {
            let formData = new FormData();
            formData.append('texto', texto.value);
            formData.append('solicitacao_id', props.data);
            formData.append('novo_status', 2);
            for (let i = 0; i < arquivosDetalhes.value.length; i++) {
                let arquivoDetalhes = arquivosDetalhes.value[i];
                formData.append('arquivo[]', arquivoDetalhes.file);
                formData.append('titulo[]', arquivoDetalhes.titulo);
                formData.append('descricao[]', arquivoDetalhes.descricao);
            }
            try {
                const response = await axios.post('/admin/mensagem_solicitacao/', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                });
                const novaMensagem = {
                    id: 0,
                    texto: texto.value,
                    usuario: {
                        nome_usuario: 'eu'
                    },
                    arquivos: arquivosDetalhes.value.map(arquivo => ({
                        id: arquivo.id,
                        nome_arquivo: arquivo.file.name,
                    }))
                };
                solicitacao.value.atendimentos.push(novaMensagem);
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

        const formatarDataHora = (data) => {
            return moment(data).format('DD/MM/YYYY HH:mm');
        }

        onMounted(async () => {
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
        user_id: {default: null},
        data: {default: null},
        readOnly: {default: false}
    }

};
</script>

<style scoped>
.custom-file-upload {
    display: inline-block;
    cursor: pointer;
    width: 100%;
    height: 100%;
    text-align: center;
}

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
    display: flex;
    align-items: center;
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


.custom-file-upload {
    display: inline-block;
    cursor: pointer;
}

.botao-enviar {
    background-color: #2275D4;
    border-radius: 5px;
    color: white;
    border-color: #079B8A;
}

.barra-escrita {
    margin-top: 15px;
    backdrop-filter: blur(5px);
    position: sticky;
    z-index: 1020;
    padding-right: 12px;
}

textarea.form-control {
    min-height: calc(1.5em + 0.75rem + 40px) !important;
}


.file-info {
    flex: 1;
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
</style>
