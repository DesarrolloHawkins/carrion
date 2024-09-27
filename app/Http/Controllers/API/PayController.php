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

use GlobalPayments\Api\Entities\Enums\ColorDepth;
use GlobalPayments\Api\Entities\Enums\ChallengeWindowSize;
use GlobalPayments\Api\Entities\Enums\MethodUrlCompletion;
use GlobalPayments\Api\Entities\ThreeDSecure;
use GlobalPayments\Api\Entities\BrowserData;

use App\Models\Order;
use Ssheduardo\Redsys\Facades\Redsys; // Usando Redsys SDK


class PayController extends Controller
{
    protected $globalPayService;


    public function __construct()
    {


        // $config->version = 2;

        // $config = new GpEcomConfig();
        // $config->merchantId = env('MERCHANT_ID');
        // $config->accountId = env('ACCOUNT');
        // $config->sharedSecret = env('SHARED_SECRET');
        // $config->serviceUrl = env('ENVIRONMENT');


        // ServicesContainer::configureService($config);
    }

    /**
     * Procesa un pago desde el frontend
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */



    // public function checkout(Request $request)
    // {
    //     // \Stripe\Stripe::setApiKey(env('STRIPE'));
    //     // header('Content-Type: application/json');

    //     // $YOUR_DOMAIN = env('APP_URL');

    //     // $checkout_session = \Stripe\Checkout\Session::create([
    //     //   'line_items' => [[
    //     //     # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
    //     //     'price' => '{{PRICE_ID}}',
    //     //     'quantity' => 1,
    //     //   ]],
    //     //   'mode' => 'payment',
    //     //   'success_url' => $YOUR_DOMAIN . '?success=true',
    //     //   'cancel_url' => $YOUR_DOMAIN . '?canceled=true',
    //     // ]);

    //     // header("HTTP/1.1 303 See Other");
    //     // header("Location: " . $checkout_session->url);
    // }




    // public function processPayment(Request $request)
    // {

    //         // Crear el objeto de tarjeta
    //         $card = new CreditCardData();
    //         $card->number = $request->input('card_number');
    //         $card->expMonth = $request->input('expiry_month');
    //         $card->expYear = $request->input('expiry_year');
    //         $card->cvn = $request->input('cvv');
    //         $card->cardHolderName = $request->input('card_name');

    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Payment failed in try Secure3dService',
    //             'error' => $card,
    //         ]);
    //         $amount = $request->input('amount');
    //         $orderId = $request->input('orderId');

    //         try{
    //             $threeDSecureData = Secure3dService::checkEnrollment($card)->execute('default', Secure3dVersion::TWO);

    //         }catch(ApiException $e){
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Payment failed in try principio',
    //                 'error' => $e->getMessage(),
    //             ]);
    //         }



    //         $enrolled = $threeDSecureData->enrolled; // TRUE
    //         // if enrolled, the available response data
    //         $serverTransactionId = $threeDSecureData->serverTransactionId; // af65c369-59b9-4f8d-b2f6-7d7d5f5c69d5
    //         $dsStartProtocolVersion = $threeDSecureData->directoryServerStartVersion; // 2.1.0
    //         $dsEndProtocolVersion = $threeDSecureData->directoryServerEndVersion; // 2.1.0
    //         $acsStartProtocolVersion = $threeDSecureData->acsStartVersion; // 2.1.0
    //         $acsEndProtocolVersion = $threeDSecureData->acsEndVersion; // 2.1.0
    //         $methodUrl = $threeDSecureData->issuerAcsUrl; // https://www.acsurl.com/method
    //         $encodedMethodData = $threeDSecureData->payerAuthenticationRequest; // Base64 encoded string




    //         //SIGUIENTE PASO
    //         $billingAddress = new Address();
    //         $billingAddress->streetAddress1 = "Apartment 852";
    //         $billingAddress->streetAddress2 = "Complex 741";
    //         $billingAddress->streetAddress3 = "Unit 4";
    //         $billingAddress->city = "Chicago";
    //         $billingAddress->state = "IL";
    //         $billingAddress->postalCode = "50001";
    //         $billingAddress->countryCode = "840";

    //         // Add the customer's shipping address
    //         $shippingAddress = new Address();
    //         $shippingAddress->streetAddress1 = "Flat 456";
    //         $shippingAddress->streetAddress2 = "House 789";
    //         $shippingAddress->streetAddress3 = "Basement Flat";
    //         $shippingAddress->city = "Halifax";
    //         $shippingAddress->postalCode = "W5 9HR";
    //         $shippingAddress->countryCode = "826";

    //         // Add captured browser data from the client-side and server-side
    //         $browserData = new BrowserData();
    //         $browserData->acceptHeader = "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8";
    //         $browserData->colorDepth = ColorDepth::TWENTY_FOUR_BITS;
    //         $browserData->ipAddress = "123.123.123.123";
    //         $browserData->javaEnabled = TRUE;
    //         $browserData->language = "en";
    //         $browserData->screenHeight = "1080";
    //         $browserData->screenWidth = "1920";
    //         $browserData->challengWindowSize = ChallengeWindowSize::FULL_SCREEN;
    //         $browserData->timeZone = "0";
    //         $browserData->userAgent = "Mozilla/5.0 (Windows NT 6.1; Win64, x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.110 Safari/537.36";

    //         $threeDSecureData = new ThreeDSecure();
    //         $threeDSecureData->serverTransactionId =  $serverTransactionId;

    //         try {
    //             $threeDSecureData = Secure3dService::initiateAuthentication($card, $threeDSecureData)
    //                ->withAmount(0.05)
    //                ->withCurrency("EUR")
    //                ->withOrderCreateDate(date("Y-m-d H:i:s"))
    //                ->withCustomerEmail("james.mason@example.com")
    //                ->withAddress($billingAddress, AddressType::BILLING)
    //                ->withAddress($shippingAddress, AddressType::SHIPPING)
    //                ->withBrowserData($browserData)
    //                ->withMobileNumber("44", "7123456789")
    //                ->withMethodUrlCompletion(MethodUrlCompletion::YES)
    //                ->execute(Secure3dVersion::TWO);
    //          } catch (ApiException $e) {
    //             return response()->json([
    //                 'status status prueba' => '3ds_required',
    //                 'transactionId' => $e,
    //             ]);
    //          }

    //          $status = $threeDSecureData->status;
    //          return response()->json([
    //             'status' => '3ds_required status',
    //             'transactionId' => $enrolled,
    //             'message' => $threeDSecureData,
    //         ]);
    //         //  return response()->json([
    //         //     'status' => '3ds_required',
    //         //     'redirectUrl' => $threeDSecureData,
    //         //     'transactionId' => $threeDSecureData,
    //         //     'message' => $threeDSecureData,
    //         //  ]);




    //          //SIGUIENTE PASO

    //          try {
    //             $threeDSecureData = Secure3dService::getAuthenticationData()
    //                ->withServerTransactionId($serverTransactionId)
    //                ->execute(Secure3dVersion::TWO);
    //          } catch (ApiException $e) {
    //             // TODO: add your error handling here
    //          }

    //          if (isset($threeDSecureData)) {
    //             $status = $threeDSecureData->status; // for example AUTHENTICATION_SUCCESSFUL or AUTHENTICATION_FAILED
    //             // Data required for authorization or database record
    //             $authenticationValue = $threeDSecureData->authenticationValue; // ODQzNjgwNjU0ZjM3N2JmYTg0NTM=s
    //             $dsTransId = $threeDSecureData->directoryServerTransactionId; // c272b04f-6e7b-43a2-bb78-90f4fb94aa25
    //             $messageVersion = $threeDSecureData->messageVersion; // 2.1.0
    //             $eci = $threeDSecureData->eci; // 05
    //             // Additional response data
    //             $acsTransID = $threeDSecureData->acsTransactionId; // 13c701a3-5a88-4c45-89e9-ef65e50a8bf9
    //             $statusReason = $threeDSecureData->statusReason; // LOW_CONFIDENCE
    //             $authenticationSource = $threeDSecureData->authenticationSource; // BROWSER
    //             $messageCategory = $threeDSecureData->messageCategory; // PAYMENT_AUTHENTICATION
    //          }


    //          //SIGUIENTE PASO

    //          // add obtained 3D Secure 2 authentication data
    //             $threeDSecureData = new ThreeDSecure();
    //             $threeDSecureData->authenticationValue = $authenticationValue;
    //             $threeDSecureData->directoryServerTransactionId = $dsTransId;
    //             $threeDSecureData->eci = $eci;
    //             $threeDSecureData->messageVersion = $messageVersion;

    //             $card->threeDSecure = $threeDSecureData;


    //             try {
    //                 // process an auto-settle authorization
    //                 $response = $card->charge(0.01)
    //                     ->withCurrency("EUR")
    //                     ->execute();
    //             } catch (ApiException $e) {
    //                 // TODO: Add your error handling here
    //                 return response()->json([
    //                     'status' => '3ds_required',
    //                     'redirectUrl' => $e,

    //                  ]);
    //             }


    //             if (isset($response)) {
    //                 $result = $response->responseCode; // 00 == Success
    //                 $message = $response->responseMessage; // [ test system ] AUTHORISED

    //                 // get the details to save to the DB for future requests
    //                 $orderId = $response->orderId; // N6qsk4kYRZihmPrTXWYS6g
    //                 $authCode = $response->authorizationCode; // 12345
    //                 $paymentsReference = $response->transactionId; // 14610544313177922
    //                 $schemeReferenceData = $response->schemeId; // MMC0F00YE4000000715
    //              }


    //          //----------------------------------------------------------------------------//


    //             return response()->json([
    //                             'status' => '3ds_required',
    //                             'redirectUrl' => $response,

    //                         ]);




    //         try{
    //             $response = $card->charge(0.01)
    //                 ->withCurrency("EUR")
    //                 ->execute();
    //             return $response;



    //         }catch (ApiException $e){
    //             Log::error('Error al procesar el pago: ' . $e->getMessage());

    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Payment failed in try Secure3dService',
    //                 'error' => $e->getMessage(),
    //             ]);
    //         }
    //         return 'Respuesto de Response: ' .$response;
    //         $status = $threeDSecureData->status;

    //         return response()->json([
    //             'status' => '3ds_required',
    //             'redirectUrl' => $threeDSecureData->redirectUrl,
    //             'transactionId' => $threeDSecureData->transactionId,
    //             'message' => $response->transactionId,

    //         ]);

    //         $enrolled = $threeDSecureData->enrolled; // TRUE
    //         // if enrolled, the available response data
    //         $serverTransactionId = $threeDSecureData->serverTransactionId; // af65c369-59b9-4f8d-b2f6-7d7d5f5c69d5
    //         $dsStartProtocolVersion = $threeDSecureData->directoryServerStartVersion; // 2.1.0
    //         $dsEndProtocolVersion = $threeDSecureData->directoryServerEndVersion; // 2.1.0
    //         $acsStartProtocolVersion = $threeDSecureData->acsStartVersion; // 2.1.0
    //         $acsEndProtocolVersion = $threeDSecureData->acsEndVersion; // 2.1.0
    //         $methodUrl = $threeDSecureData->issuerAcsUrl; // https://www.acsurl.com/method
    //         $encodedMethodData = $threeDSecureData->payerAuthenticationRequest; // Base64 encoded string


    //         // Procesar el pago
    //         try {

    //             $authResponse = $card->verify()
    //                 ->withCurrency("EUR")
    //                 ->withAmount($amount)
    //                 ->with3DSecure()
    //                 ->execute();

    //             // Si se requiere 3DS, redirigir al usuario para autenticación
    //             if ($authResponse->is3DSecureRequired()) {
    //                 return response()->json([
    //                     'status' => '3ds_required',
    //                     'redirectUrl' => $authResponse->redirectUrl,
    //                     'transactionId' => $authResponse->transactionId
    //                 ]);
    //             }

    //             // Si no se requiere 3DS, proceder con el cargo
    //             $chargeResponse = $card->charge($amount)
    //                 ->withCurrency("EUR")
    //                 ->withTransactionId($authResponse->transactionId)
    //                 ->execute();
    //         } catch (ApiException $e) {

    //             Log::error('Error al procesar el pago: ' . $e->getMessage());

    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Payment failed in try metodo continuado',
    //                 'error' => $e->getMessage(),
    //             ]);
    //         }

    //         // Si el pago es exitoso
    //         if ($response->responseCode === '00') {
    //             $orderId = $response->orderId;
    //             foreach ($reservas as $reserva) {
    //                 $reserva->estado = 'pagada';
    //                 $reserva->order = $orderId;
    //                 $reserva->save();
    //             }



    //             // Enviar correo de confirmación
    //             try {
    //                 $sillas = [];
    //                 foreach ($reservas as $reserva) {
    //                     $silla = Sillas::find($reserva->id_silla);
    //                     array_push($sillas, $silla);
    //                 }
    //                 Log::info('Procesando envío de correo para el cliente: ' . $cliente->email);

    //                 Mail::to($cliente->email)->send(new ReservaPagada($reservas, $sillas, $cliente));
    //                 Log::info('Correo enviado correctamente a ' . $cliente->email);

    //             } catch (\Exception $e) {
    //                 Log::error('Error al enviar el correo: ' . $e->getMessage());

    //                 return response()->json([
    //                     'status' => 'error',
    //                     'message' => 'Payment processed but email not sent',
    //                     'error' => $e->getMessage() . ' ' . $e->getTraceAsString(),
    //                 ]);
    //             }

    //             return response()->json([
    //                 'status' => 'success',
    //                 'message' => 'Payment processed successfully',
    //                 'orderId' => $orderId,
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Payment failed',
    //             ]);
    //         }

    // }

    // Iniciar el pago y generar la URL para Redsys

    public function initiatePayment(Request $request)
{
    try {
        // Datos de entrada
        $clienteId = $request->input('cliente_id');
        $amount = $request->input('amount'); // La cantidad en céntimos
        $orderId = $request->input('orderId');

        // Gestionar el pedido (cancelar el anterior si es necesario y crear uno nuevo)
        // $order = $this->gestionarPedido($clienteId, $amount);
        $order = Order::find($orderId);
        if (!$order || $order->status != 'pending') {
            return response()->json(['error' => 'El pedido ya no se encuentra disponible'], 500);
        }
        // Configura los datos de Redsys
        $key = config('redsys.key');
        $code = config('redsys.merchantcode');

        // Configurar los parámetros de Redsys
        Redsys::setAmount($amount / 100); // Pasamos la cantidad a euros
        Redsys::setOrder(str_pad($order->id, 12, '0', STR_PAD_LEFT)); // Orden con 12 dígitos
        Redsys::setMerchantcode($code);
        Redsys::setCurrency('978'); // Código del euro
        Redsys::setTransactiontype('0'); // Tipo de transacción = Venta
        Redsys::setTerminal('1'); // Terminal
        Redsys::setMethod('T'); // Solo tarjeta
        Redsys::setNotification(config('redsys.url_notification')); // URL de notificación
        Redsys::setUrlOk(config('redsys.url_ok')); // URL en caso de éxito
        Redsys::setUrlKo(config('redsys.url_ko')); // URL en caso de fallo
        Redsys::setVersion('HMAC_SHA256_V1');

        // Generar la firma
        $signature = Redsys::generateMerchantSignature($key);
        Redsys::setMerchantSignature($signature);
        $paymentUrl = env('PAYMENT_URL');
        // Generar el formulario HTML de Redsys
        $form = Redsys::createForm();

        // Devolver el formulario HTML al frontend
        return response()->json(data: ['form' => $form, 'orderId' => $order->id, 'paymentUrl' => $paymentUrl], status: 200);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}



// Cancelar pedido anterior
public function cancelarPedido($orderId)
{
    // Buscar el pedido por ID
    $order = Order::find($orderId);

    // Verificar si el pedido está pendiente o fallido
    if ($order && in_array($order->status, ['pending', 'failed'])) {
        // Cambiar el estado del pedido a cancelado
        $order->status = 'cancelled';
        $order->save();

        // Actualizar las reservas asociadas para reflejar la cancelación
        Reservas::where('order_id', $orderId)->update([
            'estado' => 'cancelada',
        ]);

        return response()->json(['message' => 'Pedido cancelado correctamente.']);
    } else {
        return response()->json(['error' => 'No se puede cancelar este pedido.'], 400);
    }
}


    public function pagoOk(Request $request){
        return response()->json([$request->all()]);
    }
    public function pagoFallo(){
        return false;
    }

    // Callback que recibe la notificación de Redsys cuando el pago es completado o fallido
    public function paymentCallback(Request $request)
    {
        $redsys = Redsys::getFacadeRoot(); // Obtiene la instancia de Redsys

        // Verifica la firma
        if ($redsys->check($request->all())) {
            $merchantOrder = $request->input('Ds_Order');
            $order = Order::where('id', ltrim($merchantOrder, '0'))->first();

            if ($order) {
                $dsResponse = $request->input('Ds_Response');

                // Verificar si el pago fue exitoso
                if (intval($dsResponse) <= 99) {
                    // Pago exitoso
                    $order->status = 'paid'; // Cambiar estado a 'paid' cuando el pago sea exitoso
                    $order->save();

                    // Actualizar todas las reservas asociadas con los datos del pago
                    Reservas::where('order_id', $order->id)->update([
                        'estado' => 'pagada',
                        'transaction' => $request->input('Ds_AuthorisationCode'), // Código de autorización de Redsys
                        'metodo_pago' => 'redsys', // Método de pago
                        'order' => $order->id,
                        'procesando' => 0 // Asumimos que ya no está procesando
                    ]);

                    return response()->json(['message' => 'Pago completado correctamente']);
                } else {
                    // Pago fallido
                    $order->status = 'failed'; // Cambiar estado a 'failed'
                    $order->save();

                    // Actualizar todas las reservas asociadas
                    Reservas::where('order_id', $order->id)->update([
                        'estado' => 'fallida', // Estado de la reserva cuando el pago falla
                        'transaction' => null, // No hay código de autorización
                        'metodo_pago' => 'redsys', // Método de pago
                        'order' => $order->id,
                        'procesando' => 0 // Asumimos que ya no está procesando
                    ]);

                    return response()->json(['message' => 'Pago fallido. Orden actualizada.'], 400);
                }
            }
        } else {
            return response()->json(['message' => 'Firma no válida.'], 400);
        }
    }


    // Verificación del estado del pago para el frontend
    public function checkPaymentStatus($orderId)
    {
        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['error' => 'Orden no encontrada'], 404);
        }

        if ($order->status === 'paid') {
            return response()->json(['status' => 'completed']);
        } elseif ($order->status === 'failed') {
            return response()->json(['status' => 'failed']);
        } else {
            return response()->json(['status' => 'pending']);
        }
    }
    public function status($orderId)
    {
        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['error' => 'Orden no encontrada'], 404);
        }

        if ($order->status === 'paid') {
            return response()->json(['status' => 'completed']);
        } elseif ($order->status === 'failed') {
            return response()->json(['status' => 'failed']);
        } else {
            return response()->json(['status' => 'pending']);
        }
    }

}
