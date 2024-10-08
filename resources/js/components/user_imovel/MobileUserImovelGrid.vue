<template>
    <div v-if="loading" class="text-center">
        <p>Carregando imóveis...</p>
    </div>

    <div v-else>
        <div v-for="(imoveis, cidade) in groupedImoveis" :key="cidade" class="cidade-group mb-4">
            <h3 class="text-primary">{{ cidade }}</h3>
            <div class="list-group">
                <a
                    v-for="imovel in imoveis"
                    :key="imovel.id"
                    :href="`/imovel/${imovel.id}`"
                    class="list-group-item list-group-item-action clickable-card"
                >
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fa fa-map-marker-alt"></i>
                            Loteamento: {{ imovel.loteamento_nome }}
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <div>
                            <i class="fa fa-th-large"></i>
                            Quadra: {{ imovel.quadra }}
                        </div>
                        <div>
                            <i class="fa fa-border-all"></i>
                            Lote: {{ imovel.lote }}
                        </div>
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
        const imoveis = ref([]);
        const loading = ref(true);

        // Função para agrupar os imóveis por cidade
        const groupedImoveis = computed(() => {
            return imoveis.value.reduce((acc, imovel) => {
                const { cidade_nome } = imovel;
                if (!acc[cidade_nome]) {
                    acc[cidade_nome] = [];
                }
                acc[cidade_nome].push(imovel);
                return acc;
            }, {});
        });

        // Função para carregar imóveis da API
        const loadImoveis = async () => {
            try {
                const response = await axios.get('/user/imovel/listMobile');
                imoveis.value = response.data.data; // Ajustar conforme a estrutura da resposta
            } catch (error) {
                console.error('Erro ao carregar imóveis:', error);
            } finally {
                loading.value = false;
            }
        };

        // Chamar a função loadImoveis quando o componente for montado
        onMounted(() => {
            loadImoveis();
        });

        return {
            imoveis,
            loading,
            groupedImoveis,
        };
    },
};
</script>

<style scoped>
.cidade-group {
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
    background-color: #2275D4;
    color: #FFFFFF;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

a {
    color: #007bff;
}

a:hover {
    background-color: #2275D4;
    text-decoration: #FFFFFF;
    text-decoration: none;
}

i {
    margin-right: 5px;
}
</style>
