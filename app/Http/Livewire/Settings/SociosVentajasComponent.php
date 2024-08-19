<?php

namespace App\Http\Livewire\Settings;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\MembresiasVentajas;
use App\Models\Zonas;
use App\Models\Membresias;

class SociosVentajasComponent extends Component
{
    use LivewireAlert;

    public $membresiasVentajas;
    public $zonas;
    public $membresias;
    public $zona_id;
    public $membresia_id;
    public $tipo_descuento;
    public $descuento;
    public $lunes, $martes, $miercoles, $jueves, $viernes, $sabado, $domingo;
    public $hora_inicio, $hora_fin;
    public $antelacion_reserva;
    public $isEditing = false;
    public $editingId;

    public function mount()
    {
        $this->lunes = false;
        $this->martes = false;
        $this->miercoles = false;
        $this->jueves = false;
        $this->viernes = false;
        $this->sabado = false;
        $this->domingo = false;
        $this->membresiasVentajas = MembresiasVentajas::with(['membresia', 'zona'])->get();
        $this->zonas = Zonas::all();
        $this->membresias = Membresias::all();
    }

    public function render()
    {
        return view('livewire.settings.socios-ventajas-component');
    }

    public function submit()
    {
        $this->validate([
            'membresia_id' => 'required',
            'zona_id' => 'required',
            'tipo_descuento' => 'required|string',
            'descuento' => 'required|numeric',
            'hora_inicio' => 'required',
            'hora_fin' => 'required',
            'antelacion_reserva' => 'required|numeric',
        ]);

        MembresiasVentajas::create([
            'membresia_id' => $this->membresia_id,
            'zona_id' => $this->zona_id,
            'tipo_descuento' => $this->tipo_descuento,
            'descuento' => $this->descuento,
            'lunes' => $this->lunes,
            'martes' => $this->martes,
            'miercoles' => $this->miercoles,
            'jueves' => $this->jueves,
            'viernes' => $this->viernes,
            'sabado' => $this->sabado,
            'domingo' => $this->domingo,
            'hora_inicio' => $this->hora_inicio,
            'hora_fin' => $this->hora_fin,
            'antelacion_reserva' => $this->antelacion_reserva
        ]);

        $this->alert('success', 'Datos guardados correctamente');
        $this->resetForm();
        $this->mount();
    }

    public function edit($id)
    {
        $ventaja = MembresiasVentajas::find($id);

        if ($ventaja) {
            $this->editingId = $ventaja->id;
            $this->membresia_id = $ventaja->membresia_id;
            $this->zona_id = $ventaja->zona_id;
            $this->tipo_descuento = $ventaja->tipo_descuento;
            $this->descuento = $ventaja->descuento;
            $this->lunes = $ventaja->lunes;
            $this->martes = $ventaja->martes;
            $this->miercoles = $ventaja->miercoles;
            $this->jueves = $ventaja->jueves;
            $this->viernes = $ventaja->viernes;
            $this->sabado = $ventaja->sabado;
            $this->domingo = $ventaja->domingo;
            $this->hora_inicio = $ventaja->hora_inicio;
            $this->hora_fin = $ventaja->hora_fin;
            $this->antelacion_reserva = $ventaja->antelacion_reserva;
            $this->isEditing = true;
            $this->dispatchBrowserEvent('show-modal', ['modalId' => 'addVentajaModal']);
        } else {
            $this->alert('error', 'Ventaja no encontrada');
        }
    }

    public function update()
    {
        $this->validate([
            'membresia_id' => 'required',
            'zona_id' => 'required',
            'tipo_descuento' => 'required|string',
            'descuento' => 'required|numeric',
            'hora_inicio' => 'required',
            'hora_fin' => 'required',
            'antelacion_reserva' => 'required|numeric',
        ]);

        $ventaja = MembresiasVentajas::find($this->editingId);

        if ($ventaja) {
            $ventaja->update([
                'membresia_id' => $this->membresia_id,
                'zona_id' => $this->zona_id,
                'tipo_descuento' => $this->tipo_descuento,
                'descuento' => $this->descuento,
                'lunes' => $this->lunes,
                'martes' => $this->martes,
                'miercoles' => $this->miercoles,
                'jueves' => $this->jueves,
                'viernes' => $this->viernes,
                'sabado' => $this->sabado,
                'domingo' => $this->domingo,
                'hora_inicio' => $this->hora_inicio,
                'hora_fin' => $this->hora_fin,
                'antelacion_reserva' => $this->antelacion_reserva
            ]);

            $this->alert('success', 'Ventaja actualizada correctamente');
            $this->resetForm();
            $this->mount();
        } else {
            $this->alert('error', 'Ventaja no encontrada');
        }
    }

    public function delete($id)
    {
        $ventaja = MembresiasVentajas::find($id);
        if ($ventaja) {
            $ventaja->delete();
            $this->alert('success', 'Ventaja eliminada correctamente');
        } else {
            $this->alert('error', 'Ventaja no encontrada');
        }

        $this->mount();
    }

    public function resetForm()
    {
        $this->reset([
            'zona_id', 'membresia_id', 'tipo_descuento', 'descuento',
            'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo',
            'hora_inicio', 'hora_fin', 'antelacion_reserva', 'isEditing', 'editingId'
        ]);
    }
}
