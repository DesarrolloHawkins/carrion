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
        <div class="col-12 d-flex justify-content-end px-5">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" wire:click="resetFields">Crear Cliente</button>
        </div>
    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ $cliente_id ? 'Editar Cliente' : 'Nuevo Cliente' }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="" class="row ">
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
                        
                        <button type="submit" class="btn btn-primary col-8 m-auto" wire:click="submit" @if($cliente_id) data-dismiss="modal" @endif>{{ $cliente_id ? 'Actualizar' : 'Guardar' }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    @if (count($clientes) > 0)
                        <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Apellidos</th>
                                    <th scope="col">DNI</th>
                                    <th scope="col">Fijo</th>
                                    <th scope="col">Movil</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Direccion</th>


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
                                        <td>{{ $cliente->email }}</td>
                                        <td>{{ $cliente->direccion }}</td>


                                        <td>
                                            <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#exampleModal" wire:click="edit({{ $cliente->id }})">Ver/Editar</button>
                                            <button class="btn btn-sm btn-danger" wire:click="confirmDelete({{ $cliente->id }})">Eliminar</button>
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
</div>

@section('scripts')
<script>
    document.addEventListener('livewire:load', function () {
        window.livewire.on('close-modal', () => {
            $('#exampleModal').modal('hide');
        });
        window.livewire.on('open-modal', () => {
            $('#exampleModal').modal('show');
        });
    });
</script>
@endsection
