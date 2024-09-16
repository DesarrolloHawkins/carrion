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
use GlobalPayments\Api\Entities\ThreeDSecure;
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

        // https://api.globalpay-ecommerce.com

    }

    /** 
     * Procesa un pago desde el frontend
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function processPayment(Request $request)
    {
        // Validar los datos de la solicitud
        // $validatedData = $request->validate([
        //     'authenticationValue' => 'required|string',
        //     'directoryServerTransactionId' => 'required|string',
        //     'eci' => 'required|string',
        //     'card_number' => 'required|string',
        //     'expiry_month' => 'required|numeric',
        //     'expiry_year' => 'required|numeric',
        //     'cvv' => 'required|string',
        //     'card_name' => 'required|string',
        //     'amount' => 'required|numeric',
        //     'orderId' => 'required|string',
        // ]);

// Add 3D Secure 2 Mandatory and Recommended Fields
        $config = new GpEcomConfig();
        $config->merchantId = env('MERCHANT_ID');
        $config->accountId = env('ACCOUNT');
        $config->sharedSecret = env('SHARED_SECRET');
        $config->serviceUrl = env('ENVIRONMENT');

        // configure client, request and HPP settings

        $config->hostedPaymentConfig = new HostedPaymentConfig();
        $config->hostedPaymentConfig->version = HppVersion::VERSION_2;
        $service = new HostedService($config);
        $hostedPaymentData = new HostedPaymentData();
        $hostedPaymentData->customerEmail = "james.mason@example.com";
        $hostedPaymentData->customerPhoneMobile = "44|07123456789";
        $hostedPaymentData->addressesMatch = false;

        $billingAddress = new Address();
        $billingAddress->streetAddress1 = "Flat 123";
        $billingAddress->streetAddress2 = "House 456";
        $billingAddress->streetAddress3 = "Unit 4";
        $billingAddress->city = "Halifax";
        $billingAddress->postalCode = "W5 9HR";
        $billingAddress->country = "826";

        $shippingAddress = new Address();
        $shippingAddress->streetAddress1 = "Apartment 825";
        $shippingAddress->streetAddress2 = "Complex 741";
        $shippingAddress->streetAddress3 = "House 963";
        $shippingAddress->city = "Chicago";
        $shippingAddress->state = "IL";
        $shippingAddress->postalCode = "50001";
        $shippingAddress->country = "840";

        try {
            $hppJson = $service->charge(19.99)
               ->withCurrency("EUR")
               ->withHostedPaymentData($hostedPaymentData)
               ->withAddress($billingAddress, AddressType::BILLING)
               ->withAddress($shippingAddress, AddressType::SHIPPING)
               ->serialize();      
            // TODO: pass the HPP JSON to the client-side    
         } catch (ApiException $e) {
            
            // Handle the exception
            Log::error($e->getMessage());

            return response()->json([
                'message' => 'Error en el procesamiento: ' . $e->getMessage(),
            ], 500);
         }



return response()->json([
    'hppJson' => $hppJson,
]);











        // Crear el objeto de tarjeta
        $card = new CreditCardData();
        $card->number = $validatedData['card_number'];
        $card->expMonth = intval($validatedData['expiry_month']);
        $card->expYear = intval($validatedData['expiry_year']);
        $card->cvn = $validatedData['cvv'];
        $card->cardHolderName = $validatedData['card_name'];

        $amount = $validatedData['amount'];
        $orderId = $validatedData['orderId'];

        // Crear los datos 3D Secure 2
        $threeDSecureData = new ThreeDSecure();
        $threeDSecureData->authenticationValue = $validatedData['authenticationValue'];
        $threeDSecureData->directoryServerTransactionId = $validatedData['directoryServerTransactionId'];
        $threeDSecureData->eci = $validatedData['eci'];
        $threeDSecureData->messageVersion = "2.1.0";

        // Añadir los datos 3D Secure 2 al objeto de tarjeta
        $card->threeDSecure = $threeDSecureData;

        try {
            // Procesar el cargo utilizando el SDK de Global Payments
            $response = $card->charge($amount)
                ->withCurrency("EUR")
                ->withOrderId($orderId)  // Usar el orderId si es necesario
                ->execute();

            // Verificar la respuesta del procesamiento de pago
            if ($response->responseCode === '00') {
                return response()->json([
                    'message' => 'Pago autorizado',
                    'orderId' => $response->orderId,
                    'authCode' => $response->authorizationCode,
                    'transactionId' => $response->transactionId,
                    'schemeReferenceData' => $response->schemeId,
                ]);


                // Actualizar el estado de la reserva
                $reserva = Reservas::where('order', $orderId)->first();
                $reserva->estado = 'pagada';
                $reserva->transaction = $response->transactionId;
                $reserva->metodo_pago = 'tarjeta';
                $reserva->save();


            } else {
                return response()->json([
                    'message' => 'Error en el pago: ' . $response->responseMessage,
                ], 400);
            }
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'message' => 'Error en el procesamiento: ' . $e->getMessage(),
            ], 500);
        }
    }

    
}
