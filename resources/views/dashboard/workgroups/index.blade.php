@extends('dashboard.index')

@section('header')
@if(Session::has('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif
<h3>Workgroups</h3>
@endsection

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5>Data Workgroups</h5>
            <a class="btn btn-primary" href="{{ request()->is('dashboard/workgroups') ? '/dashboard/workgroups/create' : '/dashboard/projects/'.$workgroups[0]['project_id'].'/workgroups/create' }}">
                <span>
                    <i class="bi bi-pencil me-2"></i>
                    Create Workgroup
                </span>
            </a>
        </div>
        <div class="card-body">
            <table class="table" id="table1">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Project ID</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($workgroups as $workgroup)
                        <tr>
                            <td>{{ $workgroup['name'] }}</td>
                            <td>{{ $workgroup['project']->name }}</td>
                            <td>{{ $workgroup['created_at'] }}</td>
                            <td class="d-flex justify-content-start align-items-center">
                                <a href="/dashboard/workgroups/{{ $workgroup['id'] }}/jobs" class="btn btn-primary  me-2">
                                    <span>
                                        <i class="bi bi-people"></i>
                                    </span>
                                </a>
                                <a href="/dashboard/workgroups/{{ $workgroup['id'] }}/edit" class="btn btn-success  me-2">
                                    <span>
                                        <i class="bi bi-pencil-square"></i>
                                    </span>
                                </a>
                                <form action="/dashboard/workgroups/{{ $workgroup['id'] }}" method="POST">
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

            {{ $workgroups->links() }}

        </div>
    </div>
</section>
@endsection
