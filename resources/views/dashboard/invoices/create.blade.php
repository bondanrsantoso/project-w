@extends('dashboard.index')

@section('header')
<h3>Create Invoice</h3>
@endsection

@section('content')
<section id="basic-horizontal-layouts">
    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <form class="form form-horizontal" action="{{ env('APP_DOMAIN_PM','http://pm-admin.docu.web.id') }}/dashboard/invoices" method="POST">
                    @csrf
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label>VA Number</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" id="name" class="form-control" name="va_number" placeholder="VA Number">
                            </div>

                            <div class="col-md-4">
                                <label>Transaction Fee</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" id="name" class="form-control" name="transaction_fee" placeholder="Transaction Fee">
                            </div>

                            <div class="col-md-4">
                                <label>Service Fee</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" id="name" class="form-control" name="service_fee" placeholder="Service Fee">
                            </div>

                            <div class="col-md-4">
                                <label>Subtotal</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" id="name" class="form-control" name="subtotal" placeholder="Subtotal">
                            </div>

                            <div class="col-md-4">
                                <label>Transaction Status</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select name="transaction_status" class="form-select" aria-label="Default select example">
                                    <option value="unpaid">Unpaid</option>
                                    <option value="authorize">Authorize</option>
                                    <option value="capture">Capture</option>
                                    <option value="settlement">Settlement</option>
                                    <option value="deny">Deny</option>
                                    <option value="pending">Pending</option>
                                    <option value="cancel">Cancel</option>
                                    <option value="refund">Refund</option>
                                    <option value="partial_refund">Partial_refund</option>
                                    <option value="chargeback">Chargeback</option>
                                    <option value="partial_chargeback">Partial_chargeback</option>
                                    <option value="expire">Expire</option>
                                    <option value="failure">Failure</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Job ID</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select name="job_id" class="form-select" aria-label="Default select example">
                                    @foreach ($jobs as $job)
                                        <option value="{{ $job->id }}">{{ $job->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Company ID</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select name="company_id" class="form-select" aria-label="Default select example">
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Payment Method ID</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select name="payment_method_id" class="form-select" aria-label="Default select example">
                                    @foreach ($paymentMethods as $pm)
                                        <option value="{{ $pm->id }}">{{ $pm->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Worker ID</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select name="worker_id" class="form-select" aria-label="Default select example">
                                    @foreach ($workers as $worker)
                                        <option value="{{ $worker->id }}">{{ $worker->user->name }}</option>
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