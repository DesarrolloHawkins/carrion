<?php
namespace App\Http\Livewire\Calendario;

use Livewire\Component;
use App\Models\Reservas;
use App\Models\Pistas;
use App\Models\Cliente;
use App\Models\Monitor;
use App\Models\ReservaPago;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class IndexComponent extends Component
{
    use LivewireAlert;

    // Variables de reserva
    public $reservas;
    public $clientes;
    public $monitores;
    public $pistas;
    public $endTimeOptions;
    public $nombre_jugador;
    public $hora_inicio;
    public $hora_fin;
    public $precio;
    public $tipo_pago;
    public $tipo_reserva;
    public $selectedPista;
    public $selectedCliente;
    public $selectedMonitor;
    public $nota;
    public $modalType;
    public $selectedDate;
    public $selectedReservaId;
    public $repetir_cada;

    // Variables de pagos
    public $pagos = [];
    public $fechaPago;
    public $horaPago;
    public $montoPago;
    public $tipoPago;
    public $notaPago;
    public $selectedPagoId;

    public function mount()
    {
        $this->reservas = Reservas::where(function($query) {
            $query->whereNotNull('partido_id')
                  ->whereNotNull('torneo_id');
        })->orWhereNull('torneo_id')->get();
        $this->clientes = Cliente::all();
        $this->monitores = Monitor::all();
        $this->pistas = Pistas::all();
        $this->tipo_reserva = 'normal';
        $this->tipo_pago = 'unico';
        $this->fechaPago = date('Y-m-d');
        $this->tipoPago = 'efectivo';
        //dd("hola");
        $this->updateEndTimes();
    }

    public function getListeners()
    {
        return [
            'setDate',
            'editReserva',
            'loadPagos'
        ];
    }

    public function updateEndTimes()
    {
        $this->endTimeOptions = [];

        if ($this->hora_inicio) {
            $startTime = strtotime($this->hora_inicio);
            $this->hora_fin = date('H:i', strtotime('+30 minutes', $startTime));

            for ($hour = 0; $hour < 24; $hour++) {
                foreach ([0, 30] as $minute) {
                    $time = sprintf('%02d:%02d', $hour, $minute);
                    $currentTime = strtotime($time);

                    if ($currentTime > $startTime || ($hour == 23 && $minute == 30)) {
                        $this->endTimeOptions[] = $time;
                    }
                }
            }

            $this->endTimeOptions[] = '24:00';
        }
    }

    public function updatedHoraInicio($value)
    {
        $this->hora_inicio = $value;
        $this->updateEndTimes();
    }

    public function updatedHoraFin($value)
    {
        $this->hora_fin = $value;
    }

    public function updated($type, $value)
    {
        if ($type == 'tipo_reserva') {
            $this->selectedPista = null;
            $this->selectedCliente = null;
            $this->selectedMonitor = null;
        }

        if ($type == 'selectedCliente') {
            $this->nombre_jugador = Cliente::find($this->selectedCliente)->nombre . ' ' . Cliente::find($this->selectedCliente)->apellido;
        }
    }

    public function render()
    {
        return view('livewire.calendario.index-component', [
            'reservas' => $this->reservas,
            'clientes' => $this->clientes,
            'monitores' => $this->monitores,
            'pistas' => $this->pistas,
        ]);
    }

    public function save()
    {
        if ($this->hora_fin == '24:00') {
            $this->hora_fin = '23:59';
        }

        // Validaciones
        if ($this->hora_inicio == '00:00' && $this->hora_fin == '00:00') {
            $this->alert('error', 'La hora de inicio y fin no pueden ser 00:00');
            return;
        }
        if ($this->nombre_jugador == '') {
            $this->alert('error', 'El nombre del jugador es requerido');
            return;
        }
        if ($this->precio == '') {
            $this->alert('error', 'El precio es requerido');
            return;
        }
        if ($this->tipo_pago == '') {
            $this->alert('error', 'El tipo de pago es requerido');
            return;
        }
        if ($this->tipo_reserva == '') {
            $this->alert('error', 'El tipo de reserva es requerido');
            return;
        }
        if ($this->selectedPista == '' && $this->tipo_reserva == 'normal') {
            $this->alert('error', 'La pista es requerida');
            return;
        }
        if ($this->selectedMonitor == '' && $this->tipo_reserva == 'clase') {
            $this->alert('error', 'El monitor es requerido');
            return;
        }
        if ($this->hora_inicio == '') {
            $this->alert('error', 'La hora de inicio es requerida');
            return;
        }
        if ($this->hora_fin == '') {
            $this->alert('error', 'La hora de fin es requerida');
            return;
        }

        $validatedData = $this->validate([
            'nombre_jugador' => 'required',
            'hora_inicio' => 'required',
            'hora_fin' => 'required',
            'precio' => 'required',
            'tipo_pago' => 'required',
            'tipo_reserva' => 'required',
            'selectedCliente' => 'nullable',
            'selectedMonitor' => 'nullable',
            'selectedPista' => 'required',
            'nota' => 'nullable|string',
            'repetir_cada' => 'nullable|integer',
        ]);

        $reservas = Reservas::where('dia', $this->selectedDate)
            ->where('pista_id', $this->selectedPista)
            ->where(function ($query) {
                $query->whereBetween('hora_inicio', [$this->hora_inicio, $this->hora_fin])
                    ->orWhereBetween('hora_fin', [$this->hora_inicio, $this->hora_fin]);
            })
            ->where('id', '!=', $this->selectedReservaId)
            ->get();

        if ($reservas->count() > 0) {
            $this->alert('error', 'La pista ya está ocupada en ese intervalo de tiempo');
            return;
        }

        if ($this->modalType === 'edit') {
            $reserva = Reservas::find($this->selectedReservaId);
            $reserva->update([
                'nombre_jugador' => $validatedData['nombre_jugador'],
                'hora_inicio' => $validatedData['hora_inicio'],
                'hora_fin' => $validatedData['hora_fin'],
                'precio' => $validatedData['precio'],
                'tipo_pago' => $validatedData['tipo_pago'],
                'tipo_reserva' => $validatedData['tipo_reserva'],
                'pista_id' => $validatedData['selectedPista'],
                'cliente_id' => $validatedData['selectedCliente'],
                'monitor_id' => $validatedData['selectedMonitor'],
                'nota' => $validatedData['nota'],
                'repetir_cada' => $validatedData['repetir_cada'],
            ]);
            $this->alert('success', 'Reserva actualizada con éxito');
        } else {
            Reservas::create([
                'nombre_jugador' => $validatedData['nombre_jugador'],
                'hora_inicio' => $validatedData['hora_inicio'],
                'hora_fin' => $validatedData['hora_fin'],
                'precio' => $validatedData['precio'],
                'tipo_pago' => $validatedData['tipo_pago'],
                'tipo_reserva' => $validatedData['tipo_reserva'],
                'pista_id' => $validatedData['selectedPista'],
                'cliente_id' => $validatedData['selectedCliente'],
                'monitor_id' => $validatedData['selectedMonitor'],
                'nota' => $validatedData['nota'],
                'dia' => $this->selectedDate,
                'repetir_cada' => $validatedData['repetir_cada'],
            ]);
            $this->alert('success', 'Reserva creada con éxito');
            return redirect()->route('calendario.index');
        }

        $this->reservas = Reservas::all();
        $this->resetForm();
        $this->emit('closeModal');
    }

    public function editReserva($id)
    {
        $reserva = Reservas::find($id);

        $this->selectedDate = $reserva->dia;
        $this->nombre_jugador = $reserva->nombre_jugador;
        $this->hora_inicio = $reserva->hora_inicio;
        $this->hora_fin = $reserva->hora_fin;
        $this->precio = $reserva->precio;
        $this->tipo_pago = $reserva->tipo_pago;
        $this->tipo_reserva = $reserva->tipo_reserva;
        $this->selectedPista = $reserva->pista_id;
        $this->selectedCliente = $reserva->cliente_id;
        $this->selectedMonitor = $reserva->monitor_id;
        $this->nota = $reserva->nota;
        $this->selectedReservaId = $id;
        $this->modalType = 'edit';
        $this->repetir_cada = $reserva->repetir_cada;

        $this->updateEndTimes();
        $this->emit('openModal');
    }

    public function setDate($date)
    {
        $this->resetForm();
        $this->selectedDate = $date;
        $this->modalType = 'add';
        $this->emit('openModal');
    }

    public function delete()
    {
        if ($this->selectedReservaId) {
            Reservas::find($this->selectedReservaId)->delete();
            $this->reservas = Reservas::all();
            $this->resetForm();
            $this->emit('closeModal');
            $this->alert('success', 'Reserva eliminada con éxito');
            return redirect()->route('calendario.index');
        }
    }

    public function loadPagos()
    {
        //dd('loadPagos');    
        $this->pagos = ReservaPago::where('reserva_id', $this->selectedReservaId)->get();

        $reserva = Reservas::find($this->selectedReservaId);
        //dd($reserva->precio);
        $this->horaPago = date('H:i');
        $this->fechaPago = date('Y-m-d');
        $this->montoPago =$reserva->precio;
        //dd($this->montoPago);
        $this->resetPagoForm();
        $this->emit('openPagosModal');
    }

    public function addPago()
    {
        $this->storePago();
        // Asignar fecha y hora actuales por defecto
        //$this->tipoPago = 'efectivo'; // O el tipo de pago predeterminado
        //$this->notaPago = '';

        $this->emit('openPagoModal');
    }

    public function storePago()
    {
        //dd("hola");
        $this->validate([
            'fechaPago' => 'required|date',
            'horaPago' => 'required|date_format:H:i',
            'montoPago' => 'required|numeric',
            'tipoPago' => 'required|string',
            'notaPago' => 'nullable|string',
        ]);

        ReservaPago::create([
            'reserva_id' => $this->selectedReservaId,
            'monto' => $this->montoPago,
            'tipo_pago' => $this->tipoPago,
            'fecha_pago' => $this->fechaPago,
            'hora_pago' => $this->horaPago,
            'nota' => $this->notaPago,
        ]);

        $this->pagos = ReservaPago::where('reserva_id', $this->selectedReservaId)->get();
        $this->alert('success', 'Pago añadido con éxito');
        $this->resetPagoForm();
    }

    public function editPago($pagoId)
    {
        $pago = ReservaPago::find($pagoId);
        $this->selectedPagoId = $pago->id;
        $this->fechaPago = $pago->fecha_pago;
        $this->horaPago = $pago->hora_pago;
        $this->montoPago = $pago->monto;
        $this->tipoPago = $pago->tipo_pago;
        $this->notaPago = $pago->nota;
        $this->emit('openEditPagoModal');
    }

    public function updatePago()
    {
        $this->validate([
            'fechaPago' => 'required|date',
            'horaPago' => 'required|date_format:H:i',
            'montoPago' => 'required|numeric',
            'tipoPago' => 'required|string',
            'notaPago' => 'nullable|string',
        ]);

        $pago = ReservaPago::find($this->selectedPagoId);
        $pago->update([
            'fecha_pago' => $this->fechaPago,
            'hora_pago' => $this->horaPago,
            'monto' => $this->montoPago,
            'tipo_pago' => $this->tipoPago,
            'nota' => $this->notaPago,
        ]);

        $this->pagos = ReservaPago::where('reserva_id', $this->selectedReservaId)->get();
        $this->alert('success', 'Pago actualizado con éxito');
        $this->resetPagoForm();
    }

    public function deletePago($pagoId)
    {
        ReservaPago::find($pagoId)->delete();
        $this->pagos = ReservaPago::where('reserva_id', $this->selectedReservaId)->get();
        $this->alert('success', 'Pago eliminado con éxito');
    }

    private function resetForm()
    {
        $this->nombre_jugador = '';
        $this->hora_inicio = '';
        $this->hora_fin = '';
        $this->precio = '';
        $this->tipo_pago = 'unico';
        $this->tipo_reserva = 'normal';
        $this->selectedPista = null;
        $this->selectedCliente = null;
        $this->selectedMonitor = null;
        $this->nota = '';
        $this->selectedDate = '';
        $this->modalType = 'add';
        $this->selectedReservaId = null;
        $this->updateEndTimes();
    }

    private function resetPagoForm()
    {
        //$this->fechaPago = '';
        //$this->horaPago = '';
        //$this->montoPago = '';
        $this->tipoPago = 'efectivo';
        $this->notaPago = '';
        $this->selectedPagoId = null;
    }
}
