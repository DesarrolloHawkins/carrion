<form action="/admin/import-clientes" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" required>
    
    <!-- Selección entre "palco" o "silla" -->
    <label for="tipo_abonado">Tipo de Abonado</label>
    <select name="tipo_abonado" id="tipo_abonado" required>
        <option value="palco">Palco</option>
        <option value="silla">Silla</option>
    </select>

    <button type="submit">Importar Clientes</button>
</form>
