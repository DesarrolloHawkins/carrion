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

        try {
            // Crear sesión de pago
            $checkout_session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Product name',
                        ],
                        'unit_amount' => 2000, // Precio en centavos (20 USD)
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => env('APP_URL') . '/success?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => env('APP_URL') . '/cancel',
            ]);

            return response()->json(['id' => $checkout_session->id]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
