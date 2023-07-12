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
    <h3>Questionnaire Sessions</h3>
@endsection

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5>Questionnaire Sessions</h5>
            </div>
            <div class="card-body">
                <table
                    class="table"
                    id="session-table"
                >
                    <thead>
                        <tr>
                            <th>User Name</th>
                            <th>Taken at</th>
                            <th>Last Answered At</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Will be populated by Datatables --}}
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
        const sessionTable = new DataTable(
            "#session-table", {
                ajax: "{{ url('api/questionnaire_sessions/datatables') }}",
                serverSide: true,
                columns: [{
                        name: "user_name",
                        data: "user_name",
                    },
                    {
                        name: "created_at",
                        data: data => dayjs(data.created_at).format("DD MMMM YYYY HH:mm")
                    },
                    {
                        name: "updated_at",
                        data: data => dayjs(data.updated_at).format("DD MMMM YYYY HH:mm")
                    },
                    {
                        name: "id",
                        sortable: false,
                        data: data => `
                            <a
                                href="{{ url('dashboard/questionnaire_sessions') }}/${data.id}"
                                class="btn btn-outline-primary"
                            >
                                <i class="bi-eye"></i>
                            </a>
                        `,
                    }
                ]
            }
        )
    </script>
@endsection
