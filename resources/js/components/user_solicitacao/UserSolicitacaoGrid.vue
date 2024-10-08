<template>
    <div>
        <datatable id="solicitacao" :columns="columns" :source="source"></datatable>
    </div>
</template>

<script setup>
import { ref, inject, onMounted } from 'vue';

const props = defineProps({
    canUpdate: { default: false },
    canDelete: { default: false },
});

const events = inject('events');
const source = '/user/solicitacoes/list';

const columns = ref([
    {
        name: 'created_at',
        title: 'Data',
        template: 'date',
        width: '10%',
        sort: 'created_at',
        nowrap: true
    },
    { name: 'usuario.name', title: 'Solicitante', width: '20%', sort: 'usuario.name' },
    { name: 'usuario.cpf', title: 'CPF', width: '10%', sort: 'usuario.cpf', nowrap: true },
    {
        name: 'status',
        title: 'Situação',
        width: '10%',
        nowrap: true,
        headerClass: 'text-center',
        contentClass: 'text-center',
        formatter: (value) => {
            if(value === 'P') {
                return `<span class="badge bg-warning text-white">PENDENTE</span>`;
            }
            if(value === 'E') {
                return `<span class="badge bg-info text-white">EM PROGRESSO</span>`;
            }
            if(value === 'A') {
                return `<span class="badge bg-success bg-gray-500 text-white">APROVADO</span>`;
            }
            if (value === 'R') {
                return `<span class="badge bg-secondary bg-blue-500 text-white">CANCELADO</span>`;
            }
        },
    },
    {
        name: 'id',
        title: 'Ação',
        width: '5%',
        headerClass: 'text-center',
        nowrap: true,
        contentClass: 'text-center',
        formatter: (value, row) => {
            let output = "";
            output += `<a href="/mapa/solicitacao_mapa/${row.imovel_id}"  data-tooltip="Verificar Localização" data-title="Atender Solicitação" class=" mx-1 action text-align-center"><i class="fa fa-map-marker-alt"></i></a>`;
            output += `<a href="/admin/mensagem_solicitacao/solicitacao/${value}" data-json='{"id": "${value}"}' data-tooltip="Editar" data-title="Atender Solicitação" class=" mx-1 action text-align-center"><i class="fa fa-comment"></i></a>`;
            return output;
        }
    }
]);


onMounted(() => {
})
</script>
