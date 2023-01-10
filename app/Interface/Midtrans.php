<?php

namespace App\Interface;

use Error;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class Midtrans
{
    private $id;
    private $clientKey;
    private $serverKey;

    const BASE_URL = "https://api.sandbox.midtrans.com";
    const CHARGE_URL = "/v2/charge";

    public function __construct()
    {
        $this->id = env("MIDTRANS_ID");
        $this->clientKey = env("MIDTRANS_CLIENT_KEY");
        $this->serverKey = env("MIDTRANS_SERVER_KEY");
    }

    public function charge($payload)
    {
        try {
            $chargeURL = Midtrans::BASE_URL . Midtrans::CHARGE_URL;
            $authKey = base64_encode("{$this->serverKey}:");
            $midtransResponse = Http::withHeaders([
                "Authorization" => "Basic {$authKey}",
            ])->post($chargeURL, $payload);

            if ($midtransResponse->json("status_code", null) != "200" && $midtransResponse->json("status_code") != "201") {
                Log::error("Midtrans Error", $midtransResponse->json());
                throw new Error("Invalid Midtrans Request", 5001);
            }
            return $midtransResponse;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function snap($orderId, $grossAmount, $customerDetails = null, $redirectUrl = null)
    {
        $transactionDetail = [
            "order_id" => $orderId,
            "gross_amount" => $grossAmount,
        ];

        $snapParam = [
            "transaction_details" => $transactionDetail,
        ];

        if ($customerDetails) {
            $snapParam["customer_details"] = $customerDetails;
        }


        Config::$serverKey = $this->serverKey;
        Config::$isSanitized = true;
        Config::$isProduction = App::environment("production");

        if (!preg_match("/localhost|127\.0\.0\.1/", url()->current())) {
            // If it's not a localhost request
            // Override notification URL to use current host
            Config::$overrideNotifUrl = url("/api/midtrans/webhook");
        }

        if ($redirectUrl) {
            $snapParam["callbacks"] = [
                "finish" => $redirectUrl,
            ];
        }

        $snapToken = Snap::getSnapToken($snapParam);

        return $snapToken;
    }
}
