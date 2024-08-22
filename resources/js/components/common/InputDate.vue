<template>
    <div>
        <date-picker :disabled="disabled" :format="dateFormat" :input-attr="dateAttr" v-model:value="dateValue" input-class="form-control" @change="onSelect"></date-picker>
    </div>
</template>

<script>
import {ref, onMounted, watchEffect} from 'vue';
import DatePicker from 'vue-datepicker-next';
import 'vue-datepicker-next/index.css';
import moment from "moment";
export default {
    setup(props, { emit }) {
        const dateValue = ref(null);
        const dateFormat = ref(null);
        const dateAttr = ref({
            name: props.name ?? 'date',
            id: props.id ?? 'id'
        });


        const onSelect = (date) => {
            const dt = moment(date).format(dateFormat.value);
            emit('update:modelValue', dt);
            emit('selected', dt);
        }

        const convertData = (dt) => {
            const [dia, mes, ano] = dt.substring(0, 10).split("/");
            return `${ano}-${mes}-${dia}`;
        }

        onMounted(() => {
            dateFormat.value = props.format ?? 'DD/MM/YYYY';
            const dt = props.modelValue ?? props.value;
            if(dt) {
                dateValue.value = new Date(convertData(dt) + ' 00:00:00');
            }
        });

        watchEffect(() => {
            if(props.modelValue || props.value) {
                const dt = (props.modelValue ?? props.value);
                dateValue.value = new Date(convertData(dt) + ' 01:00');
            } else {
                dateValue.value = null;
            }
        })

        return {
            dateValue,
            dateFormat,
            dateAttr,
            onSelect
        }
    },

    props: {
        modelValue: { default: null },
        name: { default: null },
        value: { default: null },
        id: { default: null },
        format: { default: null },
        disabled: { type: Boolean, default: false },
        readonly: { type: Boolean, default: false },
    },

    components: {
        DatePicker
    }
}
</script>
