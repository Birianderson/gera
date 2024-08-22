<template>
    <button class="btn btn-success text-white" @click="sort">
        <i class="fa fa-sort"></i>
        {{ label }}
    </button>
</template>

<script setup>
import {inject} from 'vue';

const events = inject('events');

const sort = () => {
    events.emit('popup', {
        title: props.titulo,
        component: 'order',
        size: props.tamanho,
        data: {
            source: props.source,
            json: props.json,
            callback: props.callback
        },
    });
};

const props = defineProps({
    titulo: {default: 'Ordenar'},
    source: {default: null, required: false},
    json: {default: null, required: false},
    label: {default: 'Ordenar'},
    tamanho: {default: 'md'},
    callback: {default: null}
});
</script>
