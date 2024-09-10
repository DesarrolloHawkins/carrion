<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GlobalPayments\Api\ServiceConfigs\Gateways\GpEcomConfig;
use GlobalPayments\Api\ServicesContainer;
use GlobalPayments\Api\Entities\Exceptions\ApiException;
use GlobalPayments\Api\PaymentMethods\CreditCardData;

use App\Models\Reservas;
use App\Models\Cliente;
use App\Mail\ReservaPagada;
use App\Models\Sillas;

use Illuminate\Support\Facades\Mail;


class PayController extends Controller
{
    protected $globalPayService;
   

    public function __construct()
    {
        $config = new GpEcomConfig();
        $config->merchantId = env('MERCHANT_ID');
        $config->accountId = env('ACCOUNT');
        $config->sharedSecret = env('SHARED_SECRET');
        $config->serviceUrl = env('ENVIRONMENT');
        
        ServicesContainer::configureService($config);
    }

    /**
     * Procesa un pago desde el frontend
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function processPayment(Request $request)
    {
        try {
            // Validar los datos del request
            $request->validate([
                'card_number'   => 'required|min:16|max:16',
                'expiry_month'  => 'required|numeric|min:1|max:12',
                'expiry_year'   => 'required|numeric|min:' . date('Y') . '|max:' . (date('Y') + 10),
                'cvv'           => 'required|min:3|max:4',
                'card_name'     => 'required',
                'amount'        => 'required|numeric|min:1',
                'orderId'       => 'required',
            ]);
    
            // Crear el objeto de tarjeta
            $card = new CreditCardData();
            $card->number = $request->input('card_number');
            $card->expMonth = $request->input('expiry_month');
            $card->expYear = $request->input('expiry_year');
            $card->cvn = $request->input('cvv');
            $card->cardHolderName = $request->input('card_name');
    
            $amount = $request->input('amount');
            $orderId = $request->input('orderId');
    
            // Obtener las reservas asociadas al pedido
            $reservas = Reservas::where('order', $orderId)->get();
    
            if (count($reservas) === 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No reservations found for this order',
                ]);
            }
    
            $clienteId = $reservas[0]->id_cliente;
            $cliente = Cliente::find($clienteId);
    
            // Comprobar si las sillas ya están reservadas por otros
            foreach ($reservas as $reserva) {
                $silla = $reserva->id_silla;
                $reservasConflicto = Reservas::where('id_silla', $silla)
                                             ->where('estado', '!=', 'cancelada')
                                             ->where('order', '!=', $orderId)
                                             ->get();
                if (count($reservasConflicto) > 0) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Some seats are already reserved by another user',
                    ]);
                }
            }
    
            // Procesar el pago
            try {
                $response = $card->charge($amount)
                                ->withCurrency("EUR")
                                ->execute();
            } catch (ApiException $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Payment failed',
                    'error' => $e->getMessage(),
                ]);
            }
    
            // Si el pago es exitoso
            if ($response->responseCode === '00') {
                $orderId = $response->orderId;
                foreach ($reservas as $reserva) {
                    $reserva->estado = 'pagada';
                    $reserva->order = $orderId;
                    $reserva->save();
                }
    
                // Enviar correo de confirmación
                try {
                    $sillas = [];
                    foreach ($reservas as $reserva) {
                        $silla = Sillas::find($reserva->id_silla);
                        array_push($sillas, $silla);
                    }
    
                    Mail::to($cliente->email)->send(new ReservaPagada($reservas, $sillas, $cliente));
    
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Payment processed but email not sent',
                        'error' => $e->getMessage(),
                    ]);
                }
    
                return response()->json([
                    'status' => 'success',
                    'message' => 'Payment processed successfully',
                    'orderId' => $orderId,
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Payment failed',
                ]);
            }
    
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment failed',
                'error' => $e->getMessage(),
            ]);
        }
    }
    
}
