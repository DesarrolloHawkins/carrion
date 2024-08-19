<?php

namespace App\Http\Livewire\Presupuestos;

use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Evento;
use App\Models\Presupuesto;
use App\Models\TipoEvento;
use Livewire\Component;

class IndexComponent extends Component
{
    // public $search;
    public $presupuestos;
    public $clientes;
    public $eventos;
    public $empresas;
    public $tipos_eventos;
    public function mount()
    {
        $this->presupuestos = Presupuesto::all();
        $this->clientes = Cliente::all();
        $this->eventos = Evento::all();
        $this->tipos_eventos = TipoEvento::all();
    }

    public function getClienteNombre($id){

        if($id == 0){
            return "Presupuesto sin cliente";
        }
        $cliente = $this->clientes->find($id);

        $nombre = $cliente->nombre;
        $apellido = $cliente->apellido;

        return "$nombre $apellido";
    }
    public function getEventoNombre($id){
        $evento = $this->tipos_eventos->find($id);
        return $evento->nombre;
    }

    public function render()
    {

        return view('livewire.presupuestos.index-component');
    }

}
