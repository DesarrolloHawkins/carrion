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
    <h2 style="margin:0">Magna de Jerez 2024</h2>
    <p>Aquí tiene sus reservas disponibles para la Magna de Jerez 2024. Gracias por confiar en nosotros.</p>

    <!-- Información del cliente -->
    <h3>Datos del Cliente</h3>
    <table >
        <tr>
            <td style="font-size:12px"><strong>Nombre:</strong></td>
            <td style="font-size:12px">{{ $cliente->nombre }}</td>
        </tr>
        <tr>
            <td style="font-size:12px"><strong>Apellidos:</strong></td>
            <td style="font-size:12px">{{ $cliente->apellidos }}</td>
        </tr>
        <tr>
            <td style="font-size:12px"><strong>DNI:</strong></td>
            <td style="font-size:12px">{{ $cliente->dni }}</td>
        </tr>
        <tr>
            <td style="font-size:12px"><strong>Teléfono:</strong></td>
            <td style="font-size:12px">{{ $cliente->telefono }}</td>
        </tr>
        <tr>
            <td style="font-size:12px"><strong>Email:</strong></td>
            <td style="font-size:12px">{{ $cliente->email }}</td>
        </tr>
    </table>

    <!-- Información de las reservas -->
    <table style="font-size:12px">
        <thead>
            <tr>
                <th>Asiento</th>
                <th>Fila</th>
                <th>Sector</th>
                <th>Posición</th>
                <th>Fecha</th>
                <th>Año</th>
                <th>Precio</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detallesReservas as $detalle)
            <tr>
                <td>{{ $detalle['asiento'] }}</td>
                <td>{{ $detalle['fila'] }}</td>
                <td>{{ $detalle['sector'] }}</td>
                <td>{{ $detalle['palco'] ? 'Palco '.$detalle['palco'] : 'Grada '.$detalle['grada'] }}</td>
                <td>{{ $detalle['fecha'] }}</td>
                <td>{{ $detalle['año'] }}</td>
                <td>{{ $detalle['precio'] }}€</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Total de las sillas reservadas:</strong> {{ $totalReservas }}€</p>

    <div class="qr-code">
        {!! $qrCodeSvg !!} <!-- Inserta el SVG directamente -->
    </div>

    <h3 style="margin:0 0 8px 0;">Mapa de la Zona:</h3>
    <img src="data:image/png;base64,{{ $mapImage }}" alt="Mapa de la Zona">
    
</body>
</html>
