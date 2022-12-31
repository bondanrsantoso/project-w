<?php

namespace App\Interface;

use Error;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
}
