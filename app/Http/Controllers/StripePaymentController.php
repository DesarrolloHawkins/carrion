<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;



class StripePaymentController extends Controller
{
    public function createCheckoutSession(Request $request)
    {
        // Configura la clave secreta de Stripe
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $orderId = $request->input('orderId'); // Obtener el orderId desde el frontend
        $amount = $request->input('amount'); // Obtener el monto desde el frontend
        try {
            // Crear sesión de pago
            $checkout_session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Reserva de silla',
                        ],
                        'unit_amount' => $amount, // Precio en centavos (20 USD)
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => 'myapp://resumen-compra?orderId=' . $orderId . '&session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => 'myapp://cancel?orderId=' . $orderId,
            ]);

            return response()->json(['id' => $checkout_session->id]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
