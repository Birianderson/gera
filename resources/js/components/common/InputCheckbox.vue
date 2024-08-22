
<template>
    <div class="switch" :class="{ 'disabled': readOnly }">
        <input :id="inputId" :name="name" type="checkbox" class="switch-input" :value="1" v-model="inputChecked"
               @change="onUpdate" />
        <label :for="inputId" class="switch-label">Switch</label>
    </div>
</template>

<script>
import {ref, onMounted, inject} from "vue";

export default {
    setup(props, {emit}) {
        const events = inject('events');
        const inputId = ref(null);
        const inputChecked = ref(false);
        const readOnly = ref(false);

        const toggle = (params) => {
            if (params.id !== inputId.value) return false;
            inputChecked.value = params.checked || !inputChecked.value;
        }

        const onUpdate = (evt) => {
            if (props.hideOnActive) {
                document.getElementById(props.hideOnActive).style.display = inputChecked.value ? 'none' : 'block';
            }

            if (props.showOnActive) {
                document.getElementById(props.showOnActive).style.display = inputChecked.value ? 'block' : 'none';
            }

            emit('update:modelValue', evt.target.checked ? 'S' : 'N');
        }

        onMounted(() => {
            readOnly.value = props.disabled;
            inputId.value = props.id || `checkbox_${props.name}`;
            inputChecked.value = props.checked;

            events.on('checkbox-toggle', toggle);
        });

        return {
            inputId,
            inputChecked,
            onUpdate,
            readOnly,
        }
    },

    props: {
        id: {default: null},
        name: {default: null},
        value: {default: null},
        checked: {default: false, type: Boolean},
        hideOnActive: {default: null},
        showOnActive: {default: null},
        disabled: {default: false, type: Boolean}
    }
}
</script>

<style scoped>
.switch {
    position: relative;
    display: inline-block;
}

.switch-input {
    display: none;
}

.switch-label {
    display: block;
    width: 48px;
    height: 24px;
    text-indent: -150%;
    clip: rect(0 0 0 0);
    color: transparent;
    user-select: none;
}

.switch-label::before,
.switch-label::after {
    content: "";
    display: block;
    position: absolute;
    cursor: pointer;
}

.switch-label::before {
    width: 100%;
    height: 100%;
    background-color: #f65866;
    border-radius: 9999em;
    -webkit-transition: background-color 0.25s ease;
    transition: background-color 0.25s ease;
}

.switch-label::after {
    top: 0;
    left: 0;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background-color: #fff;
    box-shadow: 0 0 2px rgba(0, 0, 0, 0.45);
    -webkit-transition: left 0.25s ease;
    transition: left 0.25s ease;
}

.switch-input:checked + .switch-label::before {
    background-color: #3db97f;
}

.switch-input:checked + .switch-label::after {
    left: 24px;
}

.disabled {
    pointer-events: none;
    opacity: 0.5;
}
</style>
