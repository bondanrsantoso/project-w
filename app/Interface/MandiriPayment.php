<?php

namespace App\Interface;

class MandiriPayment
{
    public static function charge(string $orderId, int $grossAmount, string $billKey = null, array $items = null)
    {
        $payload = [
            "payment_type" => "echannel",
            "transaction_details" => [
                "order_id" => $orderId,
                "gross_amount" => $grossAmount
            ],
            "item_details" => $items,
            "echannel" => $billKey ? [
                "bill_key" => $billKey,
                "bill_info1" => substr("Tagihan", 0, 10),
                "bill_info2" => substr("Invoice ID {$orderId}", 0, 10),
            ] : [
                "bill_info1" => substr("Tagihan", 0, 10),
                "bill_info2" => substr("Invoice ID {$orderId}", 0, 10),
            ],
        ];

        $midtrans = new Midtrans();
        $bankResponse = $midtrans->charge($payload);

        return $bankResponse;
    }
}
