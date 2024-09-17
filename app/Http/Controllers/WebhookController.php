<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Event;
use Stripe\Webhook;
use Log;



class WebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
            Log::info('Webhook recibido: ' . $request->getContent());
        // Obtén el cuerpo de la petición
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');
        
        $event = null;
        Log::info('Webhook recibido: ' . $payload);
        // Verifica la firma del webhook para asegurar que la petición proviene de Stripe
        try {
            $event = Webhook::constructEvent(
                $payload, $signature, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Payload inválido
            Log::info('Payload inválido');
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Firma inválida
            Log::info('Firma inválida');
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Manejar el evento de Stripe
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object; // contiene un \Stripe\PaymentIntent
                $this->handlePaymentIntentSucceeded($paymentIntent);
                break;

            case 'payment_method.attached':
                $paymentMethod = $event->data->object; // contiene un \Stripe\PaymentMethod
                $this->handlePaymentMethodAttached($paymentMethod);
                break;

            default:
                Log::info('Webhook recibido con un tipo de evento desconocido: ' . $event->type);
                return response()->json(['error' => 'Unknown event type'], 400);
        }

        // Respuesta exitosa
        return response()->json(['status' => 'success'], 200);
    }

    protected function handlePaymentIntentSucceeded($paymentIntent)
    {
        // Lógica para un pago exitoso
        Log::info('Pago exitoso para PaymentIntent ID: ' . $paymentIntent->id);
    }

    protected function handlePaymentMethodAttached($paymentMethod)
    {
        // Lógica para método de pago adjunto
        Log::info('Método de pago adjunto ID: ' . $paymentMethod->id);
    }
}
