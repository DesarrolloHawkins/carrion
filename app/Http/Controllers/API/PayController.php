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


use GlobalPayments\Api\Entities\Address;
use GlobalPayments\Api\Entities\Enums\AddressType;
use GlobalPayments\Api\HostedPaymentConfig;
use GlobalPayments\Api\Entities\HostedPaymentData;
use GlobalPayments\Api\Entities\Enums\HppVersion;
use GlobalPayments\Api\Services\HostedService;

class PayController extends Controller
{
    protected $globalPayService;
   

    public function __construct()
    {
        

        // $config->version = 2;

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
        

     
            // Crear el objeto de tarjeta
            $card = new CreditCardData();
            $card->number = $request->input('card_number');
            $card->expMonth = $request->input('expiry_month');
            $card->expYear = $request->input('expiry_year');
            $card->cvn = $request->input('cvv');
            $card->cardHolderName = $request->input('card_name');
    
            $amount = $request->input('amount');
            $orderId = $request->input('orderId');
    
          
            
            try{
                $response = Secure3dService::checkEnrollment($card)
                        ->withAmount(0.01)
                        ->withCurrency('EUR')
                        ->execute(Secure3dVersion::TWO);

                
                return $response;

            

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
