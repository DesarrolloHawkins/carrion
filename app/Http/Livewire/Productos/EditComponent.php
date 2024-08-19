<?php

namespace App\Http\Livewire\Productos;

use App\Models\Productos;
use App\Models\ProductosCategories;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class EditComponent extends Component
{
    use LivewireAlert;

    public $identificador;

    public $id_categoria;
    public $cod_producto;
    public $nombre;
    public $descripcion;
    public $precio;
    public $stock;    
    public $categorias;

    public function mount()
    {
        $product = Productos::find($this->identificador);
        $this->cod_producto = $product->cod_producto;
        $this->id_categoria = $product->id_categoria;

        $this->nombre = $product->nombre;
        $this->descripcion = $product->descripcion;
        $this->precio = $product->precio;
        $this->stock = $product->stock;
        $this->categorias = ProductosCategories::all();
    }

    public function render()
    {
        return view('livewire.productos.edit-component');
    }

    // Al hacer update en el formulario
    public function update()
    {
        // Validación de datos
        $this->validate([
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

        // Encuentra el producto identificado
        $product = Productos::find($this->identificador);

        // Guardar datos validados
        $productSave = $product->update([
            'cod_producto' => $this->cod_producto,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'precio' => $this->precio,
            'stock' => $this->stock,
        ]);

        if ($productSave) {
            $this->alert('success', '¡Produco actualizado correctamente!', [
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

        session()->flash('message', 'Product updated successfully.');

        $this->emit('productUpdated');
    }

      // Elimina el producto
      public function destroy(){
        // $product = Productos::find($this->identificador);
        // $product->delete();

        $this->alert('warning', '¿Seguro que desea borrar el producto? No hay vuelta atrás', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => false,
            'showConfirmButton' => true,
            'onConfirmed' => 'confirmDelete',
            'confirmButtonText' => 'Sí',
            'showDenyButton' => true,
            'denyButtonText' => 'No',
            'timerProgressBar' => true,
        ]);

    }

    // Función para cuando se llama a la alerta
    public function getListeners()
    {
        return [
            'confirmed',
            'confirmDelete'
        ];
    }

    // Función para cuando se llama a la alerta
    public function confirmed()
    {
        // Do something
        return redirect()->route('productos.index');

    }
    // Función para cuando se llama a la alerta
    public function confirmDelete()
    {
        $product = Productos::find($this->identificador);
        $product->delete();
        return redirect()->route('productos.index');

    }
}
