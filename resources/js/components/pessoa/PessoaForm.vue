<template>
    <div class="m-2" v-if="ready">
        <form id="frm" name="frm" data-method="post" :action="acao">
            <form-error></form-error>
            <div class="row mb-4">

                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="cpf" class="form-label required">CPF/CNPJ</label>
                    <div class="input-group">
                        <input
                            v-model="info.cpf"
                            required
                            type="text"
                            name="cpf_find"
                            id="cpf_find"
                            v-mask="['###.###.###-##', '##.###.###/####-##']"
                            class="form-control"
                            :disabled="cpfSearched !== 0"
                        />
                        <input
                            v-model="info.cpf"
                            required
                            type="hidden"
                            name="cpf"
                            id="cpf"
                        />
                        <button
                            type="button"
                            :disabled="cpfSearched === 1 || cpfSearched === 2 || info.cpf.length !== 14 && info.cpf.length !== 18"
                            :class="{'btn btn-primary': cpfSearched === 0, 'btn btn-success': cpfSearched === 2 , 'btn btn-warning': cpfSearched === 1}"
                            @click="cpfSearched  ? enableForm() : findCPF()"
                        >
                            <i class="fa"  :class="{'fa-search': cpfSearched === 0, 'fa-check': cpfSearched === 2 , 'fa-warning': cpfSearched === 1}"></i>
                            {{ cpfSearched === 0 ? 'Buscar' : cpfSearched === 2 ? 'Liberado' : 'Cadastrado' }}
                        </button>
                        <button
                            v-if="cpfSearched === 1 || cpfSearched === 2"
                            type="button"
                            class="btn btn-secondary"
                            @click="resetForm"
                        >
                            <i class="fa fa-trash"></i> Limpar
                        </button>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="nome" class="form-label required">Nome</label>
                    <input v-model="info.nome" required type="text" name="nome" id="nome" class="form-control" :disabled="readOnly" />
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="profissao" class="form-label">Profissão</label>
                    <input v-model="info.profissao" type="text" name="profissao" id="profissao" class="form-control" :disabled="readOnly" />
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="renda" class="form-label">Renda</label>
                    <input-money id="valor" name="valor" :value="info.renda"></input-money>
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="nome_pai" class="form-label">Nome do Pai</label>
                    <input v-model="info.nome_pai" type="text" name="nome_pai" id="nome_pai" class="form-control" :disabled="readOnly" />
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="nome_mae" class="form-label">Nome da Mãe</label>
                    <input v-model="info.nome_mae" type="text" name="nome_mae" id="nome_mae" class="form-control" :disabled="readOnly" />
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="telefone" class="form-label">Telefone</label>
                    <input v-model="info.telefone"  type="text" name="telefone" id="telefone" class="form-control"  v-mask="'(##) #####-####'" :disabled="readOnly" />
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="registro_geral" class="form-label">Registro Geral</label>
                    <input v-model="info.registro_geral"  type="text" name="registro_geral" id="registro_geral" v-mask="'#######-#'" class="form-control" :disabled="readOnly" />
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="orgao_expedidor" class="form-label">Órgão Expedidor</label>
                    <input v-model="info.orgao_expedidor" type="text" name="orgao_expedidor" id="orgao_expedidor" class="form-control" :disabled="readOnly" />
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="nacionalidade" class="form-label">Nacionalidade</label>
                    <input v-model="info.nacionalidade" type="text" name="nacionalidade" id="nacionalidade" class="form-control" :disabled="readOnly" />
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="estado_civil" class="form-label">Estado Civil</label>
                    <select v-model="info.estado_civil" name="estado_civil" id="estado_civil" class="form-select" :disabled="readOnly">
                        <option value="0" disabled>Selecionar</option>
                        <option value="C">CASADO(A)</option>
                        <option value="D">DIVORCIADO(A)</option>
                        <option value="E">SEPARADO(A)</option>
                        <option value="O">SOLTEIRO(A)</option>
                        <option value="U">UNIÃO ESTÁVEL</option>
                        <option value="V">VIÚVO(A)</option>
                    </select>
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="regime_casamento" class="form-label">Regime de Casamento</label>
                    <select v-model="info.regime_casamento" name="regime_casamento" id="regime_casamento" class="form-select" :disabled="readOnly">
                        <option value="0" disabled>Selecionar</option>
                        <option value="1">COMUNHÃO PARCIAL DE BENS</option>
                        <option value="2">COMUNHÃO UNIVERSAL DE BENS</option>
                        <option value="3">SEPARAÇÃO TOTAL DE BENS</option>
                    </select>
                </div>

                <div class="col-lg-6 col-md-6 mt-3 d-flex align-items-center">
                    <label class="form-label mb-0 me-3">União Estável</label>
                    <div class="form-check form-check-inline mb-0">
                        <input-checkbox name="uniao_estavel" :checked="checkedUniaoEstavel" id="uniao_estavel" v-model="info.uniao_estavel" :disabled="readOnly" />
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 mt-3 d-flex align-items-center">
                    <label class="form-label mb-0 me-3">Falecida</label>
                    <div class="form-check form-check-inline mb-0">
                        <input-checkbox name="falecida" :checked="checkedFalecida" id="falecida" v-model="info.falecida" :disabled="readOnly" />
                    </div>
                </div>
            </div>

            <div class="row border-top pt-4">
                <div class="col-12 d-flex justify-content-center align-items-center" v-if="readOnly">
                    <button type="button" class="btn btn-danger text-white" @click="close" aria-label="Close">
                        <i class="fa fa-close"></i> Sair
                    </button>
                </div>
                <div class="col-12 text-center" v-if="!readOnly">
                    <submit-rest label="Salvar"></submit-rest>
                    &nbsp;
                    <button type="button" class="btn btn-danger text-white" @click="close" aria-label="Close">
                        <i class="fa fa-close"></i> Cancelar
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import { inject, onMounted, ref, computed } from 'vue';

export default {
    setup(props, { emit }) {
        const events = inject('events');
        const info = ref({});
        const ready = ref(false);
        const acao = ref('/pessoa/');
        const checkedFalecida = ref(false);
        const checkedUniaoEstavel = ref(false);
        const readOnly = ref(true);
        const cpfSearched = ref(0);

        const loadData = async () => {
            try {
                const response = await axios.get(`${acao.value}${props.data.id}`);
                acao.value += props.data.id;
                info.value = response.data;
                console.log(info.value)
                if (info.value.falecida === '1') {
                    checkedFalecida.value = true;
                }
                if (info.value.uniao_estavel === '1') {
                    checkedUniaoEstavel.value = true;
                }
                readOnly.value = Boolean(props.data.readOnly);
            } catch (err) {
                emit('notification', {
                    type: 'error',
                    message: 'Não foi possível recuperar os dados da pessoa.'
                });
            }
            ready.value = true;
        }

        const resetForm = () => {
            info.value = {};
            info.value.cpf = '';
            checkedFalecida.value = false;
            checkedUniaoEstavel.value = false;
            cpfSearched.value = 0;
            readOnly.value = true;
        };

        const isCPFComplete = computed(() => {
            if (info.value.cpf) {
                return info.value.cpf.length === 14 || info.value.cpf.length === 18;
            }
            return false;
        });

        const findCPF = async () => {
            try {
                const cpfNumeros = info.value.cpf.replace(/\D/g, '');
                const response = await axios.get(`/pessoa/findCPF/${cpfNumeros}`);
                if (response.data.id) {
                    info.value = response.data;
                    if (info.value.falecida === '1') {
                        checkedFalecida.value = true;
                    }
                    if (info.value.uniao_estavel === '1') {
                        checkedUniaoEstavel.value = true;
                    }
                    cpfSearched.value = 1; // CPF found, disable inputs
                    readOnly.value = true;
                } else {
                    cpfSearched.value = 2; // CPF not found, enable inputs
                    readOnly.value = false;
                }
            } catch (err) {
                emit('notification', {
                    type: 'error',
                    message: 'Erro ao buscar CPF/CNPJ.'
                });
            }
        };

        const enableForm = () => {
            cpfSearched.value = false;
            readOnly.value = false;
        };

        const close = () => {
            events.emit('popup-close', true);
        }

        onMounted(async () => {
            events.off("form-submitted");
            events.on("form-submitted", (sucesso) => {
                if (sucesso) {
                    events.emit('table-reload', true);
                    events.emit('notification', {
                        type: 'success',
                        message: 'Pessoa salva com Sucesso!'
                    });
                    emit('close', true);
                }
            });
            info.value.cpf = ''
            if (props.data) {
                await loadData();
            } else {
                ready.value = true;
            }

        });

        return {
            info, ready, acao, checkedFalecida, checkedUniaoEstavel, readOnly, close,enableForm, findCPF, cpfSearched, isCPFComplete, resetForm
        }

    },

    props: {
        data: {default: null, required: true},
    }

}
</script>
