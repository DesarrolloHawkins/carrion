<!-- ========== Left Sidebar Start ========== -->
<div class="left side-menu">
    <div class="slimscroll-menu" id="remove-scroll">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu" id="side-menu">
                <!-- General Section -->
                <li class="menu-title">General</li>
                
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('home') }}" class="waves-effect">
                        <i class="icon-accelerator"></i>
                        <span> Dashboard </span>
                    </a>
                </li>
                
                <!-- Clientes -->
                <li>
                    <a href="{{ route('clientes.index') }}" class="waves-effect">
                        <i class="fas fa-user-tie"></i>
                        <span> Clientes </span>
                    </a>
                </li>

                <!-- Caja -->
                <li>
                    <a href="{{ route('caja.index') }}" class="waves-effect">
                        <i class="icon-calendar"></i>
                        <span> Caja </span>
                    </a>
                </li>

                <!-- Ingresos -->
                <li>
                    <a href="{{ route('ingresos.index') }}" class="waves-effect">
                        <i class="icon-calendar"></i>
                        <span> Ingresos </span>
                    </a>
                </li>

                <!-- Gastos -->
                <li>
                    <a href="{{ route('gastos.index') }}" class="waves-effect">
                        <i class="icon-calendar"></i>
                        <span> Gastos </span>
                    </a>
                </li>

                <!-- Proveedores -->
                <li>
                    <a href="{{ route('proveedores.index') }}" class="waves-effect">
                        <i class="fas fa-warehouse"></i>
                        <span> Proveedores </span>
                    </a>
                </li>

                <!-- Deudas -->
                <li>
                    <a href="{{ route('deudas.index') }}" class="waves-effect">
                        <i class="fas fa-money-check-alt"></i>
                        <span> Deudas </span>
                    </a>
                </li>
                <!-- Empresa -->
                <li>
                    <a href="{{ route('empresas.index') }}" class="waves-effect">
                        <i class="fas fa-money-check-alt"></i>
                        <span> Empresa </span>
                    </a>
                </li>

                <!-- Usuarios -->
                <li>
                    <a href="javascript:void(0);" class="waves-effect">
                        <i class="fas fa-user"></i>
                        <span> Usuarios 
                            <span class="float-right menu-arrow">
                                <i class="mdi mdi-chevron-right"></i>
                            </span> 
                        </span>
                    </a>
                    <ul class="submenu">
                        <li><a href="/admin/usuarios">Ver Todos</a></li>
                        <li><a href="/admin/usuarios-create">Crear Usuario</a></li>
                    </ul>
                </li>

            </ul>
        </div>
        <!-- Sidebar End -->
        
        <div class="clearfix"></div>

    </div>
    <!-- Sidebar Left End -->
</div>
<!-- Left Sidebar End -->

<style>
    /* Customize scrollbar appearance */
    .slimscroll-menu {
        height: 100%;
        overflow: hidden;
    }
    
    .slimscroll-menu:hover {
        overflow: auto;
    }
    
    /* Customize icons and hover effects */
    #sidebar-menu ul li a {
        color: #6c757d;
    }

    #sidebar-menu ul li a:hover {
        color: #343a40;
        background-color: #f8f9fa;
    }

    .menu-arrow i {
        transition: transform 0.3s;
    }

    /* Menu arrow rotation on open */
    .metismenu li.active > a .menu-arrow i {
        transform: rotate(90deg);
    }
</style>
