@extends('adminlte::page')

@section('title', 'Decision Control')

@section('content_header')
    <h1>Decision Control</h1>
@stop

@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-4">
                        <h3 class="card-title">Testing Decision Control</h3>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <h3>{{ $question->question }} ?</h3>
                <div class="form-check">
                    <input class="form-check-input" onclick="setLink({{ $yes->id }})" type="radio" name="answer">
                    <label class="form-check-label">{{ $yes->question }}</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" onclick="setLink({{ $no->id }})" type="radio" name="answer">
                    <label class="form-check-label">{{ $no->question }}</label>
                </div>
            </div>

            <div class="card-footer clearfix">
                <a href="#"><button class="btn btn-success" type="button">Next</button></a>
            </div>
        </div>
    </div>
@stop

@section('css')
    
@stop

@section('js')
    <script>
        function setLink(id){
            $("a").prop("href", "/dashboard/decision-controls?question_id="+id);
        }
    </script>
@stop