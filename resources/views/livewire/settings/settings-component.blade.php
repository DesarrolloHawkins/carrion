<div>
    <div class="container-fluid">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h4 class="page-title">OPCIONES DEL CRM</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Opciones</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h5>Configuración</h5>
                        <a href="{{ route('settings.pista') }}" class="btn btn-primary mb-2">Pista</a>
                        <a href="{{ route('settings.jugador') }}" class="btn btn-primary mb-2">Jugadores</a>
                        <a href="{{ route('settings.socios') }}" class="btn btn-primary mb-2">Socios</a>
                        <a href="{{ route('settings.festivos') }}" class="btn btn-primary mb-2">Festivos</a>
                        <a href="{{ route('settings.precios') }}" class="btn btn-primary mb-2">Precios</a>
                        <a href="{{ route('settings.club') }}" class="btn btn-primary mb-2">Club</a>

                        {{-- <a href="{{ route('settings.pista') }}" class="btn btn-primary mb-2">Tipos de Pista</a> --}}

                        <!-- Añadir más opciones aquí -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>