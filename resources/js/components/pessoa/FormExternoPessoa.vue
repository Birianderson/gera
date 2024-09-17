<template>
    <div class="m-2" v-if="ready">
        <form id="frm" name="frm" data-method="post" :action="acao">
            <form-error></form-error>

            <div class="row mb-4">
                <h2>Morador</h2>
                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="cpf" class="form-label required">CPF/CNPJ</label>
                    <div class="input-group">
                        <input
                            v-model="info.cpf"
                            required
                            type="text"
                            name="cpf"
                            id="cpf"
                            v-mask="['###.###.###-##', '##.###.###/####-##']"
                            class="form-control"
                        />
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="nome" class="form-label required">Nome</label>
                    <input v-model="info.nome" required type="text" name="nome" id="nome" class="form-control"/>
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="profissao" class="form-label">Profissão</label>
                    <input v-model="info.profissao" type="text" name="profissao" id="profissao" class="form-control"/>
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="renda" class="form-label">Renda</label>
                    <input-money id="valor" name="valor" :value="info.renda"></input-money>
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="nome_pai" class="form-label">Nome do Pai</label>
                    <input v-model="info.nome_pai" type="text" name="nome_pai" id="nome_pai" class="form-control"/>
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="nome_mae" class="form-label">Nome da Mãe</label>
                    <input v-model="info.nome_mae" type="text" name="nome_mae" id="nome_mae" class="form-control"/>
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="telefone" class="form-label">Telefone</label>
                    <input v-model="info.telefone" type="text" name="telefone" id="telefone" class="form-control"
                           v-mask="'(##) #####-####'"/>
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="registro_geral" class="form-label">Registro Geral</label>
                    <input v-model="info.registro_geral" type="text" name="registro_geral" id="registro_geral"
                           v-mask="'#######-#'" class="form-control"/>
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="orgao_expedidor" class="form-label">Órgão Expedidor</label>
                    <input v-model="info.orgao_expedidor" type="text" name="orgao_expedidor" id="orgao_expedidor"
                           class="form-control"/>
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="nacionalidade" class="form-label">Nacionalidade</label>
                    <input v-model="info.nacionalidade" type="text" name="nacionalidade" id="nacionalidade"
                           class="form-control"/>
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="estado_civil" class="form-label">Estado Civil</label>
                    <select v-model="info.estado_civil" name="estado_civil" id="estado_civil" class="form-select"
                            @change="onEstadoCivilChange">
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
                    <select v-model="info.regime_casamento" name="regime_casamento" id="regime_casamento"
                            class="form-select">
                        <option value="0" disabled>Selecionar</option>
                        <option value="1">COMUNHÃO PARCIAL DE BENS</option>
                        <option value="2">COMUNHÃO UNIVERSAL DE BENS</option>
                        <option value="3">SEPARAÇÃO TOTAL DE BENS</option>
                    </select>
                </div>
            </div>
            <div v-if="['C', 'U'].includes(info.estado_civil)" class="row mb-4 border-top">
                <h2 class="mt-2">Conjuge</h2>
                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="conjuge_cpf" class="form-label required">CPF/CNPJ</label>
                    <div class="input-group">
                        <input
                            v-model="info.cpf"
                            required
                            type="text"
                            name="conjuge_cpf"
                            id="cpf"
                            v-mask="['###.###.###-##', '##.###.###/####-##']"
                            class="form-control"
                        />
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="conjuge_nome" class="form-label required">Nome</label>
                    <input v-model="info.nome" required type="text" name="conjuge_nome" id="conjuge_nome" class="form-control"/>
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="conjuge_profissao" class="form-label">Profissão</label>
                    <input v-model="info.profissao" type="text" name="conjuge_profissao" id="conjuge_profissao" class="form-control"/>
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="conjuge_renda" class="form-label">Renda</label>
                    <input-money id="conjuge_valor" name="conjuge_valor" :value="info.renda"></input-money>
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="conjuge_nome_pai" class="form-label">Nome do Pai</label>
                    <input v-model="info.nome_pai" type="text" name="conjuge_nome_pai" id="conjuge_nome_pai" class="form-control"/>
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="conjuge_nome_mae" class="form-label">Nome da Mãe</label>
                    <input v-model="info.nome_mae" type="text" name="conjuge_nome_mae" id="conjuge_nome_mae" class="form-control"/>
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="conjuge_telefone" class="form-label">Telefone</label>
                    <input v-model="info.telefone" type="text" name="conjuge_telefone" id="conjuge_telefone" class="form-control"
                           v-mask="'(##) #####-####'"/>
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="conjuge_registro_geral" class="form-label">Registro Geral</label>
                    <input v-model="info.registro_geral" type="text" name="conjuge_registro_geral" id="conjuge_registro_geral"
                           v-mask="'#######-#'" class="form-control"/>
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="conjuge_orgao_expedidor" class="form-label">Órgão Expedidor</label>
                    <input v-model="info.orgao_expedidor" type="text" name="conjuge_orgao_expedidor" id="conjuge_orgao_expedidor"
                           class="form-control"/>
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="conjuge_nacionalidade" class="form-label">Nacionalidade</label>
                    <input v-model="info.nacionalidade" type="text" name="conjuge_nacionalidade" id="conjuge_nacionalidade"
                           class="form-control"/>
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="estado_civil" class="form-label">Estado Civil</label>
                    <select v-model="info.estado_civil" name="estado_civil" id="estado_civil" class="form-select"
                            @change="onEstadoCivilChange">
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
                    <select v-model="info.regime_casamento" name="regime_casamento" id="regime_casamento"
                            class="form-select">
                        <option value="0" disabled>Selecionar</option>
                        <option value="1">COMUNHÃO PARCIAL DE BENS</option>
                        <option value="2">COMUNHÃO UNIVERSAL DE BENS</option>
                        <option value="3">SEPARAÇÃO TOTAL DE BENS</option>
                    </select>
                </div>
            </div>

            <div class="row border-top pt-4">
                <div class="col-12 d-flex justify-content-center align-items-center">
                    <submit-rest label="Salvar"></submit-rest>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import {inject, onMounted, ref} from 'vue';

export default {
    setup(props, {emit}) {
        const events = inject('events');
        const info = ref({
            cpf: '',
            nome: '',
            profissao: '',
            renda: '',
            nome_pai: '',
            nome_mae: '',
            telefone: '',
            registro_geral: '',
            orgao_expedidor: '',
            nacionalidade: '',
            estado_civil: '',
            regime_casamento: '',
            morador_nome: '',
            morador_telefone: '',
            conjuge_nome: '',
            conjuge_telefone: ''
        });
        const ready = ref(false);
        const acao = ref('/pessoa/create');


        const resetForm = () => {
            info.value = {
                cpf: '',
                nome: '',
                profissao: '',
                renda: '',
                nome_pai: '',
                nome_mae: '',
                telefone: '',
                registro_geral: '',
                orgao_expedidor: '',
                nacionalidade: '',
                estado_civil: '',
                regime_casamento: '',
                morador_nome: '',
                morador_telefone: '',
                conjuge_nome: '',
                conjuge_telefone: ''
            };
        };

        const onEstadoCivilChange = () => {
            // Se o estado civil for "Casado" ou "União Estável", mostra campos do cônjuge
            if (['C', 'U'].includes(info.value.estado_civil)) {
                // Campos do cônjuge são exibidos
            } else {
                // Campos do cônjuge são ocultados
                info.value.conjuge_nome = '';
                info.value.conjuge_telefone = '';
            }
        };

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

            ready.value = true;
        });

        return {
            info, ready, acao, resetForm, onEstadoCivilChange
        };
    },

    props: {
        data: {default: null, required: true},
    }
};
</script>
