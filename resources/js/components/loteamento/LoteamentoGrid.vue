<template>
    <div>
        <datatable id="imovel" :columns="columns" @delete="confirmRemove" :source="source"></datatable>
    </div>
</template>

<script setup>
import {ref, inject, onMounted} from 'vue';

const props = defineProps({
    cidade_id: {default: false},
});

const events = inject('events');
const source = '/loteamento/list';

const columns = ref([
    {name: 'nome', title: 'Nome', width: '10%', sort: 'nome', nowrap: true},
    {name: 'sigla', title: 'Sigla', width: '10%', sort: 'sigla', nowrap: true},
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
    console.log(props)
})
</script>
