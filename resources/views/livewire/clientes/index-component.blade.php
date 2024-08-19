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
                    <form wire:submit.prevent="">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre" wire:model="nombre" placeholder="Nombre">
                            @error('nombre') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="apellido">Apellidos</label>
                            <input type="text" class="form-control" id="apellido" wire:model="apellido" placeholder="Apellidos">
                            @error('apellido') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="nombre">DNI</label>
                            <input type="text" class="form-control" id="DNI" wire:model="DNI" placeholder="DNI">
                            @error('DNI') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="nickName">nickName</label>
                            <input type="text" class="form-control" id="nickName" wire:model="nickName" placeholder="nickName">
                            @error('nickName') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="tlf1">Teléfono</label>
                            <input type="text" class="form-control" id="tlf1" wire:model="telefono" placeholder="Teléfono">
                            @error('tlf1') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="email1">Email</label>
                            <input type="email" class="form-control" id="email1" wire:model="email1" placeholder="Email">
                            @error('email1') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="ciudad">Ciudad</label>
                            <input type="text" class="form-control" id="ciudad" wire:model="ciudad" placeholder="Ciudad">
                            @error('ciudad') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="genero">Género</label>
                            <input type="text" class="form-control" id="genero" wire:model="genero" placeholder="Género">
                            @error('genero') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="categoria_id">Categoría</label>
                            <select class="form-control" id="categoria_id" wire:model="categoria_id">
                                <option value="">-- Seleccione categoria --</option>
                                @foreach ($categorias as $categoria )
                                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                @endforeach
                            </select>
                            @error('categoria_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="btn btn-primary" wire:click="submit" @if($cliente_id) data-dismiss="modal" @endif>{{ $cliente_id ? 'Actualizar' : 'Guardar' }}</button>
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
                                    <th scope="col">NickName</th>
                                    <th scope="col">Teléfono</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Ciudad</th>
                                    <th scope="col">Genero</th>

                                    <th scope="col">Categoría</th>

                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($clientes as $cliente)
                                    <tr>
                                        @if($cliente->tipo_cliente != 1)
                                            <td>{{ $cliente->nombre }}</td>
                                            <td>{{ $cliente->apellido }}</td>
                                        @else
                                            <td>{{ $cliente->nombre }}</td>
                                            <td></td>
                                        @endif
                                        <td>{{ $cliente->DNI }}</td>
                                        <td>{{ $cliente->nickName}}</td>
                                        <td>{{ $cliente->telefono }}</td>
                                        <td>{{ $cliente->email1 }}</td>
                                        <td>{{ $cliente->ciudad }}</td>
                                        <td>{{ $cliente->genero }}</td>
                                        <td>{{ $cliente->categoriaJugadores->nombre ?? 'N/A' }}</td>

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
