@extends('layouts.app')

@section('title', 'Ver Deudas')

@section('content-principal')
<div class="container" style="max-width: 95%;">
    <h1 class="mb-4 text-center">Gestión de Deudas</h1>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('deudas.create') }}" class="btn btn-primary">Crear Deuda</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>Cliente</th>
                    <th>Concepto</th>
                    <th>Cantidad</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Fecha de Pago</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($deudas as $deuda)
                <tr>
                    <td>{{ $deuda->cliente->nombre }} {{ $deuda->cliente->apellidos }}</td>
                    <td>{{ $deuda->concepto }}</td>
                    <td>{{ $deuda->cantidad }}</td>
                    <td>{{ $deuda->fecha }}</td>
                    <td>{{ $deuda->pagada ? 'Pagada' : 'No Pagada' }}</td>
                    <td>{{ $deuda->pagada ? $deuda->fecha_pago : '-' }}</td>
                    <td>
                        @if(!$deuda->pagada)
                        <form action="{{ route('deudas.marcarComoPagada', $deuda->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success">Marcar como Pagada</button>
                        </form>
                        @endif
                        <a class="btn-warning bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded" href="{{ route('deudas.edit', $deuda->id) }}">
                            Editar
                        </a>
                        <form action="{{ route('deudas.destroy', $deuda->id) }}" method="POST" style="display:inline;">
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
        {{ $deudas->links() }}
    </div>
</div>
@endsection
