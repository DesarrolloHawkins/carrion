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

use SimpleSoftwareIO\QrCode\Facades\QrCode;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;



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
    public $abonado;
    public $tipo_abonado;
    public $cliente_id;
    public $mapImage;

    public function getListeners()
    {
        return [
            'confirmed',
            'deleteConfirmed'
        ];
        
    }

    protected $rules = [
        'nombre' => 'required',
        'apellidos' => 'required',
        'direccion' => 'nullable',
        'codigo_postal' => 'nullable',
        'poblacion' => 'nullable',
        'provincia' => 'nullable',
        'fijo' => 'nullable',
        'movil' => 'required',
        'DNI' => 'required',
        'email' => 'required',
        'abonado' => 'nullable',
    ];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->clientes = Cliente::with('categoriaJugadores')->get();
        foreach($this->clientes as $item){
            $reservas = Reservas::where('id_cliente', $item->id)->where('estado', 'pagada')->get();

            if(!$reservas->isEmpty()){
                $item['isReserva'] = true; 
            }else {
                $item['isReserva'] = false; 

            }
        }
        // $this->categorias = CategoriaJugadores::all();
    }

    public function resetFields()
    {
        $this->reset(['cliente_id', 'nombre', 'apellidos', 'direccion', 'codigo_postal', 'poblacion', 'provincia', 'fijo', 'movil', 'DNI', 'email']);
    }

    public function submit()
    {
        $validatedData = $this->validate();

        if ($this->cliente_id) {
            // dd($this->abonado);
            $cliente = Cliente::findOrFail($this->cliente_id);
            $cliente->abonado = $this->abonado;
            $cliente->tipo_abonado = $this->tipo_abonado == true ? 'palco' : 'silla';
            $cliente->update($validatedData);
        } else {
            Cliente::create($validatedData);
        }

        $this->resetFields();
        $this->loadData();
        $this->dispatchBrowserEvent('close-modal');
        $this->alert('success', 'Cliente guardado correctamente.');
    }
    public function update()
    {
        // $validatedData = $this->validate();
            // dd($this->abonado);
            $cliente = Cliente::findOrFail($this->cliente_id);
            $cliente->nombre =  $this->nombre;
            $cliente->apellidos =  $this->apellidos;
            $cliente->direccion =  $this->direccion;
            $cliente->codigo_postal =  $this->codigo_postal;
            $cliente->poblacion =  $this->poblacion;
            $cliente->provincia =  $this->provincia;
            $cliente->fijo =  $this->fijo;
            $cliente->movil =  $this->movil;
            $cliente->DNI =  $this->DNI;
            $cliente->email =  $this->email;
            $cliente->abonado = $this->abonado;
            $cliente->tipo_abonado = $this->tipo_abonado == true ? 'palco' : 'silla';
            $cliente->DNI =  $this->DNI;
           
            $cliente->save();
        

        // $this->resetFields();
        // $this->loadData();
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
        $this->abonado = $cliente->abonado;
        $this->tipo_abonado = $cliente->tipo_abonado;
        // $cliente->tipo = $this->tipo == true ? 'palco' : 'silla';

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
            $palco = null;
            $grada = null;

            if ($silla->id_palco != null) {
                $palco = Palcos::find($silla->id_palco);
                $zona = Sectores::find($palco->id_sector);
            } elseif ($silla->id_grada != null) {
                $grada = Gradas::find($silla->id_grada);
                $zona = Zonas::find($grada->id_zona);
            }

            $detalleReserva = [
                'asiento' => $silla->numero ?? 'N/A',
                'sector' => $zona->nombre ?? 'N/A', // Asumiendo que $zona->nombre es el nombre de la zona
                'fecha' => $reserva->fecha,
                'año' => $reserva->año,
                'precio' => $reserva->precio,
                'fila' => $silla->fila ?? 'N/A',
                'order' => $reserva->order,
                'palco' => $palco->numero ?? '',
                'grada' => $grada->numero ?? '',
            ];

            // Agrupar las reservas por zona
            $detallesReservas[$zona->nombre][] = $detalleReserva;
        }

        $mapImage = $this->getMapImageByZona($zona->nombre);
        $mapImageBase64 = $this->imageToBase64( $this->mapImage);

        
        

        //$pdf = \PDF::loadView('pdf.cliente', compact('cliente'));

        $pdf = PDF::loadView('pdf.cliente', compact('detallesReservas'))->setPaper('a4', 'vertical')->output(); 
        return response()->streamDownload(
            fn () => print($pdf),
            'export_protocol.pdf'
        );

    }

    private function imageToBase64($path)
    {
        if (file_exists(public_path($path))) {
            $imageData = file_get_contents(public_path($path));
            return base64_encode($imageData);
        }
        return null;
    }

    // Función para obtener la imagen según el nombre de la zona
    private function getMapImageByZona($zonaNombre)
    {
        switch ($zonaNombre) {
            case '01- Asunción (Protocolo)':
            case 'Plaza Asunción (Protocolo)':
                return '/images/zonas/asuncion.png';
            case '02.- Consistorio':
            case 'Consistorio II':
            case 'Consistorio I':
            case '':
                return '/images/zonas/consistorio.png';
            case '03. Arenal':
            case 'Arenal II':
            case 'Arenal I':
            case 'Arenal III':
            case 'Arenal IV':
            case 'Arenal V':
            case 'Arenal VI':
                return '/images/zonas/arenal.png';
            case '04.- Lancería-Gallo Azul':
            case 'Lancería-Gallo Azul':
                return '/images/zonas/lanceria.png';
            case '05.- Algarve-Plaza del Banco':
            case 'Algarve-Plaza del Banco':
                return '/images/zonas/larga.png';
            case '06.- Rotonda de los Casinos-Santo Domingo':
            case 'Rotonda de los Casinos-Santo Domingo':    
            case 'Rotonda de los Casinos-Santo Domingo II':
                return '/images/zonas/casinos.png';
            case '07.- Marqués de Casa Domecq':
            case 'Marqués de Casa Domecq II':
            case 'Marqués de Casa Domecq':
            case 'Marqués de Casa Domecq I':
                return '/images/zonas/santodomingo.png';
            case '08.- Eguiluz':
            case 'Eguiluz II':
            case 'Eguiluz I':
            case 'Eguiluz':
                return '/images/zonas/domecq.png';
            default:
                return '/images/zonas/default.png';
        }
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
