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
    <h3>Jobs</h3>
@endsection

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5>Data Jobs</h5>
                @if ($jobs[0] && request()->is('dashboard/workgroups*'))
                    <a
                        class="btn btn-primary"
                        href="{{ url('/dashboard/workgroups') }}/{{ $jobs[0]->workgroup_id }}/dashboard/jobs/create"
                    >
                        <span>
                            <i class="bi bi-pencil me-2"></i>
                            Create Job
                        </span>
                    </a>
                @else
                    <a
                        class="btn btn-primary"
                        href="{{ url('/dashboard/jobs/create') }}"
                    >
                        <span>
                            <i class="bi bi-pencil me-2"></i>
                            Create Job
                        </span>
                    </a>
                @endif
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
                <form
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
                </form>
                <table
                    class="table"
                    id="table1"
                >
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Workgroup ID</th>
                            <th>Order</th>
                            <th>Budget</th>
                            <th>Status</th>
                            <th>Is Public</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jobs as $job)
                            <tr>
                                <td>{{ $job->name }}</td>
                                <td>{{ Str::limit($job->description, 50) }}</td>
                                <td>{{ $job->workgroup->name }}</td>
                                <td>{{ $job->order }}</td>
                                <td>{{ number_format($job->budget, 0, ',', '.') }}</td>
                                <td>
                                    @if ($job->status == 'done')
                                        <span class="badge bg-success">{{ $job->status }}</span>
                                    @endif
                                    @if ($job->status == 'canceled')
                                        <span class="badge bg-danger">{{ $job->status }}</span>
                                    @endif
                                    @if ($job->status == 'pending')
                                        <span class="badge bg-warning">{{ $job->status }}</span>
                                    @endif
                                    @if ($job->status == 'on_progress')
                                        <span class="badge bg-primary">{{ $job->status }}</span>
                                    @endif
                                    @if ($job->status == 'paid')
                                        <span class="badge bg-secondary">{{ $job->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($job->is_public == 1)
                                        <span class="badge bg-success"><i class="bi bi-check"></i></span>
                                    @else
                                        <span class="badge bg-danger"><i class="bi bi-x"></i></span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a
                                            href="{{ url('dashboard/jobs/' . $job->id . '/job_applications') }}"
                                            class="btn btn-primary"
                                        >
                                            <span>
                                                <i class="bi bi-file-text"></i>
                                            </span>
                                        </a>
                                        <a
                                            href="{{ env('APP_DOMAIN_PM', 'http://pm-admin.docu.web.id') }}/dashboard/jobs/{{ $job->id }}/edit"
                                            class="btn btn-success"
                                        >
                                            <span>
                                                <i class="bi bi-pencil-square"></i>
                                            </span>
                                        </a>
                                        <button
                                            type="submit"
                                            class="btn btn-danger"
                                            form="delete-form"
                                            formaction="{{ env('APP_DOMAIN_PM', 'http://pm-admin.docu.web.id') }}/dashboard/jobs/{{ $job->id }}"
                                        >
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @if ($workgroup)
                            {{-- If the job list is filtered by a workgroup --}}
                            <tr>
                                <td
                                    colspan="4"
                                    class="text-end"
                                >
                                    Total
                                </td>
                                <td colspan="4">
                                    <em>
                                        Rp{{ number_format($workgroup->jobs()->sum('budget'), 0, ',', '.') }}
                                    </em>
                                </td>
                            </tr>
                            <tr>
                                <td
                                    colspan="4"
                                    class="text-end"
                                >
                                    Budget spent in other workgroup(s)
                                </td>
                                <td colspan="4">
                                    <em>
                                        Rp{{ number_format($workgroup->project->allocated_fund - $workgroup->jobs()->sum('budget'), 0, ',', '.') }}
                                    </em>
                                </td>
                            </tr>
                            <tr>
                                <td
                                    colspan="4"
                                    class="text-end"
                                >
                                    Total Available Budget
                                </td>
                                <td colspan="4">
                                    <em>
                                        Rp{{ number_format($workgroup->project->budget, 0, ',', '.') }}
                                    </em>
                                </td>
                            </tr>
                            <tr>
                                <td
                                    colspan="4"
                                    class="text-end"
                                >
                                    Remaining Budget
                                </td>
                                <td colspan="4">
                                    <strong>
                                        Rp{{ number_Format($workgroup->project->budget - $workgroup->project->allocated_fund, 0, ',', '.') }}
                                    </strong>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                {{ $jobs->links() }}
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            console.log("aaaa");
            $('#body').css('background-color', '#678983');
        });
    </script>
@endsection
