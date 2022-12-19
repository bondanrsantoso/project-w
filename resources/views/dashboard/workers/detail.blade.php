@extends('dashboard.index')

@section('header')
<h3>Create Project</h3>
@endsection

@section('content')
<section id="basic-horizontal-layouts">
    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <form class="form form-horizontal" action="{{ env('APP_DOMAIN_JOB','http://job-admin.docu.web.id') }}/dashboard/workers/{{ $worker['id'] }}" method="POST">
                    @csrf

                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Job Category ID</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select class="form-select" name="job_category_id" aria-label="Default select example">
                                    @foreach ($jobCats as $jobcat)
                                        <option {{ $jobcat['id'] == $worker['category_id'] ? "selected" : "" }} value="{{ $jobcat['id'] }}">{{ $jobcat['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Address</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <textarea class="form-control" id="address" name="address" placeholder="Address" rows="5">{{ $worker['address'] }}</textarea>
                            </div>
                            <div class="col-md-4">
                                <label>Birth Place</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" id="name" class="form-control" name="birth_place"
                                placeholder="Birth Place" value="{{ $worker['place_of_birth'] }}">
                            </div>
                            <div class="col-md-4">
                                <label>Date of Birth</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" id="name" class="form-control" name="birthday"
                                placeholder="Date of Birth" value="{{ $worker['date_of_birth'] }}">
                            </div>
                            <div class="col-md-4">
                                <label>Gender</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" {{ $worker['gender'] == 'MALE' ? "checked" : "" }} id="inlineRadio1" value="MALE">
                                    <label class="form-check-label" for="inlineRadio1">Male</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" {{ $worker['gender'] == 'FEMALE' ? "checked" : "" }} id="inlineRadio2" value="FEMALE">
                                    <label class="form-check-label" for="inlineRadio2">Female</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label>Account Number</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" id="name" class="form-control" name="account_number"
                                placeholder="Account Number" value="{{ $worker['account_number'] }}">
                            </div>
                            <div class="col-md-4">
                                <label>Description</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <textarea class="form-control" id="exampleFormControlTextarea1" name="description" placeholder="Description" rows="5">{{ $worker['description'] }}</textarea>
                            </div>
                            <div class="col-md-4">
                                <label>Experience</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <textarea class="form-control" id="exampleFormControlTextarea1" name="experience" placeholder="Experience" rows="5">{{ $worker['experience'] }}</textarea>
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
        $('#body').css('background-color', '#FFD4B2');
    });
</script>
@endsection