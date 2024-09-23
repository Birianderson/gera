<template>
    <div>
        <datatable id="imovel" :columns="columns"  :source="source"></datatable>
    </div>
</template>

<script setup>
import {ref, inject, onMounted} from 'vue';

const props = defineProps({
    canUpdate: {default: false},
    canDelete: {default: false},
});

const events = inject('events');
const source = '/admin/cidade/list';

const columns = ref([
    {name: 'nome', title: 'Nome', width: '10%', sort: 'nome', nowrap: true},
    {name: 'sigla', title: 'Sigla', width: '20%'},
    {
        name: 'id',
        title: 'Ação',
        width: '3%',
        contentClass: 'text-center',
        formatter: (value, row) => {
            let output = "";
            output += `<a href='/admin/loteamento/${value}/cidade' data-json='{"id": "${value}"}' data-tooltip="Editar" class=" mx-1 action text-align-center tooltip tooltip--top"><i class="fa fa-location-dot"></i></a>`;
            output += `<a href="javascript:;" data-json='{"id": "${value}"}' data-tooltip="Editar" data-action="popup" data-size="xl" data-component="imovel-form" data-title="Editar imovel" class=" mx-1 action text-align-center tooltip tooltip--top"><i class="fa fa-pencil"></i></a>`;
            return output;
        }
    }
]);


onMounted(() => {
})
</script>
