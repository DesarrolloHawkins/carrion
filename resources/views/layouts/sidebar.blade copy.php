<style>
    .crmName {
        font-size: 130%;
    }
    /* @media (max-width: 768px) {
        .sidebar {
            display: flex;
            flex-direction: column;
        }
    } */
/*
    #sidebar{
        background-color: red;
        transition: 0.5s ease;
        transform: translateX(-250px);
    }
    input{
        -webkit-appearance: none;
        visibility: hidden;
        display: none;
    }
    span{
        position: absolute;
        right: -40px;
        top: 30px;
        font-size: 25px;
        border-radius: 3px;
        color: #fff;
        padding: 3px 8px;
        cursor: pointer;
        background: #000;
    }
    #bars{
        background: red;
    }
    #check:checked ~ #sidebar{
        transform: translateX(0);
    }

    #check:checked ~ #sidebar #bars{
        display: none;
    }
    .sidebar-content{

    } */
</style>


<input type="checkbox" id="check">
<nav id="sidebar" class="sidebar-wrapper">
    <label for="check">
        <span class="fas fa-times" id="times"></span>
        <span class="fas fa-bars" id="bars"></span>
    </label>
    <div class="sidebar-content">
        @if(isset($empresa))
        <div class="sidebar-brand">
            <a href="#" class="crmName">CRM {{$empresa->name}}</a>
            <div id="close-sidebar">
            </div>
        </div>
        @else
        <div class="sidebar-brand">
            <a href="#" class="crmName">CRM Formal S.L.</a>
            <div id="close-sidebar">
            </div>
        </div>
        @endif
        @if(isset($empresa))

        <img src="/assets/{{$empresa->photo}}" alt="Logo" title="" class="img-fluid" style="max-width: 200px; padding: 0 1.2rem;"></a>

        @else
        <img src="{{ asset('images/logo_formal_fondo_negro.png') }}" class="img-fluid" alt="Logo">
        @endif

        <div class="sidebar-header">
            <div class="user-pic">
                <p style="color: white">User Image</p>
                {{-- <img class="img-fluid" src="{{ asset('images/logo_formal_fondo_negro.png') }}"
                    alt="User picture"> --}}

            </div>
            <div class="user-info">
                {{-- Si hay un usuario logeado, se pasa al sidebar --}}
                @if ($user)
                    <span class="user-name">{{ $user->name }}
                        <strong>{{ $user->surname }}</strong>
                    </span>
                    <span class="user-role">{{ $user->role }}</span>
                    <span class="user-status">
                        <i class="fa fa-circle"></i>
                        <span>Online</span>
                    </span>
                @endif

            </div>
        </div>

        <!-- sidebar-header  -->
        <div class="sidebar-search">
            <div>
                <div class="input-group">
                    <input type="text" class="form-control search-menu" placeholder="Search...">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <!-- sidebar-search  -->
        <div class="sidebar-menu">
            <ul>
                <li class="header-menu">
                    <span>General</span>
                </li>
                <li class="">
                    <a href="/admin/alumnos">
                        <i class="fa-solid fa-user"></i>
                        <span>Alumnos</span>
                    </a>
                </li>
                <li class="">
                    <a href="/admin/empresas">
                        <i class="fas fa-building"></i>
                        <span>Empresas</span>
                    </a>
                </li>
                <li class="">
                    <a href="/admin/cursos">
                        <i class="fas fa-book"></i>
                        <span>Cursos</span>
                    </a>
                </li>
                <li class="">
                    <a href="/admin/presupuestos">
                        <i class="fas fa-hand-holding-usd"></i>
                        <span>Presupuestos</span>
                    </a>
                </li>
                <li class="">
                    <a href="/admin/facturas">
                        <i class="fas fa-file-invoice"></i>
                        <span>Facturas</span>
                    </a>
                </li>
                <li class="">
                    <a href="/admin/usuarios">
                        <i class="fa-solid fa-users"></i>
                        <span>Usuarios</span>
                    </a>
                </li>
                <li class="">
                    <a href="/../home">
                        <i class="fa fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                {{-- <li class="">
                    <a href="/admin/factura">
                        <i class="fa fa-shopping-cart"></i>
                        <span>Facturas</span>
                    </a>
                </li>
                <li class="">
                    <a href="/admin/clients">
                        <i class="fa-solid fa-user"></i>
                        <span>Clientes</span>
                    </a>
                </li>
                <li class="">
                    <a href="/admin/productos">
                        <i class="fa-solid fa-user"></i>
                        <span>Productos</span>
                    </a>
                </li>
                <li class="">
                    <a href="/admin/productos-categories">
                        <i class="fa-solid fa-user"></i>
                        <span>Productos Categorias</span>
                    </a>
                </li>
                <li class="">
                    <a href="/admin/iva">
                        <i class="fa-solid fa-user"></i>
                        <span>Tipos de Iva</span>
                    </a>
                </li>

                <li class="">
                    <a href="{{route('settings.index')}}">
                        <i class="fa fa-tachometer-alt"></i>
                        <span>Settings</span>
                    </a>
                </li> --}}


            </ul>
        </div>
        <!-- sidebar-menu  -->
    </div>
    <!-- sidebar-content  -->
    <div class="sidebar-footer">
        <a href="#">
            <i class="fa fa-bell"></i>
            <span class="badge badge-pill badge-warning notification">3</span>
        </a>
        <a href="#">
            <i class="fa fa-envelope"></i>
            <span class="badge badge-pill badge-success notification">7</span>
        </a>
        <a href="#">
            <i class="fa fa-cog"></i>
            <span class="badge-sonar"></span>
        </a>
        <a href="#">
            <i class="fa fa-power-off"></i>
        </a>
    </div>
</nav>
<!-- sidebar-wrapper  -->
