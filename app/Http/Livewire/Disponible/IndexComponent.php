<?php

namespace App\Http\Livewire\Disponible;

use App\Models\Presupuesto;
use App\Models\Servicio;
use App\Models\Articulos;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class IndexComponent extends Component
{
    public $servicios;
    public $articulos;
    public $dia;



    public function mount()
    {
        $this->servicios = Servicio::all();

        $this->dia = Carbon::now()->format('Y-m-d');
        $articulosEnUso = DB::table('presupuestos')
        ->join('servicio_presupuesto', 'presupuestos.id', '=', 'servicio_presupuesto.presupuesto_id')
        ->whereRaw('? BETWEEN presupuestos.diaEvento AND presupuestos.diaFinal', [$this->dia])
        ->pluck('servicio_presupuesto.articulo_seleccionado');

    $this->articulos = Articulos::whereNotIn('id', $articulosEnUso)->get();
    }
    public function stockTotal($id){

        $sumatorio = 0;

        $stocks = Articulos::all();
        foreach ($stocks as $articulo) {
            if ($articulo->id_categoria == $id) {
                $sumatorio += 1;
            }
        }
        echo $sumatorio; // Mostramos el sumatorio
    }
    public function stock($id){
        $servicioId = $id;

        $fechaEvento = $this->dia;

        $sumaTotalStockUsado = DB::table('presupuestos')
            ->join('servicio_presupuesto', 'presupuestos.id', '=', 'servicio_presupuesto.presupuesto_id')
            ->whereRaw('? BETWEEN presupuestos.diaEvento AND presupuestos.diaFinal', [$fechaEvento])
            ->where('servicio_presupuesto.servicio_id', $servicioId)
            ->selectRaw('SUM(CASE
                WHEN servicio_presupuesto.articulo_seleccionado != 0 THEN 1
                ELSE servicio_presupuesto.num_art_indef
                END) AS total_stock_usado')
            ->value('total_stock_usado');
            // Obtener el stock total fijo del artículo
            $stockTotal = Articulos::where('id_categoria', $servicioId)->count();
            $disponible =$stockTotal- $sumaTotalStockUsado;
        return  $disponible;
    }

    public function render()
    {
        return view('livewire.disponible.index-component');
    }
    protected $listeners = ['refresh' => '$refresh'];
    public function updatedDia($value)
    {
        $articulosEnUso = DB::table('presupuestos')
            ->join('servicio_presupuesto', 'presupuestos.id', '=', 'servicio_presupuesto.presupuesto_id')
            ->whereRaw('? BETWEEN presupuestos.diaEvento AND presupuestos.diaFinal', [$value])
            ->pluck('servicio_presupuesto.articulo_seleccionado');

        $this->articulos = Articulos::whereNotIn('id', $articulosEnUso)->get();
    }
}
