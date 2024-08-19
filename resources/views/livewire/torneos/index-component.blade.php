<div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2>Lista de Torneos</h2>
                <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#torneoModal" wire:click="resetFields">Crear Torneo</button>
                @if (session()->has('message'))
                    <div class="alert alert-success">{{ session('message') }}</div>
                @endif
            </div>
        </div>

        <!-- Tabla de Torneos -->
        <div class="row">
            <div class="col-12">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Cartel</th>
                            <th>Torneo</th>
                            <th>Inscritos</th>
                            <th>Fecha Cierre</th>
                            <th>Fecha Inicio</th>
                            <th>Circuito</th>
                            <th>Inscripciones</th>
                            <th>Estado</th>
                            <th>Visible</th>
                            <th>Notificado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($torneos as $torneo)
                            <tr >
                                <td onclick="window.location='{{ route('torneos.edit', $torneo->id) }}'">
                                    @if ($torneo->imagen)
                                        <img src="{{ asset('storage/' . $torneo->imagen) }}" alt="Cartel" width="100">
                                    @endif
                                </td>
                                <td onclick="window.location='{{ route('torneos.edit', $torneo->id) }}'">{{ $torneo->nombre }}</td>
                                <td onclick="window.location='{{ route('torneos.edit', $torneo->id) }}'">{{ $this->getNumInscripciones($torneo->id) ?? 'N/A' }}</td>
                                <td onclick="window.location='{{ route('torneos.edit', $torneo->id) }}'">{{ $torneo->fecha_cierre ?? 'N/A' }}</td>
                                <td onclick="window.location='{{ route('torneos.edit', $torneo->id) }}'">{{ $torneo->fecha_inicio ?? 'N/A' }}</td>
                                <td onclick="window.location='{{ route('torneos.edit', $torneo->id) }}'"> {{ $torneo->circuito ?? 'N/A' }}</td>
                                <td onclick="window.location='{{ route('torneos.edit', $torneo->id) }}'">{{ 'N/A' }}</td>
                                <td onclick="window.location='{{ route('torneos.edit', $torneo->id) }}'">{{ $torneo->estado ?? 'N/A' }}</td>
                                <td onclick="window.location='{{ route('torneos.edit', $torneo->id) }}'">{{ $torneo->visible ? 'Sí' : 'No' }}</td>
                                <td onclick="window.location='{{ route('torneos.edit', $torneo->id) }}'">{{ $torneo->notificado ? 'Sí' : 'No' }}</td>
                                <td>
                                    <button class="btn btn-danger btn-sm" wire:click="deleteTorneo({{ $torneo->id }})">Eliminar</button>
                                    <!-- Agrega aquí más acciones si es necesario -->
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="torneoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Crear Torneo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="store" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="inscripcion">Tipo Inscripción</label>
                            <select class="form-control" id="inscripcion" wire:model="inscripcion">
                                <option value="individual">Individual</option>
                                <option value="doble">Doble</option>
                            </select>
                            @error('inscripcion') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre" wire:model="nombre">
                            @error('nombre') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea class="form-control" id="descripcion" wire:model="descripcion"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="imagen">Imagen (Cartel)</label>
                            <input type="file" class="form-control" id="imagen" wire:model="imagen">
                            @error('imagen') <span class="text-danger">{{ $message }}</span> @enderror
                            @if ($imagen)
                                <img src="{{ $imagen->temporaryUrl() }}" width="100" class="mt-2">
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="normativa">Normativa (PDF)</label>
                            <input type="file" class="form-control" id="normativa" wire:model="normativa">
                            @error('normativa') <span class="text-danger">{{ $message }}</span> @enderror
                           
                        </div>
                        <div class="form-group">
                            <label for="precio">Precio</label>
                            <input type="number" class="form-control" id="precio" wire:model="precio">
                        </div>
                        <div class="form-group">
                            <label for="precio_socio">Precio Socio</label>
                            <input type="number" class="form-control" id="precio_socio" wire:model="precio_socio">
                        </div>
                        <div class="form-group">
                            <label for="precio_pronto_pago">Precio Pronto Pago</label>
                            <input type="number" class="form-control" id="precio_pronto_pago" wire:model="precio_pronto_pago">
                        </div>
                        <div class="form-group">
                            <label for="precio_socio_pronto_pago">Precio Socio Pronto Pago</label>
                            <input type="number" class="form-control" id="precio_socio_pronto_pago" wire:model="precio_socio_pronto_pago">
                        </div>
                        <div class="form-group">
                            <label for="condiciones">Condiciones</label>
                            <textarea class="form-control" id="condiciones" wire:model="condiciones"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
