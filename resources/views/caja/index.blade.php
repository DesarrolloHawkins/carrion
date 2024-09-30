@extends('layouts.app')

@section('title', 'Diario de Caja')

@section('content-principal')
<div class="container" style="max-width: 95%;">
    <h1 class="mb-4 text-center">Diario de Caja</h1>

    <form action="{{ route('caja.index') }}" method="GET" class="form-inline mb-4">
        <label for="start_date">Fecha inicio:</label>
        <input type="date" name="start_date" class="form-control mx-2" value="{{ $startDate }}">

        <label for="end_date">Fecha fin:</label>
        <input type="date" name="end_date" class="form-control mx-2" value="{{ $endDate }}">

        <label for="tipo">Tipo:</label>
        <select name="tipo" class="form-control mx-2">
            <option value="todo"{{ $tipo == 'todo' ? ' selected' : '' }}>Todo</option>
            <option value="ingresos"{{ $tipo == 'ingresos' ? ' selected' : '' }}>Ingresos</option>
            <option value="gastos"{{ $tipo == 'gastos' ? ' selected' : '' }}>Gastos</option>
        </select>

        <button type="submit" class="btn btn-primary">Filtrar</button>
    </form>

    <table class="table table-hover table-bordered">
        <thead class="thead-light">
            <tr>
                <th>Asiento</th>
                <th>Fecha</th>
                <th>Concepto</th>
                <th>Debe</th>
                <th>Haber</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="5">Saldo Inicial</td>
                <td>{{ number_format($saldoInicial, 2) }}</td>
            </tr>
            @foreach($transaccionesConSaldo as $index => $transaccion)
            <tr>
                <td>{{ sprintf('%06d', $index + 1) }}</td> <!-- Formatear con 6 cifras -->
                <td>{{ $transaccion['fecha'] }}</td>
                <td>{{ $transaccion['concepto'] }}</td>
                <td>{{ $transaccion['debe'] ? number_format($transaccion['debe'], 2) : '' }}</td>
                <td>{{ $transaccion['haber'] ? number_format($transaccion['haber'], 2) : '' }}</td>
                <td>{{ number_format($transaccion['saldo'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
