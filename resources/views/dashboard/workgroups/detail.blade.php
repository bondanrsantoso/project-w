@extends('dashboard.index')

@section('header')
    <h3>Edit Workgroup</h3>
@endsection

@section('content')
    <section id="basic-horizontal-layouts">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <form
                        class="form form-horizontal"
                        action="{{ env('APP_DOMAIN_PM', 'http://pm-admin.docu.web.id') }}/dashboard/workgroups/{{ $workgroup->id }}"
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
                                    <label>Project ID</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <select
                                        class="form-select"
                                        name="project_id"
                                        aria-label="Default select example"
                                    >
                                        @foreach ($projects as $project)
                                            <option
                                                @selected($project->id == $workgroup->project_id)
                                                value="{{ $project->id }}"
                                            >{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
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
                                        value="{{ $workgroup->name }}"
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
                                    >{{ $workgroup->description }}</textarea>
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
            $('#body').css('background-color', '#FCF9BE');
        });
    </script>
@endsection
