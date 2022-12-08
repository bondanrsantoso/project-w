@extends('dashboard.index')

@section('header')
@if(Session::has('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                <a class="btn btn-primary" href="/dashboard/workgroups/{{$jobs[0]['workgroup_id']}}/jobs/create">
                    <span>
                        <i class="bi bi-pencil me-2"></i>
                        Create Job
                    </span>
                </a>
            @else
                <a class="btn btn-primary" href="/dashboard/jobs/create">
                    <span>
                        <i class="bi bi-pencil me-2"></i>
                        Create Job
                    </span>
                </a>
            @endif
        </div>
        <div class="card-body">
            <table class="table" id="table1">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Workgroup ID</th>
                        <th>Order</th>
                        <th>Budget</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jobs as $job)
                        <tr>
                            <td>{{ $job['name'] }}</td>
                            <td>{{ Str::limit($job['description'], 50) }}</td>
                            <td>{{ $job['workgroup']->name }}</td>
                            <td>{{ $job['order'] }}</td>
                            <td>{{ $job['budget'] }}</td>
                            <td>

                            </td>
                            <td class="d-flex justify-content-start align-items-center">
                                <a href="/dashboard/jobs/{{ $job['id'] }}/edit" class="btn btn-success  me-2">
                                    <span>
                                        <i class="bi bi-pencil-square"></i>
                                    </span>
                                </a>
                                <form action="/dashboard/jobs/{{ $job['id'] }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="_method" value="DELETE" />
                                    <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $jobs->links() }}
        </div>
    </div>
</section>
@endsection
