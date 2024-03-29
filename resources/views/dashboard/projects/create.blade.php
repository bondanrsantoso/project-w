@extends('dashboard.index')

@section('header')
    <h3>Create Project</h3>
@endsection

@section('content')
    <section id="basic-horizontal-layouts">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <form
                        class="form form-horizontal"
                        action="{{ env('APP_DOMAIN_PM', 'http://pm-admin.docu.web.id') }}/dashboard/projects"
                        method="POST"
                    >
                        @csrf

                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Service Pack ID (optional)</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input
                                        type="text"
                                        id="first-name"
                                        class="form-control"
                                        name="service_pack_id"
                                        placeholder="Service Pack ID"
                                    >
                                </div>
                                <div class="col-md-4">
                                    <label>Company ID</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <select
                                        class="form-select"
                                        name="company_id"
                                        aria-label="Default select example"
                                    >
                                        @foreach ($companies as $cp)
                                            <option value="{{ $cp->id }}">{{ $cp->name }}</option>
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
                                    >
                                </div>
                                <div class="col-md-4">
                                    <label for="budget">Budget</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input
                                            type="number"
                                            name="budget"
                                            id="budget"
                                            class="form-control"
                                        >
                                    </div>
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
                                    ></textarea>
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
            $('#body').css('background-color', '#ADA2FF');
        });
    </script>
@endsection
