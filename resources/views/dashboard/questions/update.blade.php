@extends('dashboard.index')

@section('header')
<h3>Update Question</h3>
@endsection

@section('content')
<section id="basic-horizontal-layouts">
    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <form class="form form-horizontal" action="{{ env('APP_DOMAIN_JOB','http://job.docu.web.id') }}/dashboard/questions/{{ $question->id }}" method="POST">
                    @csrf

                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Statement</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" id="first-name" class="form-control" name="statement"
                                    placeholder="Statement" value="{{ $question->statement }}">
                            </div>
                            <div class="col-md-4">
                                <label>Next On Yes</label>
                            </div>
                            <div class="col-md-8 form-group">
                              <input type="text" id="first-name" class="form-control" name="next_on_yes"
                              placeholder="Next On Yes" value="{{ $question->next_on_yes }}">
                            </div>
                            <div class="col-md-4">
                                <label>Next On No</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" id="name" class="form-control" name="next_on_no"
                                    placeholder="Next On No" value="{{ $question->next_on_no }}">
                            </div>
                            <div class="col-md-4">
                              <label>Answer No</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" id="name" class="form-control" name="answer_no"
                                    placeholder="Answer No" value="{{ $question->answer_no }}">
                            </div>
                            <div class="col-md-4">
                              <label>Answer Yes</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" id="name" class="form-control" name="answer_yes"
                                    placeholder="Answer Yes" value="{{ $question->answer_yes }}">
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
        $('#body').css('background-color', '#ADA2FF');
    });
</script>
@endsection

