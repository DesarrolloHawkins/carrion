<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Customer;
use Stripe\EphemeralKey;
use Stripe\PaymentIntent;

use App\Models\Reservas;
use App\Models\Cliente;
use App\Models\Sillas;
use App\Models\Palcos;
use App\Mail\ReservaPagada;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;






class StripePaymentController extends Controller
{
    public function createCheckoutSession(Request $request)
    {



        // Configura la clave secreta de Stripe
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $orderId = $request->input('orderId'); // Obtener el orderId desde el frontend

        if($request->input('email') == null  ){
            return response()->json(['error' => 'El email es requerido'], 400);
        }

        $cliente = Cliente::where('email', $request->input('email'))->first();

        if(!$cliente){
            return response()->json(['error' => 'El cliente no existe'], 400);
        }

        


        $reservas = Reservas::where('id_cliente', $cliente->id)->where('estado', 'reservada')->get();
        $sillas = [];

        foreach ($reservas as $reserva) {
            $silla = Sillas::find($reserva->id_silla);
            array_push($sillas, $silla->id);
        }

        $totalPrecio = $this->calcularPrecioMultiple($sillas);
        $totalPrecio = $totalPrecio * 100; // Convertir a centavos
        
        $amount = $request->input('amount'); // Obtener el monto desde el frontend


        if ($totalPrecio != $amount) {
            return response()->json(['error' => 'El monto no coincide con el total de la orden' , 'monto' => $amount , 'totalPrecio' => $totalPrecio , 'reservas' => $reservas , 'orderId' => $orderId ], 400);
            foreach ($reservas as $reserva) {
                $reserva->estado = 'cancelada';
                $reserva->order = null;
                $reserva->save();
            }
        }

        

        //calcular tasa de impuesto
        $montoObjetivo = $totalPrecio/100;  // Monto objetivo que deseas recibir
        $tarifaFija = 0.30;      // Tarifa fija de Stripe (en dólares)
        $tarifaPorcentaje = 0.029; // Tarifa porcentual de Stripe (2.9% es 0.029)

        $montoCobrar = $this->calcularMontoCobrar($montoObjetivo, $tarifaFija, $tarifaPorcentaje);
        $montoCobrar = $montoCobrar * 100; // Convertir a centavos

        try {
            // Crear un cliente de Stripe
            $customer = Customer::create([
                'email' => $request->input('email'), // Asegúrate de pasar el email desde el frontend
            ]);

            // Crear una clave efímera (Ephemeral Key) para el cliente
            $ephemeralKey = EphemeralKey::create(
                ['customer' => $customer->id],
                ['stripe_version' => '2024-06-20'] // Asegúrate de utilizar la versión de la API correcta
            );

            // Crear un PaymentIntent
            $paymentIntent = PaymentIntent::create([
                'amount' => $montoCobrar, // Cantidad en centavos
                'currency' => 'eur',
                'customer' => $customer->id,
                'automatic_payment_methods' => ['enabled' => true],
            ]);

            foreach ($reservas as $reserva) {
                $reserva->procesando = true;
                $reserva->order = $paymentIntent->id;
                $reserva->save();
            }

            // Devolver el cliente, la clave efímera y el PaymentIntent
            return response()->json([
                'paymentIntent' => $paymentIntent,
                'clientSecret' => $paymentIntent->client_secret,
                'ephemeralKey' => $ephemeralKey->secret,
                'customer' => $customer->id
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

   public function calcularMontoCobrar($montoObjetivo, $tarifaFija, $tarifaPorcentaje) {
        // Fórmula: Pcarga = (Pobjetivo + Ffijo) / (1 - Fporcentaje)
        $montoCobrar = ($montoObjetivo + $tarifaFija) / (1 - $tarifaPorcentaje);
        return round($montoCobrar, 2); // Redondeamos a 2 decimales
    }

    public function calcularPrecioMultiple($sillaIds)
    {
        $totalPrecio = 0;
        foreach ($sillaIds as $sillaId) {
            $silla = Sillas::findOrFail($sillaId);
            $totalPrecio += $this->calcularPrecio($silla);
        }
        return $totalPrecio;
    }

    public function calcularPrecio($silla)
    {
        $numeroFila = intval(substr($silla->fila, 1));

        if($silla->id_grada){

            $precio = \DB::table('precios_sillas')
            ->where('tipo_asiento', 'grada')
            ->where(function ($query) use ($numeroFila) {
                $query->where('fila_inicio', '<=', $numeroFila)
                    ->where(function ($query) use ($numeroFila) {
                        $query->where('fila_fin', '>=', $numeroFila)
                            ->orWhereNull('fila_fin');
                    });
            })
            ->value('precio');

            return $precio ?: 12;
        }else{
            $palcoIds = [
                16, 17, 18, 19, 20,21,22,23,24,25,26,27,28,122,121,120,119,118,117,116,115,114,113,112,111,110,109,108,220,221,222,223,224,225,226,227,228,229,230,231,232,233,234,
                534, 533, 532, 531, 530, 529, 528, 527, 526, 525, 524, 523, 522, 521, 520, 519, 518, 517, 516, 515, 514, 513, 512, 511, 510, 509, 508, 507, 506, 505, 504, 503, 502, 501, 500, 499, 498, 497, 496, 495, 494, 493,
                
            ];
    
            $palco = Palcos::find($silla->id_palco);
    
            if ($palco) {
                if (in_array($palco->numero, $palcoIds)) {
                    return 18;
                } else {
                    return 20;
                }
            }
        }

           
    }


    public function registrarPago(Request $request)
    {


        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $paymentIntentId = $request->input('paymentIntent');
            $orderId = $request->input('orderId');
            
            //comprobar las reservas pagadas por 

            // Consultar el PaymentIntent para verificar el estado del pago
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

            //cancelar pago
            //$paymentIntent->cancel();

            
            if ($paymentIntent->status == 'succeeded') {
                // Actualizar el estado de la orden a 'pagada'
                // $order = Order::findOrFail($orderId);
                // $order->status = 'pagada';
                // $order->save();
                $reservas = Reservas::where('order', $paymentIntentId)->get();
                $cliente = Cliente::find($reservas[0]->id_cliente);
                foreach ($reservas as $reserva) {
                    $reserva->estado = 'pagada';
                    $reserva->procesando = false;
                    $reserva->save();
                }
                try {
                    $sillas = [];
                    foreach ($reservas as $reserva) {
                        $silla = Sillas::find($reserva->id_silla);
                        array_push($sillas, $silla);
                    }
                    Log::info('Procesando envío de correo para el cliente: ' . $cliente->email);

                    Mail::to($cliente->email)->send(new ReservaPagada($reservas, $sillas, $cliente));
                    Log::info('Correo enviado correctamente a ' . $cliente->email);

                } catch (\Exception $e) {
                    Log::error('Error al enviar el correo: ' . $e->getMessage());

                    return response()->json([
                        'status' => 'error',
                        'message' => 'Payment processed but email not sent',
                        'error' => $e->getMessage() . ' ' . $e->getTraceAsString(),
                    ]);
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'Payment processed successfully',
                    'orderId' => $paymentIntentId,
                ]);
                //return response()->json(['message' => 'Pago registrado con éxito'], 200);
            } else {
                $reservas = Reservas::where('order', $paymentIntentId)->get();
                foreach ($reservas as $reserva) {
                    $reserva->estado = 'cancelada';
                    $reserva->procesando = false;
                    $reserva->save();
                }
                return response()->json(['error' => 'El pago no fue exitoso'], 400);
                
            
            }
        } catch (\Exception $e) {
            $reservas = Reservas::where('order', $paymentIntentId)->get();
            foreach ($reservas as $reserva) {
                $reserva->estado = 'cancelada';
                $reserva->procesando = false;
                $reserva->save();
            }
            return response()->json(['error' => $e->getMessage()], 500);
            

        }
    }
}
