<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Reservas;
use App\Models\Sillas;
use App\Models\Zonas;
use App\Models\Gradas;
use App\Models\Palcos;
use App\Models\Sectores;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReservasController extends Controller
{
    public function pdfDownload($cliente_id)
    {
        $cliente = Cliente::find($cliente_id);

        if (!$cliente) {
            return redirect()->back()->with('error', 'Cliente no encontrado.');
        }

        $reservas = Reservas::where('id_cliente', $cliente_id)->where('estado', 'pagada')->get();

        if ($reservas->isEmpty()) {
            return redirect()->back()->with('error', 'No hay reservas pagadas para este cliente.');
        }

        $tasas = 100; // Puedes calcular las tasas aquí si es necesario

        return $this->generarYDescargarPDF($reservas, $cliente, $tasas);
    }

    // public function generarYDescargarPDF($reservas, $cliente, $tasas)
    //     {

    //     // Preparar los detalles de las reservas para el PDF
    //     $detallesReservas = [];
    //     $zona = null;

    //     foreach ($reservas as $reserva) {
    //         $silla = Sillas::find($reserva->id_silla);
    //         $zona = Zonas::find($silla->id_zona);

    //         if ($silla->id_palco != null) {
    //             $palco = Palcos::find($silla->id_palco);
    //             $zona = Sectores::find($palco->id_sector);
    //         } elseif ($silla->id_grada != null) {
    //             $grada = Gradas::find($silla->id_grada);
    //             $zona = Zonas::find($grada->id_zona);
    //         }

    //         $detallesReservas[] = [
    //             'asiento' => $silla->numero ?? 'N/A',
    //             'sector' => $zona->nombre ?? 'N/A',
    //             'fecha' => $reserva->fecha,
    //             'año' => $reserva->año,
    //             'precio' => $reserva->precio,
    //             'fila' => $silla->fila ?? 'N/A',
    //             'order' => $reserva->order,
    //             'palco' => $palco->numero ?? '',
    //             'grada' => $grada->numero ?? '',
    //         ];
    //     }

    //     // Obtener la imagen del mapa según la zona
    //     $mapImage = $this->getMapImageByZona($zona->nombre);
    //     $mapImageBase64 = $this->imageToBase64($mapImage);

    //     // Generar el código QR y almacenarlo como base64
    //     // $qrCodeBase64 = base64_encode(QrCode::format('png')
    //     //     ->size(200)
    //     //     ->generate(url('/reservas/' . $cliente->id)));
    //     $qrCodeBase64 = QrCode::format('svg')
    //         ->size(200)
    //         ->generate(url('/reservas/' . $cliente->id));

    //     // Cálculo del total de precios
    //     $totalReservas = array_sum(array_column($detallesReservas, 'precio'));
    //     $totalPagado = $tasas;
    //     // dd($detallesReservas, $qrCodeBase64, $mapImageBase64, $zona->nombre, $mapImage);

    //     $pdf = PDF::loadView('pdf.reserva_qr', [
    //         'detallesReservas' => $detallesReservas,
    //         'qrCodeBase64' => $qrCodeBase64,
    //         'cliente' => $cliente,
    //         'mapImage' => $mapImageBase64,
    //         'totalReservas' => $totalReservas,
    //         'tasas' => $tasas,
    //         'totalPagado' => $totalPagado,
    //     ])->setPaper('a4', 'vertical');

    //     return response()->streamDownload(
    //         fn () => print($pdf),
    //         "reserva_cliente_" . $cliente->id . ".pdf"
    //     );

    // }

    public function generarYDescargarPDF($reservas, $cliente, $tasas)
    {
        // Preparar los detalles de las reservas para el PDF
        $detallesReservas = [];
        $zona = null;

        foreach ($reservas as $reserva) {
            $silla = Sillas::find($reserva->id_silla);
            $zona = Zonas::find($silla->id_zona);

            if ($silla->id_palco != null) {
                $palco = Palcos::find($silla->id_palco);
                $zona = Sectores::find($palco->id_sector);
            } elseif ($silla->id_grada != null) {
                $grada = Gradas::find($silla->id_grada);
                $zona = Zonas::find($grada->id_zona);
            }

            $detallesReservas[] = [
                'asiento' => $silla->numero ?? 'N/A',
                'sector' => $zona->nombre ?? 'N/A',
                'fecha' => $reserva->fecha,
                'año' => $reserva->año,
                'precio' => $reserva->precio,
                'fila' => $silla->fila ?? 'N/A',
                'order' => $reserva->order,
                'palco' => $palco->numero ?? '',
                'grada' => $grada->numero ?? '',
            ];
        }

        // Si $zona no está definida (por ejemplo, si no hay reservas), establecer un valor por defecto
        if (!$zona) {
            $zona = new \stdClass();
            $zona->nombre = 'default';
        }

        // Generar el código QR en formato SVG directamente
        $qrCodeSvg = QrCode::format('svg')
            ->size(200)
            ->generate(url('/reservas/' . $cliente->id));
        // dd($qrCodeSvg);
        $QrFinal = $this->svgToBase64($qrCodeSvg);
        // Obtener la imagen del mapa según la zona
        $mapImage = $this->getMapImageByZona($zona->nombre);
        $mapImageBase64 = $this->imageToBase64($mapImage);

        // Cálculo del total de precios
        $totalReservas = array_sum(array_column($detallesReservas, 'precio'));
        $totalPagado = $tasas;

        // Generar el PDF
        $pdf = PDF::loadView('pdf.reserva_qr_2', [
            'detallesReservas' => $detallesReservas,
            'qrCodeSvg' => $QrFinal, // Pasar SVG directamente
            'cliente' => $cliente,
            'mapImage' => $mapImageBase64,
            'totalReservas' => $totalReservas,
            'tasas' => $tasas,
            'totalPagado' => $totalPagado,
        ])->setPaper('a4', 'portrait');

        // Abrir el PDF en una nueva pestaña
        return $pdf->stream('reserva_cliente_' . $cliente->id . '.pdf');
    }

    function svgToBase64($svgContent) {
        $output = base64_encode($svgContent);
        return 'data:image/svg+xml;base64,' . $output;
    }

    private function imageToBase64($path)
    {
        if (file_exists(public_path($path))) {
            $imageData = file_get_contents(public_path($path));
            return base64_encode($imageData);
        }
        return null;
    }

    private function getMapImageByZona($zonaNombre)
    {
        // Normalizar el nombre de la zona: eliminar espacios y convertir todo a minúsculas
        $zonaNombre = trim(strtolower($zonaNombre));

        switch ($zonaNombre) {
            case '01- asunción (protocolo)':
            case 'plaza asunción (protocolo)':
                return '/images/zonas/asuncion.png';

            case '02.- consistorio':
            case 'consistorio ii':
            case 'consistorio i':
                return '/images/zonas/consistorio.png';

            case '03. arenal':
            case 'arenal ii':
            case 'arenal i':
            case 'arenal iii':
            case 'arenal iv':
            case 'arenal v':
            case 'arenal vi':
                return '/images/zonas/arenal.png';

            case '04.- lancería-gallo azul':
            case 'lancería-gallo azul':
            case 'lancería-gallo azul i':
                return '/images/zonas/lanceria.png';

            case '05.- algarve-plaza del banco':
            case 'algarve-plaza del banco':
                return '/images/zonas/larga.png';

            case '06.- rotonda de los casinos-santo domingo':
            case 'rotonda de los casinos-santo domingo':
            case 'rotonda de los casinos-santo domingo ii':
                return '/images/zonas/casinos.png';

            case '07.- marqués de casa domecq':
            case 'marqués de casa domecq ii':
            case 'marqués de casa domecq i':
                return '/images/zonas/santodomingo.png';

            case '08.- eguiluz':
            case 'eguiluz ii':
            case 'eguiluz i':
                return '/images/zonas/domecq.png';

            default:
                return '/images/zonas/default.png';
        }
    }

    //index
    public function index()
    {
        return view('reservas.index');
    }
    //index
    public function edit($id)
    {
            // Retornar la vista de edición y pasarle los datos necesarios
        return view('reservas.edit', compact('id'));
    }


    public function show($clienteId)
{
    // Buscar al cliente por su ID
    $cliente = Cliente::find($clienteId);

    // Si el cliente no existe, redirigir con error
    if (!$cliente) {
        return redirect()->back()->with('error', 'Cliente no encontrado.');
    }

    // Obtener todas las reservas del cliente
    $reservas = Reservas::where('id_cliente', $clienteId)->get();

    // Inicializar array para los detalles de las reservas
    $detallesReservas = [];

    // Recorrer las reservas para obtener los detalles de cada una
    foreach ($reservas as $reserva) {
        // Buscar la silla asociada a la reserva
        $silla = Sillas::find($reserva->id_silla);

        // Buscar la zona correspondiente a la silla
        $zona = Zonas::find($silla->id_zona);

        // Verificar si la silla pertenece a un palco o una grada
        $palco = null;
        $grada = null;

        if ($silla->id_palco) {
            $palco = Palcos::find($silla->id_palco);
            $zona = Sectores::find($palco->id_sector); // Asegurarse de obtener el sector del palco
        } elseif ($silla->id_grada) {
            $grada = Gradas::find($silla->id_grada);
            $zona = Zonas::find($grada->id_zona);
        }

        // Añadir los detalles de la reserva al array
        $detallesReservas[] = [
            'asiento' => $silla->numero ?? 'N/A',
            'sector' => $zona->nombre ?? 'N/A',
            'fecha' => $reserva->fecha,
            'año' => $reserva->año,
            'precio' => $reserva->precio,
            'fila' => $silla->fila ?? 'N/A',
            'order' => $reserva->order,
            'palco' => $palco ? $palco->numero : '',
            'grada' => $grada ? $grada->numero : '',
            'estado' => ucfirst($reserva->estado)
        ];
    }

    // Retornar la vista con los detalles de las reservas
    return view('reservas.show', compact('detallesReservas', 'cliente'));
}

    public function deleted($id){
        $reserva = Reservas::find($id);
        $reserva->delete();
        return response()->json("ok", 200);
    }




     public function duplicados()
    {
        $sillasDuplicadas = Reservas::select('id_silla')
        ->where('estado', 'pagada')  // Filtrar solo reservas pagadas
        ->groupBy('id_silla')
        ->having(DB::raw('COUNT(id_silla)'), '>', 1)
        ->pluck('id_silla');

        // Obtener todas las reservas asociadas a las sillas duplicadas
        $reservasConSillasDuplicadas = Reservas::select('reservas.*', 'clientes.nombre', 'clientes.apellidos', 'sillas.id_palco', 'sillas.id_grada')
        ->join('clientes', 'reservas.id_cliente', '=', 'clientes.id')
        ->join('sillas', 'reservas.id_silla', '=', 'sillas.id')
        ->whereIn('reservas.id_silla', $sillasDuplicadas)
        ->where('estado', 'pagada')
        ->orderBy('clientes.apellidos')  // Ordenar por apellido del cliente
        ->orderBy('sillas.id_palco')  // Luego ordenar por palco
        ->orderBy('sillas.id_grada')  // Luego ordenar por grada
        ->get();

        return [
            'reservas' =>  $reservasConSillasDuplicadas,
            'count' => count( value: $reservasConSillasDuplicadas),
        ];
    }

    public function getReservasDuplicadas()
    {
        $reservas = DB::table('reservas as r1')
            ->join('reservas as r2', function($join) {
                $join->on('r1.id_silla', '=', 'r2.id_silla')
                    ->where('r1.estado', '=', 'pagada')
                    ->where('r2.estado', '=', 'pagada');
            })
            ->whereColumn('r1.id_cliente', '<>', 'r2.id_cliente')
            ->whereColumn('r1.id_cliente', '<', 'r2.id_cliente')  // Evitar duplicados
            ->join('sillas', 'r1.id_silla', '=', 'sillas.id')
            ->join('clientes as c1', 'r1.id_cliente', '=', 'c1.id')
            ->join('clientes as c2', 'r2.id_cliente', '=', 'c2.id')
            ->leftJoin('palcos', 'sillas.id_palco', '=', 'palcos.id')
            ->leftJoin('gradas', 'sillas.id_grada', '=', 'gradas.id')
            ->join('zonas', 'sillas.id_zona', '=', 'zonas.id')
            ->groupBy('r1.id','r2.id', 'r1.id_silla', 'r1.id_cliente', 'r2.id_cliente', 'sillas.numero','sillas.fila', 'sillas.id', 'zonas.nombre', 'palcos.numero', 'gradas.numero', 'c1.nombre', 'c1.apellidos', 'c2.nombre', 'c2.apellidos')
            ->orderBy('r1.id_silla', 'asc')
            ->select(
                'r1.id_silla',
                'r1.id as id1',
                'r2.id as id2',
                'sillas.numero as silla_numero',
                'sillas.id as silla_id',
                'sillas.fila as fila',
                'c1.nombre as cliente_1_nombre',
                'c1.apellidos as cliente_1_apellidos',
                'c2.nombre as cliente_2_nombre',
                'c2.apellidos as cliente_2_apellidos',
                'zonas.nombre as zona_nombre',
                'palcos.numero as palco_numero',
                'gradas.numero as grada_numero'
            )
            ->get();

        $reservasArray = [];

        foreach ($reservas as $reserva) {
            $reservasArray[] = [
                'silla_id' => $reserva->silla_id,
                'silla_numero' => $reserva->silla_numero,
                'fila' => $reserva->fila,
                'palco' => $reserva->palco_numero,
                'grada' => $reserva->grada_numero,
                'zona' => $reserva->zona_nombre,
                'Reserva 1' => $reserva->id1,
                'cliente_1' => $reserva->cliente_1_nombre . ' ' . $reserva->cliente_1_apellidos,
                'Reserva 2' => $reserva->id2,
                'cliente_2' => $reserva->cliente_2_nombre . ' ' . $reserva->cliente_2_apellidos,
            ];
        }
        //return Excel::download(new ReservasDuplicadasExport($reservasArray), 'reservas_duplicadas.xlsx');
        dd($reservasArray);
    }




    }
