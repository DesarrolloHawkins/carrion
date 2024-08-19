<?php

namespace App\Http\Livewire\Settings;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\Festivos;

class FestivosComponent extends Component
{
    use LivewireAlert;

    public $festivos;
    public $nombre;
    public $fecha_inicio;
    public $fecha_fin;
    public $cierre;
    public $editMode = false;
    public $festivo_id;

    public function mount()
    {
        $this->membresiasVentajas = Festivos::all();
    }

    public function render()
    {
        return view('livewire.settings.festivos-component');
    }

    public function submit()
    {
        $this->validate([
            'nombre' => 'required',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
            'cierre' => 'required|boolean'
        ]);

        if ($this->editMode) {
            $festivo = Festivos::find($this->festivo_id);
            $festivo->update([
                'nombre' => $this->nombre,
                'fecha_inicio' => $this->fecha_inicio,
                'fecha_fin' => $this->fecha_fin,
                'cierre' => $this->cierre
            ]);
            $this->alert('success', 'Festivo actualizado correctamente');
        } else {
            Festivos::create([
                'nombre' => $this->nombre,
                'fecha_inicio' => $this->fecha_inicio,
                'fecha_fin' => $this->fecha_fin,
                'cierre' => $this->cierre
            ]);
            $this->alert('success', 'Festivo añadido correctamente');
        }

        $this->resetForm();
        $this->mount(); // Actualiza la lista de ventajas
    }

    public function edit($id)
    {
        $festivo = Festivos::find($id);
        $this->festivo_id = $festivo->id;
        $this->nombre = $festivo->nombre;
        $this->fecha_inicio = $festivo->fecha_inicio;
        $this->fecha_fin = $festivo->fecha_fin;
        $this->cierre = $festivo->cierre;
        $this->editMode = true;

        $this->dispatchBrowserEvent('open-modal');
    }

    public function delete($id)
    {
        $ventaja = Festivos::find($id);
        if ($ventaja) {
            $ventaja->delete();
            $this->alert('success', 'Festivo eliminado correctamente');
        } else {
            $this->alert('error', 'Festivo no encontrado');
        }

        $this->mount(); // Actualiza la lista de ventajas después de eliminar
    }

    public function resetForm()
    {
        $this->reset(['nombre', 'fecha_inicio', 'fecha_fin', 'cierre', 'festivo_id', 'editMode']);
    }
}
