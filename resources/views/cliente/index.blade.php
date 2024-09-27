
@extends('layouts.app')

@section('title', 'Ver Clientes')


@section('content-principal')
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
    <!-- Mostrar mensajes de éxito -->
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif
<div class="container" style="max-width: 95%;">
    <h1 class="mb-4 text-center">Gestión de Clientes</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Buscar Clientes</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('clientes.index') }}" method="GET" class="form-row align-items-center">
                <div class="col-sm-4 my-1">
                    <input type="text" name="filtro" class="form-control" placeholder="Buscar por nombre o DNI..." value="{{ $filtro }}">
                </div>
                <div class="col-sm-2 my-1">
                    <select name="abonado" class="form-control">
                        <option value="">Abonado</option>
                        <option value="1"{{ $abonado == '1' ? ' selected' : '' }}>Sí</option>
                        <option value="0"{{ $abonado == '0' ? ' selected' : '' }}>No</option>
                    </select>
                </div>
                <div class="col-sm-2 my-1">
                    <select name="tipo_abonado" class="form-control">
                        <option value="">Tipo de Abonado</option>
                        @foreach ($tiposAbonado as $tipo)
                        <option value="{{ $tipo }}"{{ $tipo_abonado == $tipo ? ' selected' : '' }}>{{ $tipo }}</option>
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

    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('clientes.create', request()->all()) }}" class="btn btn-primary">Crear Cliente</a>
        <a href="{{ route('clientes.export', request()->all()) }}" class="btn btn-success">Exportar a Excel</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead class="thead-light">
                <tr>
                    @foreach(['nombre' => 'Nombre', 'apellidos' => 'Apellidos', 'DNI' => 'DNI', 'fijo' => 'Teléfono Fijo', 'movil' => 'Teléfono Móvil', 'email' => 'Email', 'abonado' => 'Abonado', 'tipo_abonado' => 'Tipo de Abonado'] as $col => $name)
                        <th class="border px-2 py-2">
                            <a href="{{ route('clientes.index', [
                                'sortColumn' => $col,
                                'sortDirection' => $sortColumn == $col && $sortDirection == 'asc' ? 'desc' : 'asc',
                                'filtro' => request()->filtro,
                                'abonado' => request()->abonado,
                                'tipo_abonado' => request()->tipo_abonado,
                                'perPage' => request()->perPage
                            ]) }}">
                                {{ $name }}
                                @if ($sortColumn == $col)
                                <span>{!! $sortDirection == 'asc' ? '&#9650;' : '&#9660;' !!}</span>
                                @endif
                            </a>
                        </th>
                    @endforeach
                    <th class="border px-2 py-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clientes as $cliente)
                <tr>
                    <td>{{ $cliente->nombre }}</td>
                    <td>{{ $cliente->apellidos }}</td>
                    <td>{{ $cliente->DNI }}</td>
                    <td>{{ $cliente->fijo }}</td>
                    <td>{{ $cliente->movil }}</td>
                    <td>{{ $cliente->email }}</td>
                    <td>
                        @if($cliente->abonado)
                        <span class="badge badge-success">Sí</span>
                        @else
                        <span class="badge badge-danger">No</span>
                        @endif
                    </td>
                    <td>{{ $cliente->tipo_abonado }}</td>
                    <td class="border px-2 py-2">
                        <a class="btn-warning bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 my-1 rounded" href="{{route('clientes.edit', $cliente->id)}}" >
                            Ver/Editar
                        </a>
                        <a class="btn-danger bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 my-1 rounded" href="{{route('clientes.delete', $cliente->id)}}">
                            Eliminar
                        </a>
                        {{-- @if ($cliente->isReserva)
                            <a class="btn-dark text-white font-bold py-1 px-2 my-1 rounded" wire:click="pdfDownload({{ $cliente->id }})">
                                PDF Reserva
                            </a>
                        @endif --}}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div>
        {{ $clientes->appends([
            'filtro' => request()->filtro,
            'abonado' => request()->abonado,
            'tipo_abonado' => request()->tipo_abonado,
            'perPage' => request()->perPage,
            'sortColumn' => request()->sortColumn,
            'sortDirection' => request()->sortDirection
        ])->links('vendor.pagination.bootstrap-5') }}
    </div>
</div>



@endsection