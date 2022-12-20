<?php

namespace App\Interface;

use App\Models\Company;

class QrisPayment
{
    public static function charge(string $orderId, int $grossAmount, Company $company = null, array $items = null)
    {
        $payload = [
            "payment_type" => "qris",
            "transaction_details" => [
                "order_id" => $orderId,
                "gross_amount" => $grossAmount
            ],
            "item_details" => $items,
        ];

        $midtrans = new Midtrans();
        $qrisResponse = $midtrans->charge($payload);

        return $qrisResponse;

        // if($company) {
        //     $payload["customer_details"] = [
        //         "first_name" => $company->name,
        //         "email" => $company->user->email,
        //         "phone"
        //     ]
        // }
    }
}
