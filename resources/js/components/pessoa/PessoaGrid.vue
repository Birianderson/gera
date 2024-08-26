<template>
    <div>
        <datatable id="pessoa" :columns="columns" @delete="confirmRemove" :source="source"></datatable>
    </div>
</template>

<script setup>
import {ref, inject, onMounted} from 'vue';

const props = defineProps({
    canUpdate: {default: false},
    canDelete: {default: false},
});

const events = inject('events');
const source = '/pessoa/list';

const columns = ref([
    {name: 'nome', title: 'Nome', width: '30%', sort: 'nome', nowrap: true},
    {
        name: 'cpf',
        title: 'CPF',
        width: '20%',
        nowrap: true,
        formatter: (value) => {
            // Remove todos os caracteres não numéricos
            const cpf = value.replace(/\D/g, '');

            // Adiciona a formatação
            if (cpf.length === 11) {
                return cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
            } else if (cpf.length === 14) {
                return cpf.replace(/(\d{2})(\d{3})(\d{3})\/(\d{4})-(\d{2})/, '$1.$2.$3/$4-$5');
            }
            return value; // Retorna o valor original se não corresponder a nenhum formato
        }
    },
    {
        name: 'estado_civil',
        title: 'Estado Civil',
        width: '20%',
        nowrap: true,
        formatter: (value, row) => {
            const estadoCivilMap = {
                'C': 'CASADO(A)',
                'D': 'DIVORCIADO(A)',
                'E': 'SEPARADO(A)',
                'O': 'SOLTEIRO(A)',
                'U': 'UNIÃO ESTÁVEL',
                'V': 'VIÚVO(A)',
            };
            // Exibir o estado civil correspondente ao valor armazenado
            return estadoCivilMap[value] || 'Não informado';
        }
    },
    {
        name: 'id',
        title: 'Ação',
        width: '5%',
        nowrap: true,
        contentClass: 'text-center',
        formatter: (value, row) => {
            let output = "";
            output += `<a href="javascript:;" data-json='{"id": "${value}"}' data-tooltip="Editar" data-action="popup" data-size="xl" data-component="pessoa-form" data-title="Editar Pessoa" class=" mx-1 action text-align-center tooltip tooltip--top"><i class="fa fa-pencil"></i></a>`;
            output += `<a href="javascript:;" data-json='{"id": "${value}"}' data-tooltip="Remover" data-action="delete" class="action mx-0 action-delete tooltip tooltip--top"><i class="fa fa-trash mx-1"></i></a>`;
            return output;
        }
    }
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
})
</script>
