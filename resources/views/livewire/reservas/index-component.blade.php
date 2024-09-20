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
                    <li class="breadcrumb-item active">Todas las reservas</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Filtro por estado -->
    <div class="mb-4">
        <label for="estado" class="mr-2">Filtrar por estado:</label>
        <select wire:model="estado" wire:change="filtrarPorEstado($event.target.value)" id="estado" class="form-control w-25 d-inline">
            <option value="">Todos</option>
            <option value="pagada">Pagadas</option>
            <option value="reservada">Reservadas</option>
            <option value="cancelada">Canceladas</option>
        </select>
    </div>

    <div class="col-md-12 mt-4" x-data="{}" x-init="$nextTick(() => {
            $('#datatable-reservas').DataTable({
                stateSave: true,
                scrollX: true,  // Agrega scroll horizontal
                responsive: false,
                layout: {
                    topStart: {
                        buttons: [
                            'copy', 'excel', 'pdf'
                        ]
                    }
                },
                lengthChange: false,
                pageLength: 30,
                buttons: ['copy', 'excelHtml5', 'pdf', 'colvis'],
                language: {
                    lengthMenu: 'Mostrar _MENU_ registros por página',
                    zeroRecords: 'No se encontraron registros',
                    info: 'Mostrando página _PAGE_ de _PAGES_',
                    infoEmpty: 'No hay registros disponibles',
                    emptyTable: 'No hay registros disponibles',

                    infoFiltered: '(filtrado de _MAX_ total registros)',
                    search: 'Buscar:'
                },
            
            })
                            })" wire:key="{{ rand() }}">
        <table id="datatable-reservas" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;" wire:key="{{ rand() }}">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-4 py-2">Nombre</th>
                    <th class="border px-4 py-2">Apellidos</th>
                    <th class="border px-4 py-2">DNI</th>
                    <th class="border px-4 py-2">Telefono</th>
                    <th class="border px-4 py-2">Fila - Asiento</th>
                    {{-- <th class="border px-4 py-2">Fila</th> --}}
                    <th class="border px-4 py-2">Sector</th>
                    <th class="border px-4 py-2">Posición</th>
                    <th class="border px-4 py-2">Fecha</th>
                    {{-- <th class="border px-4 py-2">Año</th> --}}
                    <th class="border px-4 py-2">Precio</th>
                    <th class="border px-4 py-2">M. Pago</th>

                    <th class="border px-4 py-2">Estado</th>
                    <th class="border px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detallesReservas as $detalle)
                <tr>
                    <td class="border px-4 py-2">{{ $detalle['nombre'] }}</td>
                    <td class="border px-4 py-2">{{ $detalle['apellidos'] }}</td>
                    <td class="border px-4 py-2">{{ $detalle['DNI'] }}</td>
                    <td class="border px-4 py-2">{{ $detalle['movil'] }}</td>
                    <td class="border px-4 py-2">{{ $detalle['fila'] }} - {{ $detalle['asiento']  }}</td>
                    {{-- // <td class="border px-4 py-2">{{ $detalle['fila']  }}</td> --}}
                    <td class="border px-4 py-2">{{ $detalle['sector'] }}</td>
                    <td class="border px-4 py-2">{{ $detalle['palco'] ? 'Palco '.$detalle['palco'] : 'Grada '.$detalle['grada'] }}</td>
                    <td class="border px-4 py-2">{{ $detalle['fecha'] }}</td>
                    {{-- <td class="border px-4 py-2">{{ $detalle['año'] }}</td> --}}
                    <td class="border px-4 py-2">{{ $detalle['precio'] }}€</td>
                    <td class="border px-4 py-2">{{ $detalle['metodo_pago']  ?? 'Tarjeta'}}</td>
                    <td class="border px-4 py-2 @if($detalle['estado'] == 'pagada') text-success @elseif($detalle['estado'] == 'reservada') text-warning @else text-danger @endif">
                        {{ $detalle['estado'] }}
                    </td>
                    <td class="border px-4 py-2">
                        <button wire:click="confirmarCancelacion({{ $detalle['id'] }})" class="btn-warning bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded">
                            Cancelar
                        </button>
                        <button wire:click="confirmarEliminacion({{ $detalle['id'] }})" class="btn-danger bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">
                            Eliminar
                        </button>
                        {{-- {{dd($detalle)}} --}}
                        <a href="{{ route('reservas.pdfDownload', $detalle['cliente_id']) }}" class="btn btn-primary">
                            Descargar PDF
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@section('scripts')
    <script src="../assets/js/jquery.slimscroll.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-2.0.3/b-3.0.1/b-colvis-3.0.1/b-html5-3.0.1/b-print-3.0.1/r-3.0.1/datatables.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>

    <script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-2.0.3/b-3.0.1/b-colvis-3.0.1/b-html5-3.0.1/b-print-3.0.1/r-3.0.1/datatables.min.js"></script>
@endsection