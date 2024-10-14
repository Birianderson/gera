<template>
    <div class="m-2" v-if="ready">
        <form id="frm" name="frm" data-method="post" :action="acao">
            <form-error></form-error>
            <div class="row">
                <div class="col-lg-12 col-md-6 mb-3">
                    <label for="nome" class="form-label" :class="{ 'required': !readOnly }">Nome</label>
                    <input v-model="info.nome" type="text" name="nome" id="nome" class="form-control" :disabled="readOnly"/>
                </div>
            </div>
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
        const acao = ref('/admin/helpdesk_categoria/create');
        const readOnly = ref(false);

        const loadData = async () => {
            try {
                acao.value = '/admin/helpdesk_categoria/'
                const response = await axios.get(`${acao.value}${props.data.id}/edit`);
                acao.value= '/admin/helpdesk_categoria/'
                acao.value += props.data.id;
                acao.value += '/update';
                info.value = response.data;
                console.log(info.value)
            } catch (err) {
                emit('notification', {
                    type: 'error',
                    message: 'Não foi possível recuperar os dados Arquivos.'
                });
            }
            ready.value = true;
        }

        const close = () => {
            events.emit('popup-close', true);
        }

        onMounted(async () => {
            console.log(props);
            events.off("form-submitted");
            events.on("form-submitted", (sucesso) => {
                if (sucesso) {
                    events.emit('table-reload', true);
                    events.emit('notification', {
                        type: 'success',
                        message: 'Tipo de arquivo salvo com Sucesso!'
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
            info, ready, acao, readOnly, close
        }
    },

    props: {
        data: {default: null, required: true}
    }
}
</script>

<style scoped>
</style>
