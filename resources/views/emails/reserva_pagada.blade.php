@component('mail::message')
# Confirmación de Reserva

### Estimado **{{ $cliente->nombre }}**,

Gracias por su pago. Aquí están los detalles de su reserva con código {{$detallesReservas[0]['order']}}

@component('mail::table')
| Asiento       | Fila       | Sector        | Fecha       | Año       | Precio       |
| ------------- |:-------------:|:-------------:| -----------:|:---------:|:---------:|
@foreach($detallesReservas as $detalle)
| {{ $detalle['asiento'] }} | {{ $detalle['fila'] }} | {{ $detalle['sector'] }} | {{ $detalle['fecha'] }} | {{ $detalle['año'] }} | {{ $detalle['precio'] }}€ |
@endforeach
@endcomponent

El total pagado es: **{{ array_sum(array_column($detallesReservas, 'precio')) }}€**

<!-- @component('mail::button', ['url' => 'http://example.com/mis-reservas'])
Ver mis reservas
@endcomponent -->

Gracias por confiar en nosotros.

Saludos cordiales,<br>
Unión de Hermandades de Jerez

@slot('footer')
@component('mail::footer')
Si tienes alguna duda, contáctanos en soporte@example.com
@endcomponent
@endslot
@endcomponent
