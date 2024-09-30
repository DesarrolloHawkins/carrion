@extends('layouts.app')

@section('title', 'Crear Empresa')

@section('content-principal')
<div class="container" style="max-width: 95%;">
    <h1 class="mb-4 text-center">Crear Nueva Empresa</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Datos de la Empresa</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('empresas.store') }}" method="POST">
                @csrf
                <!-- Datos de la empresa -->
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}">
                    @error('nombre')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="text" class="form-control @error('telefono') is-invalid @enderror" id="telefono" name="telefono" value="{{ old('telefono') }}">
                    @error('telefono')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                    @error('email')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="direccion">Dirección</label>
                    <input type="text" class="form-control @error('direccion') is-invalid @enderror" id="direccion" name="direccion" value="{{ old('direccion') }}">
                    @error('direccion')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="cif">CIF</label>
                    <input type="text" class="form-control @error('cif') is-invalid @enderror" id="cif" name="cif" value="{{ old('cif') }}">
                    @error('cif')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Otros campos legales -->
                <div class="form-group">
                    <label for="legal1">Legal 1</label>
                    <input type="text" class="form-control @error('legal1') is-invalid @enderror" id="legal1" name="legal1" value="{{ old('legal1') }}">
                    @error('legal1')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Saldo Inicial -->
                <div class="form-group">
                    <label for="saldo_inicial">Saldo Inicial</label>
                    <input type="number" step="0.01" class="form-control @error('saldo_inicial') is-invalid @enderror" id="saldo_inicial" name="saldo_inicial" value="{{ old('saldo_inicial') }}">
                    @error('saldo_inicial')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="año">Año</label>
                    <input type="number" class="form-control @error('año') is-invalid @enderror" id="año" name="año" value="{{ old('año') }}">
                    @error('año')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Crear Empresa</button>
            </form>
        </div>
    </div>
</div>
@endsection
