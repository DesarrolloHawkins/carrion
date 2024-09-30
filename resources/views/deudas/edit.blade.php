@extends('layouts.app')

@section('title', 'Editar Deuda')

@section('content-principal')
<div class="container" style="max-width: 95%;">
    <h1 class="mb-4 text-center">Editar Deuda</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Datos de la Deuda</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('deudas.update', $deuda->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="cliente_id">Cliente</label>
                    <select class="form-control @error('cliente_id') is-invalid @enderror" id="cliente_id" name="cliente_id">
                        <option value="">Seleccionar cliente...</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}" {{ old('cliente_id', $deuda->cliente_id) == $cliente->id ? 'selected' : '' }}>
                                {{ $cliente->nombre }} {{ $cliente->apellidos }}
                            </option>
                        @endforeach
                    </select>
                    @error('cliente_id')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="concepto">Concepto</label>
                    <input type="text" class="form-control @error('concepto') is-invalid @enderror" id="concepto" name="concepto" value="{{ old('concepto', $deuda->concepto) }}" placeholder="Concepto de la deuda">
                    @error('concepto')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="cantidad">Cantidad</label>
                    <input type="number" step="0.01" class="form-control @error('cantidad') is-invalid @enderror" id="cantidad" name="cantidad" value="{{ old('cantidad', $deuda->cantidad) }}" placeholder="Cantidad">
                    @error('cantidad')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="fecha">Fecha</label>
                    <input type="date" class="form-control @error('fecha') is-invalid @enderror" id="fecha" name="fecha" value="{{ old('fecha', $deuda->fecha) }}">
                    @error('fecha')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Actualizar Deuda</button>
            </form>
        </div>
    </div>
</div>
@endsection
