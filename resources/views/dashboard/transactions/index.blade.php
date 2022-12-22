@extends('dashboard.index')

@section('header')
@if(Session::has('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif
<h3>Transactions</h3>
@endsection

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5>Data Transactions</h5>
        </div>
        <div class="card-body">
            <table class="table" id="table1">
                <thead>
                    <tr>
                        <th>Invoice ID</th>
                        <th>Transaction Status</th>
                        <th>Fraud Status</th>
                        <th>Payment Type</th>
                        <th>Transaction Time</th>
                        <th>Gross Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $tc)
                        <td>{{ $tc->invoice_id }}</td>
                        <td>{{ $tc->transaction_status }}</td>
                        <td>{{ $tc->fraud_status }}</td>
                        <td>{{ $tc->payment_type }}</td>
                        <td>{{ $tc->transaction_time }}</td>
                        <td>{{ $tc->gross_amount }}</td>
                    @endforeach
                </tbody>
            </table>

            {{ $transactions->links() }}
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        console.log("aaaa");
        $('#body').css('background-color', '#C0DEFF');
    });
</script>
@endsection
