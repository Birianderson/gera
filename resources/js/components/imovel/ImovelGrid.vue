<template>
    <div>
        <datatable id="imovel" :columns="columns" @delete="confirmRemove" :source="source"></datatable>
    </div>
</template>

<script setup>
import {ref, inject, onMounted} from 'vue';

const props = defineProps({
    canUpdate: {default: false},
    canDelete: {default: false},
});

const events = inject('events');
const source = '/imovel/list';

const columns = ref([
    {name: 'municipio', title: 'Município', width: '10%', sort: 'municipio', nowrap: true},
    {name: 'loteamento', title: 'Loteamento', width: '10%', sort: 'loteamento', nowrap: true},
    {name: 'quadra', title: 'Quadra', width: '10%', sort: 'quadra'},
    {name: 'lote', title: 'Lote', width: '10%', sort: 'lote'},
    {name: 'numero_processo_administrativo', title: 'Número Processo', width: '10%', sort: 'prefixo_titulo'},
    {name: 'pessoa.nome', title: 'Titular', width: '20%'},
    {
        name: 'id',
        title: 'Ação',
        width: '3%',
        contentClass: 'text-center',
        formatter: (value, row) => {
            let output = "";
            output += `<a href="javascript:;" data-json='{"id": "${value}"}' data-tooltip="Editar" data-action="popup" data-size="xl" data-component="imovel-form" data-title="Editar imovel" class=" mx-1 action text-align-center tooltip tooltip--top"><i class="fa fa-pencil"></i></a>`;
            output += `<a href="javascript:;" data-json='{"id": "${value}"}' data-tooltip="Remover" data-action="delete" class="action mx-0 action-delete tooltip tooltip--top"><i class="fa fa-trash mx-1"></i></a>`;
            return output;
        }
    }
]);
const confirmRemove = async (data) => {
    events.emit('loading', true);
    try {
        await axios.delete(`/imovel/${data.id}`);
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
})
</script>
