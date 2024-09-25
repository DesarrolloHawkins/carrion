<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;
use League\Csv\Statement;
use Carbon\Carbon;

class ClienteImportController extends Controller
{
    public function importClientes(Request $request)
{
    // Validar el archivo y el tipo de abonado
    $request->validate([
        'file' => 'required|mimes:csv,txt',
        'tipo_abonado' => 'required|in:palco,silla',
    ]);

    // Leer el archivo CSV
    $file = $request->file('file');
    $csv = Reader::createFromPath($file->getPathname(), 'r');

    // Obtener las cabeceras del CSV y manejar duplicados
    $headers = $csv->fetchOne(); // Obtiene la primera fila (cabecera)

    // Solución: renombrar cabeceras duplicadas
    $uniqueHeaders = [];
    foreach ($headers as $key => $header) {
        if (in_array($header, $uniqueHeaders)) {
            // Si hay un duplicado, renombrarlo agregando un índice único
            $headers[$key] = $header . '_' . $key;
        }
        $uniqueHeaders[] = $headers[$key];
    }

    // Aplicar las cabeceras únicas
    $csv->setHeaderOffset(0); // El CSV tiene cabecera en la fila 0, ya con las cabeceras ajustadas

    // Obtener los registros con las cabeceras procesadas
    $records = $csv->getRecords();
    $tipoAbonado = $request->input('tipo_abonado');
    //dd($records);
    foreach ($records as $record) {
        //si el nombre es nulo, no se importa
        if (empty($record[$headers[1]])) {
            continue;
        }
        Cliente::create([
            'nombre' => $record[$headers[1]],  // Usar el nuevo nombre de columna
            'apellidos' => $record[$headers[0]],
            'DNI' => null,
            'fijo' => $record[$headers[6]],
            'movil' => $record[$headers[7]],
            'email' => '',
            'abonado' => 1,
            'tipo_abonado' => $tipoAbonado,
        ]);
    }

    return redirect()->back()->with('success', 'Clientes importados correctamente');
}


    public function getterForm ()
    {
        return view('importClientes.index');
    }
}
