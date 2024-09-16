<template>
    <div v-if="ready">
        <datatable id="pessoa" :columns="columns" @delete="confirmRemove" :source="source"></datatable>
    </div>
</template>

<script setup>
import {ref, inject, onMounted} from 'vue';

const props = defineProps({
    data: { default: null, required: true }
});

const events = inject('events');
const source = ref({});
const ready = ref(false);
const columns = ref([
    {name: 'cidade.nome', title: 'Município', width: '10%', sort: 'municipio'},
    {name: 'loteamento.nome', title: 'Loteamento', width: '10%', sort: 'loteamento'},
    {name: 'quadra', title: 'Quadra', width: '10%', sort: 'quadra'},
    {name: 'lote', title: 'Lote', width: '10%', sort: 'lote'},
]);
const confirmRemove = async (data) => {
    events.emit('loading', true);
    try {
        await axios.delete(`/pessoa/${data.id}`);
        events.emit('table-reload');
        events.emit('notification', {
            type: 'success',
            message: 'Competência excluída com Sucesso.'
        });
    } catch (err) {
        events.emit('notification', {
            type: 'error',
            message: err.response?.data?.message || 'Não foi possível excluir o registro.'
        });
    } finally {
        events.emit('loading', false);
    }
}



onMounted(() => {
    source.value = `/pessoa/imoveis/${props.data.id}`
    console.log(source.value,'link')
    ready.value =true;
});

</script>
