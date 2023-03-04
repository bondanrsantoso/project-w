@extends('dashboard.index')
@section('css')
<link rel="stylesheet" type="text/css"
    href="https://cdn.datatables.net/v/bs5/dt-1.13.1/r-2.4.0/sb-1.4.0/datatables.min.css" />
@endsection

@section('header')
@if (Session::has('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
<h3>Training Participants</h3>
@endsection

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5>Training Participants</h5>
            <a class="btn btn-primary" {{--
                href="{{ env('APP_DOMAIN_PM', 'http://pm-admin.docu.web.id') }}/dashboard/jobs/{{ $jobs[0]->workgroup_id }}/dashboard/jobs/create"
                --}} href="{{ url('/dashboard/training_event_participants/create') }}">
                <span>
                    <i class="bi bi-pencil me-2"></i>
                    Add Participant
                </span>
            </a>
        </div>

        {{-- Hidden delete form --}}
        <form method="POST" id="delete-form">
            @csrf
            @method('DELETE')
        </form>
        {{-- End hidden delete form --}}

        <div class="card-body">
            <table class="table" id="training-table">
                <thead>
                    <tr>
                        <th>User Name</th>
                        <th>Training</th>
                        <th>Approved</th>
                        <th>Confirmed</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Reserved for datatable --}}
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script type="text/javascript"
    src="https://cdn.datatables.net/v/bs5/dt-1.13.1/r-2.4.0/sb-1.4.0/datatables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
<script>
    const trainingTable = new DataTable("#training-table", {
        ajax: "{{ url('/api/training_event_participants/datatables') }}",
        serverSide: true,
        columns: [{
            name: "user_id",
            data: "user.name",
        },
        {
            name: "event_id",
            data: "event.name",
        },

        {
            name: "is_approved",
            data: data => `
                        <input
                            type="checkbox"
                            ${data.is_approved ? "checked" : ""}
                            class="form-check-input check-approved">
                    `
        },
        {
            name: "is_confirmed",
            data: data => `
                        <input
                            type="checkbox"
                            ${data.is_confirmed ? "checked" : ""}
                            class="form-check-input check-confirmed">
                    `
        },
        {
            name: "id",
            data: data => `
                        <div class="btn-group">
                            <a
                                href="{{ url('/dashboard/training_event-participants') }}/${data.id}/edit"
                                class="btn btn-secondary"
                            ><span class="bi-pencil"></span></a>
                            <button
                                type="submit"
                                class="btn btn-danger btn-delete"
                                form="delete-form"
                                formaction="{{ url('dashboard/training_events') }}/${data.id}">
                                <span class="bi-trash"></span>
                            </button>
                        </div>
                    `,
            sortable: false,
        },
        ],
    });

    trainingTable.on('draw', () => {
        console.log("table redrawn");

        let clearToDelete = false;
        const tableBody = trainingTable.body()[0];

        const deleteButtons = tableBody.querySelectorAll(".btn-delete");

        let i = 0;
        for (const btn of deleteButtons) {
            const data = trainingTable.data()[i];

            btn.addEventListener("click", e => {
                if (!clearToDelete) {
                    e.preventDefault();

                    clearToDelete = confirm(`Delete Training: ${data.name}?`);

                    if (clearToDelete) {
                        btn.click();
                    }

                }
            });

            i++;
        }

        const approveChecks = tableBody.querySelectorAll(".check-approved");

        const csrfToken = "{{csrf_token()}}"
        const baseUrl = "{{url('/dashboard/training_event_participants')}}/";

        i = 0;
        for (const approveCheck of approveChecks) {
            const data = trainingTable.data()[i];

            approveCheck.addEventListener("change", e => {
                const updateUrl = new URL(data.id, baseUrl);
                approveCheck.classList.add("indeterminate");
                approveCheck.disabled = true;

                fetch(updateUrl, {
                    method: "PUT",
                    body: JSON.stringify({
                        _token: csrfToken,
                        is_approved: e.target.checked ? true : false
                    }),
                    headers: {
                        Accept: "application/json",
                        "Content-Type": "application/json",
                    }
                }).then(res => {
                    // Do nothing
                }).finally(() => {
                    trainingTable.ajax.reload(null, false);
                })
            });

            i++;
        }
        const confirmChecks = tableBody.querySelectorAll(".check-confirmed");

        i = 0;
        for (const confirmCheck of confirmChecks) {
            const data = trainingTable.data()[i];

            confirmCheck.addEventListener("change", e => {
                const updateUrl = new URL(data.id, baseUrl);
                confirmCheck.classList.add("indeterminate");
                confirmCheck.disabled = true;

                fetch(updateUrl, {
                    method: "PUT",
                    body: JSON.stringify({
                        _token: csrfToken,
                        is_confirmed: e.target.checked ? true : false
                    }),
                    headers: {
                        Accept: "application/json",
                        "Content-Type": "application/json",
                    }
                }).then(res => {
                    // Do nothing
                }).finally(() => {
                    trainingTable.ajax.reload(null, false);
                })
            });

            i++;
        }
    });
</script>
<script>
    $(document).ready(function () {
        const randomColor = Math.random() * parseInt("ffffff", 16)
        $('#body').css('background-color', `#${randomColor.toString(16)}`);
    });
</script>
@endsection