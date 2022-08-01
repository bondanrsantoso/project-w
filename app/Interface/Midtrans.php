<?php

namespace App\Interface;

use Illuminate\Support\Facades\Http;

class Midtrans
{
    private $id;
    private $clientKey;
    private $serverKey;
    private $authKey;

    const BASE_URL = "https://api.sandbox.midtrans.com";
    const CHARGE_URL = "/v2/charge";

    public function __construct()
    {
        $this->id = env("MIDTRANS_ID");
        $this->clientKey = env("MIDTRANS_CLIENT_KEY");
        $this->serverKey = env("MIDTRANS_SERVER_KEY");
        $this->authKey = base64_encode($this->serverKey);
    }

    public function charge($payload)
    {
        try {
            $chargeURL = Midtrans::BASE_URL . Midtrans::CHARGE_URL;
            $midtransResponse = Http::withHeaders([
                "Authorization" => "Basic " . $this->authKey,
            ])->acceptJson()->post($chargeURL, $payload);
            return $midtransResponse;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function verifySignature($orderId, $statusCode, $grossAmount, $signature)
    {
        $verificationInput = $orderId . $statusCode . $grossAmount . $this->serverKey;
        $verificationHash = openssl_digest($verificationInput, "sha512");
        return $verificationHash == $signature;
    }
}
