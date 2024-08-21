<!-- resources/views/upload-csv.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Subir Archivo CSV</h2>
    <form action="{{ route('processCsv') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="model">Selecciona el modelo:</label>
            <select name="model" id="model" class="form-control" required>
                <option value="">Seleccione un modelo</option>
                <option value="palcos">Palcos</option>
                <option value="zonas">Zonas</option>
                <option value="sectores">Sectores</option>
                <option value="gradas">Gradas</option>
                <option value="sillas">Sillas</option>
            </select>
        </div>
        <div class="form-group">
            <label for="csv_file">Archivo CSV:</label>
            <input type="file" name="csv_file" id="csv_file" class="form-control" accept=".csv" required>
        </div>
        <button type="submit" class="btn btn-primary">Subir</button>
    </form>
</div>
@endsection