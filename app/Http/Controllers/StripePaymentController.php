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
}
