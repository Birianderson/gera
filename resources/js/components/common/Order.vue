<template>
    <div class=" m-10">
        <div class="row mb-2">
            <div class="col-2">Ordem</div>
            <div class="col-10">Nome</div>
        </div>
        <draggable v-if="list.length > 0" class="list-group w-full" :list="list" @change="log">
            <div
                class="list-group-item bg-gray-100"
                v-for="element in list"
                :key="element.ordem"
            >
                <div class="row">
                    <div class="col-2">
                        {{ element.ordem }}
                    </div>
                    <div class="col-10">
                        {{ element.name }}
                    </div>
                </div>
            </div>
        </draggable>
        <div class="row mt-4">
            <div class="col">
                <button class="btn btn-success text-white" @click="save">
                    <i class="fa fa-check"></i>
                    Salvar ordem
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import {ref, onMounted, inject} from 'vue';
import {VueDraggableNext} from 'vue-draggable-next';
import axios from 'axios';

export default {
    components: {
        draggable: VueDraggableNext,
    },

    setup(props, {emit}) {
        const list = ref([]);
        const events = inject('events');

        const carregaDados = async () => {
            try {
                const response = await axios.get(props.data.source);
                console.log(props.data.source);
                list.value = response.data;
                console.log(list.value);
            } catch (err) {
                emitNotification('error', 'Não foi possível recuperar os dados para ordenação.');
            }
        };

        const save = async () => {
            try {
                const response = await axios.post(props.data.callback, list.value);
                emitClose(true);
                if (response.data.reload) {
                    document.location.reload();
                } else {
                    emitTableReload(true);
                    emitNotification('success', 'Ordenação salva com sucesso!');
                }
            } catch (err) {
                emitNotification('error', 'Não foi possível salvar a ordenação.');
            }
        };

        const log = (event) => {
            console.log(event);
        };

        onMounted(() => {
            if (props.data.json) {
                if (typeof props.data.json === 'string') {
                    list.value = JSON.parse(props.data.json);
                } else {
                    list.value = props.data.json;
                }
            } else {
                if (props.data.source) {
                    carregaDados();
                } else {
                    emitNotification('error', 'Não foi possível recuperar os dados para ordenação.');
                }
            }
        });

        const emitNotification = (type, message) => {
            events.emit('notification', {type, message});
        };

        const emitClose = (value) => {
            emit('close', value);
        };

        const emitTableReload = (value) => {
            events.emit('table-reload', value);
        };

        return {
            list,
            save,
            log
        };
    },

    props: {
        data: {default: null, required: true},
    }
};
</script>

<style scoped>
.list-group-item {
    font-size: 0.875em !important;
    cursor: grab;
}

.list-group-item:first-child {
    border-top-left-radius: 0;
    border-top-right-radius: 0;
}

.list-group-item:last-child {
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
}

</style>
