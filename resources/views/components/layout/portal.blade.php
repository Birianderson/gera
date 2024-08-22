<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portal Transparência</title>
    <link rel="icon" type="image/x-icon" href="imagens/ico.png">
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>

<body>

<div id="app" class="layout has-sidebar fixed-sidebar fixed-header">

    <div class="container layout">
        <main class="content">
            <div id="main-container" class="container-fluid painel">
                {{$slot}}
            </div>
        </main>

        <footer>
            <div class="content">
                © 2024, Tribunal de Contas de Mato Grosso
            </div>
        </footer>


        <div class="overlay"></div>
    </div>
</div>

<!-- JS -->


</body>

</html>
