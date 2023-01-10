@extends('dashboard.index')

@section('header')
    <h3>Create Invoice</h3>
@endsection

@section('content')
    <section id="basic-horizontal-layouts">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <form
                        class="form form-horizontal"
                        action="{{ env('APP_DOMAIN_PM', 'http://pm-admin.docu.web.id') }}/dashboard/invoices/{{ $invoice->id }}"
                        method="POST"
                    >
                        @csrf

                        <input
                            type="hidden"
                            name="_method"
                            value="PUT"
                        >
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Transaction Fee</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input
                                        type="text"
                                        id="name"
                                        class="form-control"
                                        name="transaction_fee"
                                        placeholder="Transaction Fee"
                                        value="{{ $invoice->transaction_fee }}"
                                    >
                                </div>

                                <div class="col-md-4">
                                    <label>Service Fee</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input
                                        type="text"
                                        id="name"
                                        class="form-control"
                                        name="service_fee"
                                        placeholder="Service Fee"
                                        value="{{ $invoice->service_fee }}"
                                    >
                                </div>

                                <div class="col-md-4">
                                    <label>Subtotal</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input
                                        type="text"
                                        id="name"
                                        class="form-control"
                                        name="subtotal"
                                        placeholder="Subtotal"
                                        value="{{ $invoice->subtotal }}"
                                    >
                                </div>

                                <div class="col-md-4">
                                    <label>Transaction Status</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <select
                                        name="transaction_status"
                                        class="form-select"
                                        aria-label="Default select example"
                                    >
                                        <option
                                            {{ $invoice->transaction_status == 'unpaid' ? 'selected' : '' }}
                                            value="unpaid"
                                        >Unpaid</option>
                                        <option
                                            {{ $invoice->transaction_status == 'authorize' ? 'selected' : '' }}
                                            value="authorize"
                                        >Authorize</option>
                                        <option
                                            {{ $invoice->transaction_status == 'capture' ? 'selected' : '' }}
                                            value="capture"
                                        >Capture</option>
                                        <option
                                            {{ $invoice->transaction_status == 'settlement' ? 'selected' : '' }}
                                            value="settlement"
                                        >Settlement</option>
                                        <option
                                            {{ $invoice->transaction_status == 'deny' ? 'selected' : '' }}
                                            value="deny"
                                        >Deny</option>
                                        <option
                                            {{ $invoice->transaction_status == 'pending' ? 'selected' : '' }}
                                            value="pending"
                                        >Pending</option>
                                        <option
                                            {{ $invoice->transaction_status == 'cancel' ? 'selected' : '' }}
                                            value="cancel"
                                        >Cancel</option>
                                        <option
                                            {{ $invoice->transaction_status == 'refund' ? 'selected' : '' }}
                                            value="refund"
                                        >Refund</option>
                                        <option
                                            {{ $invoice->transaction_status == 'partial_refund' ? 'selected' : '' }}
                                            value="partial_refund"
                                        >Partial_refund</option>
                                        <option
                                            {{ $invoice->transaction_status == 'chargeback' ? 'selected' : '' }}
                                            value="chargeback"
                                        >Chargeback</option>
                                        <option
                                            {{ $invoice->transaction_status == 'partial_chargeback' ? 'selected' : '' }}
                                            value="partial_chargeback"
                                        >Partial_chargeback</option>
                                        <option
                                            {{ $invoice->transaction_status == 'expire' ? 'selected' : '' }}
                                            value="expire"
                                        >Expire</option>
                                        <option
                                            {{ $invoice->transaction_status == 'failure' ? 'selected' : '' }}
                                            value="failure"
                                        >Failure</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label>Company</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <select
                                        name="company_id"
                                        id="company_select"
                                        class="form-select"
                                        aria-label="Default select example"
                                        required
                                    >
                                        @foreach ($companies as $company)
                                            <option
                                                @selected($company->id == $invoice->company_id)
                                                value="{{ $company->id }}"
                                            >{{ $company->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="project_select">Project</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <select
                                        required
                                        name="project_id"
                                        id="project_select"
                                        class="form-select"
                                        aria-label="Default select example"
                                    >
                                        @foreach ($invoice->company->projects as $project)
                                            <option
                                                value="{{ $project->id }}"
                                                @selected($project->id == $invoice->project_id)
                                            >{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-12 d-flex justify-content-end">
                                    <button
                                        type="submit"
                                        class="btn btn-primary btn-lg me-1 px-3 mb-1"
                                    >Submit</button>
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
    <script>
        const companySelector = document.getElementById("company_select");
        const projectSelector = document.getElementById("project_select");
        companySelector.addEventListener("change", e => {
            const selectedId = companySelector.value;
            console.log({
                selectedId
            });
            if (selectedId) {
                projectSelector.innerHTML = "<option selected disabled>Loading...</option>";

                const fetchUrl = "{{ url('/api/projects') }}?paginate=999999&filter[company_id]=" + selectedId;
                fetch(fetchUrl, {
                    headers: {
                        Accept: "application/json"
                    }
                }).then(res => {
                    if (!res.ok) {
                        throw {
                            message: "Error when fetching",
                            code: res.status,
                            data: res
                        };
                    }
                    return res.json()
                }).then(response => {
                    console.table(response.data);
                    const {
                        data
                    } = response;

                    projectSelector.innerHTML = "";
                    for (const item of data) {
                        const option = document.createElement("option");
                        option.value = item.id;
                        option.textContent = item.name;

                        projectSelector.appendChild(option);
                    }
                }).catch(e => {
                    console.error(e);
                    projectSelector.innerHTML = "<option selected disabled>Error</option>";
                })
            }
        })
    </script>
@endsection
