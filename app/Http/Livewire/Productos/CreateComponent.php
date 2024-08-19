<?php

namespace App\Http\Livewire\Productos;

use App\Models\Productos;
use App\Models\ProductosCategories;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class CreateComponent extends Component
{

    use LivewireAlert;

    public $cod_producto;
    public $nombre;
    public $descripcion;
    public $precio;
    public $stock;
    public $categorias;
    public $id_categoria;

    public function mount(){
        $this->categorias = ProductosCategories::all();
    }

    public function render()
    {
        return view('livewire.productos.create-component');
    }

    // Al hacer submit en el formulario
    public function submit()
    {
        // Validación de datos
        $validatedData = $this->validate([
            'id_categoria' => 'required',
            'cod_producto' => 'required',
            'nombre' => 'required',
            'descripcion' => 'required',
            'precio' => 'required|numeric',
            'stock' => 'required|numeric',
        ],
            // Mensajes de error
            [
                'id_categoria.required' => 'La Categoria es obligatoria.',
                'cod_producto.required' => 'El código de producto es obligatorio.',
                'nombre.required' => 'El nombre es obligatorio.',
                'descripcion.required' => 'La descripción es obligatoria.',
                'precio.required' => 'El precio es obligatorio.',
                'precio.numeric' => 'El precio debe ser un número con decimales.',
                'stock.required' => 'El stock es obligatorio.',
                'stock.numeric' => 'El precio es obligatorio.',
            ]);

        // Guardar datos validados
        $productosSave = Productos::create($validatedData);

        // Alertas de guardado exitoso
        if ($productosSave) {
            $this->alert('success', '¡Produco registrado correctamente!', [
                'position' => 'center',
                'timer' => 3000,
                'toast' => false,
                'showConfirmButton' => true,
                'onConfirmed' => 'confirmed',
                'confirmButtonText' => 'ok',
                'timerProgressBar' => true,
            ]);
        } else {
            $this->alert('error', '¡No se ha podido guardar la información del producto!', [
                'position' => 'center',
                'timer' => 3000,
                'toast' => false,
            ]);
        }
    }

    // Función para cuando se llama a la alerta
    public function getListeners()
    {
        return [
            'confirmed',
        ];
    }

    // Función para cuando se llama a la alerta
    public function confirmed()
    {
        // Do something
        return redirect()->route('productos.index');

    }
}
