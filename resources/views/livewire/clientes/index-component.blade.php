<div class="container-fluid">
    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h4 class="page-title">CLIENTES</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-right">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Clientes</a></li>
                    <li class="breadcrumb-item active">Todos los clientes</li>
                </ol>
            </div>
        </div> <!-- end row -->
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="col-12 d-flex justify-content-start px-5 tabla">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createClienteModal" wire:click="create">Crear Cliente</button>
                    </div>
                    @if (count($clientes) > 0)
                    <div class="col-md-12 mt-4 tabla" x-data="{}" x-init="$nextTick(() => {
                        $('#datatable-button').DataTable({
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
                        <table id="datatable-button" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;" wire:key="{{ rand() }}" >
                            <thead>
                                <tr>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Apellidos</th>
                                    <th scope="col">DNI</th>
                                    <th scope="col">Fijo</th>
                                    <th scope="col">Movil</th>
                                    <th scope="col">Email</th>
                                    {{-- <th scope="col">Direccion</th> --}}
                                    <th scope="col">Abonado</th>
                                    <th scope="col">Tipo</th>


                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($clientes as $cliente)
                                    <tr>
                                        <td>{{ $cliente->nombre }}</td>
                                        <td>{{ $cliente->apellidos }}</td>
                                        <td>{{ $cliente->DNI }}</td>
                                        <td>{{ $cliente->fijo }}</td>
                                        <td>{{ $cliente->movil }}</td>
                                        <td style="max-width:180px; text-overflow: ellipsis; overflow: hidden;">{{ $cliente->email }}</td>
                                        {{-- <td style="max-width:200px; text-overflow: ellipsis; overflow: hidden;">{{ $cliente->direccion }}</td> --}}
                                        <td>@if($cliente->abonado) <i class="fa-solid fa-check text-success"></i> @else <i class="fa-solid fa-xmark text-danger"></i> @endif</td>
                                        <td>{{ $cliente->tipo_abonado }}</td>


                                        <td>
                                        <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editClienteModal" wire:click="edit({{ $cliente->id }})">Ver/Editar</button>
                                        <button class="btn btn-sm btn-danger" wire:click="confirmDelete({{ $cliente->id }})">Eliminar</button>
                                        @if ($cliente->isReserva)
                                            <button class="btn btn-dark" wire:click="pdfDownload({{$cliente->id}})">PDF Reserva</button>
                                        @endif

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->


    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="createClienteModal" tabindex="-1" role="dialog" aria-labelledby="createClienteLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createClienteLabel">Crear Cliente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="submit">
                            <div class="form-group col-6">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" id="nombre" wire:model="nombre" placeholder="Nombre">
                                @error('nombre') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group col-6">
                                <label for="apellido">Apellidos</label>
                                <input type="text" class="form-control" id="apellido" wire:model="apellidos" placeholder="Apellidos">
                                @error('apellidos') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group col-6">
                                <label for="nombre">DNI</label>
                                <input type="text" class="form-control" id="DNI" wire:model="DNI" placeholder="DNI">
                                @error('DNI') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group col-6">
                                <label for="email1">Email</label>
                                <input type="email" class="form-control" id="email1" wire:model="email" placeholder="Email">
                                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group col-6">
                                <label for="fijo">Teléfono Fijo</label>
                                <input type="text" class="form-control" id="fijo" wire:model="fijo" placeholder="Teléfono Fijo">
                                @error('fijo') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group col-6">
                                <label for="movil">Teléfono Movil</label>
                                <input type="text" class="form-control" id="movil" wire:model="movil" placeholder="Movil">
                                @error('movil') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="form-group col-6">
                                <label for="ciudad">Dirección</label>
                                <input type="text" class="form-control" id="direccion" wire:model="direccion" placeholder="Direccion">
                                @error('direccion') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group col-6">
                                <label for="codigo_postal">Codigo Postal</label>
                                <input type="text" class="form-control" id="codigo_postal" wire:model="codigo_postal" placeholder="Código postal">
                                @error('codigo_postal') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group col-6">
                                <label for="codigo_postal">Población</label>
                                <input type="text" class="form-control" id="poblacion" wire:model="poblacion" placeholder="Población">
                                @error('poblacion') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
    
                            <div class="form-group col-6">
                                <label for="codigo_postal">Provincia</label>
                                <input type="text" class="form-control" id="provincia" wire:model="provincia" placeholder="Provincia">
                                @error('provincia') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Guardar Cliente</button>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    <!-- Modal para Editar Cliente -->
    <div wire:ignore.self class="modal fade" id="editClienteModal" tabindex="-1" role="dialog" aria-labelledby="editClienteLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editClienteLabel">Editar Cliente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="submit">
                    <div class="form-group col-12">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" id="nombre" wire:model="nombre" placeholder="Nombre">
                                @error('nombre') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group col-12">
                                <label for="apellido">Apellidos</label>
                                <input type="text" class="form-control" id="apellido" wire:model="apellidos" placeholder="Apellidos">
                                @error('apellidos') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group col-12">
                                <label for="nombre">DNI</label>
                                <input type="text" class="form-control" id="DNI" wire:model="DNI" placeholder="DNI">
                                @error('DNI') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group col-12">
                                <label for="email1">Email</label>
                                <input type="email" class="form-control" id="email1" wire:model="email" placeholder="Email">
                                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group col-12">
                                <label for="fijo">Teléfono Fijo</label>
                                <input type="text" class="form-control" id="fijo" wire:model="fijo" placeholder="Teléfono Fijo">
                                @error('fijo') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group col-12">
                                <label for="movil">Teléfono Movil</label>
                                <input type="text" class="form-control" id="movil" wire:model="movil" placeholder="Movil">
                                @error('movil') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="form-group col-12">
                                <label for="ciudad">Dirección</label>
                                <input type="text" class="form-control" id="direccion" wire:model="direccion" placeholder="Direccion">
                                @error('direccion') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group col-12">
                                <label for="codigo_postal">Codigo Postal</label>
                                <input type="text" class="form-control" id="codigo_postal" wire:model="codigo_postal" placeholder="Código postal">
                                @error('codigo_postal') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group col-12">
                                <label for="codigo_postal">Población</label>
                                <input type="text" class="form-control" id="poblacion" wire:model="poblacion" placeholder="Población">
                                @error('poblacion') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group col-12">
                                <label for="codigo_postal">Provincia</label>
                                <input type="text" class="form-control" id="provincia" wire:model="provincia" placeholder="Provincia">
                                @error('provincia') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group col-12">
                                <label for="abonado">Abonado</label>
                                <input type="checkbox" class="form-check" id="abonado" wire:model="abonado" placeholder="abonado">
                                @error('abonado') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group col-12">
                                <label for="tipo_abonado">Tipo - Palco</label>
                                <input type="checkbox" class="form-check" id="tipo_abonado" wire:model="tipo_abonado" placeholder="tipo_abonado">
                                @error('tipo_abonado') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        <button type="submit" class="btn btn-primary">Actualizar Cliente</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <style>
        .dataTables_filter {
            text-align: end;
        }
        .dataTables_filter label {
            text-align: start;
            width: 100%;
        }
        .tabla {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
    </style>
</div>

@section('scripts')
<script src="../assets/js/jquery.slimscroll.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-2.0.3/b-3.0.1/b-colvis-3.0.1/b-html5-3.0.1/b-print-3.0.1/r-3.0.1/datatables.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>

<script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-2.0.3/b-3.0.1/b-colvis-3.0.1/b-html5-3.0.1/b-print-3.0.1/r-3.0.1/datatables.min.js"></script>
<script>
   document.addEventListener('livewire:load', function () {
    window.livewire.on('open-create-modal', () => {
        $('#createClienteModal').modal('show');
    });

    window.livewire.on('open-edit-modal', () => {
        $('#editClienteModal').modal('show');
    });

    window.livewire.on('close-modal', () => {
        $('#createClienteModal').modal('hide');
        $('#editClienteModal').modal('hide');
    });
});

</script>

@endsection
