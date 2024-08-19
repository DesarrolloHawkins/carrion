<?php

namespace App\Http\Livewire\Clientes;

use Livewire\Component;
use App\Models\Cliente;
use App\Models\CategoriaJugadores;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class IndexComponent extends Component
{
    use LivewireAlert;

    public $clientes;
    public $categorias;
    public $categoria_id;
    public $nombre;
    public $apellido;
    public $tlf1;
    public $email1;
    public $cliente_id;
    public $telefono;
    public $DNI;
    public $nickName;
    public $ciudad;
    public $genero;

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'apellido' => 'nullable|string|max:255',
        'telefono' => 'required|string|max:15',
        'email1' => 'required|email|max:255',
        'categoria_id' => 'required|exists:categorias_jugadores,id',
        'DNI' => 'required|string|max:255',
        'nickName' => 'required|string|max:255',
        'ciudad' => 'nullable|string|max:255',
        'genero' => 'nullable|string|max:255',
    ];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->clientes = Cliente::with('categoriaJugadores')->get();
        $this->categorias = CategoriaJugadores::all();
    }

    public function resetFields()
    {
        $this->reset(['nombre', 'apellido', 'telefono', 'email1', 'categoria_id', 'cliente_id' , 'DNI', 'nickName', 'ciudad', 'genero']);
    }

    public function submit()
    {
        $validatedData = $this->validate();

        if ($this->cliente_id) {
            $cliente = Cliente::findOrFail($this->cliente_id);
            $cliente->update($validatedData);
        } else {
            Cliente::create($validatedData);
        }

        $this->resetFields();
        $this->loadData();
        $this->dispatchBrowserEvent('close-modal');
        $this->alert('success', 'Cliente guardado correctamente.');
    }

    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);

        $this->cliente_id = $cliente->id;
        $this->nombre = $cliente->nombre;
        $this->apellido = $cliente->apellido;
        $this->telefono = $cliente->telefono;
        $this->email1 = $cliente->email1;
        $this->categoria_id = $cliente->categoria_id;
        $this->DNI = $cliente->DNI;
        $this->nickName = $cliente->nickName;
        $this->ciudad = $cliente->ciudad;
        $this->genero = $cliente->genero;

        $this->dispatchBrowserEvent('open-modal');
    }

    public function confirmDelete($id)
    {
        $this->cliente_id = $id;
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
        Cliente::destroy($this->cliente_id);
        $this->resetFields();
        $this->loadData();
        $this->alert('success', 'Cliente eliminado correctamente.');
    }

    public function render()
    {
        return view('livewire.clientes.index-component');
    }
}
