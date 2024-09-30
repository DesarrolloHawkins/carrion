@extends('layouts.app')

@section('title', 'Editar Ingreso')

@section('content-principal')
<div class="container" style="max-width: 95%;">
    <h1 class="mb-4 text-center">Editar Ingreso</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Datos del Ingreso</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('ingresos.update', $ingreso->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="concepto">Concepto</label>
                    <input type="text" class="form-control @error('concepto') is-invalid @enderror" id="concepto" name="concepto" value="{{ old('concepto', $ingreso->concepto) }}" placeholder="Concepto del ingreso">
                    @error('concepto')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="precio">Precio</label>
                    <input type="number" step="0.01" class="form-control @error('precio') is-invalid @enderror" id="precio" name="precio" value="{{ old('precio', $ingreso->precio) }}" placeholder="Precio del ingreso">
                    @error('precio')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="fecha">Fecha</label>
                    <input type="date" class="form-control @error('fecha') is-invalid @enderror" id="fecha" name="fecha" value="{{ old('fecha', $ingreso->fecha) }}">
                    @error('fecha')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Actualizar Ingreso</button>
            </form>
        </div>
    </div>
</div>
@endsection
