<?php

namespace App\Http\Livewire\Settings;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\PistaPrecios;
use App\Models\Pistas;

class PreciosComponent extends Component
{
    use LivewireAlert;

    public $precios;
    public $pistas;
    public $precio_id;
    public $pista_id;
    public $regla;
    public $duracion;
    public $precio;
    public $hora_inicio;
    public $hora_fin;
    public $lunes, $martes, $miercoles, $jueves, $viernes, $sabado, $domingo;
    public $temporal = false;
    public $nombre_temporal;
    public $fecha_inicio;
    public $fecha_fin;
    public $editMode = false;

    public function mount()
    {
        $this->lunes = false;
        $this->martes = false;
        $this->miercoles = false;
        $this->jueves = false;
        $this->viernes = false;
        $this->sabado = false;
        $this->domingo = false;
        $this->temporal = false;
        $this->precios = PistaPrecios::with('pista')->get();
        $this->pistas = Pistas::all();

    
    }

    public function render()
    {
        return view('livewire.settings.precios-component');
    }

    public function submit()
    {

        if(!isset($this->temporal)){
            $this->temporal = false;
        }

        if(!isset($this->lunes)){
            $this->lunes = false;
        }

        if(!isset($this->martes)){
            $this->martes = false;
        }

        if(!isset($this->miercoles)){
            $this->miercoles = false;
        }

        if(!isset($this->jueves)){
            $this->jueves = false;
        }

        if(!isset($this->viernes)){
            $this->viernes = false;
        }

        if(!isset($this->sabado)){
            $this->sabado = false;
        }

        if(!isset($this->domingo)){
            $this->domingo = false;
        }

        $this->validate([
            'pista_id' => 'required',
            'regla' => 'required|string',
            'duracion' => 'required|numeric',
            'precio' => 'required|numeric',
            'hora_inicio' => 'required',
            'hora_fin' => 'required',
            'temporal' => 'required|boolean',
            'nombre_temporal' => 'nullable|string',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date'
        ]);

        if ($this->editMode) {
            $precio = PistaPrecios::find($this->precio_id);
            $precio->update([
                'pista_id' => $this->pista_id,
                'regla' => $this->regla,
                'duracion' => $this->duracion,
                'precio' => $this->precio,
                'hora_inicio' => $this->hora_inicio,
                'hora_fin' => $this->hora_fin,
                'lunes' => $this->lunes,
                'martes' => $this->martes,
                'miercoles' => $this->miercoles,
                'jueves' => $this->jueves,
                'viernes' => $this->viernes,
                'sabado' => $this->sabado,
                'domingo' => $this->domingo,
                'temporal' => $this->temporal,
                'nombre_temporal' => $this->nombre_temporal,
                'fecha_inicio' => $this->fecha_inicio,
                'fecha_fin' => $this->fecha_fin
            ]);
            $this->alert('success', 'Precio actualizado correctamente');
        } else {
            PistaPrecios::create([
                'pista_id' => $this->pista_id,
                'regla' => $this->regla,
                'duracion' => $this->duracion,
                'precio' => $this->precio,
                'hora_inicio' => $this->hora_inicio,
                'hora_fin' => $this->hora_fin,
                'lunes' => $this->lunes,
                'martes' => $this->martes,
                'miercoles' => $this->miercoles,
                'jueves' => $this->jueves,
                'viernes' => $this->viernes,
                'sabado' => $this->sabado,
                'domingo' => $this->domingo,
                'temporal' => $this->temporal,
                'nombre_temporal' => $this->nombre_temporal,
                'fecha_inicio' => $this->fecha_inicio,
                'fecha_fin' => $this->fecha_fin
            ]);
            $this->alert('success', 'Precio añadido correctamente');
        }

        $this->resetForm();
        $this->mount();
    }

    public function edit($id)
    {
        $precio = PistaPrecios::find($id);
        $this->precio_id = $precio->id;
        $this->pista_id = $precio->pista_id;
        $this->regla = $precio->regla;
        $this->duracion = $precio->duracion;
        $this->precio = $precio->precio;
        $this->hora_inicio = $precio->hora_inicio;
        $this->hora_fin = $precio->hora_fin;
        $this->lunes = $precio->lunes;
        $this->martes = $precio->martes;
        $this->miercoles = $precio->miercoles;
        $this->jueves = $precio->jueves;
        $this->viernes = $precio->viernes;
        $this->sabado = $precio->sabado;
        $this->domingo = $precio->domingo;
        $this->temporal = $precio->temporal;
        $this->nombre_temporal = $precio->nombre_temporal;
        $this->fecha_inicio = $precio->fecha_inicio;
        $this->fecha_fin = $precio->fecha_fin;
        $this->editMode = true;

        $this->dispatchBrowserEvent('open-modal');
    }

    public function delete($id)
    {
        $precio = PistaPrecios::find($id);
        if ($precio) {
            $precio->delete();
            $this->alert('success', 'Precio eliminado correctamente');
        } else {
            $this->alert('error', 'Precio no encontrado');
        }

        $this->mount();
    }

    public function resetForm()
    {
        $this->reset([
            'precio_id', 'pista_id', 'regla', 'duracion', 'precio', 'hora_inicio', 'hora_fin',
            'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo',
            'temporal', 'nombre_temporal', 'fecha_inicio', 'fecha_fin', 'editMode'
        ]);
    }
}
