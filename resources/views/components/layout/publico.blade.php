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
            {{$slot}}
        </div>
        <main class="content">
        </main>
    </div>
</div>
</body>

</html>
