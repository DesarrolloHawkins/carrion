<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GlobalPayments\Api\ServiceConfigs\Gateways\GpEcomConfig;
use GlobalPayments\Api\ServicesContainer;
use GlobalPayments\Api\Entities\Exceptions\ApiException;
use GlobalPayments\Api\PaymentMethods\CreditCardData;
use Illuminate\Support\Facades\Log;

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

            $precioTotal = 0;
           
            foreach ($reservas as $reserva) {
               $clienteId = $reserva->id_cliente;
               $cliente = Cliente::find($clienteId);

               $silla = Sillas::find($reserva->id_silla);

               if ($silla->id_grada != null) {
                //si es de grada debo ver en que fila esta, pero la fila viene dada por F1 o F2 asi que debo sacar el numero de la fila
                $fila = $silla->fila;
                //numero de la fila
                $numeroFila = substr($fila, 1);
                //si la fila es 1 o 2 el precio es 20, si es 3, 4 , 5 es 18, 6, 7, 8, 9 es 15, de la 9 en adelante es 12
                    if ($numeroFila == 1 || $numeroFila == 2) {
                        //precioTotal + 20
                        $precioTotal += 20;
                    } elseif ($numeroFila == 3 || $numeroFila == 4 || $numeroFila == 5) {
                        $precioTotal += 18;
                    } elseif ($numeroFila == 6 || $numeroFila == 7 || $numeroFila == 8 || $numeroFila == 9) {
                        $precioTotal += 15;
                    } else {
                        $precioTotal += 12;
                    }

                } elseif ($silla->id_palco != null) {
                    //si es de palco el precio es 50

                    $palcoIds = [205, 206, 207, 208, 209, 210, 211, 212,213,214,215,216,217,218,219, 451, 452, 453, 454, 455, 456, 457, 458, 459, 460, 461, 462, 463, 464, 465, 466,

                467, 468, 469,470,471,472,473,474,475,476,477,478,479,480,481,482,483,484,485,486,487,488,489,490,491,492
                
                ];
                    if (in_array($silla->id_palco, $palcoIds)) {
                        $precioTotal += 20;
                    }else{
                        $precioTotal += 18;

                    }
                }

            }
    
            if($precioTotal != $amount){
                return response()->json([
                    'status' => 'error',
                    'message' => 'The total price of the seats is not equal to the total price' . $precioTotal . ' ' . $amount,
                ]);
            }

            
    
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

                Log::error('Error al procesar el pago: ' . $e->getMessage());

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
