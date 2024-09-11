<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Geração</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>

<div id="app" class="layout has-sidebar fixed-sidebar fixed-header">
    <loading></loading>
    <popup></popup>
    <confirmation-popup></confirmation-popup>
    <x-layout.notification></x-layout.notification>
    <aside id="sidebar" class="sidebar break-point-lg">
        <div class="sidebar-layout">
            <div class="sidebar-header">
                <div class="logo_1 "  id="logo1">
                    <img src="{{asset('imagens/logotipo.png')}}" width="240px" alt="Logo GPE">
                </div>
                <div class="logo_2 d-none"  id="logo2">
                    <img src="{{asset('imagens/ico.png')}}" width="50px" alt="Ícone GPE ">
                </div>
            </div>
            <div class="sidebar-content">
                <x-layout.menu/>
            </div>
            <div class="sidebar-footer">
                <div class="logo_tce d-flex justify-content-center">

                </div>
            </div>
        </div>
    </aside>
    <div class="layout">
        <x-layout.topo/>
        <main class="content">
            <div id="main-container" class="container-fluid painel">
                {{$slot}}
            </div>
        </main>

        <footer>
            <div class="content">
                © 2024, SibiriSystem
            </div>
        </footer>


        <div class="overlay"></div>
    </div>
</div>

<!-- JS -->


</body>

</html>
