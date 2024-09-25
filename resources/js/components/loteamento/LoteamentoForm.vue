<template>
    <div class="m-2" v-if="ready">
        <form id="frm" name="frm" data-method="post" :action="acao">
            <form-error></form-error>
            <div class="card mb-4">
                    <div class="row">
                        <div class="col-lg-4 mb-3">
                            <label for="cidade" class="form-label">Cidade</label>
                            <input v-model="cidade.nome" type="text" name="cidade" id="cidade" class="form-control" disabled />
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label for="nome" class="form-label">Loteamento</label>
                            <input v-model="info.nome" type="text" name="nome" id="nome" class="form-control" :disabled="readOnly" />
                        </div>
                        <input type="hidden" name="cidade_id" :value="props.data">
                        <div class="col-lg-4 mb-3">
                            <label for="confinante_lado_esquerdo" class="form-label">Sigla</label>
                            <input v-model="info.sigla" type="text" name="sigla" id="sigla" class="form-control" :disabled="readOnly" />
                        </div>
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
                    <button type="button" class="btn btn-danger text-white" @click="close" aria-label="Close">
                        <i class="fa fa-close"></i> Cancelar
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import { inject, onMounted, ref } from 'vue';

export default {
    setup(props, { emit }) {
        const events = inject('events');
        const info = ref({});
        const cidade = ref({});
        const municipios = ref([]);
        const ready = ref(false);
        const acao = ref('/admin/loteamento/create');
        const readOnly = ref(false);

        const loadData = async () => {
            try {
                acao.value = '/admin/loteamento/'
                const response = await axios.get(`${acao.value}${props.data.id}`);
                acao.value += props.data.id;

                info.value = response.data;
                readOnly.value = Boolean(props.data.readOnly);
            } catch (err) {
                emit('notification', {
                    type: 'error',
                    message: 'Não foi possível recuperar os dados do imóvel.'
                });
            }
            ready.value = true;
        }

        const loadCidade = async () => {
            try {
                cidade.value = await axios.get(`/admin/loteamento/findCidade/${props.data}`);
                cidade.value = cidade.value.data;
            } catch (err) {
                emit('notification', {
                    type: 'error',
                    message: 'Não foi possível recuperar os dados do imóvel.'
                });
            }
            ready.value = true;
        }
        const close = () => {
            events.emit('popup-close', true);
        }

        onMounted(async () => {
            events.off("form-submitted");
            events.on("form-submitted", (sucesso) => {
                if (sucesso) {
                    events.emit('table-reload', true);
                    events.emit('notification', {
                        type: 'success',
                        message: 'Pessoa salva com Sucesso!'
                    });
                    emit('close', true);
                }
            });
            console.log(props.data)
            //await loadData();
            await loadCidade();
        });

        return {
            cidade,
            ready,
            municipios,
            info,
            acao,
            readOnly,
            close,
            props
        }
    },
    props: {
        data: {default: null, required: true},
    }
}
</script>
