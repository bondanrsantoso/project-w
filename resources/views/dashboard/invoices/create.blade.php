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
                        action="{{ env('APP_DOMAIN_PM', 'http://pm-admin.docu.web.id') }}/dashboard/invoices"
                        method="POST"
                    >
                        @csrf
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Transaction Fee</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input
                                            type="text"
                                            id="name"
                                            class="form-control"
                                            name="transaction_fee"
                                            placeholder="Transaction Fee"
                                            required
                                            value="0"
                                        >
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label>Service Fee</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input
                                            type="text"
                                            id="name"
                                            class="form-control"
                                            name="service_fee"
                                            placeholder="Service Fee"
                                            required
                                            value="0"
                                        >
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label>Subtotal</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input
                                            type="text"
                                            id="name"
                                            class="form-control"
                                            name="subtotal"
                                            placeholder="Subtotal"
                                            required
                                            value="0"
                                        >
                                    </div>
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
                                        <option value="">Select Company</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}">{{ $company->name }}</option>
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
