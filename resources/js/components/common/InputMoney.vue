<template>
    <div>
        <money3 v-model="amount" v-bind="config" :class="`money-${id} focus-ring`"></money3>
        <input type="hidden" :name="name" :id="id" v-model="parsedAmount" :data-error-class="`money-${id}`" />
    </div>
</template>

<script>
import {ref, inject, watch} from 'vue';
import { Money3Component } from 'v-money3'

export default {
    components: { money3: Money3Component },

    setup(props, {emit}) {
        const events = inject('events');
        const amount = ref(props.value ?? 0.00);
        const parsedAmount = ref(props.value);
        const config = ref({
            readonly: props.readonly,
            masked: props.masked,
            prefix: props.prefix,
            suffix: props.suffix,
            thousands: props.thousands,
            decimal: props.decimal,
            precision: props.precision,
            disableNegative: props.disableNegative,
            disabled: props.disabled,
            min: props.min,
            max: props.max,
            allowBlank: props.allowBlank,
            minimumNumberOfCharacters: props.minimumNumberOfCharacters,
            shouldRound: props.shouldRound,
            focusOnRight: props.focusOnRight,
        });

        const parseValue = (str) => {
            let result = "";
            for (let i = 0; i < str.length; i++) {
                if ((parseInt(str[i]) >= 0 && parseInt(str[i]) <= 9) || str[i] === ".") {
                    result += str[i];
                }
            }
            return parseFloat(result);
        }

        watch(amount, (e) => {
            parsedAmount.value = parseValue(e);
            emit('update:value', e);
        });

        watch(() => props.value, (newValue, oldValue) => {
            amount.value = newValue;
            parsedAmount.value = parseValue(newValue);
        });

        return {
            config,
            amount,
            parsedAmount
        }
    },

    props: {
        modelValue: { default: null },
        name: { default: null },
        id: { default: null },
        value: { default:  '' },
        readonly: { type: Boolean, default: false },
        masked: { type: Boolean, default: false },
        prefix: { default: 'R$ ' },
        suffix:  { default: '' },
        thousands:  { default: '.' },
        decimal:  { default: ',' },
        precision:  { default: 2 },
        disableNegative: { type: Boolean, default: false },
        disabled: { type: Boolean, default: false },
        min: { default: null },
        max: { default: null },
        allowBlank: { type: Boolean, default: false },
        minimumNumberOfCharacters: { default: 0 },
        shouldRound: { type: Boolean, default: true },
        focusOnRight: { type: Boolean, default: false },
    }
}
</script>

<style lang="scss" scoped>
.v-money3 {
    text-align: left;
    border: solid 1px #aaa;
    background-color: #FFFFFF;
    background-image: none;
    border-radius: 5px;
    color: inherit;
    display: block;
    padding: 4px;
    transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
    width: 100%;
    font-weight: 300;
    font-size: 14px;
}

.v-money3:focus {
    border-color: #aaaaaa;
}
</style>
