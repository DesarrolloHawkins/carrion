<div class="container-fluid">
    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h4 class="page-title">Reservas</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-right">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Clientes</a></li>
                    <li class="breadcrumb-item active">Editar Reserva</li>
                </ol>
            </div>
        </div>
    </div>

    <form wire:submit.prevent="updateReserva" class="row mt-4">
        <div class="col-lg-8 col-md-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Detalles de la Reserva</h5>
                </div>
                <div class="card-body">
                    @if (session()->has('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6 form-group" wire:ignore>
                            <label for="cliente">Cliente</label>
                            <select wire:model="reserva.id_cliente" class="form-control" id="select2-cliente" required wire:key='{{rand()}}'>
                                <option value="">Seleccionar cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }} {{ $cliente->apellidos }}</option>
                                @endforeach
                            </select>
                            @error('reserva.id_cliente') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="estado">Estado</label>
                            <select wire:model="reserva.estado" class="form-control" id="estado" required>
                                <option value="reservada">Reservada</option>
                                <option value="pagada">Pagada</option>
                                <option value="cancelada">Cancelada</option>
                            </select>
                            @error('reserva.estado') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="metodo_pago">Seleccionar Metodo de pago</label>
                            <select id="metodo_pago" class="form-control" wire:model="reserva.metodo_pago">
                                <option value="">-- Selecciona un estado --</option>
                                <option value="tarjeta">Tarjeta</option>
                                <option value="efectivo">Efectivo</option>
                                <option value="transferencia">Transferencia</option>
                            </select>
                            @error('reserva.metodo_pago') <span class="text-danger">{{ $message }}</span> @enderror

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Acciones</h5>
                </div>
                <div class="card-body">
                    <button type="submit" class="btn btn-success btn-block">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </form>
</div>
@section('scripts')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Inicializamos select2
            $('#select2-cliente').select2({
                placeholder: "Seleccionar cliente",
                width: '100%'
            });

            // Escuchamos los cambios y sincronizamos con Livewire
            $('#select2-cliente').on('change', function (e) {
                var clienteId = $(this).val();
                @this.set('reserva.id_cliente', clienteId); // Sincronizar con Livewire
            });

            // Cada vez que se actualiza el componente en Livewire, sincronizar el valor seleccionado
            Livewire.hook('message.processed', (message, component) => {
                $('#select2-cliente').val(@this.reserva.id_cliente).trigger('change');
            });
        });
    </script>
@endsection
