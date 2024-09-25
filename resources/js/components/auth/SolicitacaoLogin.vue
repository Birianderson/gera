<template>
    <div class="container" v-if="ready">
        <div v-if="!solicitacaoExistente" class="row justify-content-center">
            <header class="login-header bg-logo rounded-3">
                <h1>Solicitação de Imóvel</h1>
                <h5>Verifique seu CPF para continuar</h5>
            </header>

            <!-- Explicação -->
            <section class="login-info">
                <p>Para prosseguir com a vinculação deste imóvel ao seu nome, precisamos verificar se você já possui uma
                    conta no sistema.</p>
                <p>Insira o seu CPF no campo a seguir. Caso você já tenha uma conta, será solicitado o login. Se não,
                    você poderá criar uma nova conta vinculada ao imóvel selecionado.</p>
            </section>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body ">
                        <form id="frm" name="frm" data-method="post" :action="formAction">
                            <form-error></form-error>
                            <input type="hidden" name="imovel_id" :value="props.data">
                            <input type="hidden" name="cpf" :value="cpf">
                            <!-- CPF/CNPJ Input Field -->
                            <div class="row mb-3">
                                <label for="cpf" class="col-md-4 col-form-label text-md-start"></label>
                                <div class="col-md-6 input-group">
                                    <input
                                        v-model="cpf"
                                        required
                                        type="text"
                                        name="cpf"
                                        id="cpf"
                                        v-mask="['###.###.###-##', '##.###.###/####-##']"
                                        class="form-control"
                                        :disabled="cpfSearched !== 0"
                                    />
                                    <button
                                        type="button"
                                        :disabled="!isCPFComplete || cpfSearched === 1 || cpfSearched === 2"
                                        :class="{
                      'btn btn-primary': cpfSearched === 0,
                      'btn btn-success': cpfSearched === 2,
                      'btn btn-warning': cpfSearched === 1
                    }"
                                        @click="cpfSearched ? enableLoginForm() : findCPF()"
                                    >
                                        <i
                                            class="fa"
                                            :class="{
                        'fa-search': cpfSearched === 0,
                        'fa-check': cpfSearched === 2,
                        'fa-warning': cpfSearched === 1
                      }"
                                        ></i>
                                        {{
                                            cpfSearched === 0 ? 'Buscar CPF' : cpfSearched === 2 ? 'Liberado' : 'Cadastrado'
                                        }}
                                    </button>
                                    <button
                                        v-if="cpfSearched === 1 || cpfSearched === 2"
                                        type="button"
                                        class="btn btn-secondary"
                                        @click="resetCPFForm"
                                    >
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Login Form (E-mail & Password) -->
                            <div v-if="cpfSearched === 1" class="row mb-3">
                                <p>Verificamos que há uma conta registrada com esse CPF, faça o Login</p>
                            </div>
                            <div v-if="cpfSearched === 1" class="row mb-3 ">
                                <label for="email" class="col-md-4 col-form-label text-md-start">E-mail:</label>
                                <div class="col-md-8">
                                    <input
                                        id="email"
                                        type="email"
                                        class="form-control"
                                        name="email"
                                        v-model="email"
                                        required
                                        autocomplete="email"
                                        autofocus
                                    />
                                </div>
                            </div>

                            <div v-if="cpfSearched === 1" class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-start">Senha:</label>
                                <div class="col-md-8">
                                    <input
                                        id="password"
                                        type="password"
                                        class="form-control"
                                        name="password"
                                        v-model="password"
                                        required
                                        autocomplete="current-password"
                                    />
                                </div>
                            </div>

                            <div v-if="cpfSearched === 1" class="row mb-3">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" v-model="remember"
                                               id="remember"/>
                                        <label class="form-check-label" for="remember"> Lembrar de mim</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Cadastro Form (Nome, E-mail & Senha) -->
                            <div v-if="cpfSearched === 2" class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-start">Nome:</label>
                                <div class="col-md-6">
                                    <input
                                        id="name"
                                        type="text"
                                        class="form-control"
                                        v-model="name"
                                        name="name"
                                        required
                                        autofocus
                                    />
                                </div>
                            </div>

                            <div v-if="cpfSearched === 2" class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-start">E-mail:</label>
                                <div class="col-md-6">
                                    <input
                                        id="email"
                                        type="email"
                                        name="email"
                                        class="form-control"
                                        v-model="email"
                                        required
                                        autocomplete="email"
                                    />
                                </div>
                            </div>

                            <div v-if="cpfSearched === 2" class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-start">Senha:</label>
                                <div class="col-md-6">
                                    <input
                                        id="password"
                                        type="password"
                                        name="password"
                                        class="form-control"
                                        v-model="password"
                                        required
                                        autocomplete="new-password"
                                    />
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button v-if="cpfSearched === 2" class="btn btn-primary" @click.prevent="submitForm('/vinculacao/cadastro')">Cadastrar</button>
                                    <button v-if="cpfSearched === 1" class="btn btn-primary" @click.prevent="submitForm('/vinculacao/loginSolicitacao')">Login</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Informações adicionais -->
            <section class="login-extra-info">
                <h3>Dúvidas Frequentes:</h3>
                <ul>
                    <li><strong>Por que preciso informar meu CPF?</strong> O CPF é usado para verificar sua identidade e
                        garantir que o imóvel será vinculado corretamente ao proprietário.
                    </li>
                    <li><strong>Não tenho uma conta, como faço?</strong> Após a verificação do CPF, você poderá criar
                        uma nova conta caso não tenha uma.
                    </li>
                    <li><strong>Já tenho uma conta, mas esqueci minha senha.</strong> Caso seu CPF já esteja vinculado,
                        você terá a opção de recuperar sua senha.
                    </li>
                </ul>
            </section>
        </div>
        <div v-else>
            <div class="bg-accent p-2 mb-5 rounded-3 text-white"><i class="fa fa-warning me-1"></i>Verificamos que já
                existe uma solicitação em andamento para esse imóvel, faça o login para acompanhar em tempo real
            </div>
            <form id="frm" name="frm" data-method="post" :action="formAction">
                <form-error></form-error>
                <div class="row mb-3 ">
                    <label for="email" class="col-md-4 col-form-label text-md-start">E-mail:</label>
                    <div class="col-md-8">
                        <input
                            id="email"
                            type="email"
                            class="form-control"
                            name="email"
                            v-model="email"
                            required
                            autocomplete="email"
                            autofocus
                        />
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="password" class="col-md-4 col-form-label text-md-start">Senha:</label>
                    <div class="col-md-8">
                        <input
                            id="password"
                            type="password"
                            class="form-control"
                            name="password"
                            v-model="password"
                            required
                            autocomplete="current-password"
                        />
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-8 offset-md-4">
                        <button class="btn btn-primary" @click.prevent="submitForm('/login')">Login</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import {ref, computed, onMounted} from 'vue';

export default {
    setup(props, {emit}) {
        const cpf = ref('');
        const name = ref('');
        const email = ref('');
        const password = ref('');
        const remember = ref(false);
        const cpfSearched = ref(0); // 0: not searched, 1: CPF found, 2: CPF not found (for cadastro)
        const ready = ref(false);
        const solicitacaoExistente = ref(false);
        const formAction = ref('/'); // Action for login or cadastro form

        const isCPFComplete = computed(() => cpf.value.length === 14 || cpf.value.length === 18);

        const findCPF = async () => {
            const cpfCleaned = cpf.value.replace(/\D/g, ''); // Remove formatting
            try {
                const response = await axios.get(`/vinculacao/verificaConta/${cpfCleaned}`);
                if (response.data === 1) {
                    cpfSearched.value = 1; // CPF found, show login form
                } else {
                    formAction.value = '/vinculacao/cadastro'
                    cpfSearched.value = 2; // CPF not found, show cadastro form
                }
            } catch (error) {
                console.error('Erro ao buscar CPF', error);
            }
        };

        const findImovel = async () => {
            try {
                const solicitacao = await axios.get(`/vinculacao/verificaImovel/${props.data}`);
                console.log(solicitacao)
                if (solicitacao.data === 1) {
                    solicitacaoExistente.value = true;
                    ready.value = true
                }
                ready.value = true
            } catch (error) {
                console.error('Erro ao buscar CPF', error);
            }
        };

        const resetCPFForm = () => {
            cpf.value = '';
            cpfSearched.value = 0;
        };

        const enableLoginForm = () => {
            cpfSearched.value = 0;
        };

        const submitForm = async (action) => {
            console.log(action)
            try {
                axios.post(action, {
                    cpf: cpf.value,
                    name: name.value,
                    email: email.value,
                    password: password.value,
                    imovel_id: props.data,
                    _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Incluindo o token CSRF
                })
                    .then(response => {
                        window.location.href = '/home'; // Redireciona após o login
                    })
                    .catch(error => {
                        console.error('Erro durante o login', error);
                    });

            } catch (error) {
                console.error('Erro ao enviar formulário', error);
            }
        };

        onMounted(async () => {
            await findImovel();

        });


        return {
            cpf,
            name,
            email,
            password,
            remember,
            cpfSearched,
            isCPFComplete,
            ready,
            formAction,
            submitForm,
            solicitacaoExistente,
            findCPF,
            resetCPFForm,
            enableLoginForm,
            props
        };
    },

    props: {
        data: {default: null, required: true},
    }
};
</script>
<style>
.login-container {
    max-width: 600px;
    margin: auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
}

.login-header {
    text-align: center;
    margin-bottom: 20px;
}

.login-info {
    font-size: 1rem;
    margin-bottom: 20px;
}

.login-extra-info h3 {
    margin-top: 30px;
    font-size: 1.2rem;
}

.login-extra-info ul {
    list-style: disc;
    padding-left: 20px;
}

.login-extra-info li {
    margin-bottom: 10px;
}
</style>
