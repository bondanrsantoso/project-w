@extends('dashboard.index')

@section('header')
<h3>Create Invoice</h3>
@endsection

@section('content')
<section id="basic-horizontal-layouts">
    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <form class="form form-horizontal" action="{{ env('APP_DOMAIN_PM','http://pm-admin.docu.web.id') }}/dashboard/invoices/{{ $invoice->id }}" method="POST">
                    @csrf

                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label>VA Number</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" id="name" class="form-control" name="va_number" placeholder="VA Number" value="{{ $invoice->va_number }}">
                            </div>

                            <div class="col-md-4">
                                <label>Transaction Fee</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" id="name" class="form-control" name="transaction_fee" placeholder="Transaction Fee" value="{{ $invoice->transaction_fee }}">
                            </div>

                            <div class="col-md-4">
                                <label>Service Fee</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" id="name" class="form-control" name="service_fee" placeholder="Service Fee" value="{{ $invoice->service_fee }}">
                            </div>

                            <div class="col-md-4">
                                <label>Subtotal</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" id="name" class="form-control" name="subtotal" placeholder="Subtotal" value="{{ $invoice->subtotal }}">
                            </div>

                            <div class="col-md-4">
                                <label>Transaction Status</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select name="transaction_status" class="form-select" aria-label="Default select example">
                                    <option {{ $invoice->transaction_status == "unpaid" ? "selected" : "" }} value="unpaid">Unpaid</option>
                                    <option {{ $invoice->transaction_status == "authorize" ? "selected" : "" }} value="authorize">Authorize</option>
                                    <option {{ $invoice->transaction_status == "capture" ? "selected" : "" }} value="capture">Capture</option>
                                    <option {{ $invoice->transaction_status == "settlement" ? "selected" : "" }} value="settlement">Settlement</option>
                                    <option {{ $invoice->transaction_status == "deny" ? "selected" : "" }} value="deny">Deny</option>
                                    <option {{ $invoice->transaction_status == "pending" ? "selected" : "" }} value="pending">Pending</option>
                                    <option {{ $invoice->transaction_status == "cancel" ? "selected" : "" }} value="cancel">Cancel</option>
                                    <option {{ $invoice->transaction_status == "refund" ? "selected" : "" }} value="refund">Refund</option>
                                    <option {{ $invoice->transaction_status == "partial_refund" ? "selected" : "" }} value="partial_refund">Partial_refund</option>
                                    <option {{ $invoice->transaction_status == "chargeback" ? "selected" : "" }} value="chargeback">Chargeback</option>
                                    <option {{ $invoice->transaction_status == "partial_chargeback" ? "selected" : "" }} value="partial_chargeback">Partial_chargeback</option>
                                    <option {{ $invoice->transaction_status == "expire" ? "selected" : "" }} value="expire">Expire</option>
                                    <option {{ $invoice->transaction_status == "failure" ? "selected" : "" }} value="failure">Failure</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Job ID</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select name="job_id" class="form-select" aria-label="Default select example">
                                    @foreach ($jobs as $job)
                                        <option {{ $invoice->job_id == $job->id ? "selected" : "" }} value="{{ $job->id }}">{{ $job->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Company ID</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select name="company_id" class="form-select" aria-label="Default select example">
                                    @foreach ($companies as $company)
                                        <option {{ $invoice->company_id == $company->id ? "selected" : "" }} value="{{ $company->id }}">{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Payment Method ID</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select name="payment_method_id" class="form-select" aria-label="Default select example">
                                    @foreach ($paymentMethods as $pm)
                                        <option {{ $invoice->payment_method_id == $pm->id ? "selected" : "" }} value="{{ $pm->id }}">{{ $pm->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Worker ID</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select name="worker_id" class="form-select" aria-label="Default select example">
                                    @foreach ($workers as $worker)
                                        <option {{ $invoice->worker_id == $worker->id ? "selected" : "" }} value="{{ $worker->id }}">{{ $worker->user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary btn-lg me-1 px-3 mb-1">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        console.log("aaaa");
        $('#body').css('background-color', '#ADE792');
    });
</script>
@endsection