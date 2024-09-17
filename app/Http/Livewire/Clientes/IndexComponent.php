<?php

namespace App\Http\Livewire\Clientes;

use Livewire\Component;
use App\Models\Cliente;
use App\Models\CategoriaJugadores;
use App\Models\Reservas;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\Sillas;
use App\Models\Zonas;
use App\Models\Gradas;
use App\Models\Palcos;
use App\Models\Sectores;





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

    public function getListeners()
    {
        return [
            'confirmed',
            'deleteConfirmed'
        ];
        
    }

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

    public function create()
{
    $this->resetFields();
    $this->dispatchBrowserEvent('open-create-modal');
}

public function edit($id)
{
   // dd("hola");
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

    $this->dispatchBrowserEvent('open-edit-modal');
}


public function pdfDownload($cliente_id){

    $cliente = Cliente::find($cliente_id);

    if(!$cliente){
        $this->alert('error', 'Cliente no encontrado.');

        return;
    }

    $reservas = Reservas::where('id_cliente', $cliente_id)->where('estado', 'pagada')->get();

    if($reservas->isEmpty()){
        $this->alert('error', 'No hay reservas pagadas para este cliente.');

        return;
    }
    $sillas = [];
    $zonas = [];
    $detallesReservas = [];

    foreach ($reservas as $reserva) {
        $silla = Sillas::find($reserva->id_silla); 
        $zona = Zonas::find($silla->id_zona); 
        if ($silla->id_palco != null) {
            $palco = Palcos::find($silla->id_palco);
            $zona = Sectores::find($palco->id_sector);
        } elseif ($silla->id_grada != null) {
            $grada = Gradas::find($silla->id_grada);
            $zona = Zonas::find($grada->id_zona);
        }
        $detallesReservas[] = [
            'asiento' => $silla->numero ?? 'N/A',
            'sector' => $zona->nombre ?? 'N/A',
            'fecha' => $reserva->fecha,
            'año' => $reserva->año,
            'precio' => $reserva->precio,
            'fila' => $silla->fila ?? 'N/A',
            'order' => $reserva->order,
            'palco' => $palco->numero ?? '',
            'grada' => $grada->numero ?? '',
        ];
    }
    $reserva1 = $reservas[0];
    $silla = Sillas::find($reserva1->id_silla); 
    if($silla->id_palco != null){
        $palco = Palcos::find($silla->id_palco);
        $zona = zonas::find($palco->id_zona);
    }elseif($silla->id_grada != null){
        $grada = Gradas::find($silla->id_grada);
        $zona = Zonas::find($grada->id_zona);
    }else{
        $zona = Zonas::find($silla->id_zona);
    }

    dd($detallesReservas);
    
    

    //$pdf = \PDF::loadView('pdf.cliente', compact('cliente'));

    $pdf = PDF::loadView('pdf.cliente')->setPaper('a4', 'vertical')->output(); 
    return response()->streamDownload(
        fn () => print($pdf),
        'export_protocol.pdf'
    );

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
