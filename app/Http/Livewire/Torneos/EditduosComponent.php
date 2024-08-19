<?php
namespace App\Http\Livewire\Torneos;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Torneos;
use App\Models\Pistas;
use App\Models\TorneosPistas;
use App\Models\TorneosDias;
use App\Models\Reservas;
use App\Models\Cliente;
use App\Models\CategoriaJugadores;
use App\Models\TorneosCategorias;
use App\Models\TorneosCategoriasInscripciones;
use App\Models\TorneosDuos;
use App\Models\Socios;
use App\Models\TorneosInscripcionDisponibilidad;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Carbon\Carbon;

class EditduosComponent extends Component
{
    use WithFileUploads;
    use LivewireAlert;
    public $torneoDuo;
    public $inscripcion1;
    public $inscripcion2;
    public $grupo;

    public $jugador1;
    public $jugador2;
    public $comentario1;
    public $comentario2;

    public $categoriaSeleccionada;
    public $categorias;

    public $torneoId;

    public $diasDelTorneo;
    public $disponibilidadSeleccionada = [];

    public $selectedInscripcion;
    public $changingJugadorIndex;

    public $inscripcionesDisponibles = [];

    public $inscripcionNombre;
    public $apellidos;
    public $email;
    public $telefono;
    public $DNI;
    public $nickName;
    public $ciudad;
    public $genero;
    public $comentario;
    public $bloqJugador1;
    public $activeTab = 0;

    public function setActiveTab($index)
    {
        $this->activeTab = $index;
    }

    public function searchJugador($var1, $var2)
    {
        $jugador = Cliente::where($var1, $var2)->first();

        if ($jugador) {
            $this->jugadorId = $jugador->id;
            $this->inscripcionNombre = $jugador->nombre;
            $this->apellidos = $jugador->apellido;
            $this->email = $jugador->email1;
            $this->telefono = $jugador->telefono;
            $this->DNI = $jugador->DNI ?? '';
            $this->nickName = $jugador->nickName ?? '';
            $this->ciudad = $jugador->ciudad;
            $this->genero = $jugador->genero;
            $this->comentario = $jugador->comentario ?? '';
            $this->bloqJugador1 = true;
        }
    }

    public function updatedTelefono()
    {
        $this->searchJugador('telefono', $this->telefono);
    }

    public function updatedDNI()
    {
        $this->searchJugador('DNI', $this->DNI);
    }

    public function updatedNickName()
    {
        $this->searchJugador('nickName', $this->nickName);
    }

    public function updatedEmail()
    {
        $this->searchJugador('email1', $this->email);
    }

    public function mount($identificador)
    {
        $this->torneoId = $identificador;
        $this->torneoDuo = TorneosDuos::find($this->torneoId);
        $this->inscripcion1 = $this->torneoDuo->inscripcion;
        $this->inscripcion2 = $this->torneoDuo->inscripcion2;
        $this->grupo = $this->torneoDuo->grupo;

        if ($this->inscripcion1) {
            $this->comentario1 = $this->inscripcion1->comentario;
        }

        if ($this->inscripcion2) {
            $this->comentario2 = $this->inscripcion2->comentario;
        }

        $this->categoriaSeleccionada = $this->inscripcion1->torneo_categoria_id;
        $this->torneoId = TorneosCategorias::where('id', $this->categoriaSeleccionada)->first()->torneo_id;
        $this->categorias = TorneosCategorias::where('torneo_id', $this->torneoId)->get();

        $this->jugador1 = Cliente::find($this->inscripcion1->jugador_id);
        if ($this->inscripcion2)
            $this->jugador2 = Cliente::find($this->inscripcion2->jugador_id);

        $this->diasDelTorneo = TorneosDias::where('torneo_id', $this->torneoId)->get();

        // Cargar la disponibilidad existente
        $this->loadDisponibilidad();
    }

    public function saveInscripcion()
    {
        $this->validate([
            'categoriaSeleccionada' => 'required|exists:torneos_categorias,id',
            'inscripcionNombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefono' => 'required|string|max:20',
            'DNI' => 'nullable|string|max:20',
            'nickName' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:255',
            'genero' => 'nullable|string|max:10',
            'comentario' => 'nullable|string|max:1000',
        ]);

        $inscripcion = TorneosCategoriasInscripciones::create([
            'torneo_categoria_id' => $this->categoriaSeleccionada,
            'jugador_id' => $this->jugadorId,
            'fecha_inscripcion' => Carbon::now(),
            'email' => $this->email,
            'telefono' => $this->telefono,
            'DNI' => $this->DNI,
            'nickName' => $this->nickName,
            'nombre' => $this->inscripcionNombre,
            'apellidos' => $this->apellidos,
            'ciudad' => $this->ciudad,
            'genero' => $this->genero,
            'categoria' => $this->categoriaSeleccionada,
            'comentario' => $this->comentario,
            'pagado' => false,
            'total_precio' => 0.00,
            'pendiente' => 0.00,
        ]);

        $this->torneoDuo->update([
            'inscripcion_id_2' => $inscripcion->id,
        ]);

        $this->alert('success', 'Inscripción guardada correctamente.');

        $this->reset([
            'categoriaSeleccionada',
            'inscripcionNombre',
            'apellidos',
            'email',
            'telefono',
            'DNI',
            'nickName',
            'ciudad',
            'genero',
            'comentario',
        ]);

        $this->emit('inscripcionSaved');

        $this->dispatchBrowserEvent('hide-inscripcion-modal');

        // route to torneos.editduos
        return redirect()->route('torneos.editduos', $this->torneoDuo->id);
    }

    public function getListeners()
    {
        return [
            'changeJugador' => 'changeJugador',
        ];
    }

    public function loadDisponibilidad()
    {
        $disponibilidad1 = TorneosInscripcionDisponibilidad::where('inscripcion_id', $this->torneoDuo->id)->get();

        foreach ($disponibilidad1 as $disp) {
            $fechaHora = $disp->fecha_no_disponible . ' ' . $disp->hora_no_disponible;
            $this->disponibilidadSeleccionada[$fechaHora] = true;
        }
    }

    public function toggleDisponibilidad($fecha)
    {
        if (isset($this->disponibilidadSeleccionada[$fecha])) {
            unset($this->disponibilidadSeleccionada[$fecha]);
        } else {
            $this->disponibilidadSeleccionada[$fecha] = true;
        }
    }

    public function saveDisponibilidad()
    {
        TorneosInscripcionDisponibilidad::where('inscripcion_id', $this->torneoDuo->id)->delete();

        foreach ($this->disponibilidadSeleccionada as $fechaHora => $value) {
            list($fecha, $hora) = explode(' ', $fechaHora);

            TorneosInscripcionDisponibilidad::create([
                'torneo_id' => $this->torneoId,
                'inscripcion_id' => $this->torneoDuo->id,
                'fecha_no_disponible' => $fecha,
                'hora_no_disponible' => $hora
            ]);
        }
    }

    public function showChangeJugadorModal($index)
    {
        $this->changingJugadorIndex = $index;
        $inscripcionesEnDuos = TorneosDuos::whereNotNull('inscripcion_id')
            ->orWhereNotNull('inscripcion_id_2')
            ->pluck('inscripcion_id', 'inscripcion_id_2')
            ->flatten()
            ->unique();

        $this->inscripcionesDisponibles = TorneosCategoriasInscripciones::where('torneo_categoria_id', $this->categoriaSeleccionada)
            ->whereNotIn('id', $inscripcionesEnDuos)
            ->get();

        $this->selectedInscripcion = null;

        $this->dispatchBrowserEvent('show-change-jugador-modal');
    }

    public function changeJugador()
    {

        $selectedInscripcion = TorneosCategoriasInscripciones::find($this->selectedInscripcion);

        if ($this->changingJugadorIndex == 1) {
            $lastInscripcion = $this->inscripcion1;
            $this->inscripcion1 = $selectedInscripcion;

            $this->jugador1 = Cliente::find($this->inscripcion1->jugador_id);
            $this->comentario1 = $this->inscripcion1->comentario;

            if ($this->inscripcion2->id == $this->inscripcion1->id) {
                $this->inscripcion2 = $lastInscripcion;
                $this->jugador2 = Cliente::find($this->inscripcion2->jugador_id);
                $this->comentario2 = $this->inscripcion2->comentario;
            }
        } else {
            $lastInscripcion = $this->inscripcion2;
            $this->inscripcion2 = $selectedInscripcion;

            $this->jugador2 = Cliente::find($this->inscripcion2->jugador_id);
            $this->comentario2 = $this->inscripcion2->comentario;

            if ($this->inscripcion1->id == $this->inscripcion2->id) {
                $this->inscripcion1 = $lastInscripcion;
                $this->jugador1 = Cliente::find($this->inscripcion1->jugador_id);
                $this->comentario1 = $this->inscripcion1->comentario;
            }
        }

        // Actualizar el otro duo donde estaba el jugador seleccionado
        $otherDuo = TorneosDuos::where('inscripcion_id', $selectedInscripcion->id)
            ->orWhere('inscripcion_id_2', $selectedInscripcion->id)
            ->first();

        if ($otherDuo) {
            if ($otherDuo->inscripcion_id == $selectedInscripcion->id) {
                $otherDuo->update(['inscripcion_id' => $lastInscripcion->id]);
            } else {
                $otherDuo->update(['inscripcion_id_2' => $lastInscripcion->id]);
            }
        } else {
            $this->alert('error', 'No se ha podido cambiar el jugador');
        }

        // Cambiar las inscripciones en TorneosDuos
        $duo1 = TorneosDuos::find($this->torneoDuo->id);

        $duo1->update([
            'inscripcion_id' => $this->inscripcion1->id,
            'inscripcion_id_2' => $this->inscripcion2->id,
        ]);

        $this->alert('success', 'Jugadores cambiados correctamente');
        $this->dispatchBrowserEvent('hide-change-jugador-modal');
    }

    public function update()
    {
        $this->validate([
            'inscripcion1' => 'required',
            'inscripcion2' => 'required',
            'grupo' => 'required',
        ]);

        // Actualizar el duo
        $updatedTorneo = TorneosDuos::find($this->torneoDuo->id)->update([
            'inscripcion_id' => $this->inscripcion1->id,
            'inscripcion_id_2' => $this->inscripcion2->id,
            'grupo' => $this->grupo,
        ]);

        // Actualizar los comentarios de las inscripciones
        $upInscripcion1 = TorneosCategoriasInscripciones::find($this->inscripcion1->id)->update([
            'comentario' => $this->comentario1,
        ]);

        $upInscripcion2 = TorneosCategoriasInscripciones::find($this->inscripcion2->id)->update([
            'comentario' => $this->comentario2,
        ]);

        $this->saveDisponibilidad();

        $this->alert(
            'success',
            'Torneo actualizado correctamente'
        );
    }

    public function render()
    {
        return view('livewire.torneos.editduos-component');
    }
}
