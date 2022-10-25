<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Interface\MandiriPayment;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Job;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use Faker\Provider\ar_EG\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Job $job = null, Company $company = null)
    {
        if ($job != null) {
            $request->merge([
                "job_id" => $job->id
            ]);
        }

        if ($company != null) {
            $request->merge([
                "company_id" => $company->id
            ]);
        }

        $valid = $request->validate([
            "job_id" => "sometimes|nullable|exists:jobs,id",
            "company_id" => "sometimes|nullable|exists:companies,id",
            "status" => "sometimes|nullable",
        ]);

        $invoiceQuery = Invoice::with(["job", "company", "paymentMethod", "transactions"]);

        if ($request->filled("job_id")) {
            $invoiceQuery->where("job_id", $request->input("job_id"));
        }
        if ($request->filled("company_id")) {
            $invoiceQuery->where("company_id", $request->input("company_id"));
        }
        if ($request->filled("status")) {
            $invoiceQuery->where("transaction_status", $request->input("status"));
        }

        $invoices = $invoiceQuery->paginate($request->input("paginate", 15));

        if ($request->wantsJson() || $request->is("api*")) {
            return ResponseFormatter::success($invoices);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Job $job = null, Company $company = null)
    {
        if ($job != null) {
            $request->merge([
                "job_id" => $job->id,
            ]);
        }

        if ($company != null) {
            $request->merge([
                "company_id" => $company->id
            ]);
        }

        $valid = $request->validate([
            "va_number" => "required|string|max:12",
            "transaction_fee" => "sometimes|required|min:0",
            "service_fee" => "sometimes|required|min:0",
            "subtotal" => "required|min:0",
            "transaction_status" => "sometimes|required",
            "actions" => "sometimes|nullable",
            "job_id" => "required|exists:jobs,id",
            "company_id" => "required|exists:company,id",
            "payment_method_id" => "sometimes|nullable|exists:payment_methods,id",
        ]);

        $invoice = new Invoice($valid);
        $invoice->save();

        if ($request->wantsJson() || $request->is("api*")) {
            $invoice->refresh();
            $invoice->load(["job", "company", "paymentMethod", "transactions"]);
            return ResponseFormatter::success($invoice);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Invoice $invoice)
    {
        if ($request->wantsJson() || $request->is("api*")) {
            $invoice->load(["job", "company", "paymentMethod", "transactions"]);
            return ResponseFormatter::success($invoice);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        $valid = $request->validate([
            "va_number" => "sometimes|required|string|max:12",
            "transaction_fee" => "sometimes|required|min:0",
            "service_fee" => "sometimes|required|min:0",
            "subtotal" => "sometimes|required|min:0",
            "transaction_status" => "sometimes|required",
            "actions" => "sometimes|nullable",
            "job_id" => "sometimes|required|exists:jobs,id",
            "company_id" => "sometimes|required|exists:company,id",
            "payment_method_id" => "sometimes|nullable|exists:payment_methods,id",
        ]);

        $invoice->fill($valid);
        $invoice->save();

        if ($request->wantsJson() || $request->is("api*")) {
            $invoice->refresh();
            $invoice->load(["job", "company", "paymentMethod", "transactions"]);
            return ResponseFormatter::success($invoice);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return ResponseFormatter::success([], "OK");
    }

    public function pay(Request $request, $id)
    {
        $valid = $request->validate([
            "payment_method_id" => "required|exists:payment_methods,id",
        ]);

        $invoice = Invoice::findOrFail($id);
        $paymentMethod = PaymentMethod::find($request->input("payment_method_id"));

        DB::beginTransaction();
        try {
            $invoice->payment_method_id = $paymentMethod->id;

            if ($paymentMethod->transaction_fee_amount != null) {
                $invoice->transaction_fee = $paymentMethod->transaction_fee_amount;
            } else if ($paymentMethod->transaction_fee_percent != null) {
                $fee = ($invoice->subtotal + $invoice->service_fee) * ($paymentMethod->transaction_fee_percent / 100);
                $invoice->transaction_fee = $fee;
            }

            $invoice->save();
            $invoice->refresh();

            $paymentResponse = null;

            if ($paymentMethod->payment_type == "echannel") {
                $paymentResponse = MandiriPayment::charge($invoice->id, $invoice->gross_amount);
                $invoice->actions = [
                    "biller_code" => $paymentResponse->json("biller_code", "70012"),
                    "bill_key" => $paymentResponse->json("bill_key"),
                ];

                $invoice->va_number = $paymentResponse->json("bill_key");
                $invoice->save();
            }


            $transaction = new Transaction([
                "invoice_id" => $invoice->id,
                "transaction_status" => $paymentResponse->json("transaction_status", "pending"),
                "status_code" => $paymentResponse->json("status_code", 500),
                "signature_key" => $paymentResponse->json("signature_key"),
                "payment_type" => $paymentResponse->json("payment_type", $paymentMethod->payment_type),
                "currency" => $paymentResponse->json("currency"),
                "acquirer" => $paymentResponse->json("acquirer"),
            ]);

            $transaction->id = $paymentResponse->json("transaction_id");
            $transaction->save();

            DB::commit();
            return ResponseFormatter::success($invoice);
        } catch (\Throwable $th) {
            DB::rollBack();

            throw $th;
        }
    }
}
