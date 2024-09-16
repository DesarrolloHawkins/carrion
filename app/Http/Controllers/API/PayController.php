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
use App\Models\Palcos;

use Illuminate\Support\Facades\Mail;

use GlobalPayments\Api\Entities\Enums\Secure3dVersion;

use GlobalPayments\Api\Services\Secure3dService;


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
        // $config->version = 2;

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

            // return $request->all();
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
            //$reservas = Reservas::where('order', $orderId)->get();
    
            // if (count($reservas) === 0) {
            //     return response()->json([
            //         'status' => 'error',
            //         'message' => 'No reservations found for this order',
            //     ]);
            // }

            // $precioTotal = 0;
           
            // foreach ($reservas as $reserva) {
            //    $clienteId = $reserva->id_cliente;
            //    $cliente = Cliente::find($clienteId);

            //    $silla = Sillas::find($reserva->id_silla);

            //    if ($silla->id_grada != null) {
            //     //si es de grada debo ver en que fila esta, pero la fila viene dada por F1 o F2 asi que debo sacar el numero de la fila
            //     $fila = $silla->fila;
            //     //numero de la fila
            //     $numeroFila = substr($fila, 1);
            //     //si la fila es 1 o 2 el precio es 20, si es 3, 4 , 5 es 18, 6, 7, 8, 9 es 15, de la 9 en adelante es 12
            //         if ($numeroFila == 1 || $numeroFila == 2) {
            //             //precioTotal + 20
            //             $precioTotal += 20;
            //         } elseif ($numeroFila == 3 || $numeroFila == 4 || $numeroFila == 5) {
            //             $precioTotal += 18;
            //         } elseif ($numeroFila == 6 || $numeroFila == 7 || $numeroFila == 8 || $numeroFila == 9) {
            //             $precioTotal += 15;
            //         } else {
            //             $precioTotal += 12;
            //         }

            //     } elseif ($silla->id_palco != null) {
            //         //si es de palco el precio es 50

            //         $palcoIds = [
            //             16, 17, 18, 19, 20,21,22,23,24,25,26,27,28,122,121,120,119,118,117,116,115,114,113,112,111,110,109,108,220,221,222,223,224,225,226,227,228,229,230,231,232,233,234,
            //             534, 533, 532, 531, 530, 529, 528, 527, 526, 525, 524, 523, 522, 521, 520, 519, 518, 517, 516, 515, 514, 513, 512, 511, 510, 509, 508, 507, 506, 505, 504, 503, 502, 501, 500, 499, 498, 497, 496, 495, 494, 493,
                        
            //         ];

            //     $palco = Palcos::find($silla->id_palco);
            //         if($palco){
            //             if (in_array($palco->numero, $palcoIds)) {
            //                 $precioTotal += 18;
            //             }else{
            //                 $precioTotal += 20;

            //             }
            //         }
                   
            //     }

            // }
    
            // if($precioTotal != $amount){
            //     return response()->json([
            //         'status' => 'error',
            //         'message' => 'The total price of the seats is not equal to the total price' . $precioTotal . ' ' . $amount,
            //     ]);
            // }

            
    
            // // Comprobar si las sillas ya están reservadas por otros
            // foreach ($reservas as $reserva) {
            //     $silla = $reserva->id_silla;
            //     $reservasConflicto = Reservas::where('id_silla', $silla)
            //                                  ->where('estado', '!=', 'cancelada')
            //                                  ->where('order', '!=', $orderId)
            //                                  ->get();
            //     if (count($reservasConflicto) > 0) {
            //         return response()->json([
            //             'status' => 'error',
            //             'message' => 'Some seats are already reserved by another user',
            //         ]);
            //     }
            // }
            
            
            try{
                $response = Secure3dService::checkEnrollment($card)
                        ->withAmount(0.01)
                        ->withCurrency('EUR')
                        ->execute(Secure3dVersion::TWO);

                
                //return $response;

            

            }catch (ApiException $e){
                Log::error('Error al procesar el pago: ' . $e->getMessage());

                return response()->json([
                    'status' => 'error',
                    'message' => 'Payment failed in try Secure3dService',
                    'error' => $e->getMessage(),
                ]);
            }
            return 'Respuesto de Response: ' .$response;
            $status = $threeDSecureData->status;

            return response()->json([
                'status' => '3ds_required',
                'redirectUrl' => $threeDSecureData->redirectUrl,
                'transactionId' => $threeDSecureData->transactionId,
                'message' => $response->transactionId,

            ]);

            $enrolled = $threeDSecureData->enrolled; // TRUE
            // if enrolled, the available response data
            $serverTransactionId = $threeDSecureData->serverTransactionId; // af65c369-59b9-4f8d-b2f6-7d7d5f5c69d5
            $dsStartProtocolVersion = $threeDSecureData->directoryServerStartVersion; // 2.1.0
            $dsEndProtocolVersion = $threeDSecureData->directoryServerEndVersion; // 2.1.0
            $acsStartProtocolVersion = $threeDSecureData->acsStartVersion; // 2.1.0
            $acsEndProtocolVersion = $threeDSecureData->acsEndVersion; // 2.1.0
            $methodUrl = $threeDSecureData->issuerAcsUrl; // https://www.acsurl.com/method
            $encodedMethodData = $threeDSecureData->payerAuthenticationRequest; // Base64 encoded string


            // Procesar el pago
            try {

                $authResponse = $card->verify()
                    ->withCurrency("EUR")
                    ->withAmount($amount)
                    ->with3DSecure()
                    ->execute();

                // Si se requiere 3DS, redirigir al usuario para autenticación
                if ($authResponse->is3DSecureRequired()) {
                    return response()->json([
                        'status' => '3ds_required',
                        'redirectUrl' => $authResponse->redirectUrl,
                        'transactionId' => $authResponse->transactionId
                    ]);
                }

                // Si no se requiere 3DS, proceder con el cargo
                $chargeResponse = $card->charge($amount)
                    ->withCurrency("EUR")
                    ->withTransactionId($authResponse->transactionId)
                    ->execute();    
            } catch (ApiException $e) {

                Log::error('Error al procesar el pago: ' . $e->getMessage());

                return response()->json([
                    'status' => 'error',
                    'message' => 'Payment failed in try metodo continuado',
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
    
    }
    
}
