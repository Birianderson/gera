<template>
    <div class="m-2" v-if="ready">
        <form id="frm" name="frm" data-method="post" :action="acao">
            <form-error></form-error>
            <div class="row mb-4">
                <div class="col-lg-12 col-md-6 mb-3">
                    <label for="search" class="form-label required">Buscar Imóvel</label>
                    <div class="input-group">
                        <input
                            v-model="searchQuery"
                            required
                            type="text"
                            name="search"
                            id="search"
                            class="form-control"
                            placeholder="Digite o ID da coordenada"
                        />
                        <input
                            v-model="props.data.coordenada_id"
                            type="hidden"
                            name="coordenada"
                            id="coordenada"
                        />
                        <button
                            type="button"
                            :disabled="isSearching || isFound"
                            :class="{'btn btn-primary': !isSearching, 'btn btn-success': isFound}"
                            @click="findImovel"
                        >
                            <i class="fa" :class="{'fa-search': !isSearching, 'fa-check': isFound}"></i>
                            {{ isSearching ? 'Buscando...' : isFound ? 'Encontrado' : 'Buscar' }}
                        </button>
                        <button
                            v-if="isFound || isSearching"
                            type="button"
                            class="btn btn-secondary"
                            @click="resetForm"
                        >
                            <i class="fa fa-trash"></i> Limpar
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
import { inject, onMounted, ref } from 'vue';

export default {
    setup(props, { emit }) {
        const events = inject('events');
        const searchQuery = ref('');
        const ready = ref(true);
        const acao = ref('/imovel/vincula/');
        const isSearching = ref(false);
        const isFound = ref(false);
        const hasCoordinates = ref(false); // Nova variável para controlar se já existem coordenadas

        const findImovel = async () => {
            try {
                isSearching.value = true;
                const response = await axios.get(`/imovel/${searchQuery.value}`);
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
            searchQuery.value = '';
            isFound.value = false;
            hasCoordinates.value = false;
            isSearching.value = false;
        };

        const close = () => {
            events.emit('popup-close', true);
        }

        onMounted(() => {
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
            searchQuery,
            ready,
            acao,
            isSearching,
            isFound,
            hasCoordinates,
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
