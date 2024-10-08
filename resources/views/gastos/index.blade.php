@extends('layouts.app')

@section('title', 'Ver Gastos')

@section('content-principal')
<div class="container" style="max-width: 95%;">
    <h1 class="mb-4 text-center">Gestión de Gastos</h1>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('gastos.create') }}" class="btn btn-primary">Crear Gasto</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>Cliente</th>
                    <th>Concepto</th>
                    <th>Precio</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($gastos as $gasto)
                <tr>
                    <td>{{ $gasto->cliente ? $gasto->cliente->nombre . ' ' . $gasto->cliente->apellidos : 'N/A' }}</td>
                    <td>{{ $gasto->concepto }}</td>
                    <td>{{ $gasto->precio }}</td>
                    <td>{{ $gasto->fecha }}</td>
                    <td>
                        <a class="btn-warning bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded" href="{{ route('gastos.edit', $gasto->id) }}">
                            Editar
                        </a>
                        <form action="{{ route('gastos.destroy', $gasto->id) }}" method="POST" style="display:inline;">
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
        {{ $gastos->links() }}
    </div>
</div>
@endsection
