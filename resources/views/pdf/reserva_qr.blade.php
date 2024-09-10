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

    <table>
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
        <div class="margen">

        </div>
    <div class="qr-code">
        <img src="data:image/png;base64,{{ $qrCodeBase64 }}" alt="QR Code">
    </div>
    
</body>
</html>
