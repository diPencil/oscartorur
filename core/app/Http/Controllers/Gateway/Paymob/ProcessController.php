<?php

namespace App\Http\Controllers\Gateway\Paymob;

use App\Models\Deposit;
use App\Http\Controllers\Gateway\PaymentController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GatewayCurrency;
use Illuminate\Support\Facades\Http;

class ProcessController extends Controller
{
    public static function process($deposit)
    {
        $paymobAcc = json_decode($deposit->gatewayCurrency()->gateway_parameter);
        
        $apiKey = $paymobAcc->api_key;
        $integrationId = $paymobAcc->integration_id;
        $iframeId = $paymobAcc->iframe_id;
        $amountCents = round($deposit->final_amount, 2) * 100;

        try {
            // 1. Authentication
            $authResponse = Http::post('https://accept.paymob.com/api/auth/tokens', [
                'api_key' => $apiKey
            ])->json();

            $token = $authResponse['token'];

            // 2. Order Registration
            $orderResponse = Http::post('https://accept.paymob.com/api/ecommerce/orders', [
                'auth_token' => $token,
                'delivery_needed' => 'false',
                'amount_cents' => $amountCents,
                'currency' => $deposit->method_currency,
                'merchant_order_id' => $deposit->trx
            ])->json();

            $orderId = $orderResponse['id'];

            // 3. Payment Key Generation
            $user = auth()->user();
            $paymentKeyResponse = Http::post('https://accept.paymob.com/api/acceptance/payment_keys', [
                'auth_token' => $token,
                'amount_cents' => $amountCents,
                'expiration' => 3600,
                'order_id' => $orderId,
                'billing_data' => [
                    'apartment' => 'NA',
                    'email' => $user->email ?? 'test@example.com',
                    'floor' => 'NA',
                    'first_name' => $user->firstname ?? 'User',
                    'street' => 'NA',
                    'building' => 'NA',
                    'phone_number' => $user->mobile ?? '+201000000000',
                    'shipping_method' => 'NA',
                    'postal_code' => 'NA',
                    'city' => $user->address->city ?? 'NA',
                    'country' => 'EG',
                    'last_name' => $user->lastname ?? 'Name',
                    'state' => 'NA'
                ],
                'currency' => $deposit->method_currency,
                'integration_id' => $integrationId
            ])->json();

            $paymentKey = $paymentKeyResponse['token'];

            // 4. Redirect
            $send['redirect'] = true;
            $send['redirect_url'] = "https://accept.paymob.com/api/acceptance/iframes/{$iframeId}?payment_token={$paymentKey}";

        } catch (\Exception $e) {
            $send['error'] = true;
            $send['message'] = 'Payment gateway error: ' . $e->getMessage();
        }

        return json_encode($send);
    }

    public function ipn(Request $request)
    {
        $data = $request->all();
        
        // Ensure it's a valid transaction callback
        if (!isset($data['obj']) || !isset($data['obj']['order'])) {
            return response()->json(['success' => false]);
        }
        
        $trx = $data['obj']['order']['merchant_order_id'];
        $success = $data['obj']['success'];
        
        $deposit = Deposit::where('trx', $trx)->orderBy('id', 'DESC')->first();
        
        if (!$deposit) {
            return response()->json(['success' => false, 'message' => 'Deposit not found']);
        }
        
        if ($success == true && $deposit->status == 0) {
            PaymentController::userDataUpdate($deposit);
        }
        
        return response()->json(['success' => true]);
    }
}
