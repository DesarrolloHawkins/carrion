<div>
    <div id="event-tooltip" style="display: none; position: absolute; background: #333; color: #fff; padding: 5px; border-radius: 3px; z-index: 1000;"></div>

    <div wire:ignore>
        <div id='calendar' class="w-100"></div>
    </div>
   <!-- Modal para gestionar pagos -->
<div wire:ignore.self class="modal fade" id="pagosModal" tabindex="-1" role="dialog" aria-labelledby="pagosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pagosModalLabel">Gestionar Pagos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Tabla de pagos -->
                <table class="table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Monto</th>
                            <th>Tipo de Pago</th>
                            <th>Nota</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pagos as $pago)
                            <tr>
                                <td>{{ $pago->fecha_pago }}</td>
                                <td>{{ $pago->hora_pago }}</td>
                                <td>{{ $pago->monto }}</td>
                                <td>{{ $pago->tipo_pago }}</td>
                                <td>{{ $pago->nota }}</td>
                                <td>
                                    <button class="btn btn-sm btn-danger" wire:click="deletePago({{ $pago->id }})">Eliminar</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <!-- Formulario para añadir un nuevo pago -->
                <form wire:submit.prevent="addPago">
                    <div class="form-group">
                        <label for="fechaPago">Fecha</label>
                        <input type="date" class="form-control" id="fechaPago" wire:model="fechaPago">
                    </div>
                    <div class="form-group">
                        <label for="horaPago">Hora</label>
                        <input type="time" class="form-control" id="horaPago" wire:model="horaPago">
                    </div>
                    <div class="form-group">
                        <label for="montoPago">Monto</label>
                        <input type="number" class="form-control" id="montoPago" wire:model="montoPago" >
                    </div>
                    <div class="form-group">
                        <label for="tipoPago">Tipo de Pago</label>
                        <select class="form-control" id="tipoPago" wire:model="tipoPago">
                            <option value="efectivo">Efectivo</option>
                            <option value="tarjeta">Tarjeta</option>
                            <option value="transferencia">Transferencia</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nota">Nota</label>
                        <textarea class="form-control" id="nota" wire:model="nota"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Añadir Pago</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

    <!-- Modal para agregar/editar reserva -->
    <div wire:ignore.self class="modal fade" id="reservaModal" tabindex="-1" role="dialog" aria-labelledby="reservaModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reservaModalLabel">{{ $modalType == 'edit' ? 'Editar Reserva' : 'Agregar Reserva' }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="row" wire:submit.prevent="save">
                        <!-- Campo para seleccionar el día -->
                        
                        <div class="form-group">
                            <label for="dia">Día</label>
                            <input type="date" class="form-control" id="dia" wire:model="selectedDate">
                        </div>
                        <div class="form-group col-6">
                            <label for="cliente">Cliente</label>
                            <select class="form-control" id="cliente" wire:model="selectedCliente">
                                <option value="">Seleccione un cliente</option>
                                @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }} {{ $cliente->apellido }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Campo para nombre del jugador -->
                        <div class="form-group col-6">
                            <label for="nombre_jugador">Nombre del Jugador</label>
                            <input type="text" class="form-control" id="nombre_jugador" wire:model="nombre_jugador">
                        </div>

                        <div class="form-group col-6">
                            <label for="hora_inicio">Hora de Inicio</label>
                            <select class="form-control" id="hora_inicio" wire:model="hora_inicio" wire:change="updateEndTimes">
                                @foreach(range(0, 23) as $hour)
                                    @foreach([0, 30] as $minute)
                                        <option value="{{ sprintf('%02d:%02d', $hour, $minute) }}">
                                            {{ sprintf('%02d:%02d', $hour, $minute) }}
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>

                        <!-- Campo para hora fin -->
                        <div class="form-group col-6"  >
                            <label for="hora_fin">Hora de Fin</label>
                            <select class="form-control" id="hora_fin" wire:model="hora_fin">
                                @foreach($endTimeOptions as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Campo para precio -->
                        <div class="form-group col-6">
                            <label for="precio">Precio</label>
                            <input type="number" class="form-control" id="precio" wire:model="precio">
                        </div>

                        <!-- Campo para tipo de pago -->
                        <div class="form-group col-6">
                            <label for="tipo_pago">Tipo de Pago</label>
                            <select class="form-control" id="tipo_pago" wire:model="tipo_pago">
                                <option value="unico">Único</option>
                                <option value="dividido">Dividido</option>
                            </select>
                        </div>

                        <!-- Campo para tipo de reserva -->
                        <div class="form-group col-6">
                            <label for="tipo_reserva">Tipo de Reserva</label>
                            <select class="form-control" id="tipo_reserva" wire:model="tipo_reserva">
                                <option value="normal">Normal</option>
                                <option value="clase">Clase</option>
                                <option value="recurrente">Recurrente</option>
                            </select>
                        </div>

                        <!-- Campo para pista -->
                        <div class="form-group col-6">
                            <label for="pista">Pista</label>
                            <select class="form-control" id="pista" wire:model="selectedPista">
                                <option value="">Seleccione una pista</option>
                                @foreach ($pistas as $pista)
                                    <option value="{{ $pista->id }}">{{ $pista->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                       
                        @if($tipo_reserva == 'clase' )

                            <!-- Campo para monitor -->
                            <div class="form-group col-6">
                                <label for="monitor">Monitor</label>
                                <select class="form-control" id="monitor" wire:model="selectedMonitor">
                                    <option value="">Seleccione un monitor</option>
                                    @foreach ($monitores as $monitor)
                                        <option value="{{ $monitor->id }}">{{ $monitor->nombre }} {{ $monitor->apellido }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                         <!-- Campo para repetir cada X semanas (habilitado solo para 'clase' o 'recurrente') -->
                         @if($tipo_reserva == 'clase' || $tipo_reserva == 'recurrente')
                         <div class="form-group col-6">
                             <label for="repetirCadaXSemanas">Repetir cada X semanas</label>
                             <input type="number" class="form-control" id="repetirCadaXSemanas" wire:model="repetir_cada" min="1">
                         </div>
                         @endif

                        <!-- Campo para nota -->
                        <div class="form-group">
                            <label for="nota">Nota</label>
                            <textarea class="form-control" id="nota" wire:model="nota"></textarea>
                        </div>

                        <!-- Botones del formulario -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            @if($modalType == 'edit')
                                <button type="button" class="btn btn-danger" wire:click="delete">Eliminar</button>
                            @endif
                            <button type="button" class="btn btn-primary" wire:click="save">Guardar</button>
                            <!-- Nuevo botón para gestionar pagos -->
                            <button type="button" class="btn btn-info" data-toggle="modal" data-dismiss="modal" wire:click="loadPagos" data-target="#pagosModal">Gestionar Pagos</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: "es",
                headerToolbar: {
                    left: 'prev,next',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    week: 'Semana',
                    day: 'Día',
                    list: 'Lista'
                },
                selectable: true,
                events: [
                    @foreach ($reservas as $reserva)
                        {
                            title: '{{ $reserva->nombre_jugador }} - {{ $reserva->hora_inicio }} a {{ $reserva->hora_fin }} en la pista {{ $reserva->pista->nombre }}',
                            start: '{{ $reserva->dia }}T{{ $reserva->hora_inicio }}',
                            end: '{{ $reserva->dia }}T{{ $reserva->hora_fin }}',
                            startStr: '{{ $reserva->dia }}T{{ $reserva->hora_inicio }}',
                            endStr: '{{ $reserva->dia }}T{{ $reserva->hora_fin }}',
                            description: '{{ $reserva->nota }}',
                            id: '{{ $reserva->id }}',
                            @if($reserva->tipo_reserva == 'clase')
                                color: 'green',
                            @elseif($reserva->tipo_reserva == 'recurrente')
                                color: 'blue',
                            @else
                                color: 'yellow',
                            @endif
                        },
                    @endforeach
                ],
                dateClick: function(info) {
                    Livewire.emit('setDate', info.dateStr);
                },
                eventMouseEnter: function(info) {
                    var tooltip = document.getElementById('event-tooltip');
                    tooltip.innerHTML = `
                        <strong>${info.event.title}</strong><br>
                        ${info.event.extendedProps.description}
                    `;
                    tooltip.style.display = 'block';
                    tooltip.style.left = `${info.jsEvent.pageX + 10}px`;
                    tooltip.style.top = `${info.jsEvent.pageY + 10}px`;
                },
                eventMouseLeave: function(info) {
                    var tooltip = document.getElementById('event-tooltip');
                    tooltip.style.display = 'none';
                },
                eventClick: function(info) {
                    Livewire.emit('editReserva', info.event.id);
                }
            });
            calendar.render();

            Livewire.on('openModal', function() {
                $('#reservaModal').modal('show');
            });

            Livewire.on('closeModal', function() {
                $('#reservaModal').modal('hide');
            });
        });
    </script>
@endsection

