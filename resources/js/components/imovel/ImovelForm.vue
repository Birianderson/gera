<template>
    <div class="m-2" v-if="ready">
        <form id="frm" name="frm" data-method="post" :action="acao">
            <form-error></form-error>
            <!--  Localização -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6> Localização</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 mb-3">
                            <label for="municipio" class="form-label">Município</label>
                            <select v-model="info.municipio" name="municipio" id="municipio" class="form-select" :disabled="readOnly">
                                <option value="" disabled>Selecione um município</option>
                                <option v-for="municipio in municipios" :key="municipio.id" :value="municipio.nome">
                                    {{ municipio.nome }}
                                </option>
                            </select>
                        </div>

                        <div class="col-lg-6 col-md-6 mb-3">
                            <label for="loteamento" class="form-label">Loteamento</label>
                            <input v-model="info.loteamento" type="text" name="loteamento" id="loteamento" class="form-control" :disabled="readOnly" />
                        </div>

                        <div class="col-lg-4 col-md-4 mb-3">
                            <label for="quadra" class="form-label">Quadra</label>
                            <input v-model="info.quadra" type="text" name="quadra" id="quadra" class="form-control" :disabled="readOnly" />
                        </div>

                        <div class="col-lg-4 col-md-4 mb-3">
                            <label for="lote" class="form-label">Lote</label>
                            <input v-model="info.lote" type="text" name="lote" id="lote" class="form-control" :disabled="readOnly" />
                        </div>

                        <div class="col-lg-4 col-md-4 mb-3">
                            <label for="casa" class="form-label">Casa</label>
                            <input v-model="info.casa" type="text" name="casa" id="casa" class="form-control" :disabled="readOnly" />
                        </div>

                        <div class="col-lg-6 col-md-6 mb-3">
                            <label for="inscricao_imobiliaria" class="form-label">Inscrição Imobiliária</label>
                            <input v-model="info.inscricao_imobiliaria" type="text" name="inscricao_imobiliaria" id="inscricao_imobiliaria" class="form-control" :disabled="readOnly" />
                        </div>

                        <div class="col-lg-6 col-md-6 mb-3">
                            <label for="matricula_reurb" class="form-label">Matrícula Reurb</label>
                            <input v-model="info.matricula_reurb" type="text" name="matricula_reurb" id="matricula_reurb" class="form-control" :disabled="readOnly" />
                        </div>
                    </div>
                </div>
            </div>

            <!--  Medidas -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6> Medidas</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 mb-3">
                            <label for="area" class="form-label">Área (m²)</label>
                            <input v-model="info.area" type="text" name="area" id="area" class="form-control" :disabled="readOnly" />
                        </div>

                        <div class="col-lg-4 col-md-4 mb-3">
                            <label for="area_construida" class="form-label">Área Construída (m²)</label>
                            <input v-model="info.area_construida" type="text" name="area_construida" id="area_construida" class="form-control" :disabled="readOnly" />
                        </div>

                        <div class="col-lg-4 col-md-4 mb-3">
                            <label for="perimetro" class="form-label">Perímetro (m)</label>
                            <input v-model="info.perimetro" type="text" name="perimetro" id="perimetro" class="form-control" :disabled="readOnly" />
                        </div>

                        <div class="col-lg-3 col-md-3 mb-3">
                            <label for="medida_frente" class="form-label">Frente (m)</label>
                            <input v-model="info.medida_frente" type="text" name="medida_frente" id="medida_frente" class="form-control" :disabled="readOnly" />
                        </div>

                        <div class="col-lg-3 col-md-3 mb-3">
                            <label for="medida_fundo" class="form-label">Fundo (m)</label>
                            <input v-model="info.medida_fundo" type="text" name="medida_fundo" id="medida_fundo" class="form-control" :disabled="readOnly" />
                        </div>

                        <div class="col-lg-3 col-md-3 mb-3">
                            <label for="medida_lado_direito" class="form-label">Lado Direito (m)</label>
                            <input v-model="info.medida_lado_direito" type="text" name="medida_lado_direito" id="medida_lado_direito" class="form-control" :disabled="readOnly" />
                        </div>

                        <div class="col-lg-3 col-md-3 mb-3">
                            <label for="medida_lado_esquerdo" class="form-label">Lado Esquerdo (m)</label>
                            <input v-model="info.medida_lado_esquerdo" type="text" name="medida_lado_esquerdo" id="medida_lado_esquerdo" class="form-control" :disabled="readOnly" />
                        </div>

                        <div class="col-lg-3 col-md-4 mb-3">
                            <label for="confinante_frente" class="form-label">Confinante Frente</label>
                            <input v-model="info.confinante_frente" type="text" name="confinante_frente" id="confinante_frente" class="form-control" :disabled="readOnly" />
                        </div>

                        <div class="col-lg-3 col-md-4 mb-3">
                            <label for="confinante_fundo" class="form-label">Confinante Fundo</label>
                            <input v-model="info.confinante_fundo" type="text" name="confinante_fundo" id="confinante_fundo" class="form-control" :disabled="readOnly" />
                        </div>

                        <div class="col-lg-3 col-md-4 mb-3">
                            <label for="confinante_lado_direito" class="form-label">Confinante Direita</label>
                            <input v-model="info.confinante_lado_direito" type="text" name="confinante_lado_direito" id="confinante_lado_direito" class="form-control" :disabled="readOnly" />
                        </div>

                        <div class="col-lg-3 col-md-4 mb-3">
                            <label for="confinante_lado_esquerdo" class="form-label">Confinante Esquerda</label>
                            <input v-model="info.confinante_lado_esquerdo" type="text" name="confinante_lado_esquerdo" id="confinante_lado_esquerdo" class="form-control" :disabled="readOnly" />
                        </div>
                    </div>
                </div>
            </div>

            <!--  Valores -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6> Valores</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="valor_venal" class="form-label">Valor Venal (R$)</label>
                            <input-money id="valor_venal" name="valor_venal" :value="info.valor_venal"></input-money>
                        </div>

                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="valor_terreno" class="form-label">Valor Terreno</label>
                            <input-money id="valor_terreno" name="valor_terreno" :value="info.valor_terreno"></input-money>
                        </div>

                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="valor_construcao" class="form-label">Valor Construção</label>
                            <input-money id="valor_construcao" name="valor_construcao" :value="info.valor_construcao"></input-money>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <h6> Processo</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 mb-3">
                            <label for="prefixo_titulo" class="form-label">Prefixo do Título</label>
                            <input v-model="info.prefixo_titulo" type="text" name="prefixo_titulo" id="prefixo_titulo" class="form-control" :disabled="readOnly" />
                        </div>

                        <div class="col-lg-3 col-md-4 mb-3">
                            <label for="ano_titulo" class="form-label">Ano do Título</label>
                            <input v-model="info.ano_titulo" type="text" name="ano_titulo" id="ano_titulo" class="form-control" :disabled="readOnly" />
                        </div>

                        <div class="col-lg-3 col-md-4 mb-3">
                            <label for="numero_titulo" class="form-label">Número do Título</label>
                            <input v-model="info.numero_titulo" type="text" name="numero_titulo" id="numero_titulo" class="form-control" :disabled="readOnly" />
                        </div>

                        <div class="col-lg-3 col-md-6 mb-3">
                            <label for="numero_processo_administrativo" class="form-label">Nº Processo Administrativo</label>
                            <input v-model="info.numero_processo_administrativo" type="text" name="numero_processo_administrativo" id="numero_processo_administrativo" class="form-control" :disabled="readOnly" />
                        </div>
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
import { inject, onMounted, ref } from 'vue';

export default {
    setup(props, { emit }) {
        const events = inject('events');
        const info = ref({});
        const municipios = ref([]);
        const ready = ref(false);
        const acao = ref('/imovel/');
        const readOnly = ref(false);

        const loadData = async () => {
            try {
                const response = await axios.get(`${acao.value}${props.data.id}`);
                acao.value += props.data.id;
                info.value = response.data;
                readOnly.value = Boolean(props.data.readOnly);
            } catch (err) {
                emit('notification', {
                    type: 'error',
                    message: 'Não foi possível recuperar os dados do imóvel.'
                });
            }
            ready.value = true;
        }

        const carregarMunicipio = async() => {
            try {
                const response = await axios.get('https://servicodados.ibge.gov.br/api/v1/localidades/estados/51/municipios');
                municipios.value = response.data;
            } catch (error) {
                console.error('Erro ao carregar municípios:', error);
                events.emit('notification', {
                    type: 'error',
                    message: 'Erro ao carregar municípios.'
                });
            }
        }

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
            await carregarMunicipio();
            await loadData();
        });

        return {
            ready,
            municipios,
            info,
            acao,
            readOnly,
            close
        }
    },
    props: {
        data: {default: null, required: true},
    }
}
</script>
