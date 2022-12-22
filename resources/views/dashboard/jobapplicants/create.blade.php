@extends('dashboard.index')

@section('header')
<h3>Create Project</h3>
@endsection

@section('content')
<section id="basic-horizontal-layouts">
    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <form class="form form-horizontal" action="{{ env('APP_DOMAIN_JOB','http://job-admin.docu.web.id') }}/dashboard/job-applications" method="POST">
                    @csrf

                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Worker ID</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select class="form-select" name="worker_id" aria-label="Default select example">
                                    @foreach ($workers as $worker)
                                    <option value="{{ $worker['id'] }}">{{ $worker['user']->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-4">
                                <label>Job ID</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select class="form-select" name="job_id" aria-label="Default select example">
                                    @foreach ($jobs as $job)
                                    <option value="{{ $job['id'] }}">{{ $job['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary btn-lg me-1 px-3 mb-1">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
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