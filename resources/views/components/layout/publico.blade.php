<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Geração</title>
    @vite(['resources/sass/publico.scss', 'resources/js/app.js'])
    <style>
        .section-bg {
            position: relative;
            min-height: 500px;
            background-image: url('{{ Vite::asset('public/imagens/fundo.jpg') }}');
            background-size: cover;
            background-position: center 60%; /* Mover a imagem um pouco para baixo */
            background-repeat: no-repeat;
            &::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.65); /* Fundo preto com 50% de opacidade */
                z-index: 1;
            }

            /* Certificar que o conteúdo fique acima da sobreposição */
            .container {
                position: relative;
                z-index: 2;
                text-align: center;
            }
        }
    </style>
</head>

<body>

<div id="app" class="layout has-sidebar fixed-sidebar fixed-header">
    <div class="layout">
        <div id="main-container" class="painel">
            {{$slot}}
        </div>
        <main class="content">
        </main>
    </div>
</div>
</body>

</html>
