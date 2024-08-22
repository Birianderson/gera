<nav class="menu open-current-submenu">
    <ul>
        <li class="menu-item">
            <a href="#">
                  <span class="menu-icon">
                    <i class="fa fa-home"></i>
                  </span>
                <span class="menu-title">Home</span>
            </a>
        </li>
        <li class="menu-item @if(str_contains(request()->route()->getName(), 'minha_conta')) active @endif">
            <a href="#">
                  <span class="menu-icon">
                    <i class="fa fa-person"></i>
                  </span>
                <span class="menu-title">Minha Conta</span>

            </a>
        </li>
        <li class="menu-item @if(str_contains(request()->route()->getName(), 'entidade_info')) active @endif">
            <a href="#">
                  <span class="menu-icon">
                    <i class="fa fa-building"></i>
                  </span>
                <span class="menu-title">Dados da Entidade</span>
            </a>
        </li>
        <li class="menu-item @if(str_contains(request()->route()->getName(), 'unidades_atendimento')) active @endif">
            <a href="#">
                  <span class="menu-icon">
                    <i class="fa fa-headset"></i>
                  </span>
                <span class="menu-title">Unidades de Atendimento</span>

            </a>
        </li>
        <li class="menu-item @if(str_contains(request()->route()->getName(), 'assunto')) active @endif">
            <a href="#">
                  <span class="menu-icon">
                    <i class="fa fa-list"></i>
                  </span>
                <span class="menu-title">Assuntos</span>

            </a>
        </li>
        <li class="menu-item @if(str_contains(request()->route()->getName(), 'lista_publicacao')) active @endif">
            <a href="#">
                  <span class="menu-icon">
                    <i class="fa fa-sticky-note"></i>
                  </span>
                <span class="menu-title">Publicações</span>

            </a>
        </li>
        <li class="menu-item @if(str_contains(request()->route()->getName(), 'solicitacao')) active @endif">
            <a href="#">
                  <span class="menu-icon">
                    <i class="fa fa-calendar-check"></i>
                  </span>
                <span class="menu-title">Solicitações</span>

            </a>
        </li>

        <li class="menu-item">
            <a href="#">
                  <span class="menu-icon">
                    <i class="fa fa-mortar-pestle"></i>
                  </span>
                <span class="menu-title">Escola Transparência</span>

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
                    <li class="menu-item">
                        <a href="#">
                            <span class="menu-title">Entidades</span>
                        </a>
                    </li>
                    <li class="menu-item @if(str_contains(request()->route()->getName(), 'usuario')) active @endif" >
                        <a href="#">
                            <span class="menu-title">Usuários</span>
                        </a>
                    </li>
                    <li class="menu-item @if(str_contains(request()->route()->getName(), 'competencia')) active @endif" >
                        <a href="#">
                            <span class="menu-title">Competência</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

    </ul>
</nav>
