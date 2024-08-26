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
    public $apellidos;
    public $nombre;
    public $direccion;
    public $codigo_postal;
    public $poblacion;
    public $provincia;
    public $fijo;
    public $movil;
    public $DNI;
    public $email;
    public $cliente_id;

    protected $rules = [
        'nombre' => 'required',
        'apellidos' => 'nullable',
        'direccion' => 'nullable',
        'codigo_postal' => 'nullable',
        'poblacion' => 'nullable',
        'provincia' => 'nullable',
        'fijo' => 'nullable',
        'movil' => 'nullable',
        'DNI' => 'required',
        'email' => 'required',
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
        $this->reset(['cliente_id', 'nombre', 'apellidos', 'direccion', 'codigo_postal', 'poblacion', 'provincia', 'fijo', 'movil', 'DNI', 'email']);
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
        $this->apellidos = $cliente->apellidos;
        $this->direccion = $cliente->direccion;
        $this->codigo_postal = $cliente->codigo_postal;
        $this->poblacion = $cliente->poblacion;
        $this->provincia = $cliente->provincia;
        $this->fijo = $cliente->fijo;
        $this->movil = $cliente->movil;
        $this->DNI = $cliente->DNI;
        $this->email = $cliente->email;



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
