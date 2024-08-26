<div class="grada-container">
    <h2 class="grada-title">Grada: {{ $grada->numero }}</h2>

    <div class="escenario-indicador">Procesión </div>

    <div class="sillas-grid">
        @php
            $filas = $sillas->groupBy('fila');
        @endphp
        @foreach($filas as $fila => $sillasFila)
            <div class="fila">
                @foreach($sillasFila as $silla)
                    <div class="silla @if(count($silla->reservas) > 0 && $this->IsReservado($silla->reservas) ) bg-danger @endif" data-id="{{ $silla->id }}" data-fila="{{ $silla->fila }}"
                         wire:click="selectSilla({{ $silla->id }})">
                        <i class="fas fa-chair  @if(count($silla->reservas) > 0 && $this->IsReservado($silla->reservas)) text-white @endif"></i>
                        <span class="silla-numero  @if(count($silla->reservas) > 0 && $this->IsReservado($silla->reservas)) text-white @endif">{{ $silla->numero }}</span>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
<!-- Modal de Editar Reserva -->
<div wire:ignore.self class="modal fade" id="editarReservaModal" tabindex="-1" role="dialog" aria-labelledby="editarReservaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarReservaModalLabel">Editar Reserva</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group mb-3">
                        <label for="estado-select">Seleccionar Estado</label>
                        <select id="estado-select" class="form-control" wire:model="estadoSeleccionado">
                            <option value="">-- Selecciona un estado --</option>
                            <option value="reservada">Reservada</option>
                            <option value="pagada">Pagada</option>
                            <option value="cancelada">Cancelada</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="cliente-select">Seleccionar Cliente</label>
                        <select id="cliente-select" class="form-control" wire:model="clienteSeleccionado">
                            <option value="">-- Selecciona un cliente --</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->nombre }} ({{ $cliente->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" class="btn btn-link" wire:click="$toggle('mostrarFormularioNuevoCliente')">
                        Crear nuevo cliente
                    </button>

                    @if($mostrarFormularioNuevoCliente)
                        <div class="form-group">
                            <label for="nuevoClienteNombre">Nombre del Cliente</label>
                            <input type="text" class="form-control" id="nuevoClienteNombre" placeholder="Nombre del cliente" wire:model="nuevoClienteNombre">
                        </div>
                        <div class="form-group">
                            <label for="nuevoClienteEmail">Email</label>
                            <input type="email" class="form-control" id="nuevoClienteEmail" placeholder="Email del cliente" wire:model="nuevoClienteEmail">
                        </div>
                        <div class="form-group">
                            <label for="DNI">DNI</label>
                            <input type="text" class="form-control" id="DNI" placeholder="DNI" wire:model="DNI">
                        </div>
                        <button type="button" class="btn btn-primary" wire:click="crearCliente">Guardar Cliente</button>
                    @endif
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" wire:click="editarReserva">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>
    <!-- Modal de Reserva -->
    <div wire:ignore.self class="modal fade" id="reservaModal" tabindex="-1" role="dialog" aria-labelledby="reservaModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reservaModalLabel">Reservar Silla</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group mb-3">
                            <label for="estado-select">Seleccionar Estado</label>
                            <select id="estado-select" class="form-control" wire:model="estadoSeleccionado">
                                <option value="">-- Selecciona un estado --</option>
                                <option value="reservada">Reservada</option>
                                <option value="pagada">Pagada</option>
                                <option value="cancelada">Cancelada</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="cliente-select">Seleccionar Cliente</label>
                            <select id="cliente-select" class="form-control" wire:model="clienteSeleccionado">
                                <option value="">-- Selecciona un cliente --</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }} ({{ $cliente->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="button" class="btn btn-link" wire:click="$toggle('mostrarFormularioNuevoCliente')">
                            Crear nuevo cliente
                        </button>

                        @if($mostrarFormularioNuevoCliente)
                            <div class="form-group">
                                <label for="nuevoClienteNombre">Nombre del Cliente</label>
                                <input type="text" class="form-control" id="nuevoClienteNombre" placeholder="Nombre del cliente" wire:model="nuevoClienteNombre">
                            </div>

                            <div class="form-group">
                                <label for="nuevoClienteEmail">Email</label>
                                <input type="email" class="form-control" id="nuevoClienteEmail" placeholder="Email del cliente" wire:model="nuevoClienteEmail">
                            </div>
                            <div class="form-group">
                                <label for="nuevoClienteEmail">DNI</label>
                                <input type="text" class="form-control" id="DNI" placeholder="DNI" wire:model="DNI">
                            </div>

                            <button type="button" class="btn btn-primary" wire:click="crearCliente">Guardar Cliente</button>
                        @endif
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" wire:click="reservarSilla">Reservar</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .grada-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background-color: #f7f7f7;
            border: 1px solid #ddd;
            border-radius: 10px;
            max-width: 800px;
            margin: 20px auto;
            position: relative;
        }
    
        .grada-title {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }
    
        .escenario-indicador {
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 16px;
            font-weight: bold;
            background-color: #ffcc00;
            padding: 5px 10px;
            border-radius: 5px;
            color: #333;
        }
    
        .sillas-grid {
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: 100%;
            margin-top: 20px;
        }
    
        .fila {
            display: flex;
            justify-content: space-evenly;
            gap: 5px;
        }
    
        .silla {
            width: 50px;
            height: 60px;
            background-color: #fff;
            border: 2px solid #007bff;
            border-radius: 5px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 5px 0;
        }
    
        .silla i {
            font-size: 20px;
            color: #007bff;
        }
    
        .silla-numero {
            font-size: 12px;
            margin-top: 2px;
            color: #333;
        }
    
        .silla:hover {
            background-color: #007bff;
            color: #fff;
            transform: translateY(-2px);
        }
    
        .silla:hover i {
            color: #fff;
        }
    
        .silla:hover .silla-numero {
            color: #fff;
        }
    </style>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Escuchar eventos de Livewire para mostrar/ocultar el modal
            window.addEventListener('show-modal', () => {
                var myModal = new bootstrap.Modal(document.getElementById('reservaModal'));
                myModal.show();
            });
    
            window.addEventListener('hide-modal', () => {
                var myModalEl = document.getElementById('reservaModal');
                var modal = bootstrap.Modal.getInstance(myModalEl);
                modal.hide();
            });
    
            window.addEventListener('update-dropdown', () => {
                // Lógica para actualizar el dropdown si es necesario
            });

                    // Escuchar eventos de Livewire para mostrar/ocultar el modal de reserva
            window.addEventListener('show-modal-editar-reserva', () => {
                var myModal = new bootstrap.Modal(document.getElementById('editarReservaModal'));
                myModal.show();
            });

            window.addEventListener('hide-modal-editar-reserva', () => {
                var myModalEl = document.getElementById('editarReservaModal');
                var modal = bootstrap.Modal.getInstance(myModalEl);
                modal.hide();
            });
        });
    </script>
</div>