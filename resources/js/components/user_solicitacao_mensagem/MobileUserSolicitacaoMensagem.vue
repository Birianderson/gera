<template>
    <div v-if="!loading" class="mobile-container">
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
            <form @submit.prevent="enviarMensagem" class="form-envio">
                <textarea v-model="texto" placeholder="Digite sua mensagem" class="campo-mensagem"></textarea>
                <div class="botoes-envio">
                    <label class="upload-label">
                        <i class="fa fa-paperclip"></i>
                        <input type="file" :accept="acceptAttribute" multiple @change="atualizarNumeroArquivos">
                    </label>
                    <button type="submit" class="botao-enviar">Enviar</button>
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
        const arquivosDetalhes = ref([]);

        const carregarMensagens = async () => {
            try {
                const response = await axios.get(`/user/mensagem_solicitacao/${props.data}`);
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

            try {
                await axios.post('/admin/mensagem_solicitacao/', formData);
                texto.value = '';
                events.emit('notification', {type: 'success', message: 'Mensagem enviada com sucesso.'});
            } catch (error) {
                console.error('Erro ao enviar a mensagem:', error);
                events.emit('notification', {type: 'error', message: 'Erro ao enviar a mensagem.'});
            }
        };

        const formatarData = (dataISO) => {
            const data = new Date(dataISO);
            const dia = String(data.getDate()).padStart(2, '0');
            const mes = String(data.getMonth() + 1).padStart(2, '0'); // Janeiro é 0
            const ano = data.getFullYear();
            return `${dia}/${mes}/${ano}`;
        };

        const atualizarNumeroArquivos = () => {
            arquivos_envio.value = event.target.files.length;
        };

        onMounted(async () => {
            console.log(props)
            await carregarMensagens()
        });

        return {
            solicitacao,
            texto,
            arquivos_envio,
            acceptAttribute,
            loading,
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
.mobile-container {
    padding: 10px;
    background-color: #fff;
    max-width: 100%;
    box-sizing: border-box;
}

.cabecalho {
    padding: 10px;
    background-color: #f8f9fa;
    border-bottom: 1px solid #ccc;
    margin-bottom: 15px;
    border-radius: 5px;
    box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.1);
}

.titulo {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 10px;
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

form.form-envio {
    display: flex;
    flex-direction: column;
    gap: 10px;
    padding-top: 10px;
    border-top: 1px solid #ddd;
}

textarea.campo-mensagem {
    width: 100%;
    height: 60px;
    border-radius: 8px;
    padding: 10px;
    font-size: 14px;
    resize: none;
}

.botoes-envio {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.upload-label {
    display: inline-block;
    cursor: pointer;
    color: #2275d4;
    font-size: 18px;
}

.botao-enviar {
    background-color: #2275d4;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
}

input[type="file"] {
    display: none;
}
</style>

