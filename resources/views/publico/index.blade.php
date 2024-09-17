<x-layout.publico>

    <!-- Cabeçalho da Página -->
    <header class="navbar navbar-expand-lg navbar-dark bg-light">
        <div class="container">
            <!-- Logotipo -->
            <a class="navbar-brand" href="#">
                <img src="imagens/logotipo.png" alt="Logotipo" class="img-fluid" style="max-height: 50px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse text-black" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item ">
                        <a class="nav-link active text-accent border-end" href="#">HOME</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-black border-end" href="#quem-somos">QUEM SOMOS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-black border-end" href="#servicos">SERVIÇOS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-black border-end" href="#equipe">EQUIPE</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-black border-end" href="#contato">CONTATO</a>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <section class="section-spacing section-bg row ">
        <div class="container text-start py-5 col-6 ">
            <h1 class="display-4 text-white" style="text-decoration-style: solid; text-shadow: 2px 2px 8px rgba(255, 255, 255, 0.3);">
                REGULARIZAÇÃO <span class="text-accent">FUNDIÁRIA</span>
            </h1>
            <p class="lead text-white">Especialistas em Consultoria e realização de Projetos para Regularização
                Fundiária oferecendo as melhores soluções para todas as etapas técnicas de REURB-S ou REURB-E</p>
            <button class="btn btn-success">
                <i class="fa fa-phone"></i> Fale conosco
            </button>
        </div>
    </section>

    <!-- Seção de Benefícios -->
    <section class="section-spacing benefits-section">
        <div class="container text-center">
            <div class="row g-5"> <!-- Adicionado g-4 para espaçamento entre colunas -->
                <div class="col-12 col-md-6 col-lg-3 ">
                    <div class="benefit-item">
                        <i class="fas fa-map-marked-alt icon-benefit"></i>
                        <h3><span class="highlight-number">+300</span> HECTARES</h3>
                        <p class="benefit-text">Mapeados</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3 ">
                    <div class="benefit-item">
                        <i class="fas fa-people-group icon-benefit"></i>
                        <h3><span class="highlight-number">+3.000</span> FAMÍLIAS</h3>
                        <p class="benefit-text">Cadastradas</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="benefit-item">
                        <i class="fas fa-home icon-benefit"></i>
                        <h3><span class="highlight-number">+20.000</span> LOTES</h3>
                        <p class="benefit-text">Projetados</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="benefit-item">
                        <i class="fas fa-history icon-benefit"></i>
                        <h3><span class="highlight-number">+10</span> ANOS</h3>
                        <p class="benefit-text">De História</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-light section-spacing">
        <div class="container bg-white rounded-3 shadow p-4">
            <h2 class="section-heading">NOSSO PROCESSO AUTOMATIZADO É O MELHOR</h2>
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="lead text-dark mb-5">Nosso sistema de regularização fundiária é completamente automatizado, tornando o processo mais
                        rápido e eficiente, evitando burocracia e atrasos.</div>
                    <ul class="list-unstyled text-list-unstyled">
                        <li><i class="fa fa-check-circle me-2"></i> Menos tempo gasto com papelada</li>
                        <li><i class="fa fa-check-circle  me-2"></i> Acompanhamento de cada fase online
                        </li>
                        <li><i class="fas fa-check-circle me-2"></i> Integração com cartórios e prefeituras
                        </li>
                        <li><i class="fas fa-check-circle  me-2"></i> Transparência total do processo</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <!-- Slot para a imagem -->
                    <img src="imagens/segurando_celular.jpg" class="container" alt="Imagem do processo automatizado">
                </div>
            </div>
        </div>
    </section>


    <!-- Seção de Benefícios -->
    <section class="bg-light  section-spacing">
        <div class="container">
            <div class="row text-center mt-4">
                <div class="col-md-4">
                    <div class="icon-card">
                        <i class="fas fa-home"></i>
                        <h4 class="my-3">Segurança Jurídica</h4>
                        <p>Ao regularizar o seu imóvel, você garante a segurança jurídica da sua propriedade, evitando
                            disputas e garantindo direitos.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="icon-card">
                        <i class="fas fa-chart-line"></i>
                        <h4 class="my-3">Valorização Imobiliária</h4>
                        <p>Imóveis regularizados tendem a valorizar no mercado, facilitando vendas, financiamentos e
                            heranças.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="icon-card">
                        <i class="fas fa-gavel"></i>
                        <h4 class="my-3">Cumprimento da Lei</h4>
                        <p>Regularizar o imóvel significa estar em conformidade com a lei, evitando
                            multas e problemas judiciais.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Seção de Aplicativo e Monitoramento -->
    <section class="bg-light section-spacing ">
        <div class="container ">
            <h2 class="section-heading ">POR QUE ESCOLHER A GERAÇÃO?</h2>
            <div class="row mt-4">
                <div class="col-md-6">
                    <img src="imagens/mulher_feliz.jpg" class="img-fluid" alt="Vantagens Competitivas">
                </div>
                <div class="col-md-6">
                    <p>Nossa equipe é formada por especialistas com anos de experiência no setor, utilizando tecnologia
                        de ponta para garantir que seu imóvel esteja totalmente regularizado com eficiência.</p>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-trophy  me-2"></i> Atendimento personalizado</li>
                        <li><i class="fas fa-clock  me-2"></i> Rapidez na conclusão dos processos</li>
                        <li><i class="fas fa-handshake  me-2"></i> Parceiros estratégicos em todo o país
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- Seção de Contato -->
    <section class=" section-spacing border-top">
        <div class="container text-center">
            <p>Para saber mais sobre nossos serviços ou iniciar o processo de regularização fundiária, entre em contato
                conosco.</p>
            <a href="mailto:contato@geracao.com" class="btn btn-primary"><i class="fas fa-envelope"></i>
                contato@geracao.com</a>
            <p class="mt-3">Ou ligue para: <strong>(65) 1234-5678</strong></p>
        </div>
    </section>

    <!-- Rodapé -->
    <footer class="bg-dark text-white text-center py-4">
        <div class="container">
            <p class="mb-0">© 2024 Geração. Todos os direitos reservados.</p>
        </div>
    </footer>

</x-layout.publico>
