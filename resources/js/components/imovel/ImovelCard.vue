<template>
    <div v-if="imovel" >
        <div class="card-body row">
            <!-- Coluna das informações do Imóvel -->
            <div class="col-md-6">
                <div class="border rounded-3">
                    <div class="card-header text-white  rounded-top-2" style="background-color: #2E5FB4" >
                        <h6>Imóvel</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>ID Imóvel:</strong> {{ imovel.id }}</p>
                        <p><strong>Quadra:</strong> {{ imovel.quadra }}</p>
                        <p><strong>Lote:</strong> {{ imovel.lote }}</p>
                        <p><strong>Medida Frente:</strong> {{ imovel.medida_frente }}</p>
                        <p><strong>Medida Fundo:</strong> {{ imovel.medida_frente }}</p>
                        <p><strong>Medida Lado Esquerdo:</strong> {{ imovel.medida_esquerda }}</p>
                        <p><strong>Medida Lado Direito:</strong> {{ imovel.medida_direita }}</p>
                        <p><strong>Confinante Frente:</strong> {{ imovel.confinante_frente }}</p>
                        <p><strong>Confinante Fundo:</strong> {{ imovel.confinante_fundo }}</p>
                        <p><strong>Confinante Lado Esquerdo:</strong> {{ imovel.confinante_esquerda }}</p>
                        <p><strong>Confinante Lado Direito:</strong> {{ imovel.confinante_direita }}</p>
                    </div>
                </div>
            </div>

            <!-- Coluna das informações do Morador -->
            <div v-if="imovel.pessoa" class="col-md-6">
                <div class="border rounded-3">
                    <div class="card-header text-white  rounded-top-2" style="background-color: #2E5FB4" >
                        <h6>Morador</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Nome:</strong> {{ imovel.pessoa.nome }}</p>
                        <p><strong>CPF:</strong> {{ imovel.pessoa.cpf }}</p>
                        <p><strong>Telefone:</strong> {{ imovel.pessoa.telefone }}</p>
                    </div>
                </div>
                <div v-if="imovel.pessoa.conjuge" class="border rounded-3 mt-5">
                    <div class="card-header text-white  rounded-top-2" style="background-color: #2E5FB4" >
                        <h6>Conjuge</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Nome:</strong> {{ imovel.pessoa.conjuge.nome }}</p>
                        <p><strong>CPF:</strong> {{ imovel.pessoa.conjuge.cpf }}</p>
                        <p><strong>Telefone:</strong> {{ imovel.pessoa.conjuge.telefone }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-else class="alert alert-warning">
        <p>Imóvel não encontrado</p>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';

export default {
    props: {
        data: {
            required: true
        }
    },
    setup(props) {
        const imovel = ref(null);

        const fetchImovel = async () => {
            try {
                const response = await axios.get(`/admin/imovel/${props.data.imovel_id}`);
                imovel.value = response.data;
                console.log(imovel.value,'imovelValue')
            } catch (error) {
                console.error('Erro ao buscar imóvel:', error);
            }
        };

        onMounted(() => {
            console.log(props.data.imovel_id)
            fetchImovel();
        });

        return {
            imovel
        };
    }
};
</script>

<style scoped>
.card {
    margin: 20px;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.card-header {
    background-color: #f5f5f5;
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

.card-body {
    padding: 20px;
}
</style>
