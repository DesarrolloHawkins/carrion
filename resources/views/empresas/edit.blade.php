@extends('layouts.app')

@section('title', 'Editar Empresa')

@section('content-principal')
<div class="container" style="max-width: 95%;">
    <h1 class="mb-4 text-center">Editar Empresa</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Datos de la Empresa</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('empresas.update', $empresa->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Datos de la empresa -->
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $empresa->nombre) }}">
                    @error('nombre')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="text" class="form-control @error('telefono') is-invalid @enderror" id="telefono" name="telefono" value="{{ old('telefono', $empresa->telefono) }}">
                    @error('telefono')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $empresa->email) }}">
                    @error('email')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Saldo Inicial -->
                <div class="form-group">
                    <label for="saldo_inicial">Saldo Inicial</label>
                    <input type="number" step="0.01" class="form-control @error('saldo_inicial') is-invalid @enderror" id="saldo_inicial" name="saldo_inicial" value="{{ old('saldo_inicial', $empresa->saldosIniciales->first()->saldo_inicial ?? '') }}">
                    @error('saldo_inicial')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="año">Año</label>
                    <input type="number" class="form-control @error('año') is-invalid @enderror" id="año" name="año" value="{{ old('año', $empresa->saldosIniciales->first()->año ?? '') }}">
                    @error('año')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Actualizar Empresa</button>
            </form>
        </div>
    </div>
</div>
@endsection
