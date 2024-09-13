<?php
namespace App\Http\Livewire\Reservas;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

use App\Models\Sillas;

use App\Models\Zonas;

use App\Models\Reservas;

use App\Models\Gradas;

use App\Models\Palcos;

use App\Models\Sectores;




class IndexComponent extends Component
{
    use LivewireAlert;

    public $reservas;
    public $sillas;
    public $cliente;
    public $zonas;
    public $detallesReservas = [];


    public function getListeners()
    {
        return [
            'eliminarReserva',
            'cancelarReserva',
            'eliminarReservaConfirmed',
            'cancelarReservaConfirmed',
        ];
    }

    public function mount() {
        // Obtener todas las reservas
        $this->reservas = Reservas::all();
        
        $this->detallesReservas = [];
        $palco = null;
        $grada = null;
    
        // Iterar sobre todas las reservas
        foreach ($this->reservas as $reserva) {
            // Obtener la silla asociada a la reserva
            $silla = Sillas::find($reserva->id_silla); 
            
            if (!$silla) {
                continue; // Si no se encuentra la silla, saltar a la siguiente iteración
            }
    
            // Obtener la zona asociada a la silla o palco o grada
            $zona = Zonas::find($silla->id_zona);
            if ($silla->id_palco != null) {
                // Si la silla está en un palco, obtener el palco y el sector correspondiente
                $palco = Palcos::find($silla->id_palco);
                $zona = Sectores::find($palco->id_sector);
            } elseif ($silla->id_grada != null) {
                // Si la silla está en una grada, obtener la grada y su zona
                $grada = Gradas::find($silla->id_grada);
                $zona = Zonas::find($grada->id_zona);
            }
    
            // Agregar los detalles de la reserva al array $detallesReservas
            $this->detallesReservas[] = [
                'cliente' => $reserva->clientes->nombre  ? $reserva->clientes->nombre . ' '.$reserva->clientes->apellidos  : 'N/A',
                'DNI' => $reserva->clientes->DNI ?? 'N/A',
                'movil' => $reserva->clientes->movil ?? 'N/A',
                'asiento' => $silla->numero ?? 'N/A',
                'sector' => $zona->nombre ?? 'N/A',
                'fecha' => $reserva->fecha,
                'año' => $reserva->año,
                'precio' => $reserva->precio,
                'fila' => $silla->fila ?? 'N/A',
                'order' => $reserva->order,
                'palco' => $palco->numero ?? '',
                'grada' => $grada->numero ?? '',
                'estado' => $reserva->estado,
                'id' => $reserva->id,

            ];
        }
    }
    public function confirmarCancelacion($reservaId) {
        $this->confirm('¿Estás seguro de que deseas cancelar esta reserva?', [
            'toast' => false,
            'position' => 'center',
            'showConfirmButton' => true,
            'cancelButtonText' => 'Cancelar',
            'onConfirmed' => 'cancelarReserva', // Disparamos un evento
            'data' => [
                'reservaId' => $reservaId, // Pasamos el ID como data
            ],
        ]);
    }
    
    public function confirmarEliminacion($reservaId) {
        $this->confirm('¿Estás seguro de que deseas eliminar esta reserva?', [
            'toast' => false,
            'position' => 'center',
            'showConfirmButton' => true,
            'cancelButtonText' => 'Cancelar',
            'onConfirmed' => 'eliminarReserva', // Disparamos un evento
            'data' => [
                'reservaId' => $reservaId, // Pasamos el ID como data
            ],
        ]);
    }
    

     // Método para cancelar la reserva
     public function cancelarReserva($reservaId) {
        $reserva = Reservas::where('id', $reservaId['data']['reservaId'])->first();
        if ($reserva) {
            $reserva->estado = 'cancelada';
            $reserva->save();

            // Notificar al usuario que se ha cancelado la reserva
            $this->alert('success', 'Reserva cancelada exitosamente.');
        }

        // Actualizar la lista de reservas
        $this->mount();
    }

   

    // Método para eliminar la reserva
    public function eliminarReserva($reservaId) {
        $reserva = Reservas::where('id',$reservaId['data']['reservaId'])->first();

        if ($reserva) {
            $reserva->delete();

            // Notificar al usuario que se ha eliminado la reserva
            $this->alert('success', 'Reserva eliminada exitosamente.');

            // Actualizar la lista de reservas
            $this->mount();
        }
    }

    

    public function render()
    {
        return view('livewire.reservas.index-component');
    }
}
