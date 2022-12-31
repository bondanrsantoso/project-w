@extends('dashboard.index')

@section('header')
    <h3>Update Job</h3>
@endsection

@section('content')
    <section id="basic-horizontal-layouts">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <form
                        class="form form-horizontal"
                        action="{{ env('APP_DOMAIN_PM', 'http://pm-admin.docu.web.id') }}/dashboard/jobs/{{ $job->id }}"
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
                                    <label>Name</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input
                                        type="text"
                                        id="name"
                                        class="form-control"
                                        name="name"
                                        placeholder="name"
                                        value="{{ $job->name }}"
                                    >
                                </div>

                                <div class="col-md-4">
                                    <label>Description</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <textarea
                                        class="form-control"
                                        id="exampleFormControlTextarea1"
                                        name="description"
                                        placeholder="Description"
                                        rows="5"
                                    >{{ $job->description }}</textarea>
                                </div>

                                <div class="col-md-4">
                                    <label>Budget</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input
                                        type="text"
                                        id="name"
                                        class="form-control"
                                        name="budget"
                                        placeholder="budget"
                                        value="{{ $job->budget }}"
                                    >
                                </div>

                                <div class="col-md-4">
                                    <label>Date Start</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input
                                        type="datetime-local"
                                        id="name"
                                        class="form-control"
                                        name="date_start"
                                        placeholder="date start"
                                        value="{{ $job->date_start }}"
                                    >
                                </div>

                                <div class="col-md-4">
                                    <label>Date End</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input
                                        type="datetime-local"
                                        id="name"
                                        class="form-control"
                                        name="date_end"
                                        placeholder="budget"
                                        value="{{ $job->date_end }}"
                                    >
                                </div>

                                <div class="col-md-4">
                                    <label>Workgroup ID</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <select
                                        class="form-select"
                                        name="workgroup_id"
                                        aria-label="Default select example"
                                    >
                                        @foreach ($workgroups as $workgroup)
                                            <option
                                                value="{{ $workgroup->id }}"
                                                @selected($job->workgroup_id == $workgroup->id)
                                            >{{ $workgroup->project->name }}: {{ $workgroup->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label>Is Public</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <div class="form-check form-check-inline">
                                        <input
                                            class="form-check-input"
                                            type="radio"
                                            name="is_public"
                                            id="inlineRadio1"
                                            value="1"
                                            @checked($job->is_public)
                                        >
                                        <label
                                            class="form-check-label"
                                            for="inlineRadio1"
                                        >Public</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input
                                            class="form-check-input"
                                            type="radio"
                                            name="is_public"
                                            id="inlineRadio2"
                                            value="0"
                                            @checked(!$job->is_public)
                                        >
                                        <label
                                            class="form-check-label"
                                            for="inlineRadio2"
                                        >Not Public</label>
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <label>Job Category ID</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <select
                                        class="form-select"
                                        name="job_category_id"
                                        aria-label="Default select example"
                                    >
                                        @foreach ($jobCats as $jobcat)
                                            <option
                                                value="{{ $jobcat->id }}"
                                                @selected($job->job_category_id == $jobcat->id)
                                            >{{ $jobcat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="status">Status</label>
                                </div>

                                <div class="col-md-8 form-group">
                                    <select
                                        name="status"
                                        id="status"
                                        class="form-select"
                                    >
                                        @foreach (['pending', 'on_progress', 'done', 'canceled'] as $status)
                                            <option
                                                value="{{ $status }}"
                                                @selected($job->raw_status == $status)
                                            >
                                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-12 d-flex justify-content-end">
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
