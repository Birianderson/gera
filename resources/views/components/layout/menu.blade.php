<nav class="menu open-current-submenu">
    <ul>
        @if(Auth::user()->permission=='admin')
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
                    <i class="fa fa-file-import"></i>
                  </span>
                    <span class="menu-title">Importar</span>
                </a>
            </li>
            <li class="menu-item @if(str_contains(request()->route()->getName(), 'solicitacao')) active @endif">
                <a href="{{route('solicitacao.index')}}">
                  <span class="menu-icon">
                    <i class="fa fa-comment"></i>
                  </span>
                    <span class="menu-title">Solicitações</span>
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
                    <ul>
                        <li class="menu-item @if(str_contains(request()->route()->getName(), 'cidade loteamento')) active @endif">
                            <a href="{{route(('cidade.index'))}}">
                  <span class="menu-icon">
                    <i class="fa fa-location-pin-lock"></i>
                  </span>
                                <span class="menu-title">Localidades</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif
        @if(Auth::user()->permission!=='admin')
            <li class="menu-item @if(str_contains(request()->route()->getName(), 'home')) active @endif">
                <a href="{{route('home')}}">
                  <span class="menu-icon">
                    <i class="fa fa-chart-pie"></i>
                  </span>
                    <span class="menu-title">Home</span>
                </a>
            </li>
            <li class="menu-item @if(str_contains(request()->route()->getName(), 'imovel')) active @endif">
                <a href="{{route('user.imovel.index')}}">
                  <span class="menu-icon">
                    <i class="fa fa-home"></i>
                  </span>
                    <span class="menu-title">Meus Imóveis</span>
                </a>
            </li>
            <li class="menu-item @if(str_contains(request()->route()->getName(), 'solicitacao')) active @endif">
                <a href="{{route('user.solicitacao.index')}}">
                  <span class="menu-icon">
                    <i class="fa fa-comment"></i>
                  </span>
                    <span class="menu-title">Minhas Solicitações</span>
                </a>
            </li>
            <li class="menu-item @if(str_contains(request()->route()->getName(), 'minha_conta')) active @endif">
                <a href="{{route('user.pessoa.index')}}">
                  <span class="menu-icon">
                    <i class="fa fa-person"></i>
                  </span>
                    <span class="menu-title">Minha Conta</span>
                </a>
            </li>
        @endif
    </ul>
</nav>
