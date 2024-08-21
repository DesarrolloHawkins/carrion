<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Palcos;

use App\Models\Zonas;

use App\Models\Sectores;

use App\Models\Gradas;

use App\Models\Sillas;



class CsvUploadController extends Controller
{
    public function processCsv(Request $request)
    {
        $request->validate([
            'model' => 'required|string',
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $model = $request->input('model');
        $file = $request->file('csv_file');

        $path = $file->getRealPath();

        $data = array_map('str_getcsv', file($path));

        switch ($model) {
            case 'palcos':
                $this->processPalcos($data);
                break;
            case 'zonas':
                $this->processZonas($data);
                break;
            case 'sectores':
                $this->processSectores($data);
                break;
            case 'gradas':
                $this->processGradas($data);
                break;
            case 'sillas':
                $this->processSillas($data);
                break;
            default:
                return redirect()->back()->withErrors('Modelo no válido seleccionado.');
        }

        return redirect()->back()->with('success', 'Archivo CSV procesado correctamente.');
    }

    protected function processPalcos(array $data)
    {
        foreach ($data as $row) {
            Palcos::create([
                'num_sillas' => $row[0],
                'id_zona' => $row[1],
                'id_sector' => $row[2],
                'ext_prop' => $row[3],
                'numero' => $row[4],
                'coordenada_x' => $row[5],
                'coordenada_y' => $row[6],
            ]);
        }
    }

    protected function processZonas(array $data)
    {
        foreach ($data as $row) {
            Zonas::create([
                'nombre' => $row[0],
            ]);
        }
    }

    protected function processSectores(array $data)
    {
        foreach ($data as $row) {
            Sectores::create([
                'nombre' => $row[0],
            ]);
        }
    }

    protected function processGradas(array $data)
    {
        foreach ($data as $row) {
            Gradas::create([
                'numero' => $row[0],
                'id_zona' => $row[1],
            ]);
        }
    }

    protected function processSillas(array $data)
    {
        foreach ($data as $row) {
            Sillas::create([
                'numero' => $row[0],
                'id_grada' => $row[1],
            ]);
        }
    }
}
