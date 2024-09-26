<template>
    <div>
        <datatable id="tipo_arquivo" :columns="columns" @delete="confirmRemove" :source="source"></datatable>
    </div>
</template>

<script setup>
import {ref, inject, onMounted} from 'vue';

const props = defineProps({
    canUpdate: {default: false},
    canDelete: {default: false},
});

const events = inject('events');
const source = '/admin/tipo_arquivo/list';

const columns = ref([
    {name: 'nome', title: 'Nome', width: '10%', sort: 'nome', nowrap: true},
    {name: 'tabela', title: 'Tipo', width: '10%', sort: 'tabela', nowrap: true},
    {
        name: 'id',
        title: 'Ação',
        width: '5%',
        contentClass: 'text-end',
        headerClass: 'text-end',
        formatter: (value, row) => {
            let output = "";
            output += `<a href="javascript:;" data-json='{"id": "${value}"}' data-tooltip="Editar" data-action="popup" data-size="xl" data-component="tipo-arquivo-form" data-title="Editar Tipo de Arquivo" class=" mx-1 action text-align-center tooltip tooltip--top"><i class="fa fa-pencil"></i></a>`;
            output += `<a href="javascript:;" data-json='{"id": "${value}"}' data-tooltip="Remover" data-action="delete" class="action mx-0 action-delete tooltip tooltip--top"><i class="fa fa-trash mx-1"></i></a>`;
            return output;
        }
    }
]);
const confirmRemove = async (data) => {
    events.emit('loading', true);
    try {
        await axios.delete(`/admin/imovel/${data.id}`);
        events.emit('table-reload');
        events.emit('notification', {
            type: 'success',
            message: 'Tipo de Arquivo excluído com Sucesso.'
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
