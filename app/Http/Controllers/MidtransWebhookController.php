<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MidtransWebhookController extends Controller
{
    public function webhook(Request $request)
    {
        $valid = $request->validate([
            "transaction_time" => "required|date",
            "transaction_status" => "required|string",
            "transaction_id" => "required|uuid",
            "status_message" => "nullable|string",
            "status_code" => "required|string",
            "signature_key" => "required|string",
            "payment_type" => "required|string",
            "order_id" => "required|string",
            // "merchant_id" => "nullable|string",
            "gross_amount" => "required|numeric",
            "fraud_status" => "required|string",
            "currency" => "sometimes|required|string",
            "acquirer" => "sometimes|nullable|string",
        ]);

        DB::beginTransaction();

        try {
            $transaction = new Transaction([...$valid, "invoice_id" => $request->input("order_id")]);
            $transaction->id = $valid["transaction_id"];
            $transaction->save();
            $transaction->refresh();

            $invoice = $transaction->invoice;
            $invoice->transaction_status = $transaction->transaction_status;
            $invoice->save();

            DB::commit();

            return ResponseFormatter::success([], "OK");
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
