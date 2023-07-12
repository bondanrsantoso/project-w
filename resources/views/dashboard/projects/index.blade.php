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
    <h3>Projects</h3>
@endsection

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5>Data Projects</h5>

                <a
                    href="{{ env('APP_DOMAIN_PM', 'http://pm-admin.docu.web.id') }}/dashboard/projects/create"
                    class="btn btn-primary me-2"
                >
                    <span class="me-2">
                        <i class="bi bi-plus"></i>
                    </span>
                    Create Project
                </a>
            </div>
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
                            <th>Company Name</th>
                            <th>Total Budget</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($projects as $project)
                            <tr>
                                <td>{{ $project->name }}</td>
                                <td>{{ Str::limit($project->description, 50) }}</td>
                                <td>{{ !!$project->company ? $project->company->name : '-' }}</td>
                                <td>
                                    Rp.{{ number_format($project->budget, 0, ',', '.') }}
                                </td>
                                <td>{{ $project->created_at }}</td>
                                <td class="d-flex justify-content-start align-items-center">
                                    <a
                                        href="{{ env('APP_DOMAIN_PM', 'http://pm-admin.docu.web.id') }}/dashboard/projects/{{ $project->id }}/workgroups"
                                        class="btn btn-primary  me-2"
                                    >
                                        <span>
                                            <i class="bi bi-people"></i>
                                        </span>
                                    </a>
                                    <a
                                        href="{{ env('APP_DOMAIN_PM', 'http://pm-admin.docu.web.id') }}/dashboard/projects/{{ $project->id }}/edit"
                                        class="btn btn-success  me-2"
                                    >
                                        <span>
                                            <i class="bi bi-pencil-square"></i>
                                        </span>
                                    </a>
                                    <form
                                        action="{{ env('APP_DOMAIN_PM', 'http://pm-admin.docu.web.id') }}/dashboard/projects/{{ $project->id }}"
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

                {{ $projects->links() }}
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            console.log("aaaa");
            $('#body').css('background-color', '#ADA2FF');
        });
    </script>
@endsection
