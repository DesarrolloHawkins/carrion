<?php

namespace App\Http\Livewire\Productos;

use Livewire\Component;
use App\Models\Productos;
use PDF;

class IndexComponent extends Component

{

    public $productos;

    public function mount()
    {
        $this->productos = Productos::all();
    }
    public function render()
    {
        return view('livewire.productos.index-component', [
            'productos' => $this->productos,
        ]);
    }

    public function pdf()
    {
        // Se llama a los productos
        $this->productos = Productos::all();

        // Se llama a la vista Liveware y se le pasa los productos. En la vista se epecifican los estilos del PDF
        $pdf = PDF::loadView('livewire.productos.pdf-component', ['productos'=>$this->productos]);
        return $pdf->stream();

    }


}
