<div class="container-fluid">
    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h4 class="page-title">SOCIOS</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-right">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Socios</a></li>
                    <li class="breadcrumb-item active">Todos los socios</li>
                </ol>
            </div>
        </div> <!-- end row -->
    </div>
    <!-- end page-title -->
    <div class="row">
        <div class="col-12 d-flex justify-content-end px-5">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" wire:click="resetFields">Añadir Socio</button>
        </div>
    </div>

    <!-- Modal Socio -->
    <div wire:ignore.self class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ $socio_id ? 'Editar Socio' : 'Nuevo Socio' }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="">
                        <div class="form-group">
                            <label for="cliente_id">Cliente</label>
                            <select class="form-control" id="cliente_id" wire:model="cliente_id">
                                <option value="">Seleccione un cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                @endforeach
                            </select>
                            @error('cliente_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="membresia_id">Membresía</label>
                            <select class="form-control" id="membresia_id" wire:model="membresia_id">
                                <option value="">Seleccione una membresía</option>
                                @foreach($membresias as $membresia)
                                    <option value="{{ $membresia->id }}">{{ $membresia->nombre }}</option>
                                @endforeach
                            </select>
                            @error('membresia_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="tarjeta">Tarjeta</label>
                            <input type="text" class="form-control" id="tarjeta" wire:model="tarjeta" placeholder="Tarjeta">
                            @error('tarjeta') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="estado">Estado</label>
                            <select class="form-control" id="estado" wire:model="estado">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                            @error('estado') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="btn btn-primary" wire:click="submit" @if($socio_id) data-dismiss="modal"  @endif>{{ $socio_id ? 'Actualizar' : 'Guardar' }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cuotas -->
    <div wire:ignore.self class="modal fade" id="cuotasModal" tabindex="-1" role="dialog" aria-labelledby="cuotasModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cuotasModalLabel">{{ $cuota_id ? 'Editar Cuota' : 'Añadir Cuota' }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="">
                        <div class="form-group">
                            <label for="socio_id">Socio</label>
                            <select class="form-control" id="socio_id" wire:model="socio_id">
                                <option value="">Seleccione un socio</option>
                                @foreach($socios as $socio)
                                    <option value="{{ $socio->id }}">{{ $socio->cliente->nombre }}</option>
                                @endforeach
                            </select>
                            @error('socio_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="fecha_inicio">Fecha de Inicio</label>
                            <input type="date" class="form-control" id="fecha_inicio" wire:model="fecha_inicio">
                            @error('fecha_inicio') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="fecha_fin">Fecha de Fin</label>
                            <input type="date" class="form-control" id="fecha_fin" wire:model="fecha_fin">
                            @error('fecha_fin') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="precio">Precio</label>
                            <input type="number" class="form-control" id="precio" wire:model="precio" placeholder="Precio">
                            @error('precio') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="pagado">Pagado</label>
                            <select class="form-control" id="pagado" wire:model="pagado">
                                <option value="1">Sí</option>
                                <option value="0">No</option>
                            </select>
                            @error('pagado') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        @if ($pagado)
                            <div class="form-group">
                                <label for="fecha_pago">Fecha de Pago</label>
                                <input type="date" class="form-control" id="fecha_pago" wire:model="fecha_pago">
                                @error('fecha_pago') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="metodo_pago">Método de Pago</label>
                                <input type="text" class="form-control" id="metodo_pago" wire:model="metodo_pago" placeholder="Método de Pago">
                                @error('metodo_pago') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        @endif
                        <button type="submit" class="btn btn-primary" wire:click="submitCuota" @if($cuota_id) data-dismiss="modal"  @endif>{{ $cuota_id ? 'Actualizar Cuota' : 'Añadir Cuota' }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Ver Cuotas -->
    <div wire:ignore.self class="modal fade" id="viewCuotasModal" tabindex="-1" role="dialog" aria-labelledby="viewCuotasModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewCuotasModalLabel">Ver Cuotas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Fecha de Inicio</th>
                                <th>Fecha de Fin</th>
                                <th>Precio</th>
                                <th>Pagado</th>
                                <th>Fecha de Pago</th>
                                <th>Método de Pago</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cuotas as $cuota)
                                <tr>
                                    <td>{{ $cuota->fecha_inicio }}</td>
                                    <td>{{ $cuota->fecha_fin }}</td>
                                    <td>{{ $cuota->precio }}</td>
                                    <td>{{ $cuota->pagado ? 'Sí' : 'No' }}</td>
                                    <td>{{ $cuota->fecha_pago ?? 'N/A' }}</td>
                                    <td>{{ $cuota->metodo_pago ?? 'N/A' }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" data-dismiss="modal" wire:click="editCuota({{ $cuota->id }})" data-toggle="modal" data-target="#cuotasModal">Editar</button>
                                        <button class="btn btn-sm btn-danger" wire:click="confirmDeleteCuota({{ $cuota->id }})">Eliminar</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    @if (count($socios) > 0)
                        <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Apellidos</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    <th>Tipo de Membresia</th>
                                    <th>Tarjeta</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($socios as $socio)
                                    <tr>
                                        <td>{{ $socio->cliente->nombre }}</td>
                                        <td>{{ $socio->cliente->apellido }}</td>
                                        <td>{{ $socio->cliente->tlf1 }}</td>
                                        <td>{{ $socio->cliente->email1 }}</td>
                                        <td>{{ $socio->membresia->nombre }}</td>
                                        <td>{{ $socio->tarjeta }}</td>
                                        <td>{{ $socio->estado ? 'Activo' : 'Inactivo' }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" wire:click="edit({{ $socio->id }})" data-toggle="modal" data-target="#exampleModal">Editar</button>
                                            <button class="btn btn-sm btn-danger" wire:click="confirmDelete({{ $socio->id }})">Eliminar</button>
                                            <button class="btn btn-sm btn-info" wire:click="openCuotasModal({{ $socio->id }})" data-toggle="modal" data-target="#cuotasModal">Añadir Cuota</button>
                                            @if($socio->cuotas)
                                                <button class="btn btn-sm btn-primary" wire:click="viewCuotas({{ $socio->id }})" data-toggle="modal" data-target="#viewCuotasModal">Ver Cuotas</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
</div>

@section('scripts')
<script>
    document.addEventListener('livewire:load', function () {
        window.livewire.on('close-modal', () => {
            $('#exampleModal').modal('hide');
        });
        window.livewire.on('open-modal', () => {
            $('#exampleModal').modal('show');
        });
        window.livewire.on('close-cuotas-modal', () => {
            $('#cuotasModal').modal('hide');
        });
        window.livewire.on('open-cuotas-modal', () => {
            $('#cuotasModal').modal('show');
        });
        window.livewire.on('open-view-cuotas-modal', () => {
            $('#viewCuotasModal').modal('show');
        });
    });
</script>
@endsection
