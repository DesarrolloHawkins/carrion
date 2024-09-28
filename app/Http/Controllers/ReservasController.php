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
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReservasExport;


class ReservasController extends Controller
{
    public function export(Request $request)
    {
        return Excel::download(new ReservasExport($request->all()), 'reservas.xlsx');
    }
    public function index(Request $request)
{
    $palcos = Palcos::with('zonas')->get();
    $gradas = Gradas::with('zonas')->get();

    $sortColumn = $request->input('sortColumn', 'nombre');  // Columna por defecto
    $sortDirection = $request->input('sortDirection', 'asc'); // Dirección por defecto
    $filtro = $request->query('filtro', '');
    $estado = $request->query('estado', 'pagada');
    $grada = $request->query('grada', '');
    $palco = $request->query('palco', '');
    $perPage = $request->query('perPage', 10);
    $order_id = $request->query('order_id', ''); // Nuevo filtro de order_id

    $reservas = Reservas::with(['clientes', 'sillas'])
        ->when($filtro, function ($query, $filtro) {
            return $query->whereHas('clientes', function ($query) use ($filtro) {
                $query->where('nombre', 'like', "%{$filtro}%")
                    ->orWhere('apellidos', 'like', "%{$filtro}%")
                    ->orWhere('DNI', 'like', "%{$filtro}%")
                    ->orWhere('movil', 'like', "%{$filtro}%");
            })->orWhereHas('sillas', function ($query) use ($filtro) {
                $query->where('fila', 'like', "%{$filtro}%")
                      ->orWhereHas('zona', function ($query) use ($filtro) {
                          $query->where('nombre', 'like', "%{$filtro}%");
                      });
            });
        })
        ->when($estado, function ($query, $estado) {
            return $query->where('estado', $estado);
        })
        ->when($grada, function ($query, $grada) {
            return $query->whereHas('sillas.grada', function ($query) use ($grada) {
                $query->where('id', $grada);
            });
        })
        ->when($palco, function ($query, $palco) {
            return $query->whereHas('sillas.palco', function ($query) use ($palco) {
                $query->where('id', $palco);
            });
        })
        ->when($order_id, function ($query, $order_id) {
            return $query->where('order_id', 'like', "%{$order_id}%");
        })
        ->join('clientes', 'reservas.id_cliente', '=', 'clientes.id')
        ->join('sillas', 'reservas.id_silla', '=', 'sillas.id')
        ->leftJoin('gradas', 'sillas.id_grada',  '=', 'gradas.id')
        ->leftJoin('palcos', 'sillas.id_palco', '=', 'palcos.id')
        ->join( 'zonas',  'sillas.id_zona', '=', 'zonas.id')
        ->select('reservas.*',
                'clientes.nombre as nombre',
                'clientes.apellidos as apellidos',
                'clientes.DNI as DNI',
                'clientes.movil as movil',
                'sillas.fila as fila',
                'sillas.numero as asiento',
                'zonas.nombre as zona',
                'palcos.numero as palco',
                'gradas.numero as grada')
        ->orderBy($sortColumn, $sortDirection)
        ->paginate($perPage);

    return view('reservas.index', compact('reservas', 'filtro', 'estado', 'perPage', 'sortColumn', 'sortDirection', 'gradas', 'palcos', 'grada', 'palco', 'order_id'));
}



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
    // public function index()
    // {
    //     return view('reservas.index');
    // }
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
    public function cancelar($id){
        $reserva = Reservas::find($id);

        $reserva->estado = 'cancelada';
        $reserva->save();
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
                ->where('r2.estado', '=', 'pagada')
                ->whereNull('r1.deleted_at') // Asegurar que r1 no está eliminada
                ->whereNull('r2.deleted_at'); // Asegurar que r2 no está eliminada
        })
        ->whereColumn('r1.id_cliente', '<>', 'r2.id_cliente')
        ->whereColumn('r1.id_cliente', '<', 'r2.id_cliente')  // Evitar duplicados
        ->join('sillas', 'r1.id_silla', '=', 'sillas.id')
        ->join('clientes as c1', 'r1.id_cliente', '=', 'c1.id')
        ->join('clientes as c2', 'r2.id_cliente', '=', 'c2.id')
        ->leftJoin('palcos', 'sillas.id_palco', '=', 'palcos.id')
        ->leftJoin('gradas', 'sillas.id_grada', '=', 'gradas.id')
        ->join('zonas', 'sillas.id_zona', '=', 'zonas.id')
        ->groupBy('r1.id','r2.id','r1.created_at','r2.created_at', 'r1.id_silla', 'r1.id_cliente', 'r2.id_cliente', 'sillas.numero','sillas.fila', 'sillas.id', 'zonas.nombre', 'palcos.numero', 'gradas.numero', 'c1.nombre', 'c1.apellidos', 'c2.nombre', 'c2.apellidos')
        ->orderBy('r1.id_silla', 'asc')
        ->select(
            'r1.id_silla',
            'r1.id as id1',
            'r2.id as id2',
            'r1.created_at as fecha1',
            'r2.created_at as fecha2',
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
            'Creacion 1' => $reserva->fecha1,
            'cliente_1' => $reserva->cliente_1_nombre . ' ' . $reserva->cliente_1_apellidos,
            'Reserva 2' => $reserva->id2,
            'Creacion 2' => $reserva->fecha2,
            'cliente_2' => $reserva->cliente_2_nombre . ' ' . $reserva->cliente_2_apellidos,
        ];
    }
    return $reservasArray;
   // dd($reservasArray);
}


public function reservasConClientesBorrados()
{
    // Obtener todas las reservas asociadas a clientes eliminados (soft deleted)
    $reservasConClientesBorrados = Reservas::select('reservas.*', 'clientes.nombre', 'clientes.apellidos', 'sillas.id_palco', 'sillas.id_grada')
        ->where('reservas.estado', 'pagada')
        ->join('clientes', 'reservas.id_cliente', '=', 'clientes.id')
        ->join('sillas', 'reservas.id_silla', '=', 'sillas.id')
        ->whereNotNull('clientes.deleted_at')  // Filtrar clientes que hayan sido eliminados (soft delete)
        ->orderBy('clientes.apellidos')  // Ordenar por apellido del cliente
        ->orderBy('sillas.id_palco')  // Luego ordenar por palco
        ->orderBy('sillas.id_grada')  // Luego ordenar por grada
        ->get();

    return [
        'reservas' =>  $reservasConClientesBorrados,
        'count' => count($reservasConClientesBorrados),
    ];
}

public function clientesConMuchasReservas()
{
    // Clientes abonados con tipo_abonado 'palco' y más de 8 reservas pagadas
    $clientesConPalco = Cliente::select('clientes.DNI', 'clientes.nombre', 'clientes.apellidos', DB::raw('COUNT(reservas.id) as total_reservas'))
        ->join('reservas', 'clientes.id', '=', 'reservas.id_cliente')
        ->where('clientes.abonado', true)
        ->where('clientes.tipo_abonado', 'palco')
        ->where('reservas.estado', 'pagada')  // Filtrar solo las reservas pagadas
        ->whereNull('reservas.deleted_at')
        ->groupBy('clientes.id', 'clientes.DNI', 'clientes.nombre', 'clientes.apellidos')
        ->havingRaw('COUNT(reservas.id) > ?', [8])  // Clientes con más de 8 reservas
        ->get();

    // Clientes abonados con tipo_abonado 'silla' y más de 4 reservas pagadas
    $clientesConSilla = Cliente::select('clientes.DNI', 'clientes.nombre', 'clientes.apellidos', DB::raw('COUNT(reservas.id) as total_reservas'))
        ->join('reservas', 'clientes.id', '=', 'reservas.id_cliente')
        ->where('clientes.abonado', true)
        ->where('clientes.tipo_abonado', 'silla')
        ->where('reservas.estado', 'pagada')  // Filtrar solo las reservas pagadas
        ->whereNull('reservas.deleted_at')
        ->groupBy('clientes.id', 'clientes.DNI', 'clientes.nombre', 'clientes.apellidos')
        ->havingRaw('COUNT(reservas.id) > ?', [4])  // Clientes con más de 4 reservas
        ->get();

    return [
        'clientesConPalco' => $clientesConPalco,
        'clientesConSilla' => $clientesConSilla
    ];
}

}
