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
    <h3>Jobs Applications</h3>
@endsection

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5>Data Jobs Applcations</h5>
                @if ($jobApplications[0] && request()->is('dashboard/workgroups*'))
                    <a
                        class="btn btn-primary"
                        href="{{ env('APP_DOMAIN_JOB', 'http://job-admin.docu.web.id') }}/dashboard/job-applications/{{ $jobApplications[0]->workgroup_id }}/jobs/create"
                    >
                        <span>
                            <i class="bi bi-pencil me-2"></i>
                            Create Job Application
                        </span>
                    </a>
                @else
                    <a
                        class="btn btn-primary"
                        href="{{ env('APP_DOMAIN_JOB', 'http://job-admin.docu.web.id') }}/dashboard/job-applications/create"
                    >
                        <span>
                            <i class="bi bi-pencil me-2"></i>
                            Create Job Application
                        </span>
                    </a>
                @endif
            </div>
            <div class="card-body">
                <table
                    class="table"
                    id="table1"
                >
                    <thead>
                        <tr>
                            <th>Worker ID</th>
                            <th>Job ID</th>
                            <th>is Hired</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jobApplications as $jobap)
                            <tr>
                                <td>{{ $jobap->worker->user->name }}</td>
                                <td>{{ $jobap->job->name }}</td>
                                <td>
                                    @if ($jobap->is_hired == 0)
                                        <span class="badge text-bg-danger">false</span>
                                    @else
                                        <span class="badge text-bg-success">true</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($jobap->status == 'pending')
                                        <span class="badge text-bg-warning">Pending</span>
                                    @endif
                                    @if ($jobap->status == 'on_progress')
                                        <span class="badge text-bg-secondary">On Progress</span>
                                    @endif
                                    @if ($jobap->status == 'done')
                                        <span class="badge text-bg-success">Done</span>
                                    @endif
                                    @if ($jobap->status == 'cancelled')
                                        <span class="badge text-bg-danger">Cancelled</span>
                                    @endif
                                </td>
                                <td class="d-flex justify-content-start align-items-center">
                                    <a
                                        href="{{ env('APP_DOMAIN_JOB', 'http://job-admin.docu.web.id') }}/dashboard/job-applications/{{ $jobap->id }}/edit"
                                        class="btn btn-success me-2"
                                    >
                                        <span>
                                            <i class="bi bi-pencil-square"></i>
                                        </span>
                                    </a>
                                    <form
                                        action="{{ env('APP_DOMAIN_JOB', 'http://job-admin.docu.web.id') }}/dashboard/job-applications/{{ $jobap->id }}"
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

                {{ $jobApplications->links() }}
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            console.log("aaaa");
            $('#body').css('background-color', '#CE7777');
        });
    </script>
@endsection
