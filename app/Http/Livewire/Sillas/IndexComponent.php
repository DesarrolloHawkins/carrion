<?php

namespace App\Http\Livewire\Sillas;

use App\Models\Sillas;
use App\Models\Palcos;
use App\Models\Zonas;
use App\Models\Sectores;
use App\Models\Gradas;
use App\Models\SectoresZonas;
use Livewire\Component;
use Livewire\WithFileUploads;

class IndexComponent extends Component
{
    use WithFileUploads;

    public $sillas;
    public $csv_file;
    public $selectedModel;

    public function mount()
    {
        $this->sillas = Sillas::all();
    }

    public function importCsv()
    {
        //dd($this->csv_file);
        $this->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
            'selectedModel' => 'required|string',
        ]);

        $path = $this->csv_file->getRealPath();
        $data = array_map('str_getcsv', file($path));

        switch ($this->selectedModel) {
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
                session()->flash('message', 'Modelo no válido seleccionado.');
                return;
        }

        session()->flash('message', 'Archivo CSV importado correctamente.');
        $this->sillas = Sillas::all(); // Refrescar la lista de sillas después de la importación, si es necesario.
    }

    protected function processPalcos(array $data)
    {
        foreach ($data as $row) {

            if ($row[3] == 'zona') continue;

            $zona = Zonas::where('nombre', $row[3])->first();

            if (!$zona) {
                $zona = Zonas::create([
                    'nombre' => $row[3],
                ]);
            }

            $sector = Sectores::where('nombre', $row[4])->where('id_zona', $zona->id)->first();

            if (!$sector) {
                $sector = Sectores::create([
                    'nombre' => $row[4],
                    'id_zona' => $zona->id,
                ]);
            }


            Palcos::create([
                'num_sillas' => 8,
                'id_zona' => $zona->id,
                'id_sector' => $sector->id,
                'ext_prop' => $row[2],
                'numero' => $row[5],
            ]);
        }
    }

    protected function processZonas(array $data)
    {

        foreach ($data as $row) {

            if($row[1] == 'zona') continue;

            Zonas::create([
                'nombre' => $row[1],
            ]);
        }
    }

    protected function processSectores(array $data)
    {


        foreach ($data as $row) {

            if($row[1] == 'sector') continue;

            //ZONA
            $zona = Zonas::where('nombre', $row[2])->first();

            if (!$zona) {
                //crea la zona
                $zona = Zonas::create([
                    'nombre' => $row[2],
                ]);
            }

            if (Sectores::where('nombre', $row[1])->where('id_zona', $zona->id)->exists()) {
                continue;
            }

            Sectores::create([
                'nombre' => $row[1],
                'id_zona' => $zona->id,
            ]);
        }


    }

    protected function processGradas(array $data)
    {
        foreach ($data as $row) {

            if($row[2] == 'grada') continue;

            $zona = Zonas::where('nombre', $row[1])->first();

            if (!$zona) {
                //crea la zona
                $zona = Zonas::create([
                    'nombre' => $row[1],
                ]);
            }

            if (Gradas::where('numero', $row[2])->where('id_zona', $zona->id)->exists()) {
                continue;
            }


            Gradas::create([
                'numero' => $row[2],
                'id_zona' => $zona->id,
            ]);
        }



    }

    protected function processSillas(array $data)
    {
        foreach ($data as $row) {

            if($row[1] == 'numero') continue;

            $zona = Zonas::where('nombre', $row[3])->first();

            if (!$zona) {
                //crea la zona
                $zona = Zonas::create([
                    'nombre' => $row[3],
                ]);
            }

            $grada = Gradas::where('numero', $row[1])->where('id_zona', $zona->id)->first();

            if (!$grada) {
                //crea la grada
                $grada = Gradas::create([
                    'numero' => $row[1],
                    'id_zona' => $zona->id,
                ]);
            }

            Sillas::create([
                'numero' => $row[1],
                'fila' => $row[2],
                'id_grada' => $grada->id,
                'id_zona' => $zona->id,
            ]);
        }
    }

    public function render()
    {
        return view('livewire.sillas.index-component');
    }
}
