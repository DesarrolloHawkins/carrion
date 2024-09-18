<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reservas Magna Jerez 2024</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            text-align: center;
        }
        h1 {
            color: #2c3e50;
        }
        p {
            font-size: 14px;
            color: #34495e;
        }
        .qr-code {
            margin-top: 30px;
            margin: 0 auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        .margen{
            margin: 100px;
        }
    </style>
</head>
<body>
    <h1>Magna de Jerez 2024</h1>
    <p>Aquí tiene sus reservas disponibles para la Magna de Jerez 2024. Gracias por confiar en nosotros.</p>

    @foreach($detallesReservas as $zona => $reservas) <!-- Agrupar por zona -->
    <h2>Zona: {{ $zona }}</h2>
    <table>
        <thead>
            <tr>
                <th>Asiento</th>
                <th>Fila</th>
                <th>Sector</th>
                <th>Posición</th>
                <th>Fecha</th>
                <th>Año</th>
                {{-- <th>Precio</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach($reservas as $detalle)
            <tr>
                <td>{{ $detalle['asiento'] }}</td>
                <td>{{ $detalle['fila'] }}</td>
                <td>{{ $detalle['sector'] }}</td>
                <td>
                    @if($detalle['palco'])
                        Palco {{ $detalle['palco'] }}
                    @elseif($detalle['grada'])
                        Grada {{ $detalle['grada'] }}
                    @else
                        No asignado
                    @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($detalle['fecha'])->format('d-m-Y') }}</td>
                <td>{{ $detalle['año'] }}</td>
                {{-- <td>{{ number_format($detalle['precio'], 2) }}€</td> --}}
            </tr>
            @endforeach
        </tbody>
    </table>
    @endforeach

    <div class="margen"></div>
</body>
</html>
