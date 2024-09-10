<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas de {{ $cliente->nombre }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            padding: 10px;
        }
        h1 {
            color: #2c3e50;
            text-align: center;
            margin: 20px 0;
        }
        .card {
            background-color: #fff;
            border-radius: 10px;
            margin: 15px auto;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            width: 90%;
            max-width: 400px;
            
        }
        .card-header {
            font-size: 20px;
            font-weight: bold;
            color: #34495e;
            text-align: center;
            margin-bottom: 10px;
        }
        .card-body {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 10px;
        }
        .card-item {
            margin-bottom: 15px;
            width: 100%;
            display: flex;
            justify-content: space-between;
            padding: 10px;
            background-color: #f7f7f7;
            border-radius: 8px;
        }
        .card-item strong {
            color: #2c3e50;
            font-weight: 600;
        }
        .card-item span {
            color: #34495e;
            font-size: 14px;
            font-weight: 500;
        }
        .price {
            font-size: 18px;
            color: #e74c3c;
            font-weight: bold;
        }
        .status {
            padding: 5px 10px;
            background-color: #2ecc71;
            color: #fff;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        @media (min-width: 768px) {
            .card-item {
                width: 48%;
            }
            .card {
                max-width: 700px;
            }
        }
    </style>
</head>
<body>
    <h1>Reservas de {{ $cliente->nombre }} {{ $cliente->apellidos }}</h1>

    @if(empty($detallesReservas))
        <p style="text-align: center;">No tienes reservas en este momento.</p>
    @else
        <p style="text-align: center;">Tienes un total de <strong>{{ count($detallesReservas) }}</strong> reserva(s).</p>
        @foreach($detallesReservas as $index => $detalle)
        <div class="card">
            <div class="card-header">
                Reserva {{ $index + 1 }}
            </div>
            <div class="card-body">
                <div class="card-item">
                    <strong>Asiento:</strong>
                    <span>{{ $detalle['asiento'] }}</span>
                </div>
                <div class="card-item">
                    <strong>Fila:</strong>
                    <span>{{ $detalle['fila'] }}</span>
                </div>
                <div class="card-item">
                    <strong>Sector:</strong>
                    <span>{{ $detalle['sector'] }}</span>
                </div>
                <div class="card-item">
                    <strong>Posición:</strong>
                    <span>{{ $detalle['palco'] ? 'Palco ' . $detalle['palco'] : 'Grada ' . $detalle['grada'] }}</span>
                </div>
                
                <div class="card-item">
                    <strong>Estado:</strong>
                    <span class="status">{{ $detalle['estado'] }}</span>
                </div>
            </div>
        </div>
        @endforeach
    @endif
</body>
</html>
