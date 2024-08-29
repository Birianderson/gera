<nav class="menu open-current-submenu">
    <ul>
        <li class="menu-item @if(str_contains(request()->route()->getName(), 'home')) active @endif">
            <a href="{{route('home')}}">
                  <span class="menu-icon">
                    <i class="fa fa-chart-pie"></i>
                  </span>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="menu-item @if(str_contains(request()->route()->getName(), 'pessoa')) active @endif">
            <a href="{{route('pessoa.index')}}">
                  <span class="menu-icon">
                    <i class="fa fa-people-group"></i>
                  </span>
                <span class="menu-title">Pessoas</span>
            </a>
        </li>
        <li class="menu-item @if(str_contains(request()->route()->getName(), 'imovel')) active @endif">
            <a href="{{route('imovel.index')}}">
                  <span class="menu-icon">
                    <i class="fa fa-home"></i>
                  </span>
                <span class="menu-title">Imóveis</span>
            </a>
        </li>
        <li class="menu-item @if(str_contains(request()->route()->getName(), 'mapa')) active @endif">
            <a href="{{route('mapa.index')}}">
                  <span class="menu-icon">
                    <i class="fa fa-map-location-dot"></i>
                  </span>
                <span class="menu-title">Mapa</span>
            </a>
        </li>
        <li class="menu-item @if(str_contains(request()->route()->getName(), 'upload')) active @endif">
            <a href="{{route('upload.index')}}">
                  <span class="menu-icon">
                    <i class="fa fa-upload"></i>
                  </span>
                <span class="menu-title">Upload</span>
            </a>
        </li>
        <li class="menu-item sub-menu @if(str_contains(request()->route()->getName(), 'competencia') || str_contains(request()->route()->getName(), 'usuario')) open @endif">
            <a href="#">
                  <span class="menu-icon">
                    <i class="fa fa-gear"></i>
                  </span>
                <span class="menu-title">Administração</span>
            </a>
            <div class="sub-menu-list ">
                <ul>
                    <li class="menu-item @if(str_contains(request()->route()->getName(), 'minha_conta')) active @endif">
                        <a href="#">
                  <span class="menu-icon">
                    <i class="fa fa-person"></i>
                  </span>
                            <span class="menu-title">Minha Conta</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

    </ul>
</nav>
