<div class="main-container">
    <div class="grada-container">
        <h2 class="grada-title">Grada: {{ $grada->numero }}</h2>

        <div class="escenario-indicador">Procesión</div>

        <div class="sillas-grid">
            @php
                $filas = $sillas->groupBy('fila');
            @endphp
            @foreach($filas as $fila => $sillasFila)
                <div class="fila">
                    @foreach($sillasFila as $silla)
                        <!-- Color rojo para sillas reservadas o pagadas -->
                        <div class="silla @if($this->IsReservado($silla->reservas)) bg-danger @elseif(in_array($silla->id, $selectedSillas)) bg-success @endif"
                             wire:click="selectSilla({{ $silla->id }})">
                            <i class="fas fa-chair"></i>
                            <span class="silla-numero">{{ $silla->numero }}</span>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>

        <!-- Mostrar botón de confirmar si hay sillas seleccionadas -->
        @if(count($selectedSillas) > 0)
            <div class="text-center mt-4">
                <button class="btn btn-primary" wire:click="abrirModalReserva">
                    Confirmar Selección ({{ count($selectedSillas) }} sillas seleccionadas)
                </button>
            </div>
        @endif
    </div>

    <!-- Modal de Editar Reserva -->
    <div wire:ignore class="modal fade" id="reservaModal" tabindex="-1" role="dialog" aria-labelledby="reservaModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reservaModalLabel">Reservar Sillas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Formulario para editar la reserva -->
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
                        <div class="form-group" wire:ignore>
                            <label for="cliente-select">Seleccionar Cliente</label>
                            <select data-pharaonic="select2" data-component-id="{{ $this->id }}" id="cliente-select" class="form-control" wire:model="clienteSeleccionado" style="width: 100%">                                <option value="">-- Selecciona un cliente --</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->DNI }} - {{ $cliente->nombre }} {{ $cliente->apellidos }} / ({{ $cliente->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-check mb-3 d-flex">
                            <input class="form-check input" type="checkbox" wire:model="isInvitado" id="isInvitado">
                            <label class="form-check label" for="isInvitado">¿Es Invitado?</label>
                        </div>
                        <div class="form-group mb-3">
                            <label for="metodopago-select">Seleccionar Metodo de pago</label>
                            <select id="metodopago-select" class="form-control" wire:model="metodoPago">
                                <option value="">-- Selecciona un estado --</option>
                                <option value="tarjeta">Tarjeta</option>
                                <option value="efectivo">Efectivo</option>
                                <option value="transferencia">Transferencia</option>
                            </select>
                        </div>
                        <button type="button" class="btn btn-link" wire:click="$toggle('mostrarFormularioNuevoCliente')">
                            Crear nuevo cliente
                        </button>
                        @if($mostrarFormularioNuevoCliente)
                            <!-- Mostrar el formulario para crear un nuevo cliente -->
                        @endif
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" wire:click="editarReserva">Guardar Cambios</button>
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

</div>

@section('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<x:pharaonic-select2::scripts />
<script>
    document.addEventListener("DOMContentLoaded", function () {
        window.addEventListener('show-modal', () => {
            var myModal = document.getElementById('reservaModal');
            if (myModal) {
                $(myModal).modal('show');

                // Inicializar Select2 en el modal
                $('#cliente-select').select2({
                    placeholder: "-- Selecciona un cliente --",
                    width: '100%',
                    allowClear: true,
                    dropdownParent: $('#reservaModal') // Asegurarse de que el dropdown esté dentro del modal
                });
            }
        });

        window.addEventListener('hide-modal', () => {
            var myModalEl = document.getElementById('reservaModal');
            if (myModalEl) {
                $(myModalEl).modal('hide');
            }
        });

        window.addEventListener('show-modal-editar-reserva', () => {
            var myModal = document.getElementById('editarReservaModal');
            if (myModal) {
                $(myModal).modal('show');

                // Inicializar Select2 en el modal
                $('#cliente-select').select2({
                    placeholder: "-- Selecciona un cliente --",
                    width: '100%',
                    allowClear: true,
                    dropdownParent: $('#editarReservaModal') // Asegurarse de que el dropdown esté dentro del modal
                });
            }
        });

        window.addEventListener('hide-modal-editar-reserva', () => {
            var myModalEl = document.getElementById('editarReservaModal');
            if (myModalEl) {
                $(myModalEl).modal('hide');
            }
        });

        // Sincronizar Select2 con Livewire
        $('#cliente-select').on('change', function (e) {
            var selectedCliente = $(this).val();
            @this.set('clienteSeleccionado', selectedCliente);
        });
    });
</script>

@endsection
