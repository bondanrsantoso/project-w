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
            "item_details" => $items == null ? [] : $items,
            "echannel" => $billKey ? [
                "bill_key" => $billKey,
            ] : null,
        ];

        $midtrans = new Midtrans();
        $bankResponse = $midtrans->charge($payload);

        return $bankResponse;
    }
}
