<div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2>Lista de Pistas</h2>
                <button class="btn btn-primary" data-toggle="modal" data-target="#pistaModal" wire:click="resetFields">Crear Pista</button>
                <table class="table mt-3">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Deporte</th>
                            <th>Tipo</th>
                            <th>Características</th>
                            <th>Tamaño</th>
                            <th>Online</th>
                            <th>Disponible</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pistas as $pista)
                            <tr>
                                <td>{{ $pista->nombre }}</td>
                                <td>{{ $pista->deporteRelacion->nombre ?? 'N/A' }}</td>
                                <td>{{ $pista->tipoRelacion->nombre ?? 'N/A' }}</td>
                                <td>{{ $pista->caracteristicaRelacion->nombre ?? 'N/A' }}</td>
                                <td>{{ $pista->tamanoRelacion->nombre ?? 'N/A' }}</td>
                                <td>{{ $pista->online ? 'Sí' : 'No' }}</td>
                                <td>{{ $pista->disponible ? 'Sí' : 'No' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#pistaModal" wire:click="edit({{ $pista->id }})">Editar</button>
                                    <button class="btn btn-sm btn-danger" wire:click="confirmDelete({{ $pista->id }})">Eliminar</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="pistaModal" tabindex="-1" role="dialog" aria-labelledby="pistaModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pistaModalLabel">{{ $pista_id ? 'Editar Pista' : 'Crear Pista' }}</h5>
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
                            <label for="deporte_id">Deporte</label>
                            <select class="form-control" id="deporte_id" wire:model="deporte_id">
                                <option value="">Seleccione un deporte</option>
                                @foreach($deportes as $deporte)
                                    <option value="{{ $deporte->id }}">{{ $deporte->nombre }}</option>
                                @endforeach
                            </select>
                            @error('deporte_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="tipo_id">Tipo</label>
                            <select class="form-control" id="tipo_id" wire:model="tipo_id">
                                <option value="">Seleccione un tipo</option>
                                @foreach($tiposPista as $tipo)
                                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                @endforeach
                            </select>
                            @error('tipo_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="caracteristica_id">Características</label>
                            <select class="form-control" id="caracteristica_id" wire:model="caracteristica_id">
                                <option value="">Seleccione una característica</option>
                                @foreach($caracteristicasPista as $caracteristica)
                                    <option value="{{ $caracteristica->id }}">{{ $caracteristica->nombre }}</option>
                                @endforeach
                            </select>
                            @error('caracteristica_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="tamano_id">Tamaño</label>
                            <select class="form-control" id="tamano_id" wire:model="tamano_id">
                                <option value="">Seleccione un tamaño</option>
                                @foreach($tamanosPista as $tamano)
                                    <option value="{{ $tamano->id }}">{{ $tamano->nombre }}</option>
                                @endforeach
                            </select>
                            @error('tamano_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="online">Online</label>
                            <select class="form-control" id="online" wire:model="online">
                                <option value="">Seleccione una opción</option>
                                <option value="1">Sí</option>
                                <option value="0">No</option>
                            </select>
                            @error('online') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="disponible">Disponible</label>
                            <select class="form-control" id="disponible" wire:model="disponible">
                                <option value="">Seleccione una opción</option>
                                <option value="1">Sí</option>
                                <option value="0">No</option>
                            </select>
                            @error('disponible') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="btn btn-primary" wire:click="submit" @if($pista_id) data-dismiss="modal" @endif>{{ $pista_id ? 'Actualizar' : 'Guardar' }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('livewire:load', function () {
        window.livewire.on('close-modal', () => {
            $('#pistaModal').modal('hide');
        });
        window.livewire.on('open-modal', () => {
            $('#pistaModal').modal('show');
        });
        window.livewire.on('show-delete-confirmation', () => {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete();
                    Swal.fire(
                        '¡Eliminado!',
                        'La pista ha sido eliminada.',
                        'success'
                    )
                }
            });
        });
        window.livewire.on('pista-deleted', () => {
            Swal.fire(
                '¡Eliminado!',
                'La pista ha sido eliminada.',
                'success'
            );
        });
    });
</script>
@endsection
