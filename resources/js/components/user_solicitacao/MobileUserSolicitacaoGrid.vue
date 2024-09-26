<template>
    <div v-if="loading" class="text-center">
        <p>Carregando solicitações...</p>
    </div>

    <div v-else>
        <div v-for="(solicitacoes, municipio) in groupedSolicitacoes" :key="municipio" class="municipio-group mb-4">
            <h3 class="text-primary">{{ municipio }}</h3>
            <div class="list-group">
                <a
                    v-for="solicitacao in solicitacoes"
                    :key="solicitacao.id"
                    :href="getHref(solicitacao)"
                    class="list-group-item list-group-item-action clickable-card"
                >
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fa fa-map-marker-alt"></i>
                            Loteamento: {{ solicitacao.imovel.loteamento.nome }}
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <div>
                            <i class="fa fa-th-large"></i>
                            Quadra: {{ solicitacao.imovel.quadra }}
                        </div>
                        <div>
                            <i class="fa fa-border-all"></i>
                            Lote: {{ solicitacao.imovel.lote }}
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <span :class="statusClass(solicitacao.status)" class="badge">
                            <span v-if="solicitacao.status === 'P'">
                                <i class="fa fa-warning"></i>
                            </span>
                            <span v-if="solicitacao.status === 'E'">
                                <i class="fa fa-hourglass-start"></i>
                            </span>
                            {{ statusLabel(solicitacao.status) }}
                        </span>
                    </div>

                </a>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';

export default {
    setup() {
        const solicitacoes = ref([]);
        const loading = ref(true);

        // Função para agrupar as solicitações por município
        const groupedSolicitacoes = computed(() => {
            return solicitacoes.value.reduce((acc, solicitacao) => {
                const municipio = solicitacao.imovel.loteamento.cidade.nome; // Acesso à cidade através do loteamento
                if (!acc[municipio]) {
                    acc[municipio] = [];
                }
                acc[municipio].push(solicitacao);
                return acc;
            }, {});
        });

        const getHref = (solicitacao) => {
            if (solicitacao.status === 'P') {
                return `/user/mapa/solicitacao_mapa/${solicitacao.imovel_id}`;
            } else if (solicitacao.status === 'E') {
                return `/user/mensagem_solicitacao/solicitacao/${solicitacao.id}`;
            } else {
                return `/user/mapa/solicitacao_concluida/${solicitacao.imovel_id}`;
            }
        };
        // Função para carregar solicitações da API
        const loadSolicitacoes = async () => {
            try {
                const response = await axios.get('/user/solicitacoes/list');
                solicitacoes.value = response.data.data; // Ajustar conforme a estrutura da resposta
            } catch (error) {
                console.error('Erro ao carregar solicitações:', error);
            } finally {
                loading.value = false;
            }
        };

        // Função para retornar a label do status
        const statusLabel = (status) => {
            return status === 'P' ? 'Verificar Localização' : status === 'E' ? 'Em Andamento' : 'Indefinido';
        };

        // Função para retornar a classe do status
        const statusClass = (status) => {
            return status === 'P' ? 'bg-warning' : status === 'E' ? 'bg-info' : 'bg-success';
        };

        // Chamar a função loadSolicitacoes quando o componente for montado
        onMounted(() => {
            loadSolicitacoes();
        });

        return {
            solicitacoes,
            loading,
            groupedSolicitacoes,
            statusLabel,
            statusClass,
            getHref,
        };
    },
};
</script>

<style scoped>
.municipio-group {
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

.clickable-card {
    display: block;
    padding: 15px;
    margin-bottom: 10px;
    border: 1px solid #ddd;
    border-radius: 8px;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
    text-decoration: none;
    color: inherit;
}

.clickable-card:hover {
    background-color: #f8f9fa;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.badge {
    padding: 0.5em 0.75em;
    border-radius: 1em;
    font-size: 0.875em;
}

i {
    margin-right: 5px;
}
</style>
