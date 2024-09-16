<template>
    <div class="m-2" v-if="ready">
        <form id="frm" name="frm" data-method="post" :action="acao">
            <form-error></form-error>
            <div class="row mb-4 d-flex align-items-end">
                <input type="hidden" name="loteamento_id" :value="props.data.loteamento_id" >
                <input type="hidden" name="coordenada_id" :value="props.data.coordenada_id" >
                <div class="col-lg-3 mb-3">
                    <label for="quadra" class="form-label required">Quadra</label>
                    <input
                        v-model="quadra"
                        required
                        type="text"
                        name="quadra"
                        id="quadra"
                        class="form-control"
                        placeholder="Digite a Quadra"
                    />
                </div>
                <div class="col-lg-3  mb-3">
                    <label for="lote" class="form-label required">Lote</label>
                    <input
                        v-model="lote"
                        required
                        type="text"
                        name="lote"
                        id="lote"
                        class="form-control"
                        placeholder="Digite o Lote"
                    />
                </div>
                <div class="col-lg-4  mb-3">
                    <div class="d-flex align-items-center me-2">
                        <button
                            type="button"
                            :disabled="isSearching || !isFormValid"
                            :class="{'btn btn-primary': !isSearching, 'btn btn-success': isFound}"
                            @click="findImovel"
                            class="btn d-flex align-items-center"
                        >
                            <i class="fa" :class="{'fa-search': !isSearching, 'fa-check me-2': isFound}"></i>
                            {{ isSearching ? 'Buscando...' : isFound ? 'Encontrado' : 'Buscar' }}
                        </button>
                        <button
                            v-if="isFound || isSearching"
                            type="button"
                            class="btn btn-secondary ms-1 d-inline-block d-flex align-items-center"
                            @click="resetForm"
                        >
                            <i class="fa fa-trash me-2"></i> Limpar
                        </button>
                    </div>
                </div>

            </div>
            <div class="row border-top pt-4">
                <div class="col-12 text-center">
                    <submit-rest label="Salvar" :disabled="!isFound || hasCoordinates"></submit-rest>
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
import { inject, onMounted, ref, computed } from 'vue';

export default {
    setup(props, { emit }) {
        const events = inject('events');
        const quadra = ref('');
        const lote = ref('');
        const ready = ref(true);
        const acao = ref('/imovel/vincula');
        const isSearching = ref(false);
        const isFound = ref(false);
        const hasCoordinates = ref(false); // Nova variável para controlar se já existem coordenadas

        // Computed para verificar se os campos de quadra e lote estão preenchidos
        const isFormValid = computed(() => {
            return quadra.value && lote.value;
        });

        const findImovel = async () => {
            if (!isFormValid.value) return;

            try {
                isSearching.value = true;
                const response = await axios.get(`/imovel/findByQuadraLote/${props.data.loteamento_id}/${quadra.value}/${lote.value}`);
                if (response.data && response.data.id) {
                    if (response.data.coordenadas) {
                        hasCoordinates.value = true;
                        events.emit('notification', {
                            type: 'error',
                            message: 'Imóvel já possui coordenadas registradas.'
                        });
                    } else {
                        isFound.value = true;
                        hasCoordinates.value = false;
                    }
                } else {
                    isFound.value = false;
                    events.emit('notification', {
                        type: 'error',
                        message: 'Imóvel não encontrado.'
                    });
                }
            } catch (err) {
                events.emit('notification', {
                    type: 'error',
                    message: 'Erro ao buscar imóvel.'
                });
                isFound.value = false;
                hasCoordinates.value = false;
            } finally {
                isSearching.value = false;
            }
        };

        const resetForm = () => {
            quadra.value = '';
            lote.value = '';
            isFound.value = false;
            hasCoordinates.value = false;
            isSearching.value = false;
        };

        const close = () => {
            events.emit('popup-close', true);
        }

        onMounted(() => {
            console.log(props.data)
            events.off("form-submitted");
            events.on("form-submitted", (sucesso) => {
                if (sucesso) {
                    events.emit('table-reload', true);
                    events.emit('notification', {
                        type: 'success',
                        message: 'Imóvel salvo com sucesso!'
                    });
                    emit('close', true);
                    events.emit('recarrega_mapa', true);
                }
            });
        });

        return {
            quadra,
            lote,
            ready,
            acao,
            isSearching,
            isFound,
            hasCoordinates,
            isFormValid,
            close,
            props,
            findImovel,
            resetForm
        };
    },

    props: {
        data: {default: null, required: true},
    }
}
</script>
