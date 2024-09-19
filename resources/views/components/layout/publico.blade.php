<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Geração</title>
    @vite(['resources/sass/publico.scss', 'resources/js/app.js'])
</head>

<body>

<div id="app" class="layout has-sidebar fixed-sidebar fixed-header">
    <div class="layout">
        <div id="main-container" class="painel">
            <!-- Cabeçalho da Página -->
            <header class="navbar navbar-expand-lg navbar-dark bg-light">
                <div class="container">
                    <!-- Logotipo -->
                    <a class="navbar-brand" href="#">
                        <img src="/imagens/logotipo.png" alt="Logotipo" class="img-fluid" style="max-height: 50px;">
                    </a>
                    <button class="navbar-toggler bg-accent" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
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

            {{$slot}}
        </div>
        <main class="content">
        </main>
    </div>
</div>
</body>

</html>
