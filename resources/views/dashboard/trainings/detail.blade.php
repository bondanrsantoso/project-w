@extends('dashboard.index')

@section('header')
    <h3>Detail Training</h3>
@endsection

@section('content')
    <section id="training-event">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <img
                            src="{{ $training->image_url ?? 'https://placehold.jp/3d4070/ffffff/600x600.jpg?text=Upload%20image' }}"
                            alt="Event image thumbnail"
                            class="w-100"
                            id="preview-image"
                            style="max-width: 600px; max-height: 400px; object-fit: contain;"
                        >
                    </div>
                    <div class="col-12 col-lg-8">
                        <h1>{{ $training->name }}</h1>
                        <a
                            href="{{ url('/dashboard/training_events/' . $training->id . '/edit') }}"
                            class="btn btn-primary mb-2"
                        >
                            <span class="bi-pencil me-2"></span>
                            Edit Training
                        </a>
                        <p class="mb-3">
                            {{ $training->category->name }}
                        </p>
                        <p>
                            <span class="bi-clock me-1"></span>
                            {{ (new DateTime($training->start_date))->format('d M Y H:i') }}
                            -
                            {{ (new DateTime($training->end_date))->format('d M Y H:i') }}
                        </p>
                        <p>
                            <span class="bi-geo me-1"></span>
                            {{ $training->location }}
                        </p>
                        <p>
                            <span class="bi-journal"></span>
                            {{ $training->sessions }}
                            sessions
                        </p>
                        <p>
                            <span class="bi-person"></span>
                            Capacity:
                            <strong>{{ $training->seat }}</strong>,
                            Registered:
                            <strong>
                                {{ $training->participation()->count() }}
                                {{-- @if ($training->participation()->count() > $training->seat)
                                    ⚠ ()
                                @endif --}}
                            </strong>,
                            Approved:
                            <strong>
                                {{ $training->participation()->where('is_approved', true)->count() }}
                                @if ($training->participation()->where('is_approved', true)->count() > $training->seat)
                                    ⚠ (overcapacity, max: {{ $training->seat }})
                                @endif
                            </strong>
                        </p>
                        <p class="lead mt-3">
                            {{ $training->description }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="card mt-3">
        <div class="card-body">
            <h2>Participants</h2>
            <table
                class="table"
                id="training-table"
            >
                <thead>
                    <tr>
                        <th>User Name</th>
                        <th>Training</th>
                        <th>Approved</th>
                        <th>Pretest Grade/Score</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Reserved for datatable --}}
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script
        type="text/javascript"
        src="https://cdn.datatables.net/v/bs5/dt-1.13.1/r-2.4.0/sb-1.4.0/datatables.min.js"
    ></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
    <script>
        const trainingTable = new DataTable("#training-table", {
            ajax: "{{ url('/api/training_events/' . $training->id . '/training_event_participants/datatables') }}",
            serverSide: true,
            columns: [{
                    name: "user_id",
                    data: "user.name",
                },
                {
                    name: "event_id",
                    data: "event.name",
                    visible: false,
                    search: {
                        value: {{ $training->id }},
                    }
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
                    name: "pretest_raw_grade",
                    data: data => `
                        ${data.pretest_raw_grade || 0}%
                        ${data.pretest_grade_override? "("+data.pretest_grade_override+"% corrected)" : ""}
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

            const csrfToken = "{{ csrf_token() }}"
            const baseUrl = "{{ url('/dashboard/training_event_participants') }}/";

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
        $(document).ready(function() {
            console.log("aaaa");
            $('#body').css('background-color', '#678983');
        });
    </script>
@endsection
