<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Customer;
use Stripe\EphemeralKey;
use Stripe\PaymentIntent;


class StripePaymentController extends Controller
{
    public function createCheckoutSession(Request $request)
    {
        // Configura la clave secreta de Stripe
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $orderId = $request->input('orderId'); // Obtener el orderId desde el frontend
        $amount = $request->input('amount'); // Obtener el monto desde el frontend
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
                'amount' => $request->input('amount'), // Cantidad en centavos
                'currency' => 'eur',
                'customer' => $customer->id,
                'automatic_payment_methods' => ['enabled' => true],
            ]);

            // Devolver el cliente, la clave efímera y el PaymentIntent
            return response()->json([
                'paymentIntent' => $paymentIntent->client_secret,
                'ephemeralKey' => $ephemeralKey->secret,
                'customer' => $customer->id
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function registrarPago(Request $request)
    {
        try {
            $paymentIntentId = $request->input('paymentIntent');
            $orderId = $request->input('orderId');
            
            // Consultar el PaymentIntent para verificar el estado del pago
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

            if ($paymentIntent->status == 'succeeded') {
                // Actualizar el estado de la orden a 'pagada'
                // $order = Order::findOrFail($orderId);
                // $order->status = 'pagada';
                // $order->save();
                $reservas = Reservas::where('order', $orderId)->get();
                $cliente = Clientes::find($reservas[0]->id_cliente);
                foreach ($reservas as $reserva) {
                    $reserva->estado = 'pagada';
                    $reserva->order = $paymentIntentId;
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
                return response()->json(['error' => 'El pago no fue exitoso'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
