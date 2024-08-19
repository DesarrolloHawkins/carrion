<?php

namespace App\Http\Livewire\Settings;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\Club;

class ClubComponent extends Component
{
    use LivewireAlert;

    public $club;
    public $nombre;
    public $numero_pistas;
    public $pagina_web;
    public $facebook;
    public $twitter;
    public $descripcion;
    public $nombre_contacto;
    public $email_contacto;
    public $telefono;
    public $direccion;
    public $pais;
    public $ciudad;
    public $poblacion;
    public $codigo_postal;
    public $lunes_apertura;
    public $lunes_cierre;
    public $martes_apertura;
    public $martes_cierre;
    public $miercoles_apertura;
    public $miercoles_cierre;
    public $jueves_apertura;
    public $jueves_cierre;
    public $viernes_apertura;
    public $viernes_cierre;
    public $sabado_apertura;
    public $sabado_cierre;
    public $domingo_apertura;
    public $domingo_cierre;
    public $extracto;
    public $limite_reserva;
    public $tiempo_cancelacion;
    public $maximo_reservas_dia;
    public $maximo_reservas_activas;


    public function mount()
    {
        $this->club = Club::first(); // Asumiendo que solo hay un registro

        if ($this->club) {
            $this->fill($this->club->toArray());
        } else {
            // Si no existe ningún registro, inicializamos con valores por defecto
            $this->fill([
                'nombre' => '',
                'numero_pistas' => 0,
                'pagina_web' => '',
                'facebook' => '',
                'twitter' => '',
                'descripcion' => '',
                'nombre_contacto' => '',
                'email_contacto' => '',
                'telefono' => '',
                'direccion' => '',
                'pais' => '',
                'ciudad' => '',
                'poblacion' => '',
                'codigo_postal' => '',
                'lunes_apertura' => '',
                'lunes_cierre' => '',
                'martes_apertura' => '',
                'martes_cierre' => '',
                'miercoles_apertura' => '',
                'miercoles_cierre' => '',
                'jueves_apertura' => '',
                'jueves_cierre' => '',
                'viernes_apertura' => '',
                'viernes_cierre' => '',
                'sabado_apertura' => '',
                'sabado_cierre' => '',
                'domingo_apertura' => '',
                'domingo_cierre' => '',
                'extracto' => '',
                'limite_reserva' => 0,
                'tiempo_cancelacion' => 0,
                'maximo_reservas_dia' => 0,
            ]);
        }
    }

    public function render()
    {
        return view('livewire.settings.club-component');
    }

    public function submit()
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
            'numero_pistas' => 'required|integer',
            'pagina_web' => 'nullable|url',
            'facebook' => 'nullable|url',
            'twitter' => 'nullable',
            'descripcion' => 'nullable|string',
            'nombre_contacto' => 'nullable|string',
            'email_contacto' => 'nullable|email',
            'telefono' => 'nullable|string',
            'direccion' => 'nullable|string',
            'pais' => 'nullable|string',
            'ciudad' => 'nullable|string',
            'poblacion' => 'nullable|string',
            'codigo_postal' => 'nullable|string',
            'lunes_apertura' => 'nullable|date_format:H:i',
            'lunes_cierre' => 'nullable|date_format:H:i',
            'martes_apertura' => 'nullable|date_format:H:i',
            'martes_cierre' => 'nullable|date_format:H:i',
            'miercoles_apertura' => 'nullable|date_format:H:i',
            'miercoles_cierre' => 'nullable|date_format:H:i',
            'jueves_apertura' => 'nullable|date_format:H:i',
            'jueves_cierre' => 'nullable|date_format:H:i',
            'viernes_apertura' => 'nullable|date_format:H:i',
            'viernes_cierre' => 'nullable|date_format:H:i',
            'sabado_apertura' => 'nullable|date_format:H:i',
            'sabado_cierre' => 'nullable|date_format:H:i',
            'domingo_apertura' => 'nullable|date_format:H:i',
            'domingo_cierre' => 'nullable|date_format:H:i',
            'extracto' => 'nullable|string',
            'limite_reserva' => 'nullable|integer',
            'tiempo_cancelacion' => 'nullable|integer',
            'maximo_reservas_dia' => 'nullable|integer',
            'maximo_reservas_activas' => 'nullable|integer',
        ]);

        if ($this->club) {
            $this->club->update([
                'nombre' => $this->nombre,
                'numero_pistas' => $this->numero_pistas,
                'pagina_web' => $this->pagina_web,
                'facebook' => $this->facebook,
                'twitter' => $this->twitter,
                'descripcion' => $this->descripcion,
                'nombre_contacto' => $this->nombre_contacto,
                'email_contacto' => $this->email_contacto,
                'telefono' => $this->telefono,
                'direccion' => $this->direccion,
                'pais' => $this->pais,
                'ciudad' => $this->ciudad,
                'poblacion' => $this->poblacion,
                'codigo_postal' => $this->codigo_postal,
                'lunes_apertura' => $this->lunes_apertura,
                'lunes_cierre' => $this->lunes_cierre,
                'martes_apertura' => $this->martes_apertura,
                'martes_cierre' => $this->martes_cierre,
                'miercoles_apertura' => $this->miercoles_apertura,
                'miercoles_cierre' => $this->miercoles_cierre,
                'jueves_apertura' => $this->jueves_apertura,
                'jueves_cierre' => $this->jueves_cierre,
                'viernes_apertura' => $this->viernes_apertura,
                'viernes_cierre' => $this->viernes_cierre,
                'sabado_apertura' => $this->sabado_apertura,
                'sabado_cierre' => $this->sabado_cierre,
                'domingo_apertura' => $this->domingo_apertura,
                'domingo_cierre' => $this->domingo_cierre,
                'extracto' => $this->extracto,
                'limite_reserva' => $this->limite_reserva,
                'tiempo_cancelacion' => $this->tiempo_cancelacion,
                'maximo_reservas_dia' => $this->maximo_reservas_dia,
                'maximo_reservas_activas' => $this->maximo_reservas_activas,
            ]);
            $this->alert('success', 'Información del club actualizada correctamente');
        } else {
            Club::create([
                'nombre' => $this->nombre,
                'numero_pistas' => $this->numero_pistas,
                'pagina_web' => $this->pagina_web,
                'facebook' => $this->facebook,
                'twitter' => $this->twitter,
                'descripcion' => $this->descripcion,
                'nombre_contacto' => $this->nombre_contacto,
                'email_contacto' => $this->email_contacto,
                'telefono' => $this->telefono,
                'direccion' => $this->direccion,
                'pais' => $this->pais,
                'ciudad' => $this->ciudad,
                'poblacion' => $this->poblacion,
                'codigo_postal' => $this->codigo_postal,
                'lunes_apertura' => $this->lunes_apertura,
                'lunes_cierre' => $this->lunes_cierre,
                'martes_apertura' => $this->martes_apertura,
                'martes_cierre' => $this->martes_cierre,
                'miercoles_apertura' => $this->miercoles_apertura,
                'miercoles_cierre' => $this->miercoles_cierre,
                'jueves_apertura' => $this->jueves_apertura,
                'jueves_cierre' => $this->jueves_cierre,
                'viernes_apertura' => $this->viernes_apertura,
                'viernes_cierre' => $this->viernes_cierre,
                'sabado_apertura' => $this->sabado_apertura,
                'sabado_cierre' => $this->sabado_cierre,
                'domingo_apertura' => $this->domingo_apertura,
                'domingo_cierre' => $this->domingo_cierre,
                'extracto' => $this->extracto,
                'limite_reserva' => $this->limite_reserva,
                'tiempo_cancelacion' => $this->tiempo_cancelacion,
                'maximo_reservas_dia' => $this->maximo_reservas_dia,
                'maximo_reservas_activas' => $this->maximo_reservas_activas,

            ]);
            $this->alert('success', 'Club creado correctamente');
        }
    }
}
