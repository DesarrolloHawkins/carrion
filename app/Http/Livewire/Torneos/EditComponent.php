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
use App\Models\Partidos;
use App\Models\TorneosInscripcionDisponibilidad;
use App\Models\PartidoResultados;
use App\Models\PartidoResultadoSets;
use App\Models\TorneosResultados;
use App\Models\Club;
use App\Models\TorneosClubs;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Carbon\Carbon;

class EditComponent extends Component
{
    use WithFileUploads;
    use LivewireAlert;

    public $torneoId;
    public $nombre;
    public $descripcion;
    public $imagen;
    public $normativa;
    public $inscripcion;
    public $existingImagen;
    public $existingNormativa;
    public $precio;
    public $precio_socio;
    public $precio_pronto_pago;
    public $precio_socio_pronto_pago;
    public $condiciones;
    public $pistasDisponibles;
    public $pistasSeleccionadas = [];
    public $activeTab = 'info';
    public $nuevoDia;
    public $nuevoHoraInicio;
    public $nuevoHoraFin;
    public $diasSeleccionados = [];
    public $selectedDiaIndex = 0;
    public $reservas = [];
    public $timeSlots = [];

    public $categorias;
    public $selectedCategorias = [];
    public $selectedFormato;
    public $maxJugadores;
    public $inscripcionesAbiertas;
    public $categoriasSeleccionadas = [];
    public $categoriasDisponibles;
    public $inscripciones;
    public $jugadores;

    // Variables para inscripciones
    public $categoriaSeleccionada;
    public $categoriaSeleccionada2;
    public $tipoInscripcion;
    public $inscripcionNombre;
    public $apellidos;
    public $email;
    public $telefono;
    public $nombre2;
    public $apellidos2;
    public $email2;
    public $telefono2;
    public $DNI;
    public $nickName;
    public $ciudad;
    public $genero;
    public $comentario;
    public $jugadorId;
    public $jugadorId2;
    public $DNI2;
    public $nickName2;
    public $ciudad2;
    public $genero2;
    public $comentario2;
    public $query;
    public $bloqJugador1;
    public $bloqJugador2;
    public $grupoDuo;
    public $partidos;
    public $duosPorCategoria;
    public $partidosByDay;
    public $selectedPartido;
    public $horasDisponibles = [];
    public $nuevaHora = null;
    public $comentarioPartido = null;
    public $torneoResultados;
    public $setsPartido = [];
    public $resultados = [];
    public $resultadosPorCategoria = [];
    public $clubs = [];
    public $selectedClubes = null;
    public $clubSearchQuery = '';
    public $clubSearchResults = [];
    public $selectedClubId = null;
    public $showClubSearch = false; // Inicializar variable para mostrar u ocultar la búsqueda de clubes
    public $categoriasAElegir = [];


    protected $listeners = ['openModal' => 'loadPartido',     'refreshComponent' => '$refresh',];

    public function searchClubs()
    {
        $this->clubSearchResults = Club::where('nombre', 'LIKE', "%{$this->clubSearchQuery}%")
            ->orWhere('ciudad', 'LIKE', "%{$this->clubSearchQuery}%")
            ->get();
    }

    public function getInscripcionesCategoria($categoriaId)
    {
        //retornar numero de inscripciones en la categoria
        return TorneosCategoriasInscripciones::where('torneo_categoria_id', $categoriaId)->count();
    }


    public function changeInscripcionesAbiertas($categoriaId)
    {
        $categoria = TorneosCategorias::find($categoriaId);
        $categoria->inscripciones_abiertas = !$categoria->inscripciones_abiertas;
        $categoria->save();

        $this->categoriasSeleccionadas = TorneosCategorias::where('torneo_id', $this->torneoId)->with('categoria')->get();
    }


    // Método para seleccionar un club de los resultados
    public function selectClub($clubId)
    {
        $this->selectedClubId = $clubId;
        $selectedClub = Club::find($clubId);
        if ($selectedClub) {
            $this->clubSearchQuery = $selectedClub->nombre;
        }
        $this->clubSearchResults = [];
    }

    // Método para guardar el club seleccionado en el torneo
    public function saveSelectedClub()
    {
        if ($this->selectedClubId) {
            TorneosClubs::create([
                'torneo_id' => $this->torneoId,
                'club_id' => $this->selectedClubId,
            ]);

            $this->alert('success', 'Club añadido correctamente.');
            $this->selectedClubes = TorneosClubs::where('torneo_id', $this->torneoId)->get();
        }
    }

    public function mount($identificador)
    {
        $this->torneoId = $identificador;
        $torneo = Torneos::find($this->torneoId);
        
        $this->nombre = $torneo->nombre;
        $this->descripcion = $torneo->descripcion;
        $this->existingImagen = $torneo->imagen;
        $this->existingNormativa = $torneo->normativa;
        $this->inscripcion = $torneo->inscripcion;
        $this->precio = $torneo->precio;
        $this->precio_socio = $torneo->precio_socio;
        $this->precio_pronto_pago = $torneo->precio_pronto_pago;
        $this->precio_socio_pronto_pago = $torneo->precio_socio_pronto_pago;
        $this->condiciones = $torneo->condiciones;
        $this->pistasDisponibles = Pistas::all();
        $this->pistasSeleccionadas = TorneosPistas::where('torneo_id', $this->torneoId)->pluck('pista_id')->toArray();
        $this->diasSeleccionados = TorneosDias::where('torneo_id', $this->torneoId)->get()->toArray();
        $this->updateTimeSlots();
        $this->categorias = CategoriaJugadores::all();
        $this->categoriasSeleccionadas = TorneosCategorias::where('torneo_id', $this->torneoId)->with('categoria')->get();
        $this->categoriasDisponibles = TorneosCategorias::where('torneo_id', $this->torneoId)->with('categoria')->get();
        $this->categoriasAElegir = TorneosCategorias::where('torneo_id', $this->torneoId)->where('inscripciones_abiertas', true)->with('categoria')->get();
        $this->selectedFormato = 'Eliminatoria';

        $this->inscripcionesAbiertas = 1;

        $this->torneosDias = TorneosDias::where('torneo_id', $this->torneoId)->get();
        $this->inscripciones = TorneosCategoriasInscripciones::whereIn('torneo_categoria_id', function ($query) {
            $query->select('id')
                ->from('torneos_categorias')
                ->where('torneo_id', $this->torneoId);
        })->get();

        $this->duos = TorneosDuos::with(['inscripcion', 'inscripcion2'])
        ->whereHas('inscripcion', function($query) {
            $query->whereIn('torneo_categoria_id', $this->categoriasDisponibles->pluck('id'));
        })->orWhereHas('inscripcion2', function($query) {
            $query->whereIn('torneo_categoria_id', $this->categoriasDisponibles->pluck('id'));
        })->get();


        $this->jugadores = Cliente::all();
        $this->tipoInscripcion = $this->inscripcion; // Asignar el tipo de inscripción según el torneo

        $this->loadPartidos();
        $this->organizarResultadosPorCategoria();
        $this->clubs = Club::all();
        $this->selectedClubes = TorneosClubs::where('torneo_id', $this->torneoId)->get();

    }

    
    public function getListeners()
    {
        return [
            'openModal',
            'finalizarPartidoConfirmed',

        ];
    }

    public function loadPartidos()
    {
        $this->partidos = Partidos::where('torneo_id', $this->torneoId)
            ->with(['torneo', 'equipo1', 'equipo2', 'pista'])
            ->get();        
        $this->partidosByDay = collect($this->partidos)->groupBy(function($partido) {
            return \Carbon\Carbon::parse($partido->dia)->format('Y-m-d');
        });
    }




    public function togglePagado($id){
        $inscripcion = TorneosCategoriasInscripciones::find($id);
        $inscripcion->update([
            'pagado' => !$inscripcion->pagado,
        ]);

        $this->inscripciones = TorneosCategoriasInscripciones::whereIn('torneo_categoria_id', function ($query) {
            $query->select('id')
                ->from('torneos_categorias')
                ->where('torneo_id', $this->torneoId);
        })->get();

        $this->duos = TorneosDuos::with(['inscripcion', 'inscripcion2'])
        ->whereHas('inscripcion', function($query) {
            $query->whereIn('torneo_categoria_id', $this->categoriasDisponibles->pluck('id'));
        })->orWhereHas('inscripcion2', function($query) {
            $query->whereIn('torneo_categoria_id', $this->categoriasDisponibles->pluck('id'));
        })->get();
    }

    public function getInscripcionesPorCategoria($categoriaId)
    {
        return Inscripcion::whereHas('torneosDuos', function($query) use ($categoriaId) {
            $query->where('categoria_id', $categoriaId);
        })->get();
    }

    public function isSocio($jugadorId)
    {
        $jugador = Cliente::find($jugadorId);
        $socio = Socios::where('cliente_id', $jugadorId)->first();

        if ($socio) {
            return true;
        }

        return false;
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

    public function updatedTelefono2()
    {
        $this->searchJugador2('telefono', $this->telefono2);
    }

    public function updatedDNI2()
    {
        $this->searchJugador2('DNI', $this->DNI2);
    }

    public function updatedNickName2()
    {
        $this->searchJugador2('nickName', $this->nickName2);
    }

    public function updatedEmail2()
    {
        $this->searchJugador2('email1', $this->email2);
    }

    public function render()
    {
        $this->loadPartidos();
        if($this->selectedPartido){
            $this->comentarioPartido = $this->selectedPartido->comentario;
        }
        $this->organizarResultadosPorCategoria();

        return view('livewire.torneos.edit-component');
    }

    public function updatedJugadorId($jugadorId)
    {
        $jugador = Cliente::find($jugadorId);
        //DD($jugador);
        if ($jugador) {
            $this->inscripcionNombre = $jugador->nombre;
            $this->apellidos = $jugador->apellido;
            $this->email = $jugador->email1;
            $this->telefono = $jugador->telefono;
            $this->DNI = $jugador->DNI ?? '';
            $this->nickName = $jugador->nickName ?? '';
            $this->ciudad = $jugador->ciudad;
            $this->genero = $jugador->genero;
            $this->comentario = $jugador->comentario ?? '';
            //$this->categoriaSeleccionada = $jugador->categoria_id;
        } else {
            $this->reset(['inscripcionNombre', 'apellidos', 'email', 'telefono', 'DNI', 'nickName', 'ciudad', 'genero', 'comentario', 'bloqJugador1']);
        }
    }
    public function updatedJugadorId2($jugadorId2)
    {
        $jugador2 = Cliente::find($jugadorId2);
        if ($jugador2) {
            $this->nombre2 = $jugador2->nombre;
            $this->apellidos2 = $jugador2->apellido;
            $this->email2 = $jugador2->email1;
            $this->telefono2 = $jugador2->telefono;
            $this->DNI2 = $jugador2->DNI ?? '';
            $this->nickName2 = $jugador2->nickName ?? '';
            $this->ciudad2 = $jugador2->ciudad;
            $this->genero2 = $jugador2->genero;
            $this->comentario2 = $jugador2->comentario ?? '';
            $this->categoriaSeleccionada2 = $jugador2->categoria_id;
        } else {
            $this->reset(['nombre2', 'apellidos2', 'email2', 'telefono2', 'DNI2', 'nickName2', 'ciudad2', 'genero2', 'comentario2', 'bloqJugador2']);
        }
    }

    public function updateInfo()
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|max:1024',
            'normativa' => 'nullable|file|mimes:pdf|max:2048',
            'inscripcion' => 'required|string',
        ]);

        $torneo = Torneos::find($this->torneoId);

        if ($this->imagen) {
            if ($this->existingImagen && Storage::exists('public/' . $this->existingImagen)) {
                Storage::delete('public/' . $this->existingImagen);
            }
            $imagenPath = $this->imagen->store('torneos', 'public');
        } else {
            $imagenPath = $this->existingImagen;
        }

        if ($this->normativa) {
            if ($this->existingNormativa && Storage::exists('public/' . $this->existingNormativa)) {
                Storage::delete('public/' . $this->existingNormativa);
            }
            $normativaPath = $this->normativa->store('torneos/pdf', 'public');
        } else {
            $normativaPath = $this->existingNormativa;
        }

        $torneo->update([
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'imagen' => $imagenPath,
            'normativa' => $normativaPath,
            'inscripcion' => $this->inscripcion,
        ]);

        $this->alert('success', 'Información actualizada correctamente.');
        $this->activeTab = 'info';
    }

    public function saveCategories()
    {
        $this->validate([
            'selectedCategorias' => 'required|array',
            'selectedFormato' => 'required|string',
            'maxJugadores' => 'required|integer|min:1',
            'inscripcionesAbiertas' => 'required|boolean',
        ]);

        foreach ($this->selectedCategorias as $categoriaId) {
            TorneosCategorias::updateOrCreate(
                ['torneo_id' => $this->torneoId, 'categoria_id' => $categoriaId],
                [
                    'max_jugadores' => $this->maxJugadores,
                    'formato_juego' => $this->selectedFormato,
                    'inscripciones_abiertas' => $this->inscripcionesAbiertas,
                ]
            );
        }

        $this->categoriasSeleccionadas = TorneosCategorias::where('torneo_id', $this->torneoId)->with('categoria')->get();

        $this->alert('success', 'Categorías actualizadas correctamente.');
    }

    public function removeCategory($categoriaId)
    {
        TorneosCategorias::where('id', $categoriaId)->delete();
        $this->categoriasSeleccionadas = TorneosCategorias::where('torneo_id', $this->torneoId)->with('categoria')->get();
    }

    public function updatePrices()
    {
        $this->validate([
            'precio' => 'required|numeric',
            'precio_socio' => 'nullable|numeric',
            'precio_pronto_pago' => 'nullable|numeric',
            'precio_socio_pronto_pago' => 'nullable|numeric',
            'condiciones' => 'nullable|string',
        ]);

        $torneo = Torneos::find($this->torneoId);

        $torneo->update([
            'precio' => $this->precio,
            'precio_socio' => $this->precio_socio,
            'precio_pronto_pago' => $this->precio_pronto_pago,
            'precio_socio_pronto_pago' => $this->precio_socio_pronto_pago,
            'condiciones' => $this->condiciones,
        ]);

        $this->alert('success', 'Precios actualizados correctamente.');
        $this->activeTab = 'precios';
    }

    public function updatePistas()
    {
        TorneosPistas::where('torneo_id', $this->torneoId)->delete();

        foreach ($this->pistasSeleccionadas as $pistaId) {
            TorneosPistas::create([
                'torneo_id' => $this->torneoId,
                'pista_id' => $pistaId,
            ]);
        }

        $this->alert('success', 'Pistas actualizadas correctamente.');
        $this->activeTab = 'disponibilidad';
    }

    public function updateDias()
    {
        foreach ($this->diasSeleccionados as $dia) {
            TorneosDias::updateOrCreate(
                ['id' => $dia['id'] ?? null],
                [
                    'torneo_id' => $this->torneoId,
                    'dia' => $dia['dia'],
                    'hora_inicio' => $dia['hora_inicio'],
                    'hora_fin' => $dia['hora_fin'],
                ]
            );
        }

        $this->alert('success', 'Días actualizados correctamente.');
        $this->activeTab = 'disponibilidad';
    }

    public function addDia()
    {
        $this->validate([
            'nuevoDia' => 'required|date',
            'nuevoHoraInicio' => 'required|date_format:H:i',
            'nuevoHoraFin' => 'required|date_format:H:i|after:nuevoHoraInicio',
        ]);

        $this->diasSeleccionados[] = [
            'dia' => $this->nuevoDia,
            'hora_inicio' => $this->nuevoHoraInicio,
            'hora_fin' => $this->nuevoHoraFin,
        ];

        $this->updateDias();
        $this->reset(['nuevoDia', 'nuevoHoraInicio', 'nuevoHoraFin']);
    }

    public function removeDia($index, $id = null)
    {
        if ($id) {
            TorneosDias::find($id)->delete();
        }
        $reservas = Reservas::where('torneo_id', $this->torneoId)
            ->where('dia', $this->diasSeleccionados[$index]['dia'])
            ->get();
        foreach ($reservas as $reserva) {
            $reserva->delete();
        }
        unset($this->diasSeleccionados[$index]);
        $this->diasSeleccionados = array_values($this->diasSeleccionados);
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function togglePista($pistaId)
    {
        if (in_array($pistaId, $this->pistasSeleccionadas)) {
            $this->pistasSeleccionadas = array_diff($this->pistasSeleccionadas, [$pistaId]);
        } else {
            $this->pistasSeleccionadas[] = $pistaId;
        }
    }

    public function setSelectedDiaIndex($index)
    {
        $this->selectedDiaIndex = $index;
    }

    public function toggleReservar($pistaId, $hora, $dia)
    {
        $reservaExistente = Reservas::where('pista_id', $pistaId)
            ->where('dia', $dia)
            ->where('hora_inicio', '<=', $hora)
            ->where('hora_fin', '>', $hora)
            ->first();

        if ($reservaExistente) {
            $reservaExistente->delete();
            return;
        }

        $reserva = Reservas::where('pista_id', $pistaId)
            ->where('dia', $dia)
            ->where('hora_inicio', $hora)
            ->first();

        if ($reserva) {
            $reserva->delete();
            unset($this->reservas[$pistaId][$dia][array_search($hora, $this->reservas[$pistaId][$dia])]);
        } else {
            Reservas::create([
                'pista_id' => $pistaId,
                'dia' => $dia,
                'hora_inicio' => $hora,
                'hora_fin' => \Carbon\Carbon::parse($hora)->addMinutes(30)->format('H:i'),
                'nombre_jugador' => $this->nombre,
                'torneo_id' => $this->torneoId,
            ]);
            $this->reservas[$pistaId][$dia][] = $hora;
        }
    }

    private function updateTimeSlots()
    {
        $start = \Carbon\Carbon::createFromTime(0, 0);
        $end = \Carbon\Carbon::createFromTime(23, 30);

        while ($start <= $end) {
            $this->timeSlots[] = $start->format('H:i');
            $start->addMinutes(30);
        }
    }

    private function checkReservation($pistaId, $hora, $dia)
    {
        return Reservas::where('pista_id', $pistaId)
            ->where('dia', $dia)
            ->where('hora_inicio', '<=', $hora)
            ->where('hora_fin', '>', $hora)
            ->exists();
    }

    public function reserveAllHours($dia)
    {
        foreach ($this->timeSlots as $hora) {
            foreach ($this->pistasSeleccionadas as $pistaId) {
                if (!$this->checkReservation($pistaId, $hora, $dia)) {
                    Reservas::create([
                        'pista_id' => $pistaId,
                        'dia' => $dia,
                        'hora_inicio' => $hora,
                        'hora_fin' => \Carbon\Carbon::parse($hora)->addMinutes(30)->format('H:i'),
                        'nombre_jugador' => $this->nombre,
                        'torneo_id' => $this->torneoId,
                    ]);
                }
            }
        }

        $this->updateReservations($dia);
        $this->alert('success', 'Todas las horas del día han sido reservadas.');
    }

    public function cancelAllReservations($dia)
    {
        $reservas = Reservas::where('torneo_id', $this->torneoId)
            ->where('dia', $dia)
            ->get();

        foreach ($reservas as $reserva) {
            $reserva->delete();
        }

        $this->updateReservations($dia);
        $this->alert('success', 'Todas las reservas del día han sido canceladas.');
    }

    private function updateReservations($dia)
    {
        $this->reservas = Reservas::where('torneo_id', $this->torneoId)
            ->where('dia', $dia)
            ->get()
            ->groupBy('pista_id')
            ->mapWithKeys(function ($group, $pistaId) {
                return [$pistaId => $group->pluck('hora_inicio')->toArray()];
            })
            ->toArray();
    }

    public function updatedCategoriaSeleccionada() {
        // Obtener inscripciones de la categoria seleccionada
        $inscripciones = TorneosCategoriasInscripciones::where('torneo_categoria_id', $this->categoriaSeleccionada)->pluck('id');
        
        // Obtener los duos de la categoria seleccionada
        $duos = TorneosDuos::whereIn('inscripcion_id', $inscripciones)
            ->orWhereIn('inscripcion_id_2', $inscripciones)
            ->pluck('grupo')
            ->toArray();
        
        // Obtener el grupo más alto existente
        $grupo = null;
        if (!empty($duos)) {
            // Ordenar los grupos alfabéticamente y obtener el último
            sort($duos);
            $grupo = end($duos);
        }
    
        // Determinar el siguiente grupo
        if ($grupo == null) {
            $grupo = 'A';
        } else {
            $grupo = chr(ord($grupo) + 1);
        }
    
        $this->grupoDuo = $grupo;
    }
    

    public function saveInscripcion()
    {
        //dd($this->tipoInscripcion);
        //dd($this->categoriaSeleccionada , $this->inscripcionNombre, $this->apellidos, $this->email, $this->telefono, $this->DNI, $this->nickName, $this->ciudad, $this->genero, $this->comentario, $this->nombre2, $this->apellidos2, $this->email2, $this->telefono2);
        
        if($this->tipoInscripcion == 'individual'){
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
        }else{
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
                'nombre2' => 'required_if:tipoInscripcion,doble|string|max:255',
                'apellidos2' => 'required_if:tipoInscripcion,doble|string|max:255',
                'email2' => 'required_if:tipoInscripcion,doble|email|max:255',
                'telefono2' => 'required_if:tipoInscripcion,doble|string|max:20',
            ]);
        }
        
        
        if($this->jugadorId == ''){
            $this->jugadorId = null;
        }

        if($this->jugadorId2 == ''){
            $this->jugadorId2 = null;
        }

        $inscripcion1 = TorneosCategoriasInscripciones::create([
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

        if ($this->tipoInscripcion === 'doble') {
           $inscripcion2 = TorneosCategoriasInscripciones::create([
                'torneo_categoria_id' => $this->categoriaSeleccionada,
                'jugador_id' => $this->jugadorId2, 
                'fecha_inscripcion' => Carbon::now(),
                'email' => $this->email2,
                'telefono' => $this->telefono2,
                'DNI' => $this->DNI2, 
                'nickName' => $this->nickName2, 
                'nombre' => $this->nombre2,
                'apellidos' => $this->apellidos2,
                'ciudad' => $this->ciudad2, 
                'genero' => $this->genero2, 
                'categoria' => $this->categoriaSeleccionada, 
                'comentario' => $this->comentario2, 
                'pagado' => false,
                'total_precio' => 0.00, 
                'pendiente' => 0.00, 
            ]);

            if($inscripcion1 && $inscripcion2){
                $this->alert('success', 'Inscripción guardada correctamente.');
                $this->resetInscripcionForm();
                $this->inscripciones = TorneosCategoriasInscripciones::whereIn('torneo_categoria_id', function ($query) {
                    $query->select('id')
                        ->from('torneos_categorias')
                        ->where('torneo_id', $this->torneoId);
                })->get();

                //añadir torneo duos
                TorneosDuos::create([
                    'inscripcion_id' => $inscripcion1->id,
                    'inscripcion_id_2' => $inscripcion2->id,
                    'grupo' => $this->grupoDuo,
                ]);


            } else {
                $this->alert('error', 'Ha ocurrido un error al guardar la inscripción.');
            }



        }else{
            //comprobar si hay alguna inscripcion que no tenga pareja, es decir, que este en TorneosDuos pero no tenga inscripcion_id_2, pero debe de ser una inscripcion de este torneo
            $inscripcionSinPareja = TorneosDuos::where('inscripcion_id_2', null)->whereHas('inscripcion', function($query) use ($inscripcion1){
                $query->where('torneo_categoria_id', $inscripcion1->torneo_categoria_id);
            })->first();

            //si la hay, se asigna la pareja con la inscripcion actual
            if($inscripcionSinPareja){
                $inscripcionSinPareja->update([
                    'inscripcion_id_2' => $inscripcion1->id,
                ]);

                $this->resetInscripcionForm();
                $this->inscripciones = TorneosCategoriasInscripciones::whereIn('torneo_categoria_id', function ($query) {
                    $query->select('id')
                        ->from('torneos_categorias')
                        ->where('torneo_id', $this->torneoId);
                })->get();
            }else{
                //si no la hay, se crea un torneo duos con la inscripcion actual sin pareja
                TorneosDuos::create([
                    'inscripcion_id' => $inscripcion1->id,
                    'grupo' => $this->grupoDuo,
                ]);

            }
          
                
        }

        $this->alert('success', 'Inscripción guardada correctamente.');
        $this->resetInscripcionForm();
        $this->inscripciones = TorneosCategoriasInscripciones::whereIn('torneo_categoria_id', function ($query) {
            $query->select('id')
                ->from('torneos_categorias')
                ->where('torneo_id', $this->torneoId);
        })->get();
        $this->duos = TorneosDuos::whereIn('inscripcion_id', $this->inscripciones->pluck('id'))
        ->orWhereIn('inscripcion_id_2', $this->inscripciones->pluck('id'))
        ->get();
    
    }

    public function searchJugador($var1, $var2)
    {
        $jugador = Cliente::where($var1,$var2)
            ->first();

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

    public function searchJugador2($var1, $var2)
    {
        $jugador = Cliente::where($var1, $var2)
            ->first();

        if ($jugador) {
            $this->jugadorId2 = $jugador->id;
            $this->nombre2 = $jugador->nombre;
            $this->apellidos2 = $jugador->apellido;
            $this->email2 = $jugador->email1;
            $this->telefono2 = $jugador->telefono;
            $this->DNI2 = $jugador->DNI ?? '';
            $this->nickName2 = $jugador->nickName ?? '';
            $this->ciudad2 = $jugador->ciudad;
            $this->genero2 = $jugador->genero;
            $this->comentario2 = $jugador->comentario ?? '';
            $this->bloqJugador2 = true;
        }
    }

    public function removeInscripcion($id)
    {
        TorneosCategoriasInscripciones::find($id)->delete();
        $this->inscripciones = TorneosCategoriasInscripciones::whereIn('torneo_categoria_id', function ($query) {
            $query->select('id')
                  ->from('torneos_categorias')
                  ->where('torneo_id', $this->torneoId);
        })->get();

        //buscar torneos duos con esta inscripcion
        $torneoDuo = TorneosDuos::where('inscripcion_id', $id)->first();
        if($torneoDuo){
            //si el torneoDuo no tiene inscripcion_id_2, se elimina
            if($torneoDuo->inscripcion_id_2 == null){
                $torneoDuo->delete();
            }
            //si tiene inscripcion_id_2, se pasa la inscripcion id 2 a la id 1 y se pone en null la id 2
            else{
                $torneoDuo->update([
                    'inscripcion_id' => $torneoDuo->inscripcion_id_2,
                    'inscripcion_id_2' => null,
                ]);
            }
        }else{
            $torneoDuo = TorneosDuos::where('inscripcion_id_2', $id)->first();
            if($torneoDuo){
                $torneoDuo->update([
                    'inscripcion_id_2' => null,
                ]);
            }
        }



        $this->alert('success', 'Inscripción eliminada correctamente.');
    }

    private function resetInscripcionForm()
    {
        $this->reset(['categoriaSeleccionada', 'jugadorId', 'inscripcionNombre', 'apellidos', 'email', 'telefono', 'DNI', 'nickName', 'ciudad', 'genero', 'comentario', 'nombre2', 'apellidos2', 'email2', 'telefono2', 'DNI2', 'nickName2', 'ciudad2', 'genero2', 'comentario2', 'bloqJugador1', 'bloqJugador2']);
    }




    //--------------------------------- CUADROS DE TORNEO ---------------------------------//

    public function generatePartidos()
    {
        $torneo = Torneos::find($this->torneoId);
    
        foreach ($this->categoriasDisponibles as $categoria) {
            // Obtener los duos de la categoría correspondiente y aleatorizarlos
            $duos = TorneosDuos::with(['inscripcion', 'inscripcion2'])
                ->whereHas('inscripcion', function ($query) use ($categoria) {
                    $query->where('torneo_categoria_id', $categoria->id);
                })
                ->orWhereHas('inscripcion2', function ($query) use ($categoria) {
                    $query->where('torneo_categoria_id', $categoria->id);
                })
                ->inRandomOrder()
                ->get();
    
            $totalPartidos = $duos->count() - 1;  // Total de partidos en el torneo
            //dd($totalPartidos);
            $rondaActual = 1;
            $ultimaHoraFin = null;
    
            while ($totalPartidos > 0) {
                $partidosGenerados = [];
                $reservas = $this->getReservasParaRonda($ultimaHoraFin);
    
                $numPartidos = intval($duos->count() / 2);  // Número de partidos en esta ronda
                
                for ($i = 0; $i < $numPartidos; $i++) {
                    $totalPartidos--;
                    $duo1 = $duos->shift();
                    $duo2 = $duos->shift();
    
                    $equipo1Id = $this->isDuoValido($duo1->id) ? $duo1->id : null;
                    $equipo2Id = $this->isDuoValido($duo2->id) ? $duo2->id : null;
    
                    $reserva = $this->getNextAvailableReserva($reservas, $ultimaHoraFin, null, $duo1, $duo2);
    
                    if ($reserva && !$this->isPistaOcupada($reserva['pista_id'], $reserva['dia'], $reserva['hora_inicio'], $reserva['hora_fin'])) {
                        //si ya hay un partido para ese equipo id y el equipo id 2 para este torneo y categoria, no se crea el partido


                        $partido = Partidos::create([
                            'torneo_id' => $this->torneoId,
                            'torneos_categorias_id' => $categoria->id,
                            'equipo1_id' => $equipo1Id,
                            'equipo2_id' => $equipo2Id,
                            'dia' => $reserva['dia'],
                            'hora_inicio' => $reserva['hora_inicio'],
                            'hora_fin' => $reserva['hora_fin'],
                            'pista_id' => $reserva['pista_id'],
                        ]);

                        if($equipo1Id && $equipo2Id){
                            //poner esos duos en inactivo
                            TorneosDuos::where('id', $equipo1Id)->update(['estado' => false]);
                            TorneosDuos::where('id', $equipo2Id)->update(['estado' => false]);
                        }else if($equipo1Id){
                            TorneosDuos::where('id', $equipo1Id)->update(['estado' => false]);
                        }else if($equipo2Id){
                            TorneosDuos::where('id', $equipo2Id)->update(['estado' => false]);
                        }
    
                        $partidosGenerados[] = $partido;
    
                        // Si es el último partido de esta ronda, actualizamos la última hora de finalización
                        if ($i == $numPartidos - 1) {
                            $ultimaHoraFin = $reserva['hora_fin'];
                        }
    
                        Reservas::where('id', $reserva['id'])->update(['partido_id' => $partido->id]);
                    } else {
                        \Log::error('No se pudo crear el partido porque no se encontró una reserva válida.', [
                            'duo1' => $duo1,
                            'duo2' => $duo2,
                        ]);
                    }
                }
    
                if ($duos->count() === 1) {
                    $partidosGenerados[] = $duos->pop(); // El último dúo pasa automáticamente
                }
    
                $duos = collect($partidosGenerados);  // Los ganadores avanzan a la siguiente ronda
                $rondaActual++;
            }
        }
    }
    


// Función para validar si un ID de dúo es válido y existe en la base de datos
private function isDuoValido($duoId)
{
    return TorneosDuos::where('id', $duoId)->exists();
}



private function getReservasParaRonda($ultimaHoraFin)
{

   $reservas = Reservas::where('torneo_id', $this->torneoId)
        ->whereNull('partido_id')
        ->orderBy('dia')
        ->orderBy('hora_inicio')
        ->get();


    if($ultimaHoraFin == null){
        return $reservas->toArray();
    }

    return $reservas->filter(function ($reserva) use ($ultimaHoraFin) {
        return strtotime($reserva->hora_inicio) >= strtotime($ultimaHoraFin . ' +30 minutes');
    })->toArray();
}

private function getUltimaHoraFinRonda()
{
    // Obtener la última hora de finalización de todos los partidos generados para el torneo actual
    $ultimaReserva = Partidos::where('torneo_id', $this->torneoId)
        ->orderByDesc('hora_fin')
        ->first();

    return $ultimaReserva ? $ultimaReserva->hora_fin : '00:00:00';
}

    private function getNextRondaNumber($torneoId, $categoriaId)
{
    $ultimoPartido = Partidos::where('torneo_id', $torneoId)
        ->where('torneos_categorias_id', $categoriaId)
        ->orderByDesc('ronda')
        ->first();

    return $ultimoPartido ? $ultimoPartido->ronda + 1 : 1;
}
    

private function getNextAvailableReserva($reservas, $ultimaHoraFin, $otrareserva, $duo1, $duo2)
{
    foreach ($reservas as $reserva) {

        if($otrareserva){
            if($reserva['dia'] == $otrareserva['dia'] && $reserva['hora_inicio'] == $otrareserva['hora_inicio']){
                continue;
            }
        }
        
        if ($ultimaHoraFin === null || strtotime($reserva['hora_inicio']) >= strtotime($ultimaHoraFin . ' +30 minutes')) {
            $disponibilidad = $this->checkDisponibilidad($duo1 ,$reserva['dia'], $reserva['hora_inicio']);
            if($disponibilidad){
                $disponibilidad = $this->checkDisponibilidad($duo2, $reserva['dia'], $reserva['hora_inicio']);
            }

            if(!$disponibilidad){
                continue;
            }

            if (!$this->isPistaOcupada($reserva['pista_id'], $reserva['dia'], $reserva['hora_inicio'], $reserva['hora_fin'])) {
                return $reserva;
            }
        }
    }
    return null;
}


private function crearPartidosDeRonda($duos, $reservas, $categoria, $ronda)
{
    $partidosGenerados = [];
    $indexDuo = 0;

    while ($indexDuo < count($duos) - 1 && $reservas->isNotEmpty()) {
        $duo1 = $duos[$indexDuo];
        $duo2 = $duos[$indexDuo + 1];

        $reserva = $reservas->shift(); // Tomar la primera reserva disponible

        // Crear el partido
        $partido = Partidos::create([
            'torneo_id' => $this->torneoId,
            'torneos_categorias_id' => $categoria->id,
            'equipo1_id' => $duo1->id,
            'equipo2_id' => $duo2->id,
            'dia' => $reserva['dia'],
            'hora_inicio' => $reserva['hora_inicio'],
            'hora_fin' => $reserva['hora_fin'],
            'pista_id' => $reserva['pista_id'],
            'ronda' => $ronda, // Asignar la ronda actual
        ]);

        $partidosGenerados[] = $partido;

        // Actualizar la reserva con el partido asignado
        // $reserva->update([
        //     'partido_id' => $partido->id,
        // ]);
        Reservas::where('id', $reserva['id'] )->update(['partido_id' => $partido->id]);

        $indexDuo += 2;
    }

    return $partidosGenerados;
}

private function isPartidoExistente($duo1Id, $duo2Id, $categoriaId)
{
    return Partidos::where('torneos_categorias_id', $categoriaId)
        ->where(function ($query) use ($duo1Id, $duo2Id) {
            $query->where(function ($q) use ($duo1Id, $duo2Id) {
                $q->where('equipo1_id', $duo1Id)
                    ->where('equipo2_id', $duo2Id);
            })->orWhere(function ($q) use ($duo1Id, $duo2Id) {
                $q->where('equipo1_id', $duo2Id)
                    ->where('equipo2_id', $duo1Id);
            });
        })
        ->exists();
}

private function checkDisponibilidad($duo, $dia, $hora)
{
    $disponibilidad = TorneosInscripcionDisponibilidad::where('inscripcion_id', $duo->id)
    ->where('fecha_no_disponible', $dia)
    ->where('hora_no_disponible', $hora)
    ->exists();

    

    return !$disponibilidad;
}

private function isPistaOcupada($pistaId, $dia, $horaInicio, $horaFin)
{
    $conflicto = Partidos::where('pista_id', $pistaId)
        ->where('dia', $dia)
        ->where(function ($query) use ($horaInicio, $horaFin) {
            $query->where(function ($q) use ($horaInicio, $horaFin) {
                $q->where('hora_inicio', '<', $horaFin)
                    ->where('hora_fin', '>', $horaInicio);
            });
        })
        ->exists();

    return $conflicto;
}

private function generateNextRounds($duosIds, $reservas, $torneoId, $categoriaId, $ultimaHoraFin)
{
    if (count($duosIds) <= 1) {
        return; // Termina si queda un solo equipo
    }

    $newRound = [];
    $indexReserva = 0;

    // Generar todos los partidos de la ronda actual
    while (count($duosIds) > 1) {
        $equipo1Id = array_shift($duosIds);
        $equipo2Id = array_shift($duosIds);

        $reserva = $this->getNextAvailableReserva($reservas, $ultimaHoraFin);

        if ($reserva) {
            $partido = Partidos::create([
                'torneo_id' => $torneoId,
                'torneos_categorias_id' => $categoriaId,
                'equipo1_id' => $equipo1Id,
                'equipo2_id' => $equipo2Id,
                'dia' => $reserva['dia'],
                'hora_inicio' => $reserva['hora_inicio'],
                'hora_fin' => $reserva['hora_fin'],
                'pista_id' => $reserva['pista_id'],
            ]);

            $newRound[] = $equipo1Id;
            $newRound[] = $equipo2Id;

            Reservas::where('id', $reserva['id'])->update(['partido_id' => $partido->id]);

            $ultimaHoraFin = $reserva['hora_fin'];
        }
    }

    // Si queda un equipo sin rival, pasa automáticamente a la siguiente ronda
    if (count($duosIds) === 1) {
        $newRound[] = array_shift($duosIds);
    }

    // Generar la siguiente ronda
    $this->generateNextRounds($newRound, $reservas, $torneoId, $categoriaId, $ultimaHoraFin);
}
public function getPartidos($torneoId)
{
    return Partidos::where('torneo_id', $torneoId)->get();
}

private function generateAllRounds($previousRound, $reservas, $torneoId, $categoriaId)
{
    $newRound = [];
    $indexReserva = 0;

    while (count($previousRound) > 1 && $indexReserva < count($reservas)) {
        $partido1 = array_shift($previousRound);
        $partido2 = array_shift($previousRound);

        $reserva = $reservas[$indexReserva];

        // Crear partidos sin asignar duos hasta que se conozcan los ganadores
        if (!$this->isPistaOcupada($reserva->pista_id, $reserva['dia'], $reserva['hora_inicio'], $reserva['hora_fin'])) {
            $partido = Partidos::create([
                'torneo_id' => $torneoId,
                'torneos_categorias_id' => $categoriaId,
                'equipo1_id' => null, // Ganador del partido1
                'equipo2_id' => null, // Ganador del partido2
                'dia' => $reserva['dia'],
                'hora_inicio' => $reserva['hora_inicio'],
                'hora_fin' => $reserva['hora_fin'],
                'pista_id' => $reserva['pista_id'],
            ]);

            $newRound[] = $partido;

            // $reserva->update([
            //     'partido_id' => $partido->id,
            // ]);

            Reservas::where('id', $reserva['id'] )->update(['partido_id' => $partido->id]);

            $indexReserva++;
        }
    }

    if (count($newRound) > 1) {
        $this->generateAllRounds($newRound, $reservas, $torneoId, $categoriaId);
    }
}



private function getAvailableHours($partido)
{
    $torneoId = $partido->torneo_id;
    $dia = $partido->dia;
    $pistaId = $partido->pista_id;

    $horasPosibles = Reservas::where('torneo_id', $torneoId)
        ->where('dia', $dia)
        ->where('pista_id', $pistaId)
        ->whereNull('partido_id')
        ->pluck('hora_inicio')
        ->toArray();

    // si no hay equipo 1 o equipo 2, devolver todas las horas posibles
    if (!$partido->equipo1 || !$partido->equipo2) {
        return $horasPosibles;
    }
    $noDisponiblesDuo1 = TorneosInscripcionDisponibilidad::where('inscripcion_id', $partido->equipo1->id)
        ->where('fecha_no_disponible', $dia)
        ->pluck('hora_no_disponible')
        ->toArray();

    $noDisponiblesDuo2 = TorneosInscripcionDisponibilidad::where('inscripcion_id', $partido->equipo2->id)
        ->where('fecha_no_disponible', $dia)
        ->pluck('hora_no_disponible')
        ->toArray();

    $horasNoDisponibles = array_merge($noDisponiblesDuo1, $noDisponiblesDuo2);
    $horasDisponibles = array_diff($horasPosibles, $horasNoDisponibles);
    //dd($horasPosibles);

    return $horasDisponibles;
}

public function getCategoriaNaME($id)
{
    $categoria = CategoriaJugadores::where('id', $id)->first();
    if ($categoria) {
        return $categoria->nombre;
    }

    return '';
}

public function changeSetPartido($partidoId, $set, $operacion)
{

    if($this->selectedPartido['bloqueado']){
        $this->alert('error', 'No se puede modificar el resultado de un partido bloqueado o finalizado.');
        return;
    }

    $partido = Partidos::find($partidoId);

    if ($partido) {
        $partidoResultados = PartidoResultados::where('partido_id', $partidoId)->first();

        if ($partidoResultados) {
            if ($set == '1') {
                $partidoResultados->duo_1_wins = $operacion === 'sumar' ? $partidoResultados->duo_1_wins + 1 : $partidoResultados->duo_1_wins - 1;
            } else if ($set == '2') {
                $partidoResultados->duo_2_wins = $operacion === 'sumar' ? $partidoResultados->duo_2_wins + 1 : $partidoResultados->duo_2_wins - 1;
            }

            $partidoResultados->save();
        }

        
    }

    $this->torneoResultados = PartidoResultados::where('partido_id', $partidoId)->first();
}


public function changeSets($setId, $set, $operacion){

    if($this->selectedPartido['bloqueado']){
        $this->alert('error', 'No se puede modificar el resultado de un partido bloqueado o finalizado.');
        return;
    }

    $setPartido = PartidoResultadoSets::find($setId);

    if ($setPartido) {

            if ($set == '1') {
                $setPartido->duo_1_score = $operacion === 'sumar' ? $setPartido->duo_1_score + 1 : $setPartido->duo_1_score - 1;
            } else if ($set == '2') {
                $setPartido->duo_2_score = $operacion === 'sumar' ? $setPartido->duo_2_score + 1 : $setPartido->duo_2_score - 1;
            }

            $setPartido->save();
        
            $partidoResultados = PartidoResultadoSets::where('partido_resultado_id', $setPartido->partido_resultado_id)->first();
            //dd($setPartido->partido_resultado_id);
            $this->setsPartido = PartidoResultadoSets::where('partido_resultado_id', $setPartido->partido_resultado_id)->get();
    }



}


public function finalizarPartido($partidoId)
{
    $partido = Partidos::find($partidoId);

    if ($partido) {

        //confirmar con alerta
        $this->confirm('¿Estás seguro de que quieres finalizar este partido?', [
            'toast' => false,
            'position' => 'center',
            'showConfirmButton' => true,
            'cancelButtonText' => 'Cancelar',
            'onConfirmed' => 'finalizarPartidoConfirmed',
            'onCancelled' => 'finalizarPartidoCancelled',
            'data' => [$partidoId],
        ]);
    }else{
        $this->alert('error', 'No se encontró el partido.');

    }

    //no se encontró el partido
}

public function finalizarPartidoConfirmed($partidoId)
{
    $partido = Partidos::find($partidoId['data'][0]);

    if ($partido) {
        $partido->finalizado = true;
        $partido->bloqueado = true;
        $partido->save();

        $partidoResultados = PartidoResultados::where('partido_id', $partidoId['data'][0])->first();

        $winner = null;
        $loser = null;

        //saber cual es mayor si duo_1_wins o duo_2_wins
        if($partidoResultados->duo_1_wins > $partidoResultados->duo_2_wins){
            $partidoResultados->winner_id = $partido->equipo1_id;
            //actualizar duo perdedor con estado inactivo
            TorneosDuos::where('id', $partido->equipo2_id)->update(['estado' => false]);
            //actualizar duo ganador con estado activo
            TorneosDuos::where('id', $partido->equipo1_id)->update(['estado' => true]);
            $winner = $partido->equipo1_id;
            $loser = $partido->equipo2_id;
        }else{
            $partidoResultados->winner_id = $partido->equipo2_id;
            //actualizar duo perdedor con estado inactivo
            TorneosDuos::where('id', $partido->equipo1_id)->update(['estado' => false]);
            //actualizar duo ganador con estado activo
            TorneosDuos::where('id', $partido->equipo2_id)->update(['estado' => true]);
            $winner = $partido->equipo2_id;
            $loser = $partido->equipo1_id;

        }

        $partidoResultados->save();

        //saber si es el ultimo partido de su categoria en este torneo
        $partidosCategoria = Partidos::where('torneo_id', $partido->torneo_id)->where('torneos_categorias_id', $partido->torneos_categorias_id)->get();

        $partidosFinalizados = $partidosCategoria->where('finalizado', true)->count();

        if($partidosCategoria->count() == $partidosFinalizados){
            
            $duoGanador = TorneosDuos::where('id', $winner)->first();
            $duoPerdedor = TorneosDuos::where('id', $loser)->first();

            //actualizar torneo duos con estado inactivo
            TorneosDuos::where('id', $duoPerdedor->id)->update(['estado' => false]);
            TorneosDuos::where('id', $duoGanador->id)->update(['estado' => false]);

           // dd($duoGanador, $duoPerdedor);

            $individualGanador = TorneosCategoriasInscripciones::where('id', $duoGanador->inscripcion_id)->first();
            $individualGanador2 = TorneosCategoriasInscripciones::where('id', $duoGanador->inscripcion_id_2)->first();


            $individualPerdedor = TorneosCategoriasInscripciones::where('id', $duoPerdedor->inscripcion_id)->first();
            $individualPerdedor2 = TorneosCategoriasInscripciones::where('id', $duoPerdedor->inscripcion_id_2)->first();

            $jugadorGanador = Cliente::where('id', $individualGanador->jugador_id)->first();
            $jugadorGanador2 = Cliente::where('id', $individualGanador2->jugador_id)->first();
            $jugadorPerdedor = Cliente::where('id', $individualPerdedor->jugador_id)->first();
            $jugadorPerdedor2 = Cliente::where('id', $individualPerdedor2->jugador_id)->first();

            //dd($jugadorGanador, $jugadorGanador2, $jugadorPerdedor, $jugadorPerdedor2);

            //crer torneoresultado con torneo id, jugador id, inscripcion id, posicion final.
            $resultado1 = TorneosResultados::create([
                'torneo_id' => $partido->torneo_id,
                'jugador_id' => $jugadorGanador->id,
                'inscripcion_id' => $individualGanador->id,
                'posicion_final' => 1,
                'resultado' => $partidoResultados->duo_1_wins . ' - ' . $partidoResultados->duo_2_wins,
                'puntos' => 100,
            ]);

            $resultado2 = TorneosResultados::create([
                'torneo_id' => $partido->torneo_id,
                'jugador_id' => $jugadorGanador2->id,
                'inscripcion_id' => $individualGanador2->id,
                'posicion_final' => 1,
                'resultado' => $partidoResultados->duo_1_wins . ' - ' . $partidoResultados->duo_2_wins,
                'puntos' => 100,
            ]);

            $resultado3 = TorneosResultados::create([
                'torneo_id' => $partido->torneo_id,
                'jugador_id' => $jugadorPerdedor->id,
                'inscripcion_id' => $individualPerdedor->id,
                'posicion_final' => 2,
                'resultado' => $partidoResultados->duo_1_wins . ' - ' . $partidoResultados->duo_2_wins,
                'puntos' => 50,
            ]);

            $resultado4 = TorneosResultados::create([
                'torneo_id' => $partido->torneo_id,
                'jugador_id' => $jugadorPerdedor2->id,
                'inscripcion_id' => $individualPerdedor2->id,
                'posicion_final' => 2,
                'resultado' => $partidoResultados->duo_1_wins . ' - ' . $partidoResultados->duo_2_wins,
                'puntos' => 50,
            ]);


            $this->organizarResultadosPorCategoria();



        }


    }

    $this->selectedPartido = Partidos::with(['equipo1', 'equipo2', 'pista', 'torneos_categorias'])->find($partidoId['data'][0]);
    $this->torneoResultados = PartidoResultados::where('partido_id', $partidoId['data'][0])->first();
    $this->alert('success', 'Partido finalizado correctamente.');
}


public function organizarResultadosPorCategoria()
{
    $resultados = TorneosResultados::where('torneo_id', $this->torneoId)->get();

    // Organizar resultados por categoría
    $resultadosPorCategoria = [];
    foreach ($resultados as $resultado) {
        $categoriaNombre = $resultado->inscripcion->torneoCategoria->categoria->nombre;

        if (!isset($resultadosPorCategoria[$categoriaNombre])) {
            $resultadosPorCategoria[$categoriaNombre] = [
                'campeones' => [],
                'subcampeones' => []
            ];
        }

        if ($resultado->posicion_final == 1) {
            $resultadosPorCategoria[$categoriaNombre]['campeones'][] = $resultado->inscripcion;
        } elseif ($resultado->posicion_final == 2) {
            $resultadosPorCategoria[$categoriaNombre]['subcampeones'][] = $resultado->inscripcion;
        }
    }

    $this->resultadosPorCategoria = $resultadosPorCategoria;
}



public function bloquearPartido($partidoId)
{
    $partido = Partidos::find($partidoId);

    if ($partido) {
        $partido->bloqueado = true;
        $partido->save();
        $this->selectedPartido = Partidos::with(['equipo1', 'equipo2', 'pista', 'torneos_categorias'])->find($partidoId);

        $this->alert('success', 'Partido bloqueado correctamente.');
    }else{
        $this->alert('error', 'No se encontró el partido.');
    }

    

   
}

public function desbloquearPartido($partidoId)
{
    $partido = Partidos::find($partidoId);

    if ($partido) {

        if($partido->finalizado){
            //no se puede bloquear
            //alert error
            $this->alert('error', 'No se puede desbloquear un partido finalizado.');
            return;
        }

        $partido->bloqueado = false;
        $partido->save();

        $this->selectedPartido = Partidos::with(['equipo1', 'equipo2', 'pista', 'torneos_categorias'])->find($partidoId);

        $this->alert('success', 'Partido desbloqueado correctamente.');
    }else{
        $this->alert('error', 'No se encontró el partido.');
    }
}


public function openModal($partidoId)
{
    //dd($partidoId);
    $partido = Partidos::with(['equipo1', 'equipo2', 'pista', 'torneos_categorias'])->find($partidoId);

    if ($partido) {
        $this->selectedPartido = Partidos::with(['equipo1', 'equipo2', 'pista', 'torneos_categorias'])->find($partidoId);
        $this->horasDisponibles = $this->getAvailableHours($partido);

        //Asignar torneoResultados
        $this->torneoResultados = PartidoResultados::where('partido_id', $partidoId)->first();



        //$this->setsPartido = PartidoResultadoSets::where('partido_resultado_id', $this->torneoResultados->id)->get();

        //dd($this->torneoResultados);

        if(!$this->torneoResultados){
            //crear los resultados
            $duo1 = TorneosDuos::where('id', $this->selectedPartido['equipo1_id'])->first();
            $duo2 = TorneosDuos::where('id', $this->selectedPartido['equipo2_id'])->first();

            if($duo1 && $duo2){
                PartidoResultados::create([
                    'partido_id' => $partidoId,
                    'torneo_id' => $this->selectedPartido['torneo_id'],
                    'duo_1_id' => $duo1->id,
                    'duo_2_id' => $duo2->id,
                ]);

                $this->torneoResultados = PartidoResultados::where('partido_id', $partidoId)->first();

            }

       
        }

        //crear sets
        //$this->setsPartido = PartidoResultadoSets::where('partido_resultado_id', $this->torneoResultados->id)->get();
        $duo1 = TorneosDuos::where('id', $this->selectedPartido['equipo1_id'])->first();
        $duo2 = TorneosDuos::where('id', $this->selectedPartido['equipo2_id'])->first();

        if(count($this->setsPartido) == 0 && $duo1 && $duo2){
            //crear 3 setsPartido, es decir, crear 3 registros

            $sets = PartidoResultadoSets::where('partido_resultado_id', $this->torneoResultados->id)->get();

            if($sets->count() == 0){

                for ($i=0; $i < 3; $i++) { 
                    PartidoResultadoSets::create([
                        'partido_resultado_id' => $this->torneoResultados->id,
                        'set_number' => $i + 1,
                        'duo_1_id' => $duo1->id,
                        'duo_2_id' => $duo2->id,
                    ]);
                }
            }


        }
        if($this->torneoResultados){
            $this->setsPartido = PartidoResultadoSets::where('partido_resultado_id', $this->torneoResultados->id)->get();
        }else{
            $this->setsPartido = [];
        }
        //$this->setsPartido = PartidoResultadoSets::where('partido_resultado_id', $this->torneoResultados->id)->get();

        $this->dispatchBrowserEvent('openModal', ['id' => 'partidoModal']);
    }
}


public function asignarDuo($partidoId , $posicion)
{
    //Debo encontrar duos inscritos en la categoria del torneo, que no esten inactivos
    $partido = Partidos::find($partidoId);

    if ($partido) {
        $duos = TorneosDuos::where(function ($query) use ($partido) {
            $query->whereHas('inscripcion', function ($query) use ($partido) {
                $query->whereHas('torneoCategoria', function ($query) use ($partido) {
                    $query->where('torneo_id', $partido->torneo_id)
                          ->where('id', $partido->torneos_categorias_id);
                });
            })
            ->orWhereHas('inscripcion2', function ($query) use ($partido) {
                $query->whereHas('torneoCategoria', function ($query) use ($partido) {
                    $query->where('torneo_id', $partido->torneo_id)
                          ->where('id', $partido->torneos_categorias_id);
                });
            });
        })
        ->where('estado', 1)  // Asegura que solo se seleccionen los dúos con estado verdadero (1)
        ->get();


        $this->duosDisponibles = $duos;
        //dd($duos);

    
        if($this->duosDisponibles->count() == 0){
            $this->alert('error', 'No hay duos disponibles para asignar.');
            return;
        }
        

        //coger un duo random
        $duo = $duos->random();

        $this->selectedPartido = Partidos::with(['equipo1', 'equipo2', 'pista', 'torneos_categorias'])->find($partidoId);

        //asignar el duo al partido

        if($posicion == 1){
            $partido->equipo1_id = $duo->id;
        }else{
            $partido->equipo2_id = $duo->id;
        }

        $partido->save();

        //poner el duo en inactivo
        $duo->estado = false;

        $duo->save();


        $this->selectedPartido = Partidos::with(['equipo1', 'equipo2', 'pista', 'torneos_categorias'])->find($partidoId);
        



        $this->dispatchBrowserEvent('openModal', ['id' => 'asignarDuoModal']);
    }

    
}

public function openModalWithAvailableHours($partidoId)
{
    $this->selectedPartido = Partidos::with(['equipo1.inscripcion', 'equipo2.inscripcion', 'pista'])->find($partidoId);
    $this->horasDisponibles = $this->getAvailableHours($this->selectedPartido);
}

public function updateHora($nuevaHora)
{

    if($this->selectedPartido['bloqueado']){
        $this->alert('error', 'No se puede modificar la hora de un partido bloqueado o finalizado.');
        return;
    }

    $partidoid = $this->selectedPartido['id'];
    $this->selectedPartido['hora_inicio'] = $nuevaHora;
    //hay que ver la reserva del partido y quitarle el partido_id;
    $reserva = Reservas::where('partido_id', $this->selectedPartido['id'])->first();
    if($reserva){
        $reserva->partido_id = null;
        $reserva->save();
    }   
    //Ahora coger la nueva reserva y asignarle el partido_id
    $reserva = Reservas::where('dia', $this->selectedPartido['dia'])
        ->where('hora_inicio', $nuevaHora)
        ->where('pista_id', $this->selectedPartido['pista_id'])
        ->whereNull('partido_id')
        ->first();

    $reserva->partido_id = $partidoid;

    $reserva->save();



    //cambiar en el partido la hora_inicio y hora_fin
    $partido = Partidos::find($partidoid);
    $partido->hora_inicio = $nuevaHora;
    $partido->hora_fin = \Carbon\Carbon::parse($nuevaHora)->addMinutes(30)->format('H:i');
    $partido->save();


    //actualizar la vista con la nueva hora
    $this->dispatchBrowserEvent('closeModal', ['id' => 'partidoModal']);
    $this->emit('refreshComponent');


    //dd($reserva);


}


public function updatedcomentarioPartido(){

    //dd($this->comentarioPartido);

    $partido = Partidos::find($this->selectedPartido['id']);
    if ($partido) {
        $partido->comentario = $this->comentarioPartido;
        $partido->save();
    }

    $this->dispatchBrowserEvent('closeModal', ['id' => 'partidoModal']);

    $this->emit('refreshComponent');
    $partido = Partidos::find($this->selectedPartido['id']);
    //dd($partido);

}



public function guardarNuevaHora()
{

    

    // Guardar la nueva hora en la base de datos
    $partido = Partidos::find($this->selectedPartido['id']);
    if ($partido) {
        $partido->hora_inicio = $this->selectedPartido['hora_inicio'];
        $partido->save();

        $this->dispatchBrowserEvent('closeModal', ['id' => 'partidoModal']);
        $this->emit('refreshComponent'); // Refrescar la tabla de partidos
    }
}


public function loadPartido($partidoId)
    {
        $partido = Partidos::with(['equipo1.inscripcion', 'equipo2.inscripcion', 'pista', 'categoria'])->find($partidoId);

        if ($partido) {
            $this->selectedPartido = $partido->toArray();
            $this->horasDisponibles = $this->getAvailableHours($partido);
        }

        $this->dispatchBrowserEvent('openModal');
    }

    public function changeresultado($id, $equipo)
{
    if ($this->selectedPartido['bloqueado']) {
        $this->alert('error', 'No se puede modificar el resultado de un partido bloqueado o finalizado.');
        return;
    }
    //dd($equipo);
    $equipo = preg_replace('/(?<!\\\\)\\n/', '', $equipo);

    // Verificar si $equipo es un string JSON
    if (is_string($equipo)) {
        $equipo = json_decode($equipo, true);
    }
    if (is_null($equipo)) {
        $this->alert('error', 'Error al procesar el equipo.');
        return;
    }

    $duo = TorneosDuos::where('id', $equipo['id'])->first();

    if (!$duo) {
        $this->alert('error', 'Dúo no encontrado.');
        return;
    }

    // Cambiar el estado de presentado
    $duo->presentado = !$duo->presentado;
    $duo->save();

    $this->selectedPartido = Partidos::with(['equipo1', 'equipo2', 'pista', 'torneos_categorias'])->find($id);

    $this->emit('refreshComponent');
}
    
    

}
