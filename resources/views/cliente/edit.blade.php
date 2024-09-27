@extends('layouts.app')

@section('title', 'Editar Cliente')

@section('content-principal')
<div class="container" style="max-width: 95%;">
    <h1 class="mb-4 text-center">Editar Cliente</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Datos del Cliente</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('clientes.update', $cliente->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $cliente->nombre) }}" placeholder="Nombre del cliente" >
                        @error('nombre')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="apellidos">Apellidos</label>
                        <input type="text" class="form-control @error('apellidos') is-invalid @enderror" id="apellidos" name="apellidos" value="{{ old('apellidos', $cliente->apellidos) }}" placeholder="Apellidos del cliente" >
                        @error('apellidos')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="DNI">DNI</label>
                        <input type="text" class="form-control @error('DNI') is-invalid @enderror" id="DNI" name="DNI" value="{{ old('DNI', $cliente->DNI) }}" placeholder="DNI del cliente" >
                        @error('DNI')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="movil">Teléfono Móvil</label>
                        <input type="text" class="form-control @error('movil') is-invalid @enderror" id="movil" name="movil" value="{{ old('movil', $cliente->movil) }}" placeholder="Teléfono móvil del cliente" >
                        @error('movil')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="fijo">Teléfono Fijo</label>
                        <input type="text" class="form-control @error('fijo') is-invalid @enderror" id="fijo" name="fijo" value="{{ old('fijo', $cliente->fijo) }}" placeholder="Teléfono fijo del cliente (opcional)">
                        @error('fijo')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="email">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $cliente->email) }}" placeholder="Correo electrónico del cliente" >
                        @error('email')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="abonado">¿Es Abonado?</label>
                        <select class="form-control @error('abonado') is-invalid @enderror" id="abonado" name="abonado">
                            <option value="">Seleccionar...</option>
                            <option value="1"{{ old('abonado', $cliente->abonado) == '1' ? ' selected' : '' }}>Sí</option>
                            <option value="0"{{ old('abonado', $cliente->abonado) == '0' ? ' selected' : '' }}>No</option>
                        </select>
                        @error('abonado')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="tipo_abonado">Tipo de Abonado</label>
                        <select class="form-control @error('tipo_abonado') is-invalid @enderror" id="tipo_abonado" name="tipo_abonado">
                            <option value="">Sin tipo</option>
                            <option value="palco"{{ old('tipo_abonado', $cliente->tipo_abonado) == 'palco' ? ' selected' : '' }}>Palco</option>
                            <option value="silla"{{ old('tipo_abonado', $cliente->tipo_abonado) == 'silla' ? ' selected' : '' }}>Silla</option>
                        </select>
                        @error('tipo_abonado')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </form>
        </div>
    </div>
</div>
@endsection



