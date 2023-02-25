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
    <h3>Training Tests</h3>
@endsection

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5>Training Tests</h5>
                <a
                    class="btn btn-primary"
                    {{-- href="{{ env('APP_DOMAIN_PM', 'http://pm-admin.docu.web.id') }}/dashboard/jobs/{{ $jobs[0]->workgroup_id }}/dashboard/jobs/create" --}}
                    href="{{ url('/dashboard/training_tests/create') }}"
                >
                    <span>
                        <i class="bi bi-pencil me-2"></i>
                        Create Training Test
                    </span>
                </a>
            </div>
            {{-- Hidden delete form  --}}
            <form
                method="POST"
                id="delete-form"
            >
                @csrf
                <input
                    type="hidden"
                    name="_method"
                    value="DELETE"
                />
            </form>
            {{-- End hidden delete form --}}
            <div class="card-body">
                <table
                    class="table"
                    id="training-test-table"
                >
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Pretest</th>
                            <th>Description</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Duration</th>
                            <th>Attempt Limit</th>
                            <th>Passing Grade</th>
                            <th>Training</th>
                            <th>Order</th>
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

    <form
        method="post"
        class="d-none"
        id="delete-form"
    >
        @csrf
        @method('DELETE')
    </form>
@endsection

@section('scripts')
    <script
        type="text/javascript"
        src="https://cdn.datatables.net/v/bs5/dt-1.13.1/r-2.4.0/sb-1.4.0/datatables.min.js"
    ></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
    <script>
        const testTable = new DataTable("#training-test-table", {
            ajax: "{{ url('/api/training_tests/datatables') }}",
            serverSide: true,
            columns: [{
                    name: "title",
                    data: data =>
                        `
                            <a href="{{ url('/dashboard/training_tests') }}/${data.id}/edit">${data.title}</a>
                        `
                },
                {
                    name: "is_pretest",
                    data: data => data.is_pretest ? `
                        <div class="badge rounded-pill text-bg-primary">Pretest</div>
                    ` : "",
                },
                {
                    name: "description",
                    sortable: false,
                    data: data => data.description.length > 25 ?
                        (data.description.substr(0, 23) + "...") : data.description
                },
                {
                    name: "start_time",
                    data: data => dayjs(data.start_time).format("DD MMMM YYYY HH:mm")
                },
                {
                    name: "end_time",
                    data: data => dayjs(data.end_time).format("DD MMMM YYYY HH:mm")
                },
                {
                    name: "duration",
                    data: data => `${data.duration} minutes`,
                },
                {
                    name: "attempt_limit",
                    data: data => `${data.attempt_limit || "unlimited "}x`,
                },
                {
                    name: "passing_grade",
                    data: data => `${data.passing_grade}%`,
                },
                {
                    name: "training_id",
                    data: "training_item.name",
                },
                {
                    name: "order",
                    data: "order"
                },
                {
                    name: "id",
                    data: data => `
                        <button
                            type="submit"
                            class="btn btn-danger btn-delete"
                            form="delete-form"
                            formaction="{{ url('dashboard/training_tests') }}/${data.id}">
                            <span class="bi-trash"></span>
                        </button>
                    `,
                },
            ],
        });

        testTable.on('draw', () => {
            let clearToDelete = false;
            const tableBody = testTable.body()[0];

            const deleteButtons = tableBody.querySelectorAll(".btn-delete");

            let i = 0;
            for (const btn of deleteButtons) {
                const data = testTable.data()[i];

                btn.addEventListener("click", e => {
                    if (!clearToDelete) {
                        e.preventDefault();

                        clearToDelete = confirm(`Delete Test: ${data.title}?`);

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
