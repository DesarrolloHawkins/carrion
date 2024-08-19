<div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2>Editar Torneo</h2>

                <!-- Pestañas -->
                <ul class="nav nav-tabs mb-2" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link @if($activeTab === 'info') active @endif" id="info-tab" data-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="{{ $activeTab === 'info' }}" wire:click="setActiveTab('info')">Info</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link @if($activeTab === 'precios') active @endif" id="precios-tab" data-toggle="tab" href="#precios" role="tab" aria-controls="precios" aria-selected="{{ $activeTab === 'precios' }}" wire:click="setActiveTab('precios')">Precios</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link @if($activeTab === 'sedes') active @endif" id="sedes-tab" data-toggle="tab" href="#sedes" role="tab" aria-controls="sedes" aria-selected="{{ $activeTab === 'sedes' }}" wire:click="setActiveTab('sedes')">Sedes</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link @if($activeTab === 'disponibilidad') active @endif" id="disponibilidad-tab" data-toggle="tab" href="#disponibilidad" role="tab" aria-controls="disponibilidad" aria-selected="{{ $activeTab === 'disponibilidad' }}" wire:click="setActiveTab('disponibilidad')">Disponibilidad</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link @if($activeTab === 'categorias') active @endif" id="categorias-tab" data-toggle="tab" href="#categorias" role="tab" aria-controls="categorias" aria-selected="{{ $activeTab === 'categorias' }}" wire:click="setActiveTab('categorias')">Categorias</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link @if($activeTab === 'inscripciones') active @endif" id="inscripciones-tab" data-toggle="tab" href="#inscripciones" role="tab" aria-controls="inscripciones" aria-selected="{{ $activeTab === 'inscripciones' }}" wire:click="setActiveTab('inscripciones')">Inscripciones</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link @if($activeTab === 'cuadros') active @endif" id="cuadros-tab" data-toggle="tab" href="#cuadros" role="tab" aria-controls="cuadros" aria-selected="{{ $activeTab === 'cuadros' }}" wire:click="setActiveTab('cuadros')">Cuadros</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link @if($activeTab === 'finalistas') active @endif" id="finalistas-tab" data-toggle="tab" href="#finalistas" role="tab" aria-controls="finalistas" aria-selected="{{ $activeTab === 'finalistas' }}" wire:click="setActiveTab('finalistas')">Finalistas</a>
                    </li>
                    
                </ul>

                <div class="tab-content" id="myTabContent">
                     
                    <!-- Pestaña Info -->
                    <div class="tab-pane fade @if($activeTab === 'info') show active @endif" id="info" role="tabpanel" aria-labelledby="info-tab">
                        <form wire:submit.prevent="updateInfo" enctype="multipart/form-data">
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
                                @if ($imagen && !$imagen instanceof \Livewire\TemporaryUploadedFile)
                                    <img src="{{ asset('storage/' . $existingImagen) }}" width="100" class="mt-2">
                                @elseif($imagen)
                                    <img src="{{ $imagen->temporaryUrl() }}" width="100" class="mt-2">
                                @else
                                    @if ($existingImagen)
                                        <img src="{{ asset('storage/' . $existingImagen) }}" width="100" class="mt-2">
                                    @endif
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="normativa">Normativa (PDF)</label>
                                <input type="file" class="form-control" id="normativa" wire:model="normativa">
                                @error('normativa') <span class="text-danger">{{ $message }}</span> @enderror
                                @if ($normativa && !$normativa instanceof \Livewire\TemporaryUploadedFile)
                                    <a href="{{ asset('storage/' . $existingNormativa) }}" target="_blank">Ver Normativa Actual</a>
                                @else
                                    @if ($existingNormativa)
                                        <a href="{{ asset('storage/' . $existingNormativa) }}" target="_blank">Ver Normativa Actual</a>
                                    @endif
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </form>
                    </div>

                    <!-- Pestaña Precios -->
                    <div class="tab-pane fade @if($activeTab === 'precios') show active @endif" id="precios" role="tabpanel" aria-labelledby="precios-tab">
                        <form wire:submit.prevent="updatePrices">
                            <div class="form-group">
                                <label for="precio">Precio</label>
                                <input type="number" class="form-control" id="precio" wire:model="precio">
                                @error('precio') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="precio_socio">Precio Socio</label>
                                <input type="number" class="form-control" id="precio_socio" wire:model="precio_socio">
                                @error('precio_socio') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="precio_pronto_pago">Precio Pronto Pago</label>
                                <input type="number" class="form-control" id="precio_pronto_pago" wire:model="precio_pronto_pago">
                                @error('precio_pronto_pago') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="precio_socio_pronto_pago">Precio Socio Pronto Pago</label>
                                <input type="number" class="form-control" id="precio_socio_pronto_pago" wire:model="precio_socio_pronto_pago">
                                @error('precio_socio_pronto_pago') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="condiciones">Condiciones de Devolución</label>
                                <textarea class="form-control" id="condiciones" wire:model="condiciones"></textarea>
                                @error('condiciones') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </form>
                    </div>
                    

                    <!-- Pestaña Disponibilidad -->
                    <div class="tab-pane fade @if($activeTab === 'disponibilidad') show active @endif" id="disponibilidad" role="tabpanel" aria-labelledby="disponibilidad-tab">
                        <!-- Selección de pistas -->
                        <form wire:submit.prevent="updatePistas">
                            <div class="form-group">
                                <label for="pistas">Pistas Disponibles</label>
                                <div class="row">
                                    @foreach ($pistasDisponibles as $pista)
                                        <div class="col-md-4 mb-3">
                                            <div class="card @if(in_array($pista->id, $pistasSeleccionadas)) border border-primary @endif" wire:click="togglePista({{ $pista->id }})" style="cursor: pointer;">
                                                <div class="card-body">
                                                    <h5 class="card-title">{{ $pista->nombre }}</h5>
                                                    @if(in_array($pista->id, $pistasSeleccionadas))
                                                        <p class="card-text text-primary">Seleccionada</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('pistasSeleccionadas') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </form>

                        <!-- Gestión de días de juego -->
                        <form wire:submit.prevent="">
                            <div class="form-group">
                                <label for="dia">Días de Juego</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="date" class="form-control" wire:model="nuevoDia">
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-control" wire:model="nuevoHoraInicio">
                                            @foreach($timeSlots as $slot)
                                                <option value="{{ $slot }}">{{ $slot }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-control" wire:model="nuevoHoraFin">
                                            @foreach($timeSlots as $slot)
                                                <option value="{{ $slot }}">{{ $slot }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-success" wire:click="addDia">Agregar</button>
                                    </div>
                                </div>
                                @error('nuevoDia') <span class="text-danger">{{ $message }}</span> @enderror
                                @error('nuevoHoraInicio') <span class="text-danger">{{ $message }}</span> @enderror
                                @error('nuevoHoraFin') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Día</th>
                                            <th>Hora Inicio</th>
                                            <th>Hora Fin</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($diasSeleccionados as $index => $dia)
                                            <tr>
                                                <td>{{ $dia['dia'] }}</td>
                                                <td>{{ $dia['hora_inicio'] }}</td>
                                                <td>{{ $dia['hora_fin'] }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-danger" wire:click="removeDia({{ $index }}, {{ $dia['id'] ?? null }})">Eliminar</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </form>

                        <!-- Visualización de disponibilidad de pistas -->
                        @if(count($diasSeleccionados) > 0)
                            <div class="mt-4">
                                <!-- Píldoras de navegación para días -->
                                <ul class="nav nav-pills mb-2" id="diaPills" role="tablist">
                                    @foreach($diasSeleccionados as $index => $dia)
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link dias @if($selectedDiaIndex === $index) active @endif" id="dia-{{ $index }}-tab" data-toggle="pill" href="#dia-{{ $index }}" role="tab" aria-controls="dia-{{ $index }}" aria-selected="{{ $selectedDiaIndex === $index }}" wire:click="setSelectedDiaIndex({{ $index }})">
                                                {{-- {{ \Carbon\Carbon::parse($dia['dia'])->format('l, d/m') }} --}}
                                                {{ \Carbon\Carbon::parse($dia['dia'])->isoFormat('dddd, DD/MM') }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>

                                <style>
                                    .dias{
                                        text-transform: lowercase;
                                    }

                                    .dias:first-letter {
                                        text-transform: uppercase;
                                    }
                                </style>
                                
                                <!-- Contenido de las pestañas -->
                                <div class="tab-content" id="diaPillsContent">
                                    @foreach($diasSeleccionados as $index => $dia)
                                        <div class="tab-pane fade @if($selectedDiaIndex === $index) show active @endif" id="dia-{{ $index }}" role="tabpanel" aria-labelledby="dia-{{ $index }}-tab">
                                            <!-- Botones para acciones de reservas -->
                                            <div class="mb-3">
                                                <button type="button" class="btn btn-success" wire:click="reserveAllHours('{{ $dia['dia'] }}')">Reservar Todo el Día</button>
                                                <button type="button" class="btn btn-warning" wire:click="cancelAllReservations('{{ $dia['dia'] }}')">Cancelar Todas las Reservas</button>
                                            </div>
                                            
                                            <!-- Tabla de disponibilidad -->
                                            <table class="table table-bordered mt-3">
                                                <thead>
                                                    <tr>
                                                        <th>Hora</th>
                                                        @foreach($pistasSeleccionadas as $pistaId)
                                                            <th>{{ $pistasDisponibles->find($pistaId)->nombre }}</th>
                                                        @endforeach
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @for($hora = \Carbon\Carbon::parse($dia['hora_inicio']); $hora < \Carbon\Carbon::parse($dia['hora_fin']); $hora->addMinutes(30))
                                                        <tr>
                                                            <td>{{ $hora->format('H:i') }}</td>
                                                            @foreach($pistasSeleccionadas as $pistaId)
                                                                <td class="@if($this->checkReservation($pistaId, $hora->format('H:i'), $dia['dia'])) bg-success @elseif(in_array($hora->format('H:i'), $reservas[$pistaId][$dia['dia']] ?? [])) bg-light @else bg-light @endif" wire:click="toggleReservar({{ $pistaId }}, '{{ $hora->format('H:i') }}', '{{ $dia['dia'] }}')" style="cursor: pointer;">
                                                                    @if($this->checkReservation($pistaId, $hora->format('H:i'), $dia['dia']))
                                                                        Reservada
                                                                    @endif
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    @endfor
                                                </tbody>
                                            </table>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                    <!-- Pestaña categorías -->
                    <div class="tab-pane fade @if($activeTab === 'categorias') show active @endif" id="categorias" role="tabpanel" aria-labelledby="categorias-tab">
                        <div class="container mt-3 row">
                            <!-- Formulario para seleccionar y asociar categorías -->
                            <form wire:submit.prevent="saveCategories">
                                <!-- Selección de Categorías -->
                                <div class="form-group">
                                    <label for="categoriaSelect">Categorías:</label>
                                    <select id="categoriaSelect" class="form-control" wire:model="selectedCategorias" multiple>
                                        @foreach($categorias as $categoria)
                                            <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Formato de Juego -->
                                <div class="form-group mt-3">
                                    <label for="formatoSelect">Formato de Juego:</label>
                                    <select id="formatoSelect" class="form-control" wire:model="selectedFormato">
                                        <option value="Eliminatoria">Eliminatoria</option>
                                        <option value="Eliminatoria + consolidacion">Eliminatoria + consolidacion</option>
                                        <option value="Liguilla">Liguilla</option>
                                        <option value="Liguilla + playOff">Liguilla + playOff</option>
                                        <option value="Cuadro americano">Cuadro americano</option>
                                    </select>
                                </div>

                                <!-- Número Máximo de Jugadores -->
                                <div class="form-group mt-3">
                                    <label for="maxJugadores">Número Máximo de Jugadores:</label>
                                    <input type="number" id="maxJugadores" class="form-control" wire:model="maxJugadores" min="1">
                                </div>

                                <!-- Inscripciones Abiertas -->
                                <div class="form-group mt-3">
                                    <label for="inscripcionesAbiertas">Inscripciones Abiertas:</label>
                                    <select id="inscripcionesAbiertas" class="form-control" wire:model="inscripcionesAbiertas">
                                        <option value="1">Sí</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>

                                <!-- Botón de Guardar -->
                                <button type="submit" class="btn btn-primary mt-3">Guardar</button>
                            </form>

                            <!-- Tabla de Categorías -->
                            <div class="mt-4">
                                <h5>Categorías Asociadas al Torneo:</h5>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Categoría</th>
                                            <th>Formato</th>
                                            <th>Inscripciones Actuales</th>
                                            <th>Estimación</th>
                                            <th>Inscripciones</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($categoriasSeleccionadas as $categoria)
                                            <tr>
                                                <td>{{ $categoria['categoria']['nombre'] }}</td>
                                                <td>{{ $categoria['formato_juego'] }}</td>
                                                <td>{{ $this->getInscripcionesCategoria($categoria->id) }}</td>
                                                <td>{{ $categoria['max_jugadores'] }}</td>
                                                <td style="cursor: pointer;" wire:click="changeInscripcionesAbiertas({{ $categoria->id }})">{{ $categoria['inscripciones_abiertas'] ? 'Abiertas' : 'Cerradas' }}</td>
                                                <td>
                                                    <!-- Acciones: Editar y Eliminar -->
                                                    <button class="btn btn-danger btn-sm" wire:click="removeCategory({{ $categoria['id'] }})">Eliminar</button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6">No hay categorías asociadas.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <!-- Informe de Días y Pistas -->
                            <div class="mt-4">
                                <h5>Informe de Días y Pistas</h5>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Día</th>
                                            <th>Inicio</th>
                                            <th>Fin</th>
                                            <th>Total Pistas</th>
                                            <th>Horas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalPistas = [];
                                            $totalHoras = 0;
                                        @endphp

                                        @foreach($torneosDias as $dia)
                                            @php
                                                // Obtener las pistas únicas reservadas en el día
                                                $reservasEnDia = \App\Models\Reservas::where('dia', $dia->dia)
                                                    ->whereBetween('hora_inicio', [$dia->hora_inicio, $dia->hora_fin])
                                                    ->pluck('pista_id')
                                                    ->unique();

                                                $totalPistas[$dia->dia] = $reservasEnDia->count();

                                                // Contar el total de horas reservadas en el día
                                                $reservas = \App\Models\Reservas::where('dia', $dia->dia)
                                                    ->whereBetween('hora_inicio', [$dia->hora_inicio, $dia->hora_fin])
                                                    ->get();

                                                $horasReservadas = 0;
                                                foreach ($reservas as $reserva) {
                                                    // Asumiendo que cada reserva es de 30 minutos
                                                    $horaInicio = \Carbon\Carbon::parse($reserva->hora_inicio);
                                                    $horaFin = \Carbon\Carbon::parse($reserva->hora_fin);
                                                    $horasReservadas += $horaFin->diffInMinutes($horaInicio) / 60;
                                                }
                                                
                                                $totalHoras += $horasReservadas;
                                            @endphp
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($dia->dia)->format('d/m/Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($dia->hora_inicio)->format('H:i') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($dia->hora_fin)->format('H:i') }}</td>
                                                <td>{{ $totalPistas[$dia->dia] }}</td>
                                                <td>{{ number_format($horasReservadas, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <!-- Resumen -->
                                <div class="mt-3">
                                    @php
                                        // Contar pistas únicas totales
                                        $uniquePistas = \App\Models\Reservas::pluck('pista_id')->unique()->count();
                                    @endphp
                                    <h6>Resumen:</h6>
                                    <ul>
                                        <li><strong>Días:</strong> {{ $torneosDias->count() }}</li>
                                        <li><strong>Total Pistas:</strong> {{ $uniquePistas }}</li>
                                        <li><strong>Total Horas:</strong> {{ number_format($totalHoras, 2) }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Pestaña Inscripciones -->
                    <div class="tab-pane fade @if($activeTab === 'inscripciones') show active @endif" id="inscripciones" role="tabpanel" aria-labelledby="inscripciones-tab">
                        <!-- Botón para abrir el modal -->
                        <button type="button" class="btn btn-primary mb-2   "  data-toggle="modal" data-target="#inscripcionModal">
                            Añadir Inscripción
                        </button>

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
                                            <div class="@if($tipoInscripcion === 'doble') col-6 @endif">
                                                <div class="form-group">
                                                    <label for="categoriaSeleccionada">Categoría a Inscribirse</label>
                                                    <select class="form-control" id="categoriaSeleccionada" wire:model="categoriaSeleccionada">
                                                        <option value="">Seleccione una categoría</option>
                                                        @foreach($categoriasAElegir as $categoria)
                                                            <option value="{{ $categoria->id }}">{{ $categoria->categoria->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input type="email" class="form-control" id="email" wire:model.debounce.500ms="email" @if($bloqJugador1) disabled @endif>
                                                </div>
                                                <div class="form-group">
                                                    <label for="telefono">Teléfono</label>
                                                    <input  type="text" class="form-control" id="telefono" wire:model.debounce.500ms="telefono" @if($bloqJugador1) disabled @endif>
                                                </div>
                                                <div class="form-group">
                                                    <label for="DNI">DNI</label>
                                                    <input  type="text" class="form-control" id="DNI" wire:model.debounce.500ms="DNI" @if($bloqJugador1) disabled @endif>
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
                                            
                                            @if($tipoInscripcion === 'doble')
                                            <div class="col-6">
                                                <div class="form-group" >
                                                    <label for="grupoDuo">Grupo</label>
                                                    <input type="text" wire:model="grupoDuo" class="form-control" >
                                                </div>
                                                <div class="form-group">
                                                    <label for="email2">Email Jugador 2</label>
                                                    <input type="email" class="form-control" id="email2" wire:model.debounce.500ms="email2" @if($bloqJugador2) disabled @endif>
                                                </div>
                                                <div class="form-group">
                                                    <label for="telefono2">Teléfono Jugador 2</label>
                                                    <input type="text" class="form-control" id="telefono2" wire:model.debounce.500ms="telefono2" @if($bloqJugador2) disabled @endif>
                                                </div>
                                                <div class="form-group">
                                                    <label for="DNI2">DNI Jugador 2</label>
                                                    <input type="text" class="form-control" id="DNI2" wire:model.debounce.500ms="DNI2" @if($bloqJugador2) disabled @endif>
                                                </div>
                                                <div class="form-group">
                                                    <label for="nickName2">NickName Jugador 2</label>
                                                    <input type="text" class="form-control" id="nickName2" wire:model.debounce.500ms="nickName2" @if($bloqJugador2) disabled @endif>
                                                </div>
                                                <div class="form-group">
                                                    <label for="nombre2">Nombre Jugador 2</label>
                                                    <input  type="text" class="form-control" id="nombre2" wire:model="nombre2" @if($bloqJugador2) disabled @endif>
                                                </div>
                                                <div class="form-group">
                                                    <label for="apellidos2">Apellidos Jugador 2</label>
                                                    <input type="text" class="form-control" id="apellidos2" wire:model="apellidos2" @if($bloqJugador2) disabled @endif>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="ciudad2">Ciudad Jugador 2</label>
                                                    <input type="text" class="form-control" id="ciudad2" wire:model="ciudad2" @if($bloqJugador2) disabled @endif>
                                                </div>
                                                <div class="form-group">
                                                    <label for="genero2">Género Jugador 2</label>
                                                    <input type="text" class="form-control" id="genero2" wire:model="genero2" @if($bloqJugador2) disabled @endif>
                                                </div>
                                                <div class="form-group">
                                                    <label for="comentario2">Comentario Jugador 2</label>
                                                    <textarea class="form-control" id="comentario2" wire:model="comentario2"></textarea>
                                                </div>
                                            </div>
                                            @endif
                                            <button type="submit" class="btn btn-primary">Guardar Inscripción</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Pestañas de Categorías -->
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            @foreach($categoriasDisponibles as $categoria)
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link @if($loop->first) active @endif" id="tab-{{ $categoria->id }}" data-toggle="tab" href="#pestaña-{{ $categoria->id }}" role="tab" aria-controls="pestaña-{{ $categoria->id }}" aria-selected="@if($loop->first) true @else false @endif">
                                        {{ $categoria->categoria->nombre }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                        <!-- Contenido de las Pestañas -->
                        <div class="tab-content" id="myTabContent">
                            @foreach($categoriasDisponibles as $categoria)
                                <div class="tab-pane fade @if($loop->first) show active @endif" id="pestaña-{{ $categoria->id }}" role="tabpanel" aria-labelledby="tab-{{ $categoria->id }}">
                                    <!-- Lista de Inscripciones para la categoría seleccionada -->
                                    <table class="table mt-4">
                                        <thead>
                                            <tr>
                                                <th>Grupo</th>
                                                <th>Nombre</th>
                                                <th>Apellidos</th>
                                                <th>Email</th>
                                                <th>Teléfono</th>
                                                <th>Socio</th>
                                                <th>Pagado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($duos as $duo)
                                                @php
                                                    $inscripcion1 = $duo->inscripcion;
                                                    $inscripcion2 = $duo->inscripcion2;
                                                @endphp

                                                @if($inscripcion1->categoria == $categoria->id || ($inscripcion2 && $inscripcion2->categoria == $categoria->id))
                                                    <tr>
                                                        <td rowspan="2">{{ $duo->grupo }}</td>
                                                        <td>{{ $inscripcion1->nombre }}</td>
                                                        <td>{{ $inscripcion1->apellidos }}</td>
                                                        <td>{{ $inscripcion1->email }}</td>
                                                        <td>{{ $inscripcion1->telefono }}</td>
                                                        <td>{{ $this->isSocio($inscripcion1->jugador_id) ? 'Si' : 'No' }}</td>
                                                        <td><span role="button" wire:click="togglePagado({{ $inscripcion1->id }})">{{ $inscripcion1->pagado ? 'Pagado' : 'No Pagado' }}</span></td>
                                                        <td rowspan="2">
                                                            <a class="btn btn-primary btn-sm" href={{ route('torneos.editduos', $duo->id) }}>Editar</a>
                                                        </td>
                                                    </tr>
                                                    @if($inscripcion2)
                                                        <tr>
                                                            <td>{{ $inscripcion2->nombre }}</td>
                                                            <td>{{ $inscripcion2->apellidos }}</td>
                                                            <td>{{ $inscripcion2->email }}</td>
                                                            <td>{{ $inscripcion2->telefono }}</td>
                                                            <td>{{ $this->isSocio($inscripcion2->jugador_id) ? 'Si' : 'No' }}</td>
                                                            <td><span role="button" wire:click="togglePagado({{ $inscripcion2->id }})">{{ $inscripcion2->pagado ? 'Pagado' : 'No Pagado' }}</span></td>

                                                        </tr>
                                                    @else
                                                        <tr>
                                                            <td colspan="5">Sin pareja</td>
                                                        </tr>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach
                        </div>

                    </div>
                    <!-- Pestañas de Sedes -->
                    <div class="tab-pane fade @if($activeTab === 'sedes') show active @endif" id="sedes" role="tabpanel" aria-labelledby="sedes-tab">
                        <div class="mb-3">
                            <button class="btn btn-success" wire:click="$toggle('showClubSearch')">Añadir Club</button>
                        
                            @if($showClubSearch)
                                <div class="mt-2">
                                    <input type="text" class="form-control" placeholder="Introduce el nombre del club" wire:model="clubSearchQuery" wire:input="searchClubs">
                        
                                    @if(!empty($clubSearchResults))
                                        <ul class="list-group mt-2">
                                            @foreach($clubSearchResults as $club)
                                                <li class="list-group-item d-flex justify-content-between align-items-center" wire:click="selectClub({{ $club->id }})" style="cursor: pointer;">
                                                    <div>
                                                        <strong>{{ $club->nombre }}</strong><br>
                                                        <small>{{ $club->ciudad }}</small>
                                                    </div>
                                                    <img src="{{ $club->logo_url }}" alt="{{ $club->nombre }}" width="40" class="img-thumbnail">
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                        
                                <button class="btn btn-primary mt-3" wire:click="saveSelectedClub" @if(!$selectedClubId) disabled @endif>Guardar Club</button>
                            @endif
                        </div>
                        
                        <!-- Lista de clubes ya seleccionados -->
                        <div class="mt-4">
                            <h5>Clubes Seleccionados</h5>
                            <ul class="list-group">
                                @foreach($selectedClubes as $torneoClub)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $torneoClub->club->nombre }} - {{ $torneoClub->club->ciudad }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <!-- Pestañas de Cuadros -->
                    <div class="tab-pane fade @if($activeTab === 'cuadros') show active @endif" id="cuadros" role="tabpanel" aria-labelledby="cuadros-tab">
                        <div class="container">
                            @if(count($partidos) == 0)
                                <button wire:click="generatePartidos" class="btn btn-primary mb-4">Generar cuadros</button>
                            @endif
                            <!-- Pestañas para seleccionar día -->
                            <ul class="nav nav-tabs mb-2" id="dayTabs" role="tablist">
                                @foreach($partidosByDay as $day => $partidos)
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link @if($loop->first) active @endif" id="day-tab-{{ $loop->index }}" data-toggle="tab" href="#day-{{ $loop->index }}" role="tab" aria-controls="day-{{ $loop->index }}" aria-selected="@if($loop->first) true @else false @endif">
                                            {{ \Carbon\Carbon::parse($day)->format('d/m/Y') }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                    
                            <!-- Botones para seleccionar categoría -->
                            <div class="mb-2">
                                @foreach($categoriasDisponibles as $categoria)
                                    <button class="btn btn-outline-primary categoria-btn" data-categoria-id="{{ $categoria->id }}">
                                        {{ $categoria->categoria->nombre }}
                                    </button>
                                @endforeach
                            </div>
                    
                            <!-- Contenido de las pestañas de días -->
                            <div class="tab-content mt-3" id="dayTabContent">
                                @foreach($partidosByDay as $day => $partidos)
                                    <div class="tab-pane fade @if($loop->first) show active @endif" id="day-{{ $loop->index }}" role="tabpanel" aria-labelledby="day-tab-{{ $loop->index }}">
                                        <div class="table-responsive">
                                            <table class="table table-bordered text-center">
                                                <thead>
                                                    <tr>
                                                        <th>Hora</th>
                                                        @foreach ($pistasSeleccionadas as $pistaId)
                                                            <th>{{ $pistasDisponibles->find($pistaId)->nombre }}</th>
                                                        @endforeach
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $partidosCollection = collect($partidos);
                                                        $horaInicio = \Carbon\Carbon::parse($partidosCollection->min('hora_inicio'));
                                                        $horaFin = \Carbon\Carbon::parse($partidosCollection->max('hora_fin'));
                                                    @endphp
                    
                                                    @for ($hora = $horaInicio; $hora->lt($horaFin); $hora->addMinutes(30))
                                                        <tr>
                                                            <td>{{ $hora->format('H:i') }}</td>
                                                            @foreach ($pistasSeleccionadas as $pistaId)
                                                                @php
                                                                    $partido = $partidosCollection->first(function($p) use ($pistaId, $hora) {
                                                                        return $p['pista_id'] == $pistaId && \Carbon\Carbon::parse($p['hora_inicio'])->format('H:i') == $hora->format('H:i');
                                                                    });
                                                                @endphp
                                                                <td class="partido @if ($partido) categoria-{{ $partido['torneos_categorias_id'] }} @endif" @if ($partido) 
                                                                    data-toggle="modal" 
                                                                    data-target="#partidoModal"
                                                                    data-equipo1="{{ implode(',', [$partido['equipo1']['inscripcion']['nombre'] ?? 'Jugador 1 Equipo 1', $partido['equipo1']['inscripcion2']['nombre'] ?? 'Jugador 2 Equipo 1']) }}" 
                                                                    data-equipo2="{{ implode(',', [$partido['equipo2']['inscripcion']['nombre'] ?? 'Jugador 1 Equipo 2', $partido['equipo2']['inscripcion2']['nombre'] ?? 'Jugador 2 Equipo 2']) }}" 
                                                                    data-hora="{{ $partido['hora_inicio'] }}" 
                                                                    data-pista="{{ $partido['pista']['nombre'] }}"
                                                                    data-categoria="{{ $partido['torneos_categorias_id'] }}"
                                                                    data-fecha="{{ \Carbon\Carbon::parse($day)->format('d/m/Y') }}"
                                                                    data-partidoId = "{{ $partido['id'] }}"
                                                                    data-resultado = "{{ $partido['resultado'] }}"
                                                                @endif>
                                                                    @if ($partido)
                                                                        <div class="match-info bg-danger text-white">
                                                                            {{ $partido['equipo1']['inscripcion']['nombre'] ?? 'N/A' }} y {{ $partido['equipo1']['inscripcion2']['nombre'] ?? 'N/A' }} vs {{ $partido['equipo2']['inscripcion']['nombre'] ?? 'N/A' }} y {{ $partido['equipo2']['inscripcion2']['nombre'] ?? 'N/A' }}
                                                                        </div>
                                                                    @else
                                                                        Pista libre
                                                                    @endif
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    @endfor
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    
                    <style>
                        .bg-danger {
                            background-color: #f8d7da !important;
                            color: #721c24 !important;
                        }
                    
                        .match-info {
                            padding: 5px;
                            border-radius: 4px;
                            font-size: 12px;
                            font-weight: bold;
                            text-align: center;
                        }
                    
                        .partido {
                            background-color: #f9f9f9;
                        }
                    
                        .categoria-selected {
                            border: 2px solid #007bff !important;
                        }
                    
                        .table th, .table td {
                            vertical-align: middle;
                        }
                    </style>
                    {{-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#partidoModal">
                        Abrir Modal
                    </button> --}}
                    
                    
                    <!-- end Pestañas de Cuadros -->
                    <!-- Pestañas de Finalistas -->
                    <div class="tab-pane fade @if($activeTab === 'finalistas') show active @endif" id="finalistas" role="tabpanel" aria-labelledby="finalistas-tab">
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <td class="fw-bold">Categoría</td>
                                    <td class="fw-bold" colspan="2">Campeones</td>
                                    <td class="fw-bold" colspan="2">Subcampeones</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($resultadosPorCategoria as $categoria => $resultados)
                                <tr>
                                    <td>{{ $categoria }}</td>
                                    <td>
                                        <p>{{ $resultados['campeones'][0]->nombre ?? 'N/A' }} {{ $resultados['campeones'][0]->apellidos ?? '' }}</p>
                                        <p>{{ $resultados['campeones'][0]->email ?? '' }}</p>
                                        <p>{{ $resultados['campeones'][0]->telefono ?? '' }}</p>

                                    </td>
                                    <td>
                                        <p>{{ $resultados['campeones'][1]->nombre ?? 'N/A' }} {{ $resultados['campeones'][1]->apellidos ?? '' }}</p>
                                        <p>{{ $resultados['campeones'][1]->email ?? '' }}</p>
                                        <p>{{ $resultados['campeones'][1]->telefono ?? '' }}</p>
                                    </td>
                                    <td>
                                        <p>{{ $resultados['subcampeones'][0]->nombre ?? 'N/A' }} {{ $resultados['subcampeones'][0]->apellidos ?? '' }}</p>
                                        <p>{{ $resultados['subcampeones'][0]->email ?? '' }}</p>
                                        <p>{{ $resultados['subcampeones'][0]->telefono ?? '' }}</p>

                                    </td>
                                    <td>
                                        <p>{{ $resultados['subcampeones'][1]->nombre ?? 'N/A' }} {{ $resultados['subcampeones'][1]->apellidos ?? '' }}</p>
                                        <p>{{ $resultados['subcampeones'][1]->email ?? '' }}</p>
                                        <p>{{ $resultados['subcampeones'][1]->telefono ?? '' }}</p>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
   <!-- Modal -->
    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="partidoModal" tabindex="-1" role="dialog" aria-labelledby="partidoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content @if($selectedPartido && $selectedPartido['finalizado'])  bg-danger @elseif($selectedPartido && !$selectedPartido['finalizado'] && $selectedPartido['bloqueado']) bg-warning @endif ">
                <div class="modal-header">
                    <h5 class="modal-title" id="partidoModalLabel">Gestión Partido</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Botones superiores -->
                    <div class="btn-group mb-3 w-100">
                        <button id="fechaBtn" class="btn btn-secondary">
                            {{ $selectedPartido['dia'] ?? '' }} {{ $selectedPartido['hora_inicio'] ?? '' }}
                        </button>
                        <button id="pistaBtn" class="btn btn-secondary">
                            {{ $selectedPartido['pista']['nombre'] ?? '' }}
                        </button>
                        <button id="resultadoManualBtn" class="btn btn-success">Resultado manual</button>
                        <button id="liberarPistaBtn" class="btn btn-danger">Liberar pista</button>
                    </div>

                    <!-- Sección para cambiar la fecha del partido -->
                    <div id="cambiarFecha" class="mb-3" style="display: none;">
                        <label>Cambiar Hora del Partido</label>
                        <div class="btn-group w-100">
                            @foreach($horasDisponibles as $hora)
                                <button wire:click="updateHora('{{ $hora }}')" class="btn btn-outline-secondary">{{ $hora }}</button>
                            @endforeach
                        </div>
                        <button class="btn btn-danger mt-2 w-100" wire:click="guardarNuevaHora">Guardar nueva hora</button>
                    </div>

                    <!-- Información del Partido -->
                    <div class="match-info mb-3">

                        <p>{{ $selectedPartido ? $this->getCategoriaNaME($selectedPartido['torneos_categorias']['categoria_id']) : '' }}</p>
                        <p>{{ $selectedPartido['dia'] ?? '' }} {{ $selectedPartido['hora_inicio'] ?? '' }}</p>
                    </div>

                    <!-- Equipos -->
                    <div class="row text-center">
                        <div class="col-5 @if($selectedPartido && $selectedPartido['finalizado'] && $torneoResultados->winner_id == $selectedPartido['equipo1']['id']) bg-success p-2 @endif">
                            <p class="fw-bold">{{ $selectedPartido['equipo1']['inscripcion']['nombre'] ?? '' }}</p>
                            <p class="fw-bold">{{ $selectedPartido['equipo1']['inscripcion2']['nombre'] ?? '' }}</p>
                            @if (!isset($selectedPartido['equipo1']) && $selectedPartido)

                                <button wire:click="asignarDuo({{ $selectedPartido['id'] }} , 1)" class="btn btn-success">Asignar Dúo</button>

                            @endif                                                                                                            
                            @if(isset($selectedPartido['equipo1']))
                                <button class="btn @if($selectedPartido['equipo1']['presentado']) btn-success @else btn-danger @endif w-100" wire:click="changeresultado('{{ $selectedPartido['id'] }}', '{{ json_encode($selectedPartido['equipo1'], JSON_HEX_APOS | JSON_HEX_QUOT) }}')">
                                    @if($selectedPartido['equipo1']['presentado'])
                                        Presentado
                                    @else
                                        No presentado
                                    @endif                                
                                </button>
                            @endif
                            @if($selectedPartido && $selectedPartido['finalizado'] && $torneoResultados->winner_id == $selectedPartido['equipo1']['id']) 
                            
                                <div class="p-0 mt-2" style="border: 2px solid white; background: #00c708; ">
                                    <p class="p-0 m-0 text-center fw-bold text-white " style="font-size: 1rem;">Ganadores</p>
                                </div>
                            
                            @endif
                            </div>
                        <div class="col-2">
                            <p class="mt-4 fw-bold">VS</p>
                        </div>
                        <div class="col-5 @if($selectedPartido && $selectedPartido['finalizado'] && $torneoResultados->winner_id == $selectedPartido['equipo2']['id']) bg-success p-2 @endif">
                            
                            <p class="fw-bold">{{ $selectedPartido['equipo2']['inscripcion']['nombre'] ?? '' }}</p>
                            <p class="fw-bold">{{ $selectedPartido['equipo2']['inscripcion2']['nombre'] ?? '' }}</p>
                            @if (!isset($selectedPartido['equipo2']) && $selectedPartido)

                                <button wire:click="asignarDuo({{ $selectedPartido['id'] }} , 2)" class="btn btn-success">Asignar Dúo</button>

                            @endif
                            @if(isset($selectedPartido['equipo2']) )

                            <button class="btn @if($selectedPartido['equipo2']['presentado']) btn-success @else btn-danger @endif w-100" wire:click="changeresultado('{{ $selectedPartido['id'] }}', '{{ json_encode($selectedPartido['equipo2'], JSON_HEX_APOS | JSON_HEX_QUOT) }}')" >
                                @if($selectedPartido['equipo2']['presentado'])
                                    Presentado
                                @else
                                    No presentado
                                @endif

                            </button>                            
                            @endif
                            @if($selectedPartido && $selectedPartido['finalizado'] && $torneoResultados->winner_id == $selectedPartido['equipo2']['id']) 
                            
                                <div class="p-0 mt-2" style="border: 2px solid white; background: #00c708; ">
                                    <p class="p-0 m-0 text-center fw-bold text-white " style="font-size: 1rem;">Ganadores</p>
                                </div>
                            
                            @endif
                        </div>
                    </div>

                    <!-- Resultados de Sets -->
                    <div class="results mt-4">
                        <div class="row">
                            <div class="col-5 text-center @if($selectedPartido && $selectedPartido['finalizado'] && $torneoResultados->winner_id == $selectedPartido['equipo1']['id']) bg-success @endif">
                                <div class="set-score mb-2">
                                    @if($selectedPartido)
                                        <button class="btn btn-dark" wire:click="changeSetPartido({{ $selectedPartido['id'] }}, '1', 'restar')">-</button>
                                        <span>  {{ $this->torneoResultados ? $this->torneoResultados->duo_1_wins  : '0' }}</span>
                                        <button class="btn btn-dark" wire:click="changeSetPartido({{ $selectedPartido['id'] }}, '1', 'sumar')">+</button>
                                    @endif
                                </div>
                                @foreach ( $setsPartido as $set )


                                <div class="set-score mb-2">
                                    <button class="btn btn-dark" wire:click="changeSets({{ $set->id }}, '1', 'restar')">-</button>
                                    <span>{{ $set ? $set->duo_1_score : 0 }}</span>
                                    <button class="btn btn-dark" wire:click="changeSets({{ $set->id }}, '1', 'sumar')">+</button>
                                </div>
                                    
                                @endforeach
                            </div>
                            <div class="col-2 text-center">
                                <p class="mt-0" style="font-size: 1rem;">Sets</p>

                                @foreach ( $setsPartido as $set )


                                    <p class="mt-4" style="font-size: 1rem;">Set-{{ $set->set_number }}</p>

                                    
                                @endforeach


                            </div>

                            <div class="col-5 text-center @if($selectedPartido && $selectedPartido['finalizado'] && $torneoResultados->winner_id == $selectedPartido['equipo2']['id']) bg-success @endif">
                                <div class="set-score mb-2">
                                    @if($selectedPartido)

                                        <button class="btn btn-dark" wire:click="changeSetPartido({{ $selectedPartido['id'] }}, '2', 'restar')">-</button>
                                        <span>{{ $this->torneoResultados ? $this->torneoResultados->duo_2_wins  : '0' }}</span>
                                        <button class="btn btn-dark" wire:click="changeSetPartido({{ $selectedPartido['id'] }}, '2', 'sumar')">+</button>

                                    @endif
                                </div>
                                @foreach ( $setsPartido as $set )


                                <div class="set-score mb-2 ">
                                    <button class="btn btn-dark" wire:click="changeSets({{ $set->id }}, '2', 'restar')">-</button>
                                    <span>{{ $set ? $set->duo_2_score : 0 }}</span>
                                    <button class="btn btn-dark" wire:click="changeSets({{ $set->id }}, '2', 'sumar')">+</button>
                                </div>
                                    
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Comentarios -->
                    <div class="form-group mt-4">
                        <textarea class="form-control" placeholder="Puedes introducir un comentario opcional" wire:model.lazy="comentarioPartido"> </textarea>
                    </div>

                    <!-- Botones inferiores -->
                    <div class="btn-group w-100">
                        @if($selectedPartido)
                            <button class="btn btn-danger" wire:click="finalizarPartido({{ $selectedPartido['id'] }})">Partido finalizado</button>
                            @if($selectedPartido['bloqueado'] && !$selectedPartido['finalizado'])
                                <button class="btn btn-success" wire:click="desbloquearPartido({{ $selectedPartido['id'] }})">Desbloquear partido</button>
                            @elseif(!$selectedPartido['finalizado'])
                                <button class="btn btn-warning" wire:click="bloquearPartido({{ $selectedPartido['id'] }})">Bloquear partido</button>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


    <style>
        .modal-header {
            background-color: #f8f9fa;
        }

        .modal-title {
            font-weight: bold;
        }

        .btn-group button {
            margin-right: 0.5rem;
        }

        #cambiarFecha .btn-group button {
            margin-bottom: 0.5rem;
        }

        .match-info {
            background-color: #f1f1f1;
            padding: 1rem;
            border-radius: 0.25rem;
        }

        .set-score span {
            display: inline-block;
            width: 2rem;
            font-size: 1.5rem;
            text-align: center;
        }

        .set-score button {
            margin: 0 0.5rem;
        }

        .results {
            background-color: #f9f9f9;
            padding: 1rem;
            border-radius: 0.25rem;
        }

        .btn-success, .btn-danger, .btn-warning {
            font-size: 1rem;
            font-weight: bold;
        }

    </style>
    
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
 document.addEventListener('DOMContentLoaded', function () {
    const categoriaButtons = document.querySelectorAll('.categoria-btn');
    const partidos = document.querySelectorAll('.partido');

    categoriaButtons.forEach(button => {
        button.addEventListener('click', function () {
            const categoriaId = this.getAttribute('data-categoria-id');
            console.log(categoriaId);

            // Quitar la selección previa
            partidos.forEach(partido => {
                partido.classList.remove('categoria-selected');
            });

            // Resaltar los partidos de la categoría seleccionada
            document.querySelectorAll(`.categoria-${categoriaId}`).forEach(partido => {
                partido.classList.add('categoria-selected');
            });
        });
    });

});

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.partido').forEach(cell => {
        cell.addEventListener('click', function() {

            const partidoId = this.dataset.partidoid;   
            Livewire.emit('openModal', partidoId);
        });
    });

    Livewire.on('openModal', () => {
        $('#partidoModal').modal('show');
    });

    Livewire.on('closeModal', () => {
        $('#partidoModal').modal('hide');
    });

    const fechaBtn = document.getElementById('fechaBtn');
    const cambiarFechaSection = document.getElementById('cambiarFecha');

    if (fechaBtn && cambiarFechaSection) {
        fechaBtn.addEventListener('click', function () {
            cambiarFechaSection.style.display = cambiarFechaSection.style.display === 'none' ? 'block' : 'none';
        });
    }



});


</script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
        $('.select2').on('change', function (e) {
            var data = $(this).val();
            @this.set('jugadorId', data);
        });
    });
    $(document).ready(function() {
        $('.jugador2').select2();
        $('.jugador2').on('change', function (e) {
            var data = $(this).val();
            @this.set('jugadorId2', data);
        });
    });
</script>

@endsection
