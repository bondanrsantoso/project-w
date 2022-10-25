<?php

namespace App\Interface;

use Illuminate\Support\Facades\Http;

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
            $midtransResponse = Http::post($chargeURL, $payload);
            return $midtransResponse;
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}
