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
                <a class="btn btn-primary" href="{{ env('APP_DOMAIN_PM','http://pm-admin.docu.web.id') }}/dashboard/jobs/{{$jobs[0]->workgroup_id}}/dashboard/jobs/create">
                    <span>
                        <i class="bi bi-pencil me-2"></i>
                        Create Job
                    </span>
                </a>
            @else
                <a class="btn btn-primary" href="{{ env('APP_DOMAIN_PM','http://pm-admin.docu.web.id') }}/dashboard/jobs/create">
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
                        <th>Is Public</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jobs as $job)
                        <tr>
                            <td>{{ $job->name }}</td>
                            <td>{{ Str::limit($job->description, 50) }}</td>
                            <td>{{ $job->workgroup->name }}</td>
                            <td>{{ $job->order }}</td>
                            <td>{{ $job->budget }}</td>
                            <td>
                                @if($job->status == 'done')
                                    <span class="badge bg-success">{{ $job->status }}</span>
                                @endif
                                @if($job->status == 'canceled')
                                    <span class="badge bg-danger">{{ $job->status }}</span>
                                @endif
                                @if($job->status == 'pending')
                                    <span class="badge bg-warning">{{ $job->status }}</span>
                                @endif
                                @if($job->status == 'on_progress')
                                    <span class="badge bg-primary">{{ $job->status }}</span>
                                @endif
                                @if($job->status == 'paid')
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
                            <td class="d-flex justify-content-start align-items-center">
                                <a href="{{ env('APP_DOMAIN_PM','http://pm-admin.docu.web.id') }}/dashboard/jobs/{{ $job->id }}/edit" class="btn btn-success me-2">
                                    <span>
                                        <i class="bi bi-pencil-square"></i>
                                    </span>
                                </a>
                                <form action="{{ env('APP_DOMAIN_PM','http://pm-admin.docu.web.id') }}/dashboard/jobs/{{ $job->id }}" method="POST">
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

@section('scripts')
<script>
    $(document).ready(function() {
        console.log("aaaa");
        $('#body').css('background-color', '#678983');
    });
</script>
@endsection

