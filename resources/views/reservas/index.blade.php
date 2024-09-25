@extends('layouts.app')
@section('style')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<style>
svg{
max-width: 20px;
}

th {
      white-space: nowrap !important;
    }
/* a.relative.inline-flex.items-center.px-4.py-2.text-sm.font-medium.text-gray-700.bg-white.border.border-gray-300.leading-5.rounded-md.hover\:text-gray-500.focus\:outline-none.focus\:ring.ring-gray-300.focus\:border-blue-300.active\:bg-gray-100.active\:text-gray-700.transition.ease-in-out.duration-150 {
    display: none;
} */
</style>
@endsection
@section('content-principal')
<div class="container" style="max-width: 95%;">
    <h1 class="mb-4 text-center">Gestión de Reservas</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Buscar Reservas</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('reservas.index') }}" method="GET" class="form-row align-items-center">
                <div class="col-sm-2 my-1">
                    <input type="text" name="filtro" class="form-control" placeholder="Buscar por cliente..." value="{{ $filtro }}">
                </div>
                <div class="col-sm-2 my-1">
                    <select name="estado" class="form-control">
                        <option value="">Estados</option>
                        <option value="pagada"{{ $estado == 'pagada' ? ' selected' : '' }}>Pagada</option>
                        <option value="reservada"{{ $estado == 'reservada' ? ' selected' : '' }}>Reservada</option>
                        <option value="cancelada"{{ $estado == 'cancelada' ? ' selected' : '' }}>Cancelada</option>
                    </select>
                </div>
                <div class="col-sm-2 my-1">
                    <select name="palco" class="form-control select2palco">
                        <option value="">Palcos</option>
                      @foreach ($palcos as $pal )
                      <option value="{{$pal->id}}" {{ $palco == $pal->id ? ' selected' : '' }}>{{$pal->zonas->nombre.'-Palco '.$pal->numero}}</option>
                      @endforeach
                    </select>
                </div>
                <div class="col-sm-2 my-1">
                    <select name="grada" class="form-control select2grada">
                        <option value="">Gradas</option>
                        @foreach ($gradas as $gad )
                        <option value="{{$gad->id}}" {{ $grada == $gad->id ? ' selected' : '' }}>{{$gad->zonas->nombre.'-Grada '.$gad->numero}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2 my-1">
                    <select name="perPage" class="form-control">
                        <option value="5"{{ $perPage == 5 ? ' selected' : '' }}>5 por página</option>
                        <option value="10"{{ $perPage == 10 ? ' selected' : '' }}>10 por página</option>
                        <option value="20"{{ $perPage == 20 ? ' selected' : '' }}>20 por página</option>
                    </select>
                </div>
                <div class="col-sm-2 my-1">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
            </form>
        </div>
    </div>
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('reservas.export', request()->all()) }}" class="btn btn-success">Exportar a Excel</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead class="thead-light">
                <tr>
                    @foreach(['nombre' => 'Nombre', 'apellidos' => 'Apellidos', 'DNI' => 'DNI', 'movil' => 'Telefono', 'fila' => 'Fila ', 'asiento'=>'Asi.', 'zona' => 'Sector', 'palco' => 'Palco','grada' => 'Grada', 'fecha' => 'Fecha', 'precio' => 'Precio', 'metodo_pago' => 'M. Pago', 'estado' => 'Estado'] as $col => $name)
                        <th class="border px-4 py-2">
                            <a href="{{ route('reservas.index', [
                                'sortColumn' => $col,
                                'sortDirection' => $sortColumn == $col && $sortDirection == 'asc' ? 'desc' : 'asc',
                                'filtro' => request()->filtro,
                                'estado' => request()->estado,
                                'perPage' => request()->perPage,
                                'grada' => request()->grada,
                                'palco' => request()->palco
                            ]) }}">
                                {{ $name }}
                                @if ($sortColumn == $col)
                                <span>{!! $sortDirection == 'asc' ? '&#9650;' : '&#9660;' !!}</span>
                                @endif
                            </a>
                        </th>
                    @endforeach
                    <th class="border px-4 py-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reservas as $reserva)
                <tr>
                    <td>{{ $reserva->clientes->nombre }}</td>
                    <td>{{ $reserva->clientes->apellidos }}</td>
                    <td>{{ $reserva->clientes->DNI }}</td>
                    <td>{{ $reserva->clientes->movil }}</td>
                    <td>{{ $reserva->sillas->fila }} </td>
                    <td>{{ $reserva->sillas->numero }}</td>
                    <td>{{ $reserva->sillas->zona->nombre }}</td>
                    <td>{{ $reserva->sillas->id_palco ? 'Palco '.$reserva->sillas->palco->numero : '' }}</td>
                    <td>{{ $reserva->sillas->id_grada ? 'Grada '.$reserva->sillas->grada->numero : '' }}</td>
                    <td>{{ $reserva->fecha }}</td>
                    <td>{{ $reserva->precio }}€</td>
                    <td>{{ $reserva->metodo_pago }}</td>
                    <td>
                        <span class="badge badge-{{ $reserva->estado == 'pagada' ? 'success' : ($reserva->estado == 'reservada' ? 'warning' : 'danger') }}">
                            {{ ucfirst($reserva->estado) }}
                        </span>
                    </td>
                    <td class="border px-4 py-2">
                        <button data-id="{{ $reserva->id }}" class="btn-warning bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 my-1 rounded cancelar-reserva">
                            Cancelar
                        </button>
                        <button data-id="{{ $reserva->id }}" class="btn-danger bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2  my-1 rounded eliminar-reserva">
                            Eliminar
                        </button>
                        <a href="{{ route('reservas.edit', $reserva->id) }}" class="btn btn-success text-white font-bold py-1 px-2 my-1 rounded">
                            Editar
                        </a>
                        {{-- {{dd($reserva)}} --}}
                        <a href="{{ route('reservas.pdfDownload', $reserva->id_cliente) }}" class="btn btn-primary text-white font-bold py-1 px-2 my-1 rounded">
                             PDF
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div>
        {{ $reservas->appends([
            'filtro' => request()->filtro,
            'estado' => request()->estado,
            'perPage' => request()->perPage,
            'sortColumn' => request()->sortColumn,
            'sortDirection' => request()->sortDirection,
            'palco' => request()->palco,
            'grada' => request()->grada,
            ])->links('vendor.pagination.bootstrap-5') }}
    </div>
</div>
@endsection
@section('scripts')
<script src="../assets/js/jquery.slimscroll.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" defer></script>

<script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-2.0.3/b-3.0.1/b-colvis-3.0.1/b-html5-3.0.1/b-print-3.0.1/r-3.0.1/datatables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Manejar clic en el botón de eliminar
        $('.eliminar-reserva').on('click', function() {
            var reservaId = $(this).data('id'); // Obtener el ID de la reserva
console.log(reservaId);
            // Lanzar el modal de confirmación con SweetAlert
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡Esta acción no se puede deshacer!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Hacer la petición AJAX para eliminar la reserva
                    $.ajax({
                        url: '/admin/reservas/' + reservaId + '/delete', // La URL de tu ruta de eliminación
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}', // CSRF token para protección
                            _method: 'POST' // Método DELETE para eliminar
                        },
                        success: function(response) {
                            // Mostrar alerta de éxito
                            Swal.fire(
                                'Eliminado',
                                'La reserva ha sido eliminada con éxito.',
                                'success'
                            ).then(() => {
                                // Recargar la página para reflejar los cambios
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            // Mostrar alerta de error
                            Swal.fire(
                                'Error',
                                'Ocurrió un error al intentar eliminar la reserva.',
                                'error'
                            );
                        }
                    });
                }
            });
        });

        $('.cancelar-reserva').on('click', function() {
            var reservaId = $(this).data('id'); // Obtener el ID de la reserva
            console.log(reservaId);

            // Lanzar el modal de confirmación con SweetAlert
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡Esta acción no se puede deshacer!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí ,Cancelar',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Hacer la petición AJAX para eliminar la reserva
                    $.ajax({
                        url: '/admin/reservas/' + reservaId + '/cancelar', // La URL de tu ruta de eliminación
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}', // CSRF token para protección
                            _method: 'POST' // Método DELETE para eliminar
                        },
                        success: function(response) {
                            // Mostrar alerta de éxito
                            Swal.fire(
                                'Cancelado',
                                'La reserva ha sido cancelada con éxito.',
                                'success'
                            ).then(() => {
                                // Recargar la página para reflejar los cambios
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            // Mostrar alerta de error
                            Swal.fire(
                                'Error',
                                'Ocurrió un error al intentar cancelar la reserva.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });

    $(document).ready(function() {
        $('.select2grada').select2({
            placeholder: "Grada",
            allowClear: true
        });
        $('.select2palco').select2({
            placeholder: "Palco",
            allowClear: true
        });
    });
</script>
@endsection
