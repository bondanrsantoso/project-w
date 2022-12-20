<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Interface\MandiriPayment;
use App\Interface\QrisPayment;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Job;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\Worker;
use Faker\Provider\ar_EG\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadesRequest;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Job $job = null, Company $company = null, Worker $worker = null)
    {
        if ($job && $job->id != null) {
            $request->merge([
                "job_id" => $job->id
            ]);
        }

        if ($company && $company->id != null) {
            $request->merge([
                "company_id" => $company->id
            ]);
        }

        if ($worker && $worker->id != null) {
            $request->merge([
                "worker_id" => $worker->id
            ]);
        }

        $valid = $request->validate([
            "job_id" => "sometimes|nullable|exists:jobs,id",
            "company_id" => "sometimes|nullable|exists:companies,id",
            "worker_id" => "sometimes|nullable|exists:workers,id",
            "status" => "sometimes|nullable",
        ]);

        $invoiceQuery = Invoice::with(["job", "company", "paymentMethod", "transactions", "worker"]);

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

        return view('dashboard.invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $jobs = Job::all();
        $companies = Company::all();
        $paymentMethods = PaymentMethod::all();
        $workers = Worker::with('user')->get();

        return view('dashboard.invoices.create', compact('jobs', 'companies', 'paymentMethods', 'workers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Job $job = null, Company $company = null)
    {
        // dd($request);

        if ($job && $job->id != null) {
            $request->merge([
                "job_id" => $job->id,
            ]);
        }

        if ($company && $company->id != null) {
            $request->merge([
                "company_id" => $company->id
            ]);
        }

        if ($request->user()->is_worker) {
            $request->merge([
                "worker_id" => $request->user()->worker?->id ? $request->input('worker_id') : $request->user()->worker?->id
            ]);
        }

        $valid = $request->validate([
            "va_number" => "nullable|string|max:12",
            "transaction_fee" => "sometimes|required|min:0",
            "service_fee" => "sometimes|required|min:0",
            "subtotal" => "required|min:0",
            "transaction_status" => "sometimes|required",
            "actions" => "sometimes|nullable",
            "job_id" => "required|exists:jobs,id",
            "company_id" => "sometimes|exists:companies,id",
            "payment_method_id" => "sometimes|nullable|exists:payment_methods,id",
            "worker_id" => "nullable|exists:workers,id",
        ]);

        if (!$request->filled("company_id")) {
            if (!$job || $job->id == null) {
                $job = Job::findOrFail($request->input("job_id"));
            }
            $company = $job->workgroup->project->company;
            $valid["company_id"] = $company->id;
        }

        if (!$request->filled("va_number")) {
            $user = $request->user();
            $phone = $user->phone_number ?? fake()->e164PhoneNumber();
            $va = substr($phone, 3, 12);
            $valid["va_number"] = $va;
        }

        $invoice = new Invoice($valid);
        $invoice->save();

        if ($request->wantsJson() || $request->is("api*")) {
            $invoice->refresh();
            $invoice->load(["job", "company", "paymentMethod", "transactions", "worker"]);
            return ResponseFormatter::success($invoice);
        }

        return redirect()->to('/dashboard/invoices')->with('success', 'Successfully Created Invoice');
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
            $invoice->load(["job", "company", "paymentMethod", "transactions", "worker"]);
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
        $jobs = Job::all();
        $companies = Company::all();
        $paymentMethods = PaymentMethod::all();
        $workers = Worker::with('user')->get();

        return view('dashboard.invoices.detail', compact('invoice', 'jobs', 'companies', 'paymentMethods', 'workers'));
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
            "company_id" => "sometimes|required|exists:companies,id",
            "payment_method_id" => "sometimes|nullable|exists:payment_methods,id",
            "worker_id" => "sometimes|nullable|exists:workers,id",
        ]);

        $invoice->fill($valid);
        $invoice->save();

        if ($request->wantsJson() || $request->is("api*")) {
            $invoice->refresh();
            $invoice->load(["job", "company", "paymentMethod", "transactions", "worker"]);
            return ResponseFormatter::success($invoice);
        }

        return redirect()->to('/dashboard/invoices')->with('success', 'Successfully Updated Invoice');
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

        if (FacadesRequest::wantsJson() || FacadesRequest::is("api*")) {
            return ResponseFormatter::success([], "OK");
        }

        return redirect()->to('/dashboard/invoices')->with('success', 'Successfully Deleted Invoice');
    }

    public function pay(Request $request, $id)
    {
        if (!$request->has("va_number") || !$request->filled("va_number")) {
            $user = $request->user();
            $phone = $user->phone_number ?? fake()->e164PhoneNumber();
            $va = substr($phone, 3, 12);
            $request->merge([
                "va_number" => $va,
            ]);
        }

        $valid = $request->validate([
            "payment_method_id" => "required|exists:payment_methods,id",
            "va_number" => "required|string",
        ]);

        $invoice = Invoice::findOrFail($id);
        $paymentMethod = PaymentMethod::find($request->input("payment_method_id"));

        DB::beginTransaction();
        try {
            $invoice->payment_method_id = $paymentMethod->id;

            if ($request->filled("va_number")) {
                $invoice->va_number = $request->input("va_number", "-");
            }

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
                $paymentResponse = MandiriPayment::charge($invoice->id, $invoice->grand_total, $invoice->va_number);
                $invoice->actions = [
                    "biller_code" => $paymentResponse->json("biller_code", "70012"),
                    "bill_key" => $paymentResponse->json("bill_key"),
                ];

                $invoice->va_number = $paymentResponse->json("bill_key");
                $invoice->save();
            } else if ($paymentMethod->payment_type == "qris") {
                $paymentResponse = QrisPayment::charge($invoice->id, $invoice->grand_total);
                $invoice->actions = $paymentResponse->json("actions", []);

                $invoice->save();
            }


            $transaction = new Transaction([
                "invoice_id" => $invoice->id,
                "transaction_status" => $paymentResponse->json("transaction_status", "pending"),
                "transaction_time" => date("Y-m-d"),
                "status_code" => $paymentResponse->json("status_code", 500),
                "signature_key" => $paymentResponse->json("signature_key"),
                "payment_type" => $paymentResponse->json("payment_type", $paymentMethod->payment_type),
                "currency" => $paymentResponse->json("currency", "IDR"),
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
