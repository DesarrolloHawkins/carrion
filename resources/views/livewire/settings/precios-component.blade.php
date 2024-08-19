<div>
    <div class="container-fluid">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h4 class="page-title">Gestión de Precios de Pistas</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Precios</li>
                    </ol>
                </div>
            </div>
        </div>

        
        <div class="row">
            <div class="col-md-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h5 class="card-title">Precios</h5>
                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#addEditPrecioModal" wire:click="resetForm">Añadir Precio</button>

                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Pista</th>
                                    <th>Regla</th>
                                    <th>Duración</th>
                                    <th>¿Es temporal?</th>
                                    <th>Nombre temporal</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Hora Inicio</th>
                                    <th>Hora Fin</th>
                                    <th>Precio</th>
                                    <th>Lunes</th>
                                    <th>Martes</th>
                                    <th>Miércoles</th>
                                    <th>Jueves</th>
                                    <th>Viernes</th>
                                    <th>Sábado</th>
                                    <th>Domingo</th>


                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($precios as $precio)
                                    <tr>
                                        <td>{{ $precio->pista->nombre ?? 'No disponible' }}</td>
                                        <td>{{ $precio->regla }}</td>
                                        <td>{{ $precio->duracion }}</td>
                                        <td>{{ $precio->temporal ? 'Sí' : 'No' }}</td>
                                        <td>{{ $precio->nombre_temporal }}</td>
                                        <td>{{ $precio->fecha_inicio }}</td>
                                        <td>{{ $precio->fecha_fin }}</td>
                                        <td>{{ $precio->hora_inicio }}</td>
                                        <td>{{ $precio->hora_fin }}</td>
                                        <td>{{ $precio->precio }}</td>
                                        <td>{{ $precio->lunes ? 'Sí' : 'No' }}</td>
                                        <td>{{ $precio->martes ? 'Sí' : 'No' }}</td>
                                        <td>{{ $precio->miercoles ? 'Sí' : 'No' }}</td>
                                        <td>{{ $precio->jueves ? 'Sí' : 'No' }}</td>
                                        <td>{{ $precio->viernes ? 'Sí' : 'No' }}</td>
                                        <td>{{ $precio->sabado ? 'Sí' : 'No' }}</td>
                                        <td>{{ $precio->domingo ? 'Sí' : 'No' }}</td>


                                        <td>
                                            <button class="btn btn-warning btn-sm" wire:click="edit({{ $precio->id }})" data-toggle="modal" data-target="#addEditPrecioModal">Editar</button>
                                            <button class="btn btn-danger btn-sm" wire:click="delete({{ $precio->id }})">Eliminar</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para Añadir/Editar Precio -->
        <div class="modal fade" id="addEditPrecioModal" tabindex="-1" role="dialog" aria-labelledby="addEditPrecioModalLabel" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEditPrecioModalLabel">{{ $editMode ? 'Editar Precio' : 'Añadir Precio' }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="">
                            <div class="form-group">
                                <label for="pista_id">Pista</label>
                                <select class="form-control" id="pista_id" wire:model="pista_id">
                                    <option value="">-- Seleccione una pista --</option>
                                    @foreach($pistas as $pista)
                                        <option value="{{ $pista->id }}">{{ $pista->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('pista_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="regla">Regla</label>
                                <select  class="form-control" wire:model="regla">
                                    <option value="">-- Seleccione una regla --</option>
                                    <option value="Flexible">Flexible</option>
                                    <option value="Fija">Fija</option>
                                </select>
                                @error('regla') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="duracion">Duración (minutos)</label>
                                <select  class="form-control" wire:model="duracion">
                                    <option value="">-- Seleccione una duración --</option>
                                    <option value="60">60 min</option>
                                    <option value="90">90 min</option>
                                    <option value="120">120 min</option>
                                </select>
                                @error('duracion') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="precio">Precio (€)</label>
                                <input type="number" class="form-control" id="precio" wire:model="precio">
                                @error('precio') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="hora_inicio">Hora de Inicio</label>
                                <input type="time" class="form-control" id="hora_inicio" wire:model="hora_inicio">
                                @error('hora_inicio') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="hora_fin">Hora de Fin</label>
                                <input type="time" class="form-control" id="hora_fin" wire:model="hora_fin">
                                @error('hora_fin') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group d-flex gap-2 flex-wrap">
                                <div class="d-flex flex-column justify-content-center">
                                    <input type="checkbox" wire:model="lunes">
                                    <label for="lunes">Lunes</label>
                                </div>
                                <div class="d-flex flex-column justify-content-center">
                                    <input type="checkbox" wire:model="martes">
                                    <label for="martes">Martes</label>
                                </div>
                                <div class="d-flex flex-column justify-content-center">
                                    <input type="checkbox" wire:model="miercoles">
                                    <label for="miercoles">Miércoles</label>
                                </div>
                                <div class="d-flex flex-column justify-content-center">
                                    <input type="checkbox" wire:model="jueves">
                                    <label for="jueves">Jueves</label>
                                </div>
                                <div class="d-flex flex-column justify-content-center">
                                    <input type="checkbox" wire:model="viernes">
                                    <label for="viernes">Viernes</label>
                                </div>
                                <div class="d-flex flex-column justify-content-center">
                                    <input type="checkbox" wire:model="sabado">
                                    <label for="sabado">Sábado</label>
                                </div>
                                <div class="d-flex flex-column justify-content-center">
                                    <input type="checkbox" wire:model="domingo">
                                    <label for="domingo">Domingo</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="temporal">Temporal</label>
                                <select class="form-control" id="temporal" wire:model="temporal">
                                    <option value="0">No</option>
                                    <option value="1">Sí</option>
                                </select>
                                @error('temporal') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            @if($temporal)
                                <div class="form-group">
                                    <label for="nombre_temporal">Nombre Temporal</label>
                                    <input type="text" class="form-control" id="nombre_temporal" wire:model="nombre_temporal">
                                    @error('nombre_temporal') <span class="text-danger">{{ $message }}</span> @enderror
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
                            @endif
                            <button type="submit" class="btn btn-primary" wire:click="submit" @if($editMode) data-dismiss="modal"  @endif>{{ $editMode ? 'Actualizar Precio' : 'Añadir Precio' }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>
            document.addEventListener('livewire:load', function () {
                $('#addEditPrecioModal').on('hide.bs.modal', function () {
                    Livewire.emit('resetForm');
                });
            });
        </script>
    @endsection
</div>
