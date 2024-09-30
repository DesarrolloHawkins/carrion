@extends('layouts.app')

@section('title', 'Lista de Empresas')

@section('content-principal')
<div class="container" style="max-width: 95%;">
    <h1 class="mb-4 text-center">Empresas</h1>
    <a href="{{ route('empresas.create') }}" class="btn btn-primary mb-4">Crear Empresa</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>CIF</th>
                <th>Saldo Inicial ({{ date('Y') }})</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($empresas as $empresa)
                <tr>
                    <td>{{ $empresa->nombre }}</td>
                    <td>{{ $empresa->telefono }}</td>
                    <td>{{ $empresa->email }}</td>
                    <td>{{ $empresa->cif }}</td>
                    <td>{{ $saldoInicial->saldo_inicial ?? 'No registrado' }}</td>
                    <td>
                        <a href="{{ route('empresas.edit', $empresa->id) }}" class="btn btn-warning">Editar</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div>
        {{ $empresas->links() }}
    </div>
</div>
@endsection
