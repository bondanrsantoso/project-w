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
    <h3>Data Worker {{ $worker->user->name }}</h3>
@endsection

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="row row-gap-2">
                    @foreach ($jobAggregates as $jobCount)
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="card">
                                <div class="card-body">
                                    <span class="display-5">
                                        {{ $jobCount['count'] }}
                                    </span>
                                    <span>Job(s) {{ $jobCount['status'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Job List</h5>
                    </div>
                    <div class="card-body">
                        <table
                            class="table"
                            id="job-table"
                        >
                            <thead>
                                <tr>
                                    <th>Job Name</th>
                                    <th>Workgroup</th>
                                    <th>Budget</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Reserve for datatables --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5>Data Experiences</h5>
                    </div>
                    <div class="card-body">
                        <table
                            class="table"
                            id="table1"
                        >
                            <thead>
                                <tr>
                                    <th>Organization</th>
                                    <th>Position</th>
                                    <th>Date Start</th>
                                    <th>Date End</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($worker->experiences as $exp)
                                    <tr>
                                        <td>{{ $exp->organization }}</td>
                                        <td>{{ $exp->position }}</td>
                                        <td>{{ $exp->date_start }}</td>
                                        <td>{{ $exp->date_end }}</td>
                                        <td>{{ $exp->description }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5>Data Portfolios</h5>
                    </div>
                    <div class="card-body">
                        <table
                            class="table"
                            id="table1"
                        >
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Link Url</th>
                                    <th>Attachment Url</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($worker->portofolios as $prt)
                                    <tr>
                                        <td>{{ $prt->title }}</td>
                                        <td>{{ Str::limit($prt->description, 50) }}</td>
                                        <td>
                                            @if ($prt->link_url)
                                                <a
                                                    class="btn btn-primary"
                                                    href="{{ $prt->link_url }}"
                                                >Link Porto</a>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($prt->attachment_url)
                                                <a
                                                    href="{{ $prt->attachment_url }}"
                                                    class="btn btn-primary"
                                                >
                                                    <span>
                                                        <i class="bi bi-paperclip"></i>
                                                    </span>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5>Data Achievements / Certificate</h5>
                    </div>
                    <div class="card-body">
                        <table
                            class="table"
                            id="table1"
                        >
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Issuer</th>
                                    <th>Year</th>
                                    <th>Description</th>
                                    <th>attachment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($worker->achievements as $ach)
                                    <tr>
                                        <td>{{ $ach->name }}</td>
                                        <td>{{ $ach->issuer }}</td>
                                        <td>{{ $ach->year }}</td>
                                        <td>{{ Str::limit($ach->description, 50) }}</td>
                                        <td>
                                            @if ($ach->attachment_url)
                                                <a
                                                    href="{{ $ach->attachment_url }}"
                                                    class="btn btn-primary"
                                                >
                                                    <span>
                                                        <i class="bi bi-paperclip"></i>
                                                    </span>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script
        type="text/javascript"
        src="https://cdn.datatables.net/v/bs5/dt-1.13.1/r-2.4.0/sb-1.4.0/datatables.min.js"
    ></script>
    <script>
        const jobTable = new DataTable("#job-table", {
            ajax: "{{ url('/api/jobs-datatables') }}?filter[applications.worker_id]={{ $worker->id }}&filter[applications.is_hired]=1",
            serverSide: true,
            columns: [{
                    name: "name",
                    data: data =>
                        `
                            <a href="{{ url('/dashboard/jobs') }}/${data.id}/edit">${data.name}</a>
                        `
                },
                {
                    name: "workgroup_id",
                    data: data => `${data.workgroup?.project?.name || 'N/A'} - ${data.workgroup?.name || 'N/A'}`
                },
                {
                    name: "budget",
                    data: data => `Rp${parseInt(data.budget).toLocaleString("ID")}`
                },
                {
                    name: "status",
                    data: "status",
                }
            ],
        });
    </script>
    <script>
        $(document).ready(function() {
            console.log("aaaa");
            $('#body').css('background-color', '#FFD4B2');
        });
    </script>
@endsection
