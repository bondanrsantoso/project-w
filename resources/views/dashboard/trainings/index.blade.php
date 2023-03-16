@extends('dashboard.index')
@section('css')
    <link
        rel="stylesheet"
        type="text/css"
        href="https://cdn.datatables.net/v/bs5/dt-1.13.1/r-2.4.0/sb-1.4.0/datatables.min.css"
    />
@endsection

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
    <h3>Training Events</h3>
@endsection

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5>Training Events</h5>
                <a
                    class="btn btn-primary"
                    {{-- href="{{ env('APP_DOMAIN_PM', 'http://pm-admin.docu.web.id') }}/dashboard/jobs/{{ $jobs[0]->workgroup_id }}/dashboard/jobs/create" --}}
                    href="{{ url('/dashboard/training_events/create') }}"
                >
                    <span>
                        <i class="bi bi-pencil me-2"></i>
                        Create Training
                    </span>
                </a>
            </div>
            {{-- Hidden delete form  --}}
            <form
                method="POST"
                id="delete-form"
            >
                @csrf
                @method('DELETE')
            </form>
            {{-- End hidden delete form --}}
            <div class="card-body">
                {{-- <form
                    action="{{ url()->current() }}"
                    method="get"
                    class="row justify-content-end"
                >
                    <div class="col-12 col-md-4 col-lg-3">
                        <div class="input-group">
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Search here"
                                id="search"
                                name="q"
                            >
                            <button
                                type="submit"
                                class="btn btn-secondary"
                            >Search</button>
                        </div>
                    </div>
                </form> --}}
                <table
                    class="table"
                    id="training-table"
                >
                    <thead>
                        <tr>
                            {{-- <th></th> --}}
                            <th>Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Location</th>
                            <th>Sessions</th>
                            <th>Seat</th>
                            <th>Category</th>
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
    <script
        type="text/javascript"
        src="https://cdn.datatables.net/v/bs5/dt-1.13.1/r-2.4.0/sb-1.4.0/datatables.min.js"
    ></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
    <script>
        const trainingTable = new DataTable("#training-table", {
            ajax: "{{ url('/api/training_events/datatables') }}",
            serverSide: true,
            columns: [{
                    name: "name",
                    data: data =>
                        `
                            <a href="{{ url('/dashboard/training_events') }}/${data.id}">${data.name}</a>
                        `
                },
                // {
                //     name: "description",
                //     sortable: false,
                //     data: data => data.description.length > 25 ?
                //         (data.description.substr(0, 23) + "...") : data.description
                // },
                {
                    name: "start_date",
                    data: data => dayjs(data.start_date).format("DD MMMM YYYY HH:mm")
                },
                {
                    name: "end_date",
                    data: data => dayjs(data.end_date).format("DD MMMM YYYY HH:mm")
                },
                {
                    name: "location",
                    sortable: false,
                    data: "location",
                },
                {
                    name: "sessions",
                    data: "sessions",
                },
                {
                    name: "seat",
                    data: data => {
                        let display = `${data.total_approved}/${data.seat}`
                        if (data.total_approved > data.seat) {
                            display = display + " ⚠";
                        }
                        return display
                    }
                },
                {
                    name: "category_id",
                    data: "category.name",
                },
                {
                    name: "id",
                    data: data => `
                        <button
                            type="submit"
                            class="btn btn-danger btn-delete"
                            form="delete-form"
                            formaction="{{ url('dashboard/training_events') }}/${data.id}">
                            <span class="bi-trash"></span>
                        </button>
                    `,
                },
            ],
        });

        trainingTable.on('draw', () => {
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
        });
    </script>
    <script>
        $(document).ready(function() {
            const randomColor = Math.random() * parseInt("ffffff", 16)
            $('#body').css('background-color', `#${randomColor.toString(16)}`);
        });
    </script>
@endsection
