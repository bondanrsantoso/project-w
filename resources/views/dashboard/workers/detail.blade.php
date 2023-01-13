@extends('dashboard.index')

@section('header')
    <h3>Edit Worker</h3>
@endsection

@section('content')
    <section id="basic-horizontal-layouts">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <form
                        class="form form-horizontal"
                        action="{{ env('APP_DOMAIN_JOB', 'http://job-admin.docu.web.id') }}/dashboard/workers/{{ $worker->id }}"
                        method="POST"
                    >
                        @csrf

                        <input
                            type="hidden"
                            name="_method"
                            value="PUT"
                        >
                        <div class="form-body">
                            <div class="col-12 mb-2">
                                <h4>
                                    User data
                                </h4>
                            </div>
                        </div>
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Name</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input
                                        disabled
                                        type="text"
                                        value="{{ $worker->user->name }}"
                                        id="user-name"
                                        class="form-control"
                                        readonly="readonly"
                                    >
                                </div>
                                <div class="col-md-4">
                                    <label>Username</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input
                                        disabled
                                        type="text"
                                        value="{{ $worker->user->username }}"
                                        id="user-username"
                                        class="form-control"
                                        readonly="readonly"
                                    >
                                </div>
                                <div class="col-md-4">
                                    <label>Email</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input
                                        disabled
                                        type="email"
                                        value="{{ $worker->user->email }}"
                                        id="user-email"
                                        class="form-control"
                                        readonly="readonly"
                                    >
                                </div>
                                <div class="col-md-4">
                                    <label>Phone Number</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input
                                        disabled
                                        type="tel"
                                        value="{{ $worker->user->phone_number }}"
                                        id="user-phone"
                                        class="form-control"
                                        readonly="readonly"
                                    >
                                </div>
                            </div>
                        </div>
                        <hr class="mt-1 mb-3">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <h4>Worker Data</h4>
                                </div>
                                <div class="col-md-4">
                                    <label>Specialisation</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <select
                                        class="form-select"
                                        name="job_category_id"
                                        aria-label="Default select example"
                                    >
                                        @foreach ($jobCats as $jobcat)
                                            <option
                                                @selected($jobcat->id == $worker->category_id)
                                                value="{{ $jobcat->id }}"
                                            >{{ $jobcat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Address</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <textarea
                                        class="form-control"
                                        id="address"
                                        name="address"
                                        placeholder="Address"
                                        rows="5"
                                    >{{ $worker->address }}</textarea>
                                </div>
                                <div class="col-md-4">
                                    <label>Birth Place</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input
                                        type="text"
                                        id="name"
                                        class="form-control"
                                        name="birth_place"
                                        placeholder="Birth Place"
                                        value="{{ $worker->place_of_birth }}"
                                    >
                                </div>
                                <div class="col-md-4">
                                    <label>Date of Birth</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input
                                        type="text"
                                        id="name"
                                        class="form-control"
                                        name="birthday"
                                        placeholder="Date of Birth"
                                        value="{{ $worker->date_of_birth }}"
                                    >
                                </div>
                                <div class="col-md-4">
                                    <label>Gender</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <div class="form-check form-check-inline">
                                        <input
                                            class="form-check-input"
                                            type="radio"
                                            name="gender"
                                            @checked($worker->gender == 'L')
                                            id="inlineRadio1"
                                            value="L"
                                        >
                                        <label
                                            class="form-check-label"
                                            for="inlineRadio1"
                                        >Male</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input
                                            class="form-check-input"
                                            type="radio"
                                            name="gender"
                                            @checked($worker->gender == 'P')
                                            id="inlineRadio2"
                                            value="P"
                                        >
                                        <label
                                            class="form-check-label"
                                            for="inlineRadio2"
                                        >Female</label>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label>Status</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <div class="form-check form-check-inline">
                                        <input
                                            type="radio"
                                            name="is_freelancer"
                                            @checked($worker->is_freelancer)
                                            id="freelancer-check"
                                            class="form-check-input"
                                            value="1"
                                        >
                                        <label
                                            for="freelancer-check"
                                            class="form-check-label"
                                        >Freelancer</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input
                                            type="radio"
                                            name="is_freelancer"
                                            @checked(!$worker->is_freelancer)
                                            id="non-freelancer-check"
                                            class="form-check-input"
                                            value="0"
                                        >
                                        <label
                                            for="freelancer-check"
                                            class="form-check-label"
                                        >Non-Freelancer</label>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label>Is Eligible</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <div class="form-check form-check-inline">
                                        <input
                                            class="form-check-input"
                                            type="radio"
                                            name="is_eligible_for_work"
                                            @checked($worker->is_eligible_for_work)
                                            id="inlineRadioEligible1"
                                            value="1"
                                        >
                                        <label
                                            class="form-check-label"
                                            for="inlineRadioEligible1"
                                        >True</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input
                                            class="form-check-input"
                                            type="radio"
                                            name="is_eligible_for_work"
                                            @checked(!$worker->is_eligible_for_work)
                                            id="inlineRadioEligible2"
                                            value="0"
                                        >
                                        <label
                                            class="form-check-label"
                                            for="inlineRadioEligible2"
                                        >False</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label>Is Student</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <div class="form-check form-check-inline">
                                        <input
                                            class="form-check-input"
                                            type="radio"
                                            name="is_student"
                                            @checked($worker->is_student)
                                            id="inlineRadioStudent1"
                                            value="1"
                                        >
                                        <label
                                            class="form-check-label"
                                            for="inlineRadioStudent1"
                                        >True</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input
                                            class="form-check-input"
                                            type="radio"
                                            name="is_student"
                                            @checked(!$worker->is_student)
                                            id="inlineRadioStudent2"
                                            value="0"
                                        >
                                        <label
                                            class="form-check-label"
                                            for="inlineRadioStudent2"
                                        >False</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label>Bank Account Number</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input
                                        type="text"
                                        id="name"
                                        class="form-control"
                                        name="account_number"
                                        placeholder="Account Number"
                                        value="{{ $worker->account_number }}"
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
                                    >{{ $worker->description }}</textarea>
                                </div>
                                <div class="col-md-4">
                                    <label>Experience</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <textarea
                                        class="form-control"
                                        id="exampleFormControlTextarea1"
                                        name="experience"
                                        placeholder="Experience"
                                        rows="5"
                                    >{{ $worker->experience }}</textarea>
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
            $('#body').css('background-color', '#FFD4B2');
        });
    </script>
@endsection
