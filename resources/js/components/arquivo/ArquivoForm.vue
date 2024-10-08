<template>
    <div class="m-2" v-if="ready">
        <form id="frm" name="frm" data-method="post" :action="acao">
            <form-error></form-error>
            <div class="row">
                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="nome" class="form-label">Nome do Arquivo</label>
                    <input v-model="info.nome" type="text" name="nome" id="nome" class="form-control" disabled/>
                </div>
                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="tipo" class="form-label">Tipo de Arquivo</label>
                    <input v-model="info.content_type" type="text" name="content_type" id="content_type" class="form-control" disabled/>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="descricao" class="form-label" :class="{ 'required': !readOnly }">Comentário</label>
                    <input v-model="info.descricao" type="text" name="descricao" id="descricao" class="form-control" :disabled="readOnly"/>
                </div>
                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="status" class="form-label" :class="{ 'required': !readOnly }">Status</label>
                    <select v-model="info.status" name="status" id="status" class="form-select" :disabled="readOnly">
                        <option value="A">Aprovado</option>
                        <option value="P">Pendente</option>
                        <option value="R">Reprovado</option>
                    </select>
                </div>
            </div>
            <input type="hidden" name="arquivo_id" :value="arquivoId">
            <div class="row border-top pt-4">
                <div class="col-12 d-flex justify-content-center align-items-center" v-if="readOnly">
                    <button type="button" class="btn btn-danger text-white" @click="close" aria-label="Close">
                        <i class="fa fa-close"></i> Sair
                    </button>
                </div>
                <div class="col-12 text-center" v-if="!readOnly">
                    <submit-rest label="Salvar"></submit-rest>
                    &nbsp;
                    <button type="button" class="btn btn-secondary text-white" @click="close" aria-label="Close">
                        <i class="fa fa-close"></i> Cancelar
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import { inject, onMounted, ref } from 'vue';
import axios from 'axios';

export default {
    setup(props, { emit }) {
        const events = inject('events');
        const info = ref({});
        const ready = ref(false);
        const acao = ref('/admin/arquivo/create');
        const readOnly = ref(false);
        const arquivoId = ref(null);

        const loadData = async () => {
            try {
                acao.value = '/admin/arquivo/'
                const response = await axios.get(`${acao.value}${arquivoId.value}/edit`);
                info.value = response.data;
                acao.value = '/admin/arquivo/update'
            } catch (err) {
                emit('notification', {
                    type: 'error',
                    message: 'Não foi possível recuperar os dados do arquivo.'
                });
            }
            ready.value = true;
        }

        const close = () => {
            events.emit('popup-close', true);
        }

        onMounted(async () => {
            arquivoId.value = props.data;
            events.off("form-submitted");
            events.on("form-submitted", (sucesso) => {
                if (sucesso) {
                    events.emit('status-arquivo', {
                        arquivoId: arquivoId.value,
                        novoStatus: info.value.status,
                        descricao: info.value.descricao
                    });
                    emit('close', true);
                }
            });
            if (props.data) {
                await loadData();
            } else {
                ready.value = true;
            }
        })

        return {
            info, ready, acao, readOnly, close, arquivoId
        }
    },

    props: {
        data: { default: null, required: true }
    }
}
</script>

<style scoped>
</style>
