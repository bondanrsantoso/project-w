@extends('dashboard.index')

@section('header')
    <h3>Update Question</h3>
@endsection

@section('content')
    <section id="basic-horizontal-layouts">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <form
                        class="form form-horizontal"
                        action="{{ env('APP_DOMAIN_JOB', 'http://job.docu.web.id') }}/dashboard/questions/{{ $question->id }}"
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
                                    <label>Statement</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input
                                        type="text"
                                        id="first-name"
                                        class="form-control"
                                        name="statement"
                                        placeholder="Statement"
                                        value="{{ $question->statement }}"
                                    >
                                </div>
                                <div class="col-md-4">
                                    <label>Next Question (yes)</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <select
                                        name="next_on_yes"
                                        id="next_on_yes"
                                        class="form-select"
                                    >
                                        <option value="">-</option>
                                        @foreach ($questions as $q)
                                            <option
                                                value="{{ $q->id }}"
                                                @selected($q->id == $question->next_on_yes)
                                            >{{ $q->statement }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Next Question (no)</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <select
                                        name="next_on_no"
                                        id="next_on_no"
                                        class="form-select"
                                    >
                                        <option value="">-</option>
                                        @foreach ($questions as $q)
                                            <option
                                                value="{{ $q->id }}"
                                                @selected($q->id == $question->next_on_no)
                                            >{{ $q->statement }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Service Pack Activation (Yes)</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <select
                                        name="answer_yes"
                                        id="answer_yes"
                                        class="form-select"
                                    >
                                        <option value="">-</option>
                                        @foreach ($servicePacks as $sp)
                                            <option
                                                value="{{ $sp->id }}"
                                                @selected($question->answer_yes == $sp->id)
                                            >{{ $sp->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Service Pack Activation (No)</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <select
                                        name="answer_no"
                                        id="answer_no"
                                        class="form-select"
                                    >
                                        <option value="">-</option>
                                        @foreach ($servicePacks as $sp)
                                            <option
                                                value="{{ $sp->id }}"
                                                @selected($question->answer_no == $sp->id)
                                            >{{ $sp->name }}</option>
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
            $('#body').css('background-color', '#ADA2FF');
        });
    </script>
@endsection
