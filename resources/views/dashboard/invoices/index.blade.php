@extends('dashboard.index')

@section('header')
    @if (Session::has('success'))
        <div
            class="alert alert-success alert-dismissible fade show"
            role="alert"
        >
            {{ session('success') }}
            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"
            ></button>
        </div>
    @endif
    <h3>Invoices</h3>
@endsection

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5>Data Invoices</h5>

                <a
                    href="{{ env('APP_DOMAIN_PM', 'http://pm-admin.docu.web.id') }}/dashboard/invoices/create"
                    class="btn btn-primary  me-2"
                >
                    <span class="me-2">
                        <i class="bi bi-plus"></i>
                    </span>
                    Create Invoice
                </a>
            </div>
            <div class="card-body">
                <table
                    class="table"
                    id="table1"
                >
                    <thead>
                        <tr>
                            <th>VA Number</th>
                            <th>Trasaction Fee</th>
                            <th>Service Fee</th>
                            <th>Subtotal</th>
                            <th>Grand Total</th>
                            <th>Transaction Status</th>
                            <th>Actions</th>
                            <th>Job</th>
                            <th>Project</th>
                            <th>Company ID</th>
                            <th>Payment Method ID</th>
                            <th>Worker ID</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $invoice)
                            <tr>
                                <td>{{ $invoice->va_number ?? '-' }}</td>
                                <td>{{ $invoice->transaction_fee }}</td>
                                <td>{{ $invoice->service_fee }}</td>
                                <td>{{ $invoice->subtotal }}</td>
                                <td>{{ $invoice->grand_total }}</td>
                                <td>{{ $invoice->transaction_status }}</td>
                                <td>{{ $invoice->actions }}</td>
                                <td>{{ $invoice->job ? $invoice->job->name : '-' }}</td>
                                <td>{{ $invoice->project ? $invoice->project->name : '-' }}</td>
                                <td>{{ $invoice->company->name }}</td>
                                <td>{{ $invoice->payment_method ? $invoice->payment_method->name : '-' }}</td>
                                <td>{{ $invoice->worker ? $invoice->worker->user->name : '-' }}</td>
                                <td>{{ $invoice->created_at }}</td>
                                <td class="d-flex justify-content-start align-items-center">
                                    <a
                                        href="{{ env('APP_DOMAIN_PM', 'http://pm-admin.docu.web.id') }}/dashboard/invoices/{{ $invoice->id }}/edit"
                                        class="btn btn-success  me-2"
                                    >
                                        <span>
                                            <i class="bi bi-pencil-square"></i>
                                        </span>
                                    </a>
                                    <form
                                        action="{{ env('APP_DOMAIN_PM', 'http://pm-admin.docu.web.id') }}/dashboard/invoices/{{ $invoice->id }}"
                                        method="POST"
                                    >
                                        @csrf
                                        <input
                                            type="hidden"
                                            name="_method"
                                            value="DELETE"
                                        />
                                        <button
                                            type="submit"
                                            class="btn btn-danger"
                                        >
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $invoices->links() }}
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
