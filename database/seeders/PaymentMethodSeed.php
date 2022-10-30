<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // PaymentMethod::factory()->create([
        //     "name" => "QRIS",
        //     "payment_id" => "qris",
        //     "payment_type" => "qris",
        //     "icon_url" => "https://storage.googleapis.com/go-merchant-production.appspot.com/uploads/2021/03/2fdbf10f54c4f970356742f641a6dce5_dc3818bde5dfa8e4dc2fd9f27e7567ea_compressed.png",
        //     "transaction_fee_amount" => null,
        //     "transaction_fee_percent" => 0.7,
        // ]);

        PaymentMethod::factory()->create([
            "name" => "Mandiri VA",
            "payment_id" => "mandiriva",
            "payment_type" => "echannel",
            "icon_url" => "https://storage.googleapis.com/go-merchant-production.appspot.com/uploads/2020/09/11f8970a182ad8cf6aaf0a0cd22dd9ad_3948cb3bf5c4887c7cca7ca7ee421708_compressed.png",
            "transaction_fee_amount" => 4000,
            "transaction_fee_percent" => null,
        ]);
    }
}
