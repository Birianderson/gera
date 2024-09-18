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
    {name: 'cidade.nome', title: 'Município', width: '10%', sort: 'cidade.nome', nowrap: true},
    {name: 'loteamento.nome', title: 'Loteamento', width: '10%', sort: 'loteamento.nome', nowrap: true},
    {name: 'quadra', title: 'Quadra', width: '10%', sort: 'quadra'},
    {name: 'lote', title: 'Lote', width: '10%', sort: 'lote'},
    {name: 'pessoa.nome', title: 'Titular', width: '20%'},
    {
        name: 'id',
        title: 'Ação',
        width: '5%',
        contentClass: 'text-end',
        headerClass: 'text-end',
        formatter: (value, row) => {
            let output = "";
            if(row.pessoa === null){
                output += `<a href="/gerarQrcode/${value}" data-json='{"id": "${value}"}' data-tooltip="QrCODE" target="_blank" class=" mx-1 action text-align-center tooltip tooltip--top"><i class="fa fa-qrcode"></i></a>`;
            }
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
