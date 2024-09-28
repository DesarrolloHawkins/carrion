@component('mail::message')
## Estimado **{{ $cliente->nombre }}**, hemos confirmado su pago.

# Confirmación de Reserva

### No olvide conserva este correo, para poder acceder al palco. Este es su confirmacion de los asientos reservado, debera presentar este justificante con el QR el proximo dia 12 de Octubre.

Gracias por su pago. Aquí están los detalles de su reserva con código {{ $detallesReservas[0]['order'] }}

@component('mail::table')
| Asiento       | Fila       | Sector        | Posición        | Fecha       | Año       | Precio       |
| ------------- |:-------------:|:-------------:| -----------:| -----------:|:---------:|:---------:|
@foreach($detallesReservas as $detalle)
| {{ $detalle['asiento'] }} | {{ $detalle['fila'] }} | {{ $detalle['sector'] }} | {{ $detalle['palco'] ? 'Palco '.$detalle['palco'] : 'Grada '.$detalle['grada'] }} | {{ $detalle['fecha'] }} | {{ $detalle['año'] }} | {{ $detalle['precio'] }}€ |
@endforeach
@endcomponent

@php
    $totalPrecio = $tasas - array_sum(array_column($detallesReservas, 'precio'));

@endphp

Total de las sillas reservadas: **{{ array_sum(array_column($detallesReservas, 'precio')) }}€**

Pago de tasas: **{{ $totalPrecio }}€**

El total pagado es: **{{ $tasas}}€**

<h3 style="margin-top:20px;">Escanee el siguiente código QR para acceder a sus reservas:</h3>
<img src="data:image/png;base64,{{ $qrCodeBase64 }}" alt="QR Code">
<h3 style="margin-top: 20px;">Mapa de la Zona:</h3>
<img style="margin-top: 20px;" src="data:image/png;base64,{{ $mapImage }}" alt="Mapa de la Zona">

Gracias por confiar en nosotros.

Saludos cordiales,<br>
Unión de Hermandades de Jerez

@slot('footer')
@component('mail::footer')
Si tienes alguna duda, contáctanos en tesoreria@uniondehermandades.com
@endcomponent
@endslot
@endcomponent
