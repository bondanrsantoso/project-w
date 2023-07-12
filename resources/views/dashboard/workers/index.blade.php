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
    <h3>Workers</h3>
@endsection

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5>Data Workers</h5>
            </div>
            <div class="card-body">
                <table
                    class="table"
                    id="table1"
                >
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Category ID</th>
                            <th>Address</th>
                            <th>Place And Date of Birth</th>
                            <th>Gender</th>
                            <th>Account Number</th>
                            <th>Balance</th>
                            <th>Eligible For Work</th>
                            <th>Student</th>
                            <th>College</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($workers as $worker)
                            <tr>
                                <td>{{ $worker->user->name }}</td>
                                <td>{{ $worker->category->name }}</td>
                                <td>{{ $worker->address }}</td>
                                <td>{{ $worker->place_of_birth }},{{ $worker->date_of_birth }}</td>
                                <td>{{ $worker->gender }}</td>
                                <td>{{ $worker->account_number }}</td>
                                <td>{{ $worker->balance }}</td>
                                <td class="text-center">
                                    @if ((int) $worker->is_eligible_for_work == 1)
                                        <span class="badge bg-success"><i class="bi bi-check"></i></span>
                                    @else
                                        <span class="badge bg-danger"><i class="bi bi-x"></i></span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ((int) $worker->is_student)
                                        <span class="badge bg-success"><i class="bi bi-check"></i></span>
                                    @else
                                        <span class="badge bg-danger"><i class="bi bi-x"></i></span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{ $worker->college ? $worker->college : '-' }}
                                </td>
                                <td class="d-flex justify-content-start align-items-center">
                                    <a
                                        target="_blank"
                                        href="{{ env('APP_DOMAIN_JOB', 'http://job-admin.docu.web.id') }}/dashboard/workers/{{ $worker->id }}/"
                                        class="btn btn-primary me-2"
                                    >
                                        <span>
                                            <i class="bi bi-person-bounding-box"></i>
                                        </span>
                                    </a>
                                    <a
                                        href="{{ env('APP_DOMAIN_JOB', 'http://job-admin.docu.web.id') }}/dashboard/workers/{{ $worker->id }}/edit"
                                        class="btn btn-success me-2"
                                    >
                                        <span>
                                            <i class="bi bi-pencil-square"></i>
                                        </span>
                                    </a>
                                    <form
                                        action="{{ env('APP_DOMAIN_JOB', 'http://job-admin.docu.web.id') }}/dashboard/workers/{{ $worker->id }}"
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

                {{ $workers->links() }}
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            console.log("aaaa");
            $('#body').css('background-color', '#FFD4B2');
        });
    </script>
@endsection
