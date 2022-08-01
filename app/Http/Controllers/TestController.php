<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Interface\Midtrans;

class TestController extends Controller
{
    public function testMidtransCharge(Request $req)
    {
        $midtransClient = new Midtrans();

        // Mock variable
        $nOfApples = rand(45, 1000);
        $samplePayload = [
            "payment_type" => "echannel",
            "transaction_details" => [
                "order_id" => Str::uuid(),
                "gross_amount" => $nOfApples * 1000,
            ],
            "item_details" => [
                [
                    "id" => Str::uuid(),
                    "price" => 1000,
                    "quantity" => $nOfApples,
                    "name" => "Apple",
                ],
            ],
            "echannel" => [
                "bill_info1" => "This is a ",
                "bill_info2" => "test billing",
            ]
        ];
        try {
            $chargeResponse = $midtransClient->charge($samplePayload);

            return response()->json($chargeResponse->json());
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
