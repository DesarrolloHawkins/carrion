<div>
    <div class="container-fluid">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h4 class="page-title">OPCIONES DE FESTIVOS</h4>
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
            <div class="col-md-10">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h5 class="card-title">Festivos</h5>
                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#addVentajaModal" wire:click="resetForm">Añadir</button>

                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Fecha de inicio</th>
                                    <th>Fecha de fin</th>
                                    <th>Cierre del club</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($membresiasVentajas as $ventaja)
                                    <tr>
                                        <td>{{ $ventaja->nombre ?? 'No disponible' }}</td>
                                        <td>{{ $ventaja->fecha_inicio ?? 'No disponible' }}</td>
                                        <td>{{ $ventaja->fecha_fin }}</td>
                                        <td>{{ $ventaja->cierre ? 'Sí' : 'No' }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" wire:click="edit({{ $ventaja->id }})" data-toggle="modal" data-target="#addVentajaModal">Editar</button>
                                            <button class="btn btn-sm btn-danger" wire:click="delete({{ $ventaja->id }})">Eliminar</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para Añadir/Editar Ventaja -->
        <div class="modal fade" id="addVentajaModal" tabindex="-1" role="dialog" aria-labelledby="addVentajaModalLabel" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addVentajaModalLabel">{{ $editMode ? 'Editar Festivo' : 'Añadir Festivo' }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" id="nombre" wire:model="nombre">
                                @error('nombre') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="fecha_inicio">Fecha de inicio</label>
                                <input type="date" class="form-control" id="fecha_inicio" wire:model="fecha_inicio">
                                @error('fecha_inicio') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="fecha_fin">Fecha de fin</label>
                                <input type="date" class="form-control" id="fecha_fin" wire:model="fecha_fin">
                                @error('fecha_fin') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="cierre">Cierre del club</label>
                                <select class="form-control" id="cierre" wire:model="cierre">
                                    <option value="0">No</option>
                                    <option value="1">Sí</option>
                                </select>
                                @error('cierre') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <button type="submit" wire:click="submit" class="btn btn-primary" @if($editMode) data-dismiss="modal" @endif>{{ $editMode ? 'Actualizar' : 'Añadir' }} Festivo</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>
            document.addEventListener('livewire:load', function () {
                $('#addVentajaModal').on('hide.bs.modal', function () {
                    Livewire.emit('resetForm');
                });
            });
        </script>
    @endsection
</div>
