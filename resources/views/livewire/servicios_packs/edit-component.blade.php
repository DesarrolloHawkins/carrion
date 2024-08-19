@section('head')
    @vite(['resources/sass/productos.scss'])
@endsection
<div class="container-fluid">
    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h4 class="page-title">EDJTAR PACK DE SERVICIO</span></h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-right">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Pack de Servicios</a></li>
                    <li class="breadcrumb-item active">Editar pack de servicio</li>
                </ol>
            </div>
        </div> <!-- end row -->
    </div>
    <!-- end page-title -->
    <div class="row">
        <div class="col-md-9">
            <div class="card m-b-30">
                <div class="card-body">
                    <form wire:submit.prevent="submit">
                        <input type="hidden" name="csrf-token" value="{{ csrf_token() }}">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <label for="nombre" class="col-sm-12 col-form-label">Nombre </label>
                                <div class="col-sm-11">
                                    <input type="text" wire:model="nombre" class="form-control" name="nombre"
                                        id="nombre" placeholder="Pack">
                                    @error('nombre')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-11">
                                <label for="servicio" class="col-sm-12 col-form-label">Servicio </label>
                                <div class="col-sm-11">
                                    <select class="w-100 input-group-text" name="servicio" id="servicio"
                                        wire:model="servicio">
                                        <option class="dropdown-item" value="">Servicio</option>
                                        @foreach ($servicios as $i => $servicio)
                                            <option class="dropdown-item" value="{{ $servicio->id }}">
                                                {{ $servicio->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <label for="servicio" class="col-sm-12 col-form-label">&nbsp;</label>
                                <button type="button" wire:click="addServ" class="btn btn-primary">Añadir</button>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12">
                                @if (count($serviciosPack) >= 1)
                                    <h5> Servicios añadidos </h5>
                                @endif
                                <div class="col-sm-11">
                                    <ul class="list-style">
                                        @foreach ($serviciosPack as $key => $servicio)
                                            <li for="servicio.{{ $key }}" class="row">
                                                <div class="col-sm-2">{{ $key + 1 }} -
                                                    {{ $servicio['nombre'] }}
                                                </div>
                                                <div class="col-sm-1">&nbsp;</div>
                                                <div class="col-sm-2 me-auto">
                                                    <button type="button" wire:click="removeServ({{ $servicio->id }})"
                                                        class="btn btn-outline-danger">Eliminar</button>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card m-b-30">
                <div class="card-body">
                    <h5>Acciones</h5>
                    <div class="row">
                        <div class="col-12">
                            <button class="w-100 btn btn-success mb-2" id="alertaGuardar">Guardar pack de
                                servicios</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@section('scripts')
    <script>
        $("#alertaGuardar").on("click", () => {
            Swal.fire({
                title: '¿Estás seguro?',
                icon: 'info',
                showConfirmButton: true,
                showCancelButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('update');
                }
            });
        });
        $.datepicker.regional['es'] = {
            closeText: 'Cerrar',
            prevText: '< Ant',
            nextText: 'Sig >',
            currentText: 'Hoy',
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre',
                'Octubre', 'Noviembre', 'Diciembre'
            ],
            monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
            dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
            weekHeader: 'Sm',
            dateFormat: 'dd/mm/yy',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
        };
        $.datepicker.setDefaults($.datepicker.regional['es']);
        document.addEventListener('livewire:load', function() {


        })
        $(document).ready(function() {
            console.log('select2')
            $("#datepicker").datepicker();

            $("#datepicker").on('change', function(e) {
                @this.set('fecha_nac', $('#datepicker').val());
            });

        });

        function togglePasswordVisibility() {
            var passwordInput = document.getElementById("password");
            var eyeIcon = document.getElementById("eye-icon");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.className = "fas fa-eye-slash";
            } else {
                passwordInput.type = "password";
                eyeIcon.className = "fas fa-eye";
            }
        }
    </script>
@endsection
