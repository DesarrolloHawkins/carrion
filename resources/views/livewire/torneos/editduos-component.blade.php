<div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2>Editar Duo</h2>
            </div>

            <style>
                .body {
                    padding: 20px;
                }
                .form-group {
                    border-radius: 5px;
                    background: #fff;
                }
                .header {
                    border-radius: 5px 5px 0 0;
                    font-size: 18px;
                    font-weight: bold;
                    background: #f1f1f1;
                    padding:  5px 20px;
                }
                .table th, .table td {
                    text-align: center;
                }
                .table .selected {
                    background-color: #ffc107;
                }
            </style>

            <form wire:submit.prevent="update">
                <div class="form-group">
                    <div class="header">
                        <label for="Jugadores">Jugadores</label>
                    </div>
                    <div class="body">
                        <p><strong>Jugador 1:</strong> {{ $jugador1->nombre }} {{ $jugador1->apellido }} 
                            <button type="button" data-toggle="modal" data-target="#changeJugadorModal" class="btn btn-success" wire:click="showChangeJugadorModal(1)">Cambiar</button>
                        </p>
                        <p><strong>Jugador 2:</strong> @if($jugador2){{ $jugador2->nombre }} {{ $jugador2->apellido }} @endif 
                            @if($jugador2)
                                <button type="button" data-toggle="modal" data-target="#changeJugadorModal" class="btn btn-success" wire:click="showChangeJugadorModal(2)">Cambiar</button>
                            @else
                                <button type="button" data-toggle="modal" data-target="#inscripcionModal" class="btn btn-success">Agregar</button>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="form-group">
                    <div class="header">
                        <label for="Jugadores">Comentarios</label>
                    </div>
                    <div class="body">
                        <p><strong>Jugador 1:</strong> 
                            <br>
                            <textarea class="form-control" wire:model.lazy="comentario1">{{ $comentario1 }}</textarea>
                        </p>
                        @if($jugador2)
                            <p><strong>Jugador 2:</strong> 
                                <br>
                                <textarea class="form-control" wire:model.lazy="comentario2">{{ $comentario2 }}</textarea>
                            </p>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <div class="header">
                        <label for="Jugadores">Categoría</label>
                    </div>
                    <div class="body col-2">
                        <select class="form-control" id="categoriaSeleccionada" wire:model="categoriaSeleccionada">
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}">{{ $categoria->categoria->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="header">
                        <label for="Jugadores">Disponibilidad</label>
                    </div>
                    <div class="body col-12">
                        <!-- Pestañas para los días -->
                        <ul class="nav nav-tabs" id="disponibilidadTabs" role="tablist">
                            @foreach($diasDelTorneo as $index => $dia)
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link @if($index === $activeTab) active @endif" id="tab-{{ $index }}" data-toggle="tab" href="#dia-{{ $index }}" role="tab" aria-controls="dia-{{ $index }}" aria-selected="@if($index === $activeTab) true @else false @endif" wire:click.prevent="setActiveTab({{ $index }})">
                                        {{ \Carbon\Carbon::parse($dia->dia)->format('d/m/Y') }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                        <!-- Contenido de las pestañas -->
                        <div class="tab-content" id="disponibilidadContent">
                            @foreach($diasDelTorneo as $index => $dia)
                                <div class="tab-pane fade @if($index === $activeTab) show active @endif" id="dia-{{ $index }}" role="tabpanel" aria-labelledby="tab-{{ $index }}">
                                    <table class="table mt-2">
                                        <thead>
                                            <tr>
                                                @for ($time = strtotime($dia->hora_inicio); $time < strtotime($dia->hora_fin); $time = strtotime('+30 minutes', $time))
                                                    <th>{{ date('H:i', $time) }}</th>
                                                @endfor
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                @for ($time = strtotime($dia->hora_inicio); $time < strtotime($dia->hora_fin); $time = strtotime('+30 minutes', $time))
                                                    @php $fechaHora = $dia->dia . ' ' . date('H:i:s', $time); @endphp
                                                    <td class="@if(isset($disponibilidadSeleccionada[$fechaHora])) selected @endif" wire:click="toggleDisponibilidad('{{ $fechaHora }}')">{{ date('H:i', $time) }}</td>
                                                @endfor
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    <a href="{{ route('torneos.edit' , $torneoId) }}" class="btn btn-secondary">Volver</a>
                </div>
            </form>

            <!-- Modal -->
            <div wire:ignore.self class="modal fade" id="inscripcionModal" tabindex="-1" aria-labelledby="inscripcionModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="inscripcionModalLabel">Añadir Inscripción</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form wire:submit.prevent="saveInscripcion" class="row">
                                <div class="">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" wire:model.debounce.500ms="email" @if($bloqJugador1) disabled @endif>
                                    </div>
                                    <div class="form-group">
                                        <label for="telefono">Teléfono</label>
                                        <input type="text" class="form-control" id="telefono" wire:model.debounce.500ms="telefono" @if($bloqJugador1) disabled @endif>
                                    </div>
                                    <div class="form-group">
                                        <label for="DNI">DNI</label>
                                        <input type="text" class="form-control" id="DNI" wire:model.debounce.500ms="DNI" @if($bloqJugador1) disabled @endif>
                                    </div>
                                    <div class="form-group">
                                        <label for="nickName">NickName</label>
                                        <input type="text" class="form-control" id="nickName" wire:model.debounce.500ms="nickName" @if($bloqJugador1) disabled @endif>
                                    </div>
                                    <div class="form-group">
                                        <label for="inscripcionNombre">Nombre</label>
                                        <input type="text" class="form-control" id="inscripcionNombre" wire:model="inscripcionNombre" @if($bloqJugador1) disabled @endif>
                                    </div>
                                    <div class="form-group">
                                        <label for="apellidos">Apellidos</label>
                                        <input type="text" class="form-control" id="apellidos" wire:model="apellidos" @if($bloqJugador1) disabled @endif>
                                    </div>

                                    <div class="form-group">
                                        <label for="ciudad">Ciudad</label>
                                        <input type="text" class="form-control" id="ciudad" wire:model="ciudad" @if($bloqJugador1) disabled @endif>
                                    </div>
                                    <div class="form-group">
                                        <label for="genero">Género</label>
                                        <input type="text" class="form-control" id="genero" wire:model="genero" @if($bloqJugador1) disabled @endif>
                                    </div>
                                    <div class="form-group">
                                        <label for="comentario">Comentario</label>
                                        <textarea class="form-control" id="comentario" wire:model="comentario"></textarea>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Guardar Inscripción</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para cambiar jugador -->
            <div wire:ignore.self class="modal fade" id="changeJugadorModal" tabindex="-1" role="dialog" aria-labelledby="changeJugadorModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="changeJugadorModalLabel">Cambiar Jugador</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <select class="form-control" wire:model="selectedInscripcion">
                                <option value="">Seleccionar Inscripción</option>
                                @foreach ($inscripcionesDisponibles as $inscripcion)
                                    <option value="{{ $inscripcion->id }}">{{ $inscripcion->nombre }} {{ $inscripcion->apellidos }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" wire:click="changeJugador">Guardar Cambios</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:load', function () {
        window.livewire.on('tabChanged', tabId => {
            const tab = document.querySelector(`#tab-${tabId}`);
            if (tab) {
                const tabInstance = new bootstrap.Tab(tab);
                tabInstance.show();
            }
        });
    });
</script>
