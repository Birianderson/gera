<template>
    <div v-if="ready">
        <datatable id="pessoa" :columns="columns" :source="source"></datatable>
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

onMounted(() => {
    source.value = `/admin/pessoa/imoveis/${props.data.id}`
    console.log(source.value,'link')
    ready.value =true;
});

</script>
