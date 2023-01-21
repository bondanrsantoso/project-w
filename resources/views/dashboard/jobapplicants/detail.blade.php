@extends('dashboard.index')

@section('header')
    <h3>Edit Job Applications</h3>
@endsection

@section('content')
    <section id="basic-horizontal-layouts">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <form
                        class="form form-horizontal"
                        action="{{ env('APP_DOMAIN_JOB', 'http://job-admin.docu.web.id') }}/dashboard/job_applications/{{ $jobApplication['id'] }}"
                        method="POST"
                    >
                        @csrf

                        <input
                            type="hidden"
                            name="_method"
                            value="PUT"
                        >
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Worker ID</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <select
                                        class="form-select"
                                        name="worker_id"
                                        aria-label="Default select example"
                                    >
                                        @foreach ($workers as $worker)
                                            <option
                                                @selected($worker['id'] == $jobApplication['worker_id'])
                                                value="{{ $worker['id'] }}"
                                            >{{ $worker['user']->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label>Job ID</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <select
                                        class="form-select"
                                        name="job_id"
                                        aria-label="Default select example"
                                    >
                                        @foreach ($jobs as $job)
                                            <option
                                                @selected($jobApplication->job_id == $job->id)
                                                value="{{ $job['id'] }}"
                                            >{{ $job['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label>Is Hired</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <div class="form-check form-check-inline">
                                        <input
                                            class="form-check-input"
                                            @checked($jobApplication->is_hired)
                                            type="radio"
                                            name="is_hired"
                                            id="inlineRadio1"
                                            value="1"
                                        >
                                        <label
                                            class="form-check-label"
                                            for="inlineRadio1"
                                        >True</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input
                                            class="form-check-input"
                                            @checked(!$jobApplication->is_hired)
                                            type="radio"
                                            name="is_hired"
                                            id="inlineRadio2"
                                            value="0"
                                        >
                                        <label
                                            class="form-check-label"
                                            for="inlineRadio2"
                                        >False</label>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label>Status</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <select
                                        class="form-select"
                                        name="status"
                                        aria-label="Default select example"
                                    >
                                        @foreach (['pending', 'interviewing', 'hired', 'rejected'] as $status)
                                            <option
                                                value="{{ $status }}"
                                                @selected($jobApplication->status == $status)
                                            >{{ ucfirst($status) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-12 d-flex justify-content-end mt-2">
                                    <button
                                        type="submit"
                                        class="btn btn-primary btn-lg me-1 px-3 mb-1"
                                    >Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Current User Data
                </h5>
            </div>
            <div class="card-body">
                <fieldset
                    class="row row-gap-2"
                    disabled="disabled"
                >
                    <div class="form-group col-12 col-md-6">
                        <label
                            for="user-name"
                            class="form-label"
                        >
                            Name
                        </label>
                        <input
                            type="text"
                            name="user-name"
                            value="{{ $jobApplication->worker()->first()->user->name }}"
                            id="user-name"
                            class="form-control"
                            disabled="disabled"
                        >
                    </div>
                    <div class="form-group col-12 col-md-6">
                        <label
                            class="form-group"
                            for="user-username"
                        >
                            Username
                        </label>
                        <input
                            type="text"
                            value="{{ $jobApplication->worker->user->username }}"
                            id="user-username"
                            class="form-control"
                            disabled="disabled"
                        >
                    </div>
                    <div class="form-group col-12 col-md-6">
                        <label
                            for="user-email"
                            class="form-label"
                        >
                            Email
                        </label>
                        <input
                            type="email"
                            value="{{ $jobApplication->worker->user->email }}"
                            id="user-email"
                            class="form-control"
                        >
                    </div>
                    <div class="form-group col-12 col-md-6">
                        <label
                            for="user-phone"
                            class="form-label"
                        >
                            Phone Number
                        </label>
                        <input
                            type="tel"
                            value="{{ $jobApplication->worker->user->phone_number }}"
                            id="user-phone"
                            class="form-control"
                        >
                    </div>
                    <hr>
                    <h6>Bank account information</h6>
                    <div class="form-group col-12 col-md-6">
                        <label
                            for="worker-account_bank"
                            class="form-label"
                        >
                            Bank Name
                        </label>
                        <input
                            type="text"
                            value="{{ $jobApplication->worker->account_bank ?? 'N/A' }}"
                            id="worker-account_bank"
                            class="form-control"
                        >
                    </div>
                    <div class="form-group col-12 col-md-6">
                        <label
                            for="worker-account_bank"
                            class="form-label"
                        >
                            Bank Account Number
                        </label>
                        <input
                            type="text"
                            value="{{ $jobApplication->worker->account_number ?? 'N/A' }}"
                            id="worker-account_bank"
                            class="form-control"
                        >
                    </div>
                </fieldset>
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
