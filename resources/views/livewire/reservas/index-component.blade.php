<div class="container-fluid">
    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h4 class="page-title">Reservas</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-right">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Reservas</a></li>
                    <li class="breadcrumb-item active">Todos las reservas</li>
                </ol>
            </div>
        </div> <!-- end row -->
    </div>
    <!-- end page-title -->

    <div class="row mb-3">
        <div class="col-12">
            <form wire:submit.prevent="filterByMonth">
                <div class="form-group row">
                    <label for="monthFilter" class="col-sm-2 col-form-label">Filtrar por mes</label>
                    <div class="col-sm-4">
                        <select id="monthFilter" wire:model="selectedMonth" class="form-control">
                            <option value="">Seleccione un mes</option>
                            @for ($month = 1; $month <= 12; $month++)
                                <option value="{{ $month }}">{{ \DateTime::createFromFormat('!m', $month)->format('F') }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Informe de Totales -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <h5>Informe</h5>
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Total:</strong> ${{ number_format($totalAmount, 2) }}
                        </div>
                        <div class="col-sm-4">
                            <strong>Pagado:</strong> ${{ number_format($totalPaid, 2) }}
                        </div>
                        <div class="col-sm-4">
                            <strong>Pendiente:</strong> ${{ number_format($totalPending, 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Reservas -->
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    @if (count($reservas) > 0)
                        <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th scope="col">Fecha</th>
                                    <th scope="col">Hora inicio</th>
                                    <th scope="col">Hora fin</th>
                                    <th scope="col">Pista</th>
                                    <th scope="col">Propietario</th>
                                    <th scope="col">Pagado</th>
                                    <th scope="col">Pendiente</th>
                                    <th scope="col">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reservas as $reserva)
                                    <tr>
                                        <td>{{ $reserva->dia }}</td>
                                        <td>{{ $reserva->hora_inicio }}</td>
                                        <td>{{ $reserva->hora_fin }}</td>
                                        <td>{{ $reserva->pista->nombre }}</td>
                                        <td>{{ $reserva->nombre_jugador }}</td>
                                        <td>{{ $this->existPago($reserva->id) ? $reserva->precio : '0.00' }}</td>
                                        <td>{{ $this->existPago($reserva->id) ? '0.00' : $reserva->precio }}</td>
                                        <td>{{ $reserva->precio }}</td> 
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
</div>
