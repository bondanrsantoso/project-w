@extends('dashboard.index')

@section('header')
<h3>Create Project</h3>
@endsection

@section('content')
<section id="basic-horizontal-layouts">
    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <form class="form form-horizontal" action="{{ env('APP_DOMAIN_JOB','http://job-admin.docu.web.id') }}/job-applications/{{ $jobApplication['id'] }}" method="POST">
                    @csrf

                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Worker ID</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select class="form-select" name="worker_id" aria-label="Default select example">
                                    @foreach ($workers as $worker)
                                    <option {{ $worker['id'] == $jobApplication['worker_id'] ? "selected" : "" }} value="{{ $worker['id'] }}">{{ $worker['user']->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-4">
                                <label>Job ID</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select class="form-select" name="job_id" aria-label="Default select example">
                                    @foreach ($jobs as $job)
                                    <option {{ $job['id'] == $jobApplication['worker_id'] ? "selected" : "" }} value="{{ $job['id'] }}">{{ $job['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Is Hired</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" {{ $jobApplication['is_hired'] == 1 ? "checked" : "" }} type="radio" name="is_hired" id="inlineRadio1" value="1">
                                    <label class="form-check-label" for="inlineRadio1">True</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" {{ $jobApplication['is_hired'] == 0 ? "checked" : "" }} type="radio" name="is_hired" id="inlineRadio2" value="0">
                                    <label class="form-check-label" for="inlineRadio2">False</label>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label>Status</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select class="form-select" name="status" aria-label="Default select example">
                                    <option value="on_progress">On Progress</option>
                                    <option value="done">Done</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>

                            <div class="col-sm-12 d-flex justify-content-end mt-2">
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
