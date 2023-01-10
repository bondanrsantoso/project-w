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
    <h3>Workgroups</h3>
@endsection

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5>Data Workgroups</h5>
                @php

                @endphp
                <a
                    class="btn btn-primary"
                    href="{{ $route }}"
                >
                    <span>
                        <i class="bi bi-pencil me-2"></i>
                        Create Workgroup
                    </span>
                </a>
            </div>
            <div class="card-body">
                <table
                    class="table"
                    id="table1"
                >
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Project ID</th>
                            <th>Allocated Budget</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($workgroups as $workgroup)
                            <tr>
                                <td>{{ $workgroup->name }}</td>
                                <td>{{ $workgroup->project->name }}</td>
                                <td>
                                    Rp{{ number_format($workgroup->jobs()->sum('budget'), 0, ',', '.') }}
                                    <em>
                                        ({{ $workgroup->jobs()->count('id') }} jobs)
                                    </em>
                                </td>
                                <td>{{ $workgroup->created_at }}</td>
                                <td class="d-flex justify-content-start align-items-center">
                                    <a
                                        href="{{ env('APP_DOMAIN_PM', 'http://pm-admin.docu.web.id') }}/dashboard/workgroups/{{ $workgroup->id }}/jobs"
                                        class="btn btn-primary  me-2"
                                    >
                                        <span>
                                            <i class="bi bi-briefcase"></i>
                                        </span>
                                    </a>
                                    <a
                                        href="{{ env('APP_DOMAIN_PM', 'http://pm-admin.docu.web.id') }}/dashboard/workgroups/{{ $workgroup->id }}/edit"
                                        class="btn btn-success  me-2"
                                    >
                                        <span>
                                            <i class="bi bi-pencil-square"></i>
                                        </span>
                                    </a>
                                    <form
                                        action="{{ env('APP_DOMAIN_PM', 'http://pm-admin.docu.web.id') }}/dashboard/workgroups/{{ $workgroup->id }}"
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
                        @if ($project)
                            <tr>
                                <td
                                    colspan="2"
                                    class="text-end"
                                >
                                    Allocated Budget
                                </td>
                                <td colspan="3">
                                    <em>
                                        Rp{{ number_format($project->allocated_fund, 0, ',', '.') }}
                                    </em>
                                </td>
                            </tr>
                            <tr>
                                <td
                                    colspan="2"
                                    class="text-end"
                                >
                                    Remaining Budget
                                </td>
                                <td colspan="3">
                                    <em>
                                        Rp{{ number_format($project->budget - $project->allocated_fund, 0, ',', '.') }}
                                    </em>
                                </td>
                            </tr>
                            <tr>
                                <td
                                    colspan="2"
                                    class="text-end"
                                >
                                    Total Available Budget
                                </td>
                                <td colspan="3">
                                    <strong>
                                        Rp{{ number_format($project->budget, 0, ',', '.') }}
                                    </strong>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                {{ $workgroups->links() }}

            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            console.log("aaaa");
            $('#body').css('background-color', '#FCF9BE');
        });
    </script>
@endsection
