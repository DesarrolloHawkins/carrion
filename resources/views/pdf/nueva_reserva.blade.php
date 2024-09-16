<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detalles de la Reserva</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }
        h1, h2 {
            text-align: center;
        }
        .reserva-details {
            margin-bottom: 20px;
        }
        .reserva-details th, .reserva-details td {
            padding: 5px;
            border-bottom: 1px solid #ddd;
        }
        .qr-code, .map-image {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Detalles de la Reserva</h1>
    
    <table class="reserva-details" width="100%">
        <thead>
            <tr>
                <th>Asiento</th>
                <th>Sector</th>
                <th>Fila</th>
                <th>Precio (€)</th>
                <th>Fecha</th>
                <th>Año</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detallesReservas as $reserva)
                <tr>
                    <td>{{ $reserva['asiento'] }}</td>
                    <td>{{ $reserva['sector'] }}</td>
                    <td>{{ $reserva['fila'] }}</td>
                    <td>{{ $reserva['precio'] }}</td>
                    <td>{{ $reserva['fecha'] }}</td>
                    <td>{{ $reserva['año'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <h2>Cliente: {{ $cliente->nombre }}</h2>

   
</body>
</html>
