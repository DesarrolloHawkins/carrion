<!-- resources/views/livewire/sillas/index-component.blade.php -->
<div>
    <h2>Gestión de Sillas y Otros Modelos</h2>

    <!-- Aquí puedes mostrar las sillas ya existentes -->
    <table class="table">
        <thead>
            <tr>
                <th>Número</th>
                <th>Grada</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sillas as $silla)
                <tr>
                    <td>{{ $silla->numero }}</td>
                    <td>{{ $silla->id_grada }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Formulario para subir CSV -->
    <h3>Importar CSV</h3>
    <form wire:submit.prevent="importCsv" >
        <div class="form-group">
            <label for="model">Selecciona el modelo:</label>
            <select wire:model="selectedModel" id="model" class="form-control" required>
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
            <input type="file" wire:model="csv_file" class="form-control" >
        </div>
        <button type="submit" class="btn btn-primary">Subir</button>
    </form>

    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
</div>