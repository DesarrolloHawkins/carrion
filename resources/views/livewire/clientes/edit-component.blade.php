
{{-- {{ var_dump($eventoServicios) }} --}}
<div class="container-fluid">
    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h4 class="page-title">CLIENTE <span style="text-transform: uppercase">{{$nombre}}</span></h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-right">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Clientes</a></li>
                    <li class="breadcrumb-item active">Cliente {{$nombre}}</li>
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
                        <input type="hidden" name="id" value="{{ csrf_token() }}">

                        <div class="form-group row">
                            <div class="col-sm-12">
                                <h5 class="ms-3" style="border-bottom: 1px gray solid !important; padding-bottom: 10px !important;">Datos del cliente</h5>
                            </div>
                            <div class="col-sm-4">
                                <label for="example-text-input" class="col-sm-12 col-form-label">Nombre</label>
                                <div class="col-sm-12">
                                    <input type="text" wire:model="nombre" class="form-control" name="nombre"
                                        id="nombre" aria-label="Nombre" placeholder="Nombre">
                                    @error('nombre')
                                        <span class="text-danger">{{ $message }}</span>
                                        <style>
                                            .nombre {
                                                color: red;
                                            }
                                        </style>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label for="example-text-input" class="col-sm-12 col-form-label">Apellidos</label>
                                <div class="col-sm-10">
                                    <input type="text" wire:model="apellido" class="form-control" name="apellido"
                                        id="apellido" placeholder="Apellidos">
                                    @error('apellido')
                                        <span class="text-danger">{{ $message }}</span>

                                        <style>
                                            .apellido {
                                                color: red;
                                            }
                                        </style>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label for="example-text-input" class="col-sm-12 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="text" wire:model="email1" class="form-control" name="email"
                                        id="email" placeholder="email">
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>

                                        <style>
                                            .email {
                                                color: red;
                                            }
                                        </style>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label for="example-text-input" class="col-sm-12 col-form-label">Telefono</label>
                                <div class="col-sm-10">
                                    <input type="text" wire:model="tlf1" class="form-control" name="Telefono"
                                        id="Telefono" placeholder="Telefono">
                                    @error('Telefono')
                                        <span class="text-danger">{{ $message }}</span>

                                        <style>
                                            .Telefono {
                                                color: red;
                                            }
                                        </style>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label for="example-text-input" class="col-sm-12 col-form-label">Genero</label>
                                <div class="col-sm-10">
                                    <input type="text" wire:model="genero" class="form-control" name="Genero"
                                        id="Genero" placeholder="Genero">
                                    @error('Genero')
                                        <span class="text-danger">{{ $message }}</span>

                                        <style>
                                            .Genero {
                                                color: red;
                                            }
                                        </style>
                                    @enderror
                                </div>
                            </div>
                            

                            <div class="col-sm-4">
                                <label for="example-text-input" class="col-sm-12 col-form-label">Fecha nacimiento</label>
                                <div class="col-sm-10">
                                    <input type="date" wire:model="fecha_nacimiento" class="form-control" name="fecha_nacimiento"
                                        id="fecha_nacimiento" placeholder="Fecha de nacimiento">
                                    @error('fecha_nacimiento')
                                        <span class="text-danger">{{ $message }}</span>

                                        <style>
                                            .fecha_nacimiento {
                                                color: red;
                                            }
                                        </style>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label for="example-text-input" class="col-sm-12 col-form-label">País</label>
                                <div class="col-sm-10">
                                    <input type="text" wire:model="pais" class="form-control" name="pais"
                                        id="pais" placeholder="País">
                                    @error('pais')
                                        <span class="text-danger">{{ $message }}</span>

                                        <style>
                                            .pais {
                                                color: red;
                                            }
                                        </style>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label for="example-text-input" class="col-sm-12 col-form-label">Ciudad</label>
                                <div class="col-sm-10">
                                    <input type="text" wire:model="ciudad" class="form-control" name="ciudad"
                                        id="ciudad" placeholder="Ciudad">
                                    @error('ciudad')
                                        <span class="text-danger">{{ $message }}</span>

                                        <style>
                                            .ciudad {
                                                color: red;
                                            }
                                        </style>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label for="example-text-input" class="col-sm-12 col-form-label">Categoria</label>
                                <div class="col-sm-10">
                                    <input type="number" wire:model="categoria_id" class="form-control" name="categoria_id"
                                        id="categoria_id" placeholder="categoría">
                                    @error('categoria_id')
                                        <span class="text-danger">{{ $message }}</span>

                                        <style>
                                            .categoria_id {
                                                color: red;
                                            }
                                        </style>
                                    @enderror
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
                            <button class="w-100 btn btn-success mb-2" id="alertaGuardar">Guardar
                                Cliente</button>
                        </div>
                        <div class="col-12">
                            <button class="w-100 btn btn-danger mb-2" id="alertaEliminar">Eliminar
                                Cliente</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    @section('scripts')
    <script src="../assets/js/jquery.slimscroll.js"></script>
    <script>
        $("#alertaGuardar").on("click", () => {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Pulsa el botón de confirmar para cambiar los datos del cliente.',
                icon: 'warning',
                showConfirmButton: true,
                showCancelButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('update');
                }
            });
        });
        $("#alertaEliminar").on("click", () => {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Pulsa el botón de confirmar para eliminar los datos del cliente. Esto es irreversible.',
                icon: 'error',
                showConfirmButton: true,
                showCancelButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('destroy');
                }
            });
        });
    </script>
    @endsection

