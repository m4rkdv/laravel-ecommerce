<?php

namespace App\Services;

use MercadoPago\MercadoPagoConfig;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\MercadoPagoException;
use MercadoPago\Exceptions\MPApiException;
use Surfsidemedia\Shoppingcart\Facades\Cart;
use MercadoPago\Client\Preference\PreferenceClient;


class MercadoPagoService
{
    public function __construct()
    {
        try {
            MercadoPagoConfig::setAccessToken(config('mercadopago.access_token'));
            MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);
        } catch (\Exception $e) {
            Log::error('Error al configurar Mercado Pago: ' . $e->getMessage());
            throw new MercadoPagoException();
        }
    }

    public function crearPreferencia($data)
    {
        $orderId = $data['order_id']; // ID del pedido
        $cartItems = $data['items'];  // Productos del carrito
        
        $items = [];
    
        foreach ($cartItems as $cartItem) {
            $items[] = [
                "id" => $cartItem->id,
                "title" => $cartItem->name,
                "description" => $cartItem->options->description ?? '', // Opcional
                "currency_id" => "ARS", 
                "quantity" =>  (int)$cartItem->qty,
                "unit_price" => (float)$cartItem->price
            ];
        }
    
        // Información del comprador 
        $user = Auth::user(); 
        $payer = [
            "name" => $user->name,
            "surname" => $user->surname ?? '',
            "email" => $user->email,
        ];
    
        // Crear el objeto de preferencia
        $request = $this->createPreferenceRequest($items, $payer,$orderId);
        //dd($request);
        // Instanciar el cliente y enviar la solicitud
        $client = new PreferenceClient();
    
        try {
            $preference = $client->create($request);
            //dd($preference);
            // Redirigir al cliente al Checkout Pro
            if (isset($preference->init_point)) {
                return redirect($preference->init_point);
            } else {
                return back()->withErrors(['error' => 'No se pudo crear la preferencia de pago.']);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Crea el objeto de preferencia para la solicitud
     */
    protected function createPreferenceRequest($items, $payer,$orderId): array
    {
        return [
            "items" => $items,
            "payer" => $payer,
            "payment_methods" => [
                "excluded_payment_methods" => [],
                "installments" => 12,
                "default_installments" => 1,
            ],
            "back_urls" => [
                "success" => route('mercadopago.success'),
                "failure" => route('mercadopago.failed'),
            ],
            "statement_descriptor" => "GLADIADOR STORE",
            "external_reference" => $orderId,
            "auto_return" => "approved",
        ];
    }
}
