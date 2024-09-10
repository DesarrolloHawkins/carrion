<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;

class ClienteImportController extends Controller
{
    public function importClientes(Request $request)
{
    // Validar el archivo y el tipo de abonado
    $request->validate([
        'file' => 'required|mimes:csv,txt',
        'tipo_abonado' => 'required|in:palco,silla', // Validar que el tipo abonado es válido
    ]);

    // Leer el archivo CSV
    $file = $request->file('file');
    $csv = Reader::createFromPath($file->getPathname(), 'r');
    $csv->setHeaderOffset(0); // Si el CSV tiene cabecera

    $records = $csv->getRecords();
    $tipoAbonado = $request->input('tipo_abonado');  // Recoger el tipo de abonado

    foreach ($records as $record) {
        // Insertar solo las columnas necesarias, incluyendo el tipo de abonado
        Cliente::create([
            'nombre' => $record['Nombre'],
            'apellidos' => $record['Apellidos'],
            'DNI' => $record['DNI'],
            'fijo' => $record['Fijo'],
            'movil' => $record['Móvil'],
            'email' => $record['email'],
            'direccion' => $record['Dirección'],
            'codigo_postal' => $record['C.P.'],
            'poblacion' => $record['Población'],
            'provincia' => $record['Provincia'],
            'abonado' => 1,
            'tipo_abonado' => $tipoAbonado,  // Asignar "palco" o "silla" en base a la selección
        ]);
    }

    return redirect()->back()->with('success', 'Clientes importados correctamente');
}


    public function getterForm ()
    {
        return view('importClientes.index');
    }
}
