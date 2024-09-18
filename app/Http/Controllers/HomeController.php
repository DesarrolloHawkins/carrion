<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\TipoEvento;
use Illuminate\Http\Request;
use App\Models\Evento;
use App\Models\Gastos;
use App\Models\Presupuesto;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Mail;
use App\Mail\ReservaPagada2;



class HomeController extends Controller
{
    // /**
    //  * Create a new controller instance.
    //  *
    //  * @return void
    //  */
    // public function __construct()
    // {
    //     $middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $presupuestos = Presupuesto::where('estado', 'Aceptado')->orWhere('estado', 'Pendiente')->orderBy('fechaEmision', 'ASC')->get();
        $categorias = TipoEvento::all();

        $inicioSemana = Carbon::now()->startOfWeek();  // Lunes de esta semana
        $finSemana = Carbon::now()->endOfWeek();  // Domingo de esta semana
        $inicioMes = Carbon::now()->startOfMonth()->startOfWeek();  // Lunes de esta semana
        $finMes = Carbon::now()->endOfMonth()->endOfWeek();  // Domingo de esta semana
        $inicioMesPasado = Carbon::now()->startOfMonth()->startOfWeek()->subMonth();  // Lunes de esta semana
        $finMesPasado = Carbon::now()->endofMonth()->endofWeek()->subMonth();  // Domingo de esta semana
        $ingresos_mensuales = (float) ($presupuestos->whereBetween('fechaEmision', [$inicioMes, $finMes])->sum('precioFinal') - $presupuestos->whereBetween('fechaEmision', [$inicioMes, $finMes])->sum('adelanto'));
        $ingresos_mensuales_pasado = (float) ($presupuestos->whereBetween('fechaEmision', [$inicioMesPasado, $finMesPasado])->sum('precioFinal') - $presupuestos->whereBetween('fechaEmision', [$inicioMesPasado, $finMesPasado])->sum('adelanto'));
        $porcentaje_ingresos_mensuales = $ingresos_mensuales_pasado > 0 ? round(($ingresos_mensuales / $ingresos_mensuales_pasado) * 100) : 0;
        $pendiente = (float) ($presupuestos->where('estado', '!=', 'Facturado')->whereBetween('fechaEmision', [$inicioMes, $finMes])->sum('precioFinal') - $presupuestos->where('estado', '!=', 'Facturado')->whereBetween('fechaEmision', [$inicioMes, $finMes])->sum('adelanto'));

        $user = $request->user();
        $presupuestosMes = Presupuesto::where('estado', 'Facturado')->whereBetween('fechaEmision', [$inicioMes, $finMes])->get();

        $gastos_caja = Caja::whereBetween('fecha', [$inicioSemana, $finSemana])->where('tipo_movimiento', 'Gasto')->sum('importe');
        $ingresos_caja = Caja::whereBetween('fecha', [$inicioSemana, $finSemana])->where('tipo_movimiento', 'Ingreso')->sum('importe');
        $resultados_caja = $ingresos_caja - $gastos_caja;

        return view('mapa.index');
    }

    public function mandaremails()
    {

        // Mail::to('david@hawkins.es')->send(new ReservaPagada2());

        $emails = [
            // 'apariciosanchezester@gmail.com',
            // 'info@davidpuertoroman.es',
            // 'anitafernandezreyes@gmail.com',
            // 'alberto.garrucho@gmail.com',
            // 'manuelmartinv@hotmail.com',
            // 'camachojmfernandez@gmail.com',
            // 'mjberronarbo@gmail.com',
            // 'antonio.dominguezgarcia@gmail.com',
            // 'nekem@jerez.es',
            // 'majilo18@gmail.com',
            // 'danielgalanes@outlook.es',
            // 'pedrolarraondo@gmail.com',
            // 'madisonpuente66@gmail.com',
            // 'fani110077@hotmail.com',
            // 'danielespinosa73@hotmail.com',
            // 'fani110077@hotmail.com',
            // 'abolengo@hotmail.com',
            // 'jaimegomezfm@gmail.com',
            // 'elentris@yahoo.es',
            // 'juanfragd@gmail.com',
            // 'pablojimhar@hotmail.com',
            // 'teresa.espinosa74@gmail.com',
            // 'fernandobenitezmoreno@gmail.com',
            // 'jm.avilabecerra@gmail.com',
            // 'joselu_cuenda@hotmail.com',
            // 'patrycya.80@hotmail.com',
            // 'manuelpichacogutierrez@gmail.com',
            // 'kleteklete@gmail.com',
            // 'javier.rosaro@gmail.com',
            // 'manueljesuslopezlopez14@gmail.com',
            // 'mdelvallesoto@gmail.com',
            // 'peracaula@live.com',
            // 'josignacio1994@gmail.com',
            // 'castellanoalexito@gmail.com',
            // 'juampesantiago@gmail.com',
            // 'juanlurobleda@gmail.com',
            // 'jcmoralessan@hotmail.com',
            // 'f.j.holgado@hotmail.com',
            // 'pilar.martincamas8@gmail.com',
            // 'ELMARQUES21@HOTMAIL.COM',
            // 'lolitajerezana@gmail.com',
            // 'mercedesgallego.82@gmail.com',
            // 'daniconchidaniel@gmail.com',
            // 'fotograbadosur@gmail.com',
            // 'rocioquinterorincon@hotmail.com',
            // 'esorjm@gmail.com',
            // 'alvaro.mateosarellano@hotmail.com',
            // 'cardosodominguezrocio@gmail.com',
            // 'albatros1979@hotmail.es',
            // 'dina_g_b@hotmail.com',
            // 'Info.alejandrodlomas@gmail.com',
            // 'manuanguip@gmail.com',
            // 'pacorodriguezluque@gmail.com',
            // 'enriqmarti@hotmail.com',
            // 'barrosillo@gmail.com',
            // 'mendezguijosa@gmail.com',
        ];

        // foreach ($emails as $email) {
        //     Mail::to($email)->send(new ReservaPagada2());
        // }

        // // Enviar el correo a ivan.mayol@hawkins.es
        // Mail::send('pdf.devuelta', [], function ($message) {
        //     $message->to('ivan.mayol@hawkins.es')
        //             ->subject('Confirmación de Reserva')
        //             ->from(config('mail.from.address'), config('mail.from.name'));
        // });

        // Puedes usar alertas de LivewireAlert si quieres confirmar que el correo fue enviado
    }
}
