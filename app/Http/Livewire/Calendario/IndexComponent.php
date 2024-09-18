<?php
namespace App\Http\Livewire\Calendario;

use Livewire\Component;
use App\Models\Reservas;
use App\Models\Pistas;
use App\Models\Cliente;
use App\Models\Monitor;
use App\Models\ReservaPago;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Mail; // Importar el facade de Mail

class IndexComponent extends Component
{
    public function render()
    {
        return view('livewire.calendario.index-component');
    }

    
}
