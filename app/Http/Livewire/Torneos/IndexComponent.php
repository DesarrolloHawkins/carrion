<?php

namespace App\Http\Livewire\Torneos;

use Livewire\Component;
use Livewire\WithFileUploads; // Agrega esta línea
use App\Models\Torneos;
use Illuminate\Support\Facades\Storage;

class IndexComponent extends Component
{
    use WithFileUploads; // Agrega esta línea

    public $nombre;
    public $descripcion;
    public $imagen;
    public $normativa;
    public $precio;
    public $precio_socio;
    public $precio_pronto_pago;
    public $precio_socio_pronto_pago;
    public $condiciones;
    public $inscripcion;


    public $torneos;

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
        'imagen' => 'nullable|image|max:1024', // Asegúrate de permitir imágenes y limitar el tamaño
        'normativa' => 'nullable|file|mimes:pdf|max:10240', // Máximo 10 MB
        'precio' => 'required|numeric',
        'precio_socio' => 'nullable|numeric',
        'precio_pronto_pago' => 'nullable|numeric',
        'precio_socio_pronto_pago' => 'nullable|numeric',
        'condiciones' => 'nullable|string',
        'inscripcion' => 'required|string',
    ];

    public function mount()
    {
        $this->inscripcion = 'individual';
        $this->loadTorneos();
    }

    public function getNumInscripciones($id)
    {
        return Torneos::find($id)->inscripciones->count();
    }

    public function render()
    {
        return view('livewire.torneos.index-component');
    }

    public function loadTorneos()
    {
        $this->torneos = Torneos::all();
    }

    public function resetFields()
    {
        $this->reset([
            'nombre',
            'descripcion',
            'imagen',
            'normativa',
            'precio',
            'precio_socio',
            'precio_pronto_pago',
            'precio_socio_pronto_pago',
            'condiciones',
        ]);
    }

    public function store()
    {
        $this->validate();

        // Handle the file upload for image
        $imagenPath = null;
        if ($this->imagen) {
            $imagenPath = $this->imagen->store('torneos', 'public');
        }

        // Handle the file upload for normativa
        $normativaPath = null;
        if ($this->normativa) {
            $normativaPath = $this->normativa->store('torneos/normativas', 'public');
        }

        Torneos::create([
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'imagen' => $imagenPath,
            'normativa' => $normativaPath,
            'precio' => $this->precio,
            'precio_socio' => $this->precio_socio,
            'precio_pronto_pago' => $this->precio_pronto_pago,
            'precio_socio_pronto_pago' => $this->precio_socio_pronto_pago,
            'condiciones' => $this->condiciones,
            'inscripcion' => $this->inscripcion,
        ]);

        session()->flash('message', 'Torneo creado correctamente.');

        $this->resetFields();
        $this->loadTorneos();
        $this->emit('closeModal');
    }

    public function deleteTorneo($id)
    {
        $torneo = Torneos::find($id);
        if ($torneo) {
            // // Delete the image if it exists
            // if ($torneo->imagen && Storage::exists('public/' . $torneo->imagen)) {
            //     Storage::delete('public/' . $torneo->imagen);
            // }
            // // Delete the normativa file if it exists
            // if ($torneo->normativa && Storage::exists('public/' . $torneo->normativa)) {
            //     Storage::delete('public/' . $torneo->normativa);
            // }
            $torneo->delete();
            $this->loadTorneos();
            session()->flash('message', 'Torneo eliminado correctamente.');
        }
    }
}
