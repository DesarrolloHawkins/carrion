@extends('layouts.app')

@section('title', 'Editar Gasto')

@section('content-principal')
<div class="container" style="max-width: 95%;">
    <h1 class="mb-4 text-center">Editar Gasto</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Datos del Gasto</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('gastos.update', $gasto->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="proveedor_id">Proveedor</label>
                    <select class="form-control @error('proveedor_id') is-invalid @enderror" id="proveedor_id" name="proveedor_id">
                        <option value="">Seleccionar proveedor...</option>
                        @foreach($proveedores as $proveedor)
                            <option value="{{ $proveedor->id }}" {{ old('proveedor_id', $gasto->proveedor_id ?? '') == $proveedor->id ? 'selected' : '' }}>
                                {{ $proveedor->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('proveedor_id')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>                
                <div class="form-group">
                    <label for="concepto">Concepto</label>
                    <input type="text" class="form-control @error('concepto') is-invalid @enderror" id="concepto" name="concepto" value="{{ old('concepto', $gasto->concepto) }}" placeholder="Concepto del gasto">
                    @error('concepto')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="precio">Precio</label>
                    <input type="number" step="0.01" class="form-control @error('precio') is-invalid @enderror" id="precio" name="precio" value="{{ old('precio', $gasto->precio) }}" placeholder="Precio del gasto">
                    @error('precio')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="fecha">Fecha</label>
                    <input type="date" class="form-control @error('fecha') is-invalid @enderror" id="fecha" name="fecha" value="{{ old('fecha', $gasto->fecha) }}">
                    @error('fecha')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Actualizar Gasto</button>
            </form>
        </div>
    </div>
</div>
@endsection
