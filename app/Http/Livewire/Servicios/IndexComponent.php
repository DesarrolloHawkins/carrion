<?php

namespace App\Http\Livewire\Servicios;

use App\Models\Articulos;
use App\Models\Servicio;
use App\Models\ServicioCategoria;
use App\Models\ServicioPack;
use Livewire\Component;

class IndexComponent extends Component
{
    // public $search;
    public $servicios;
    public $serviciosCategoria;
    public $serviciosPack;

    public function mount()
    {
        $this->servicios = Servicio::all();
        foreach($this->servicios as $servicioItem){
            if ($servicioItem->stock === null | $servicioItem->stock <= 0) {
                $articulos = Articulos::where('id_categoria', $servicioItem->id)->get();
                $servicioItem->stock = $articulos->count();
                $servicioItem->save();
            }
        }
        $this->serviciosCategoria = ServicioCategoria::all();
        $this->serviciosPack = ServicioPack::all();
    }

    public function precioTotal(int $id){
        $servicio = $this->servicios->find($id);
        return $servicio->minMonitor * $servicio->precioMonitor + $servicio->precioBase;

    }

    public function nombreCategoria(int $id){
        if($this->serviciosCategoria->where("id", $id)->first() !== null){
            return $this->serviciosCategoria->where("id", $id)->first()->nombre;
        }else{return "Categorian inexistente";}
    }

    public function nombrePacks($ids)
{
    if (!is_array($ids)) {
        return 'Packs no asignados correctamente';
    }

    $packs = $this->serviciosPack->whereIn('id', $ids)->pluck('nombre')->all();
    return implode(', ', $packs); // Devuelve los nombres de los packs separados por comas
}

    public function render()
    {
        return view('livewire.servicios.index-component');
    }

}
