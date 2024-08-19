<?php
namespace App\Http\Livewire\Reservas;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\Cliente;
use App\Models\Monitor;
use App\Models\Reservas;
use App\Models\Pistas;
use App\Models\ReservaPago;

class IndexComponent extends Component
{
    use LivewireAlert;

    public $reservas;
    public $clientes;
    public $monitores;
    public $pistas;
    public $reserva_id;
    public $pista_id;
    public $cliente_id;
    public $monitor_id;
    public $dia;
    public $hora_inicio;
    public $hora_fin;
    public $precio;
    public $tipo_pago;
    public $nombre_jugador;
    public $nota;
    public $tipo_reserva;
    public $fecha_inicio_recurrente;
    public $fecha_fin_recurrente;
    public $lunes;
    public $martes;
    public $miercoles;
    public $jueves;
    public $viernes;
    public $sabado;
    public $domingo;
    public $repetir_cada;

    public $selectedMonth;
    public $totalAmount = 0;
    public $totalPaid = 0;
    public $totalPending = 0;

    protected $rules = [
        // Tus reglas de validación aquí
    ];

    public function mount()
    {
        $this->loadData();
        $this->clientes = Cliente::all();
        $this->monitores = Monitor::all();
        $this->pistas = Pistas::all();
       // dd($this->pistas);
    }

    public function getListeners()
    {
        return [
            'deleteConfirmed',
            'deleteCuotaConfirmed',
        ];
    }

    public function existPago($id)
    {
        return ReservaPago::where('reserva_id', $id)->exists();
    }

    public function loadData()
{
    $query = Reservas::with('cliente', 'pista', 'monitor');

    if ($this->selectedMonth) {
        $query->whereMonth('dia', $this->selectedMonth);
    }

    // Incluir reservas donde torneo_id es null o donde torneo_id no es null y partido_id tampoco es null
    $query->where(function($query) {
        $query->whereNull('torneo_id');
    });

    $this->reservas = $query->get();

    // Calcular los totales
    $this->totalAmount = $this->reservas->sum('precio');
    $this->totalPaid = $this->reservas->filter(function ($reserva) {
        return $this->existPago($reserva->id);
    })->sum('precio');
    $this->totalPending = $this->totalAmount - $this->totalPaid;
}


    public function filterByMonth()
    {
        $this->loadData();
    }

    public function resetFields()
    {
        $this->reset([
            'reserva_id',
            'pista_id',
            'cliente_id',
            'monitor_id',
            'dia',
            'hora_inicio',
            'hora_fin',
            'precio',
            'tipo_pago',
            'nombre_jugador',
            'nota',
            'tipo_reserva',
            'fecha_inicio_recurrente',
            'fecha_fin_recurrente',
            'lunes',
            'martes',
            'miercoles',
            'jueves',
            'viernes',
            'sabado',
            'domingo',
            'repetir_cada',
            'selectedMonth',
        ]);
    }

    public function submit()
    {

        if ($this->reserva_id) {
            $socio = Reservas::findOrFail($this->reserva_id);
            $socio->update($validatedData);
        } else {
            Reservas::create($validatedData);
        }

        $this->resetFields();
        $this->loadData();
        $this->dispatchBrowserEvent('close-modal');
        $this->alert('success', 'Reserva guardada correctamente.');
    }

    public function edit($id)
    {
        $socio = Reservas::findOrFail($id);

        $this->reserva_id = $socio->id;
        $this->pista_id = $socio->pista_id;
        $this->cliente_id = $socio->cliente_id;
        $this->monitor_id = $socio->monitor_id;
        $this->dia = $socio->dia;
        $this->hora_inicio = $socio->hora_inicio;
        $this->hora_fin = $socio->hora_fin;
        $this->precio = $socio->precio;
        $this->tipo_pago = $socio->tipo_pago;
        $this->nombre_jugador = $socio->nombre_jugador;
        $this->nota = $socio->nota;
        $this->tipo_reserva = $socio->tipo_reserva;
        $this->fecha_inicio_recurrente = $socio->fecha_inicio_recurrente;
        $this->fecha_fin_recurrente = $socio->fecha_fin_recurrente;
        $this->lunes = $socio->lunes;
        $this->martes = $socio->martes;
        $this->miercoles = $socio->miercoles;
        $this->jueves = $socio->jueves;
        $this->viernes = $socio->viernes;
        $this->sabado = $socio->sabado;
        $this->domingo = $socio->domingo;
        $this->repetir_cada = $socio->repetir_cada;

        $this->dispatchBrowserEvent('open-modal');
    }

    public function confirmDeleteCuota($id)
    {
        $this->cuota_id = $id;
        $this->alert('warning', '¿Estás seguro?', [
            'showConfirmButton' => true,
            'confirmButtonText' => 'Eliminar',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancelar',
            'onConfirmed' => 'deleteCuotaConfirmed',
        ]);
    }

    public function confirmDelete($id)
    {
        $this->reserva_id = $id;
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
        Reservas::destroy($this->reserva_id);
        $this->resetFields();
        $this->loadData();
        $this->alert('success', 'Reserva eliminada correctamente.');
    }

    public function render()
    {
        return view('livewire.reservas.index-component');
    }
}
