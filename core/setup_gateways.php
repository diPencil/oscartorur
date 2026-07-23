<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Gateway;
use App\Models\GatewayCurrency;
use App\Models\Form;

try {
    Gateway::where('code', '<', 1000)->update(['status' => 0]);

    $formData = [
        "transaction_id" => ["name" => "Transaction ID / Phone Number", "label" => "Transaction ID / Phone Number", "is_required" => "required", "extensions" => "", "options" => [], "type" => "text"],
        "screenshot" => ["name" => "Screenshot", "label" => "Screenshot", "is_required" => "required", "extensions" => "jpg,jpeg,png,pdf", "options" => [], "type" => "file"]
    ];
    
    $form = new Form();
    $form->act = 'manual_deposit';
    $form->form_data = json_encode($formData);
    $form->save();

    $manualGateways = [
        ['name' => 'Instapay', 'code' => 1001, 'desc' => 'Please transfer the exact amount to Instapay username: @YOUR_INSTAPAY_USERNAME. Once transferred, provide the transaction ID or your phone number, and attach a screenshot of the receipt.'],
        ['name' => 'Wallet Cash (Vodafone, Orange, Etisalat, We)', 'code' => 1002, 'desc' => 'Please transfer to our Wallet number: 010XXXXXXXX. After the transfer, enter the number you transferred from and attach a screenshot.'],
        ['name' => 'Paylater', 'code' => 1003, 'desc' => 'Book now and pay later at our office or via other arrangements.']
    ];

    foreach($manualGateways as $mg) {
        $gateway = Gateway::where('code', $mg['code'])->first() ?? new Gateway();
        $gateway->code = $mg['code'];
        $gateway->form_id = $form->id;
        $gateway->name = $mg['name'];
        $gateway->alias = strtolower(str_replace([' ', '(', ')', ','], ['_', '', '', ''], $mg['name']));
        $gateway->status = 1;
        $gateway->gateway_parameters = json_encode([]);
        $gateway->supported_currencies = json_encode(['EGP']);
        $gateway->crypto = 0;
        $gateway->description = $mg['desc'];
        $gateway->save();

        $gc = GatewayCurrency::where('method_code', $mg['code'])->where('currency', 'EGP')->first() ?? new GatewayCurrency();
        $gc->method_code = $mg['code'];
        $gc->currency = 'EGP';
        $gc->name = $mg['name'];
        $gc->symbol = 'EGP';
        $gc->gateway_alias = $gateway->alias;
        $gc->min_amount = 10;
        $gc->max_amount = 100000;
        $gc->percent_charge = 0;
        $gc->fixed_charge = 0;
        $gc->rate = 1;
        $gc->gateway_parameter = json_encode([]);
        $gc->save();
    }

    $paymobCode = 120;
    $paymobParams = [
        'api_key' => ['title' => 'API Key', 'global' => true, 'value' => 'YOUR_API_KEY'],
        'integration_id' => ['title' => 'Integration ID', 'global' => true, 'value' => 'YOUR_INTEGRATION_ID'],
        'iframe_id' => ['title' => 'Iframe ID', 'global' => true, 'value' => 'YOUR_IFRAME_ID']
    ];

    $paymob = Gateway::where('code', $paymobCode)->first() ?? new Gateway();
    $paymob->code = $paymobCode;
    $paymob->form_id = 0;
    $paymob->name = 'Paymob';
    $paymob->alias = 'Paymob';
    $paymob->status = 1;
    $paymob->gateway_parameters = json_encode($paymobParams);
    $paymob->supported_currencies = json_encode(['EGP']);
    $paymob->crypto = 0;
    $paymob->description = 'Pay automatically via Paymob Gateway.';
    $paymob->save();

    $pc = GatewayCurrency::where('method_code', $paymobCode)->where('currency', 'EGP')->first() ?? new GatewayCurrency();
    $pc->method_code = $paymobCode;
    $pc->currency = 'EGP';
    $pc->name = 'Paymob';
    $pc->symbol = 'EGP';
    $pc->gateway_alias = 'Paymob';
    $pc->min_amount = 10;
    $pc->max_amount = 100000;
    $pc->percent_charge = 0;
    $pc->fixed_charge = 0;
    $pc->rate = 1;
    $pc->gateway_parameter = json_encode($paymobParams);
    $pc->save();

    echo "Gateways successfully created!";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
