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

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $cliente->nombre) }}" placeholder="Nombre del cliente">
                        @error('nombre')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="apellidos">Apellidos</label>
                        <input type="text" class="form-control @error('apellidos') is-invalid @enderror" id="apellidos" name="apellidos" value="{{ old('apellidos', $cliente->apellidos) }}" placeholder="Apellidos del cliente">
                        @error('apellidos')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="email">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $cliente->email) }}" placeholder="Correo electrónico del cliente">
                        @error('email')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="telefono">Teléfono</label>
                        <input type="text" class="form-control @error('telefono') is-invalid @enderror" id="telefono" name="telefono" value="{{ old('telefono', $cliente->telefono) }}" placeholder="Teléfono del cliente">
                        @error('telefono')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                        <input type="date" class="form-control @error('fecha_nacimiento') is-invalid @enderror" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $cliente->fecha_nacimiento) }}">
                        @error('fecha_nacimiento')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="genero">Género</label>
                        <select class="form-control @error('genero') is-invalid @enderror" id="genero" name="genero">
                            <option value="">Seleccionar...</option>
                            <option value="masculino"{{ old('genero', $cliente->genero) == 'masculino' ? ' selected' : '' }}>Masculino</option>
                            <option value="femenino"{{ old('genero', $cliente->genero) == 'femenino' ? ' selected' : '' }}>Femenino</option>
                        </select>
                        @error('genero')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="domicilio">Domicilio</label>
                        <input type="text" class="form-control @error('domicilio') is-invalid @enderror" id="domicilio" name="domicilio" value="{{ old('domicilio', $cliente->domicilio) }}" placeholder="Domicilio del cliente">
                        @error('domicilio')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="ciudad">Ciudad</label>
                        <input type="text" class="form-control @error('ciudad') is-invalid @enderror" id="ciudad" name="ciudad" value="{{ old('ciudad', $cliente->ciudad) }}" placeholder="Ciudad del cliente">
                        @error('ciudad')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">Actualizar Cliente</button>
            </form>
        </div>
    </div>
    <!-- Tabla de deudas -->
    <div class="card shadow mb-4 mt-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Deudas del Cliente</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>Concepto</th>
                            <th>Cantidad</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Fecha de Pago</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalPendiente = 0;
                        @endphp

                        @foreach($cliente->deudas as $deuda)
                            @php
                                $filaColor = $deuda->pagada ? 'table-success' : 'table-danger';
                                if (!$deuda->pagada) {
                                    $totalPendiente += $deuda->cantidad;
                                }
                            @endphp
                            <tr class="{{ $filaColor }}">
                                <td>{{ $deuda->concepto }}</td>
                                <td>{{ number_format($deuda->cantidad, 2) }}</td>
                                <td>{{ $deuda->fecha }}</td>
                                <td>{{ $deuda->pagada ? 'Pagada' : 'No Pagada' }}</td>
                                <td>{{ $deuda->pagada ? $deuda->fecha_pago : '-' }}</td>
                            </tr>
                        @endforeach

                        <!-- Total deuda pendiente -->
                        <tr>
                            <td colspan="4" class="text-right font-weight-bold">Total Deuda Pendiente:</td>
                            <td>{{ number_format($totalPendiente, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
