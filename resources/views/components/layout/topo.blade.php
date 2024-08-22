<header class="header">
    <a id="btn-collapse" href="#">
        <i class="fa fa-list-squares text-black"></i>
    </a>
    <a id="btn-toggle" href="#" class="sidebar-toggler break-point-lg">
        <i class="fa fa-list-squares text-black"></i>
    </a>
    <div class="container-fluid">
        <div class="row align-items-center">

            <div class="col">
                <div class="d-flex justify-content-end align-items-center">
                    <div class="noti position-relative mt-1 me-5">
                        <i class="fa fa-bell float-end" style="font-size: 1.5rem; color: rgb(255, 255, 255);">
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger mt-1"
                          style="font-size: 0.8rem;">8
                    </span>
                        </i>
                    </div>
                    <div class="foto me-2">
                        @if(isset($usuario['avatar']))
                            <img src="{{$usuario['avatar']}}" alt="Foto do usuário" class="rounded float-end d-sm-block" style="width: 40px; height: 40px;">
                        @else
                            <div class="d-flex justify-content-center align-items-center rounded float-end" style="width: 40px; height: 40px; background-color: #ccc;">
                                <i class="fas fa-user" style="font-size: 18px; color: white;" aria-label="Ícone de perfil de usuário"></i>
                            </div>
                        @endif
                    </div>
                    <div class="nome me-5 d-flex align-items-center">
                        @if(isset($usuario['nome']))
                        <p class="p-0 m-0">{{$usuario['nome_social'] ?? $usuario['nome_entidade']}}</p>
                        @endif
                    </div>
                    <div class="sair">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
