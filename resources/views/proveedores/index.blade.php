@extends('layouts.app')

@section('title', 'Ver Proveedores')

@section('content-principal')
<div class="container" style="max-width: 95%;">
    <h1 class="mb-4 text-center">Gestión de Proveedores</h1>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('proveedores.create') }}" class="btn btn-primary">Crear Proveedor</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($proveedores as $proveedor)
                <tr>
                    <td>{{ $proveedor->nombre }}</td>
                    <td>{{ $proveedor->direccion }}</td>
                    <td>{{ $proveedor->telefono }}</td>
                    <td>{{ $proveedor->email }}</td>
                    <td>
                        <a class="btn-warning bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded" href="{{ route('proveedores.edit', $proveedor->id) }}">
                            Editar
                        </a>
                        <form action="{{ route('proveedores.destroy', $proveedor->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div>
        {{ $proveedores->links() }}
    </div>
</div>
@endsection
