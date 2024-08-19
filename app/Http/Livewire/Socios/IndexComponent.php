<?php

namespace App\Http\Livewire\Socios;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\Cliente;
use App\Models\Socios;
use App\Models\Membresias;
use App\Models\Cuotas;

class IndexComponent extends Component
{
    use LivewireAlert;

    public $socios;
    public $clientes;
    public $cliente_id;
    public $socio_id;
    public $membresias;
    public $membresia_id;
    public $tarjeta;
    public $estado;
    public $cuota_id;
    public $fecha_inicio;
    public $fecha_fin;
    public $precio;
    public $pagado = 0;
    public $fecha_pago;
    public $metodo_pago;
    public $cuotas = [];

    protected $rules = [
        'cliente_id' => 'required',
        'membresia_id' => 'required',
        'tarjeta' => 'nullable|string|max:255',
        'estado' => 'required|boolean',
    ];

    public function mount()
    {
        $this->loadData();
        $this->clientes = Cliente::all();
        $this->membresias = Membresias::all();
    }


    public function getListeners()
    {
        return [
            'deleteConfirmed',
            'deleteCuotaConfirmed',
        ];
    }

    public function loadData()
    {
        $this->socios = Socios::with('cliente', 'membresia', 'cuotas')->get();
    }

    public function resetFields()
    {
        $this->reset(['cliente_id', 'membresia_id', 'tarjeta', 'estado', 'socio_id', 'fecha_inicio', 'fecha_fin', 'precio', 'pagado', 'fecha_pago', 'metodo_pago', 'cuota_id']);
    }

    public function submit()
    {
        $validatedData = $this->validate();

        if ($this->socio_id) {
            $socio = Socios::findOrFail($this->socio_id);
            $socio->update($validatedData);
        } else {
            Socios::create($validatedData);
        }

        $this->resetFields();
        $this->loadData();
        $this->dispatchBrowserEvent('close-modal');
        $this->alert('success', 'Socio guardado correctamente.');
    }

    public function edit($id)
    {
        $socio = Socios::findOrFail($id);

        $this->socio_id = $socio->id;
        $this->cliente_id = $socio->cliente_id;
        $this->membresia_id = $socio->membresia_id;
        $this->tarjeta = $socio->tarjeta;
        $this->estado = $socio->estado;

        $this->dispatchBrowserEvent('open-modal');
    }

    public function openCuotasModal($id)
    {
        $this->socio_id = $id;
        $this->reset(['fecha_inicio', 'fecha_fin', 'precio', 'pagado', 'fecha_pago', 'metodo_pago', 'cuota_id']);
        $this->dispatchBrowserEvent('open-cuotas-modal');
    }

    public function submitCuota()
    {
        $this->validate([
            'socio_id' => 'required|exists:socios,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'precio' => 'required|numeric',
            'pagado' => 'required|boolean',
            'fecha_pago' => 'nullable|date',
            'metodo_pago' => 'nullable|string|max:255',
        ]);

        $cuotaData = [
            'socio_id' => $this->socio_id,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'precio' => $this->precio,
            'pagado' => $this->pagado,
            'fecha_pago' => $this->fecha_pago,
            'metodo_pago' => $this->metodo_pago,
        ];

        if ($this->cuota_id) {
            $cuota = Cuotas::findOrFail($this->cuota_id);
            $cuota->update($cuotaData);
        } else {
            Cuotas::create($cuotaData);
        }

        $this->resetFields();
        $this->dispatchBrowserEvent('close-cuotas-modal');
        $this->alert('success', 'Cuota guardada correctamente.');
    }

    public function viewCuotas($id)
    {
        $this->cuotas = Cuotas::where('socio_id', $id)->get();
        $this->dispatchBrowserEvent('open-view-cuotas-modal');
    }

    public function editCuota($id)
    {
        $cuota = Cuotas::findOrFail($id);

        $this->cuota_id = $cuota->id;
        $this->socio_id = $cuota->socio_id;
        $this->fecha_inicio = $cuota->fecha_inicio;
        $this->fecha_fin = $cuota->fecha_fin;
        $this->precio = $cuota->precio;
        $this->pagado = $cuota->pagado;
        $this->fecha_pago = $cuota->fecha_pago;
        $this->metodo_pago = $cuota->metodo_pago;

        $this->dispatchBrowserEvent('open-cuotas-modal');
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

    public function deleteCuotaConfirmed()
    {
        Cuotas::destroy($this->cuota_id);
        $this->resetFields();
        $this->alert('success', 'Cuota eliminada correctamente.');
    }

    public function confirmDelete($id)
    {
        $this->socio_id = $id;
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
        Socios::destroy($this->socio_id);
        $this->resetFields();
        $this->loadData();
        $this->alert('success', 'Cliente eliminado correctamente.');
    }

    public function render()
    {
        return view('livewire.socios.index-component');
    }
}
