@component('mail::message')
# Pago RECHAZADO
   <h1>Magna de Jerez 2024</h1>
    <p>Sus reservas han sido canceladas por el proceso de pago, si le han cobrado, se le devolverá el dinero a su cuenta.</p>
    <h4>Intente hacer la reserva en cuanto la plataforma permita los pagos</h4>
    <p>Gracias por confiar en nosotros. Perdona las molestias</p>
@endcomponent

@slot('footer')
@component('mail::footer')
Si tienes alguna duda, contáctanos en secretaria@uniondehermandades.com
@endcomponent
@endslot
