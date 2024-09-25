<template>
    <div class="m-2" v-if="ready">
        <form id="frm" name="frm" data-method="post" :action="acao">
            <form-error></form-error>
            <div class="row mb-4">

                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="cpf" class="form-label required">CPF/CNPJ</label>
                    <div class="input-group">
                        <input
                            v-model="cpf"
                            required
                            type="text"
                            name="cpf_find"
                            id="cpf_find"
                            v-mask="['###.###.###-##', '##.###.###/####-##']"
                            class="form-control"
                            :disabled="true"
                        />
                    </div>
                    <input
                        v-model="cpf"
                        required
                        type="hidden"
                        name="cpf"
                        id="cpf"
                    />
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
                    <input-money id="renda" name="renda" :value="info.renda"></input-money>
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
        const acao = ref('/user/pessoa/create');
        const cpf = ref({});
        const checkedUniaoEstavel = ref(false);
        const readOnly = ref(false);
        const cpfSearched = ref(0);

        const loadData = async () => {
            try {
                acao.value = '/user/pessoa/findCPF'
                const response = await axios.get(`${acao.value}`);
                acao.value = '/user/pessoa/update'
                info.value = response.data;
                console.log(info.value)
                if (Object.keys(info.value).length === 0) {
                    acao.value = '/user/pessoa/create';
                }
            } catch (err) {
                emit('notification', {
                    type: 'error',
                    message: 'Não foi possível recuperar os dados da pessoa.'
                });
            }
            ready.value = true;
        }


        const close = () => {
            events.emit('popup-close', true);
        }

        onMounted(async () => {
            cpf.value = props.data
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
            if (cpf.value) {
                await loadData();
            } else {
                ready.value = true;
            }

        });

        return {
            info, ready, acao, checkedUniaoEstavel, readOnly, close, cpfSearched, cpf
        }

    },

    props: {
        data: {default: null, required: true},
    }

}
</script>

