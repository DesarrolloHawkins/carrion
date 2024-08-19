<?php

namespace App\Http\Livewire\Pistas;

use Livewire\Component;
use App\Models\Pistas;
use App\Models\PistaTipo;
use App\Models\Deporte;
use App\Models\PistaCarasteristicas;
use App\Models\PistaTamano;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Index extends Component
{
    use LivewireAlert;

    public $pistas;
    public $tiposPista;
    public $deportes;
    public $caracteristicasPista;   
    public $tamanosPista;

    public $nombre;
    public $deporte_id;
    public $tipo_id;
    public $caracteristica_id;
    public $tamano_id;
    public $online;
    public $disponible;
    public $pista_id;
    public $deletePistaId;

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'deporte_id' => 'required',
        'tipo_id' => 'required',
        'caracteristica_id' => 'required',
        'tamano_id' => 'required',
        'online' => 'required|boolean',
        'disponible' => 'required|boolean',
    ];

    public function mount()
    {
        $this->loadPistas();
        $this->tiposPista = PistaTipo::all();
        $this->deportes = Deporte::all();
        $this->caracteristicasPista = PistaCarasteristicas::all();
        $this->tamanosPista = PistaTamano::all();
    }

    public function loadPistas()
    {
        $this->pistas = Pistas::with('deporteRelacion', 'tipoRelacion', 'caracteristicaRelacion', 'tamanoRelacion')->get();
    }

    public function resetFields()
    {
        $this->reset(['nombre', 'deporte_id', 'tipo_id', 'caracteristica_id', 'tamano_id', 'online', 'disponible', 'pista_id', 'deletePistaId']);
    }

    public function submit()
    {
        $validatedData = $this->validate();

        if ($this->pista_id) {
            $pista = Pistas::findOrFail($this->pista_id);
            $pista->update([
                'nombre' => $validatedData['nombre'],
                'deporte' => $validatedData['deporte_id'],
                'tipo' => $validatedData['tipo_id'],
                'caracteristicas' => $validatedData['caracteristica_id'],
                'tamano' => $validatedData['tamano_id'],
                'online' => $validatedData['online'],
                'disponible' => $validatedData['disponible'],
            ]);
        } else {
            Pistas::create([
                'nombre' => $validatedData['nombre'],
                'deporte' => $validatedData['deporte_id'],
                'tipo' => $validatedData['tipo_id'],
                'caracteristicas' => $validatedData['caracteristica_id'],
                'tamano' => $validatedData['tamano_id'],
                'online' => $validatedData['online'],
                'disponible' => $validatedData['disponible'],
            ]);
        }

        $this->resetFields();
        $this->loadPistas();
        $this->dispatchBrowserEvent('close-modal');
    }

    // Función para cuando se llama a la alerta
    public function getListeners()
    {
        return [
            'deleteConfirmed',
        ];
    }

    public function edit($id)
    {
        $pista = Pistas::findOrFail($id);

        $this->pista_id = $pista->id;
        $this->nombre = $pista->nombre;
        $this->deporte_id = $pista->deporte;
        $this->tipo_id = $pista->tipo;
        $this->caracteristica_id = $pista->caracteristicas;
        $this->tamano_id = $pista->tamano;
        $this->online = $pista->online;
        $this->disponible = $pista->disponible;

        $this->dispatchBrowserEvent('open-modal');
    }

    public function confirmDelete($id)
    {
        $this->deletePistaId = $id;
        $this->alert('warning', '¿Estás seguro?', [
            'showConfirmButton' => true,
            'confirmButtonText' => 'Eliminar',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancelar',
            'onConfirmed' => 'deleteConfirmed',
        ]);
    }

    public function deleteConfirmed()
    {
        Pistas::destroy($this->deletePistaId);
        $this->resetFields();
        $this->loadPistas(); // recargar la lista de pistas
        $this->alert('success', '¡Eliminado!', [
            'text' => 'La pista ha sido eliminada.',
        ]);
    }

    public function render()
    {
        return view('livewire.pistas.index');
    }
}
