@extends('adminlte::page')

@section('title', 'Setting Decision Control')

@section('content_header')
    <h1>Decision Control</h1>
@stop

@section('content')
    @if (\Session::has('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ \Session::get('success') }}
        </div>
    @endif
    @if (\Session::has('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ \Session::get('error') }}
        </div>
    @endif
    
    <div class="col-md-12">
        <div class="card">
        <form action="/dashboard/decision-controls" method="post">
            <div class="card-header">
                <div class="row">
                    <div class="col-4">
                        <h3 class="card-title">Setting Decision Control</h3>
                    </div>
                </div>
            </div>

            <div class="card-body">
                    @csrf
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label>Question</label>
                                <select class="form-control" name="question_id">
                                    <option value="">Select Question</option>
                                    @foreach ($questions as $question)
                                        <option value="{{ $question->id }}">{{ $question->question }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Answer Yes</label>
                                <select class="form-control" name="answer_yes">
                                    <option value="">Select Answer</option>
                                    @foreach ($questions as $question)
                                        <option value="{{ $question->id }}">{{ $question->question }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Answer No</label>
                                <select class="form-control" name="answer_no">
                                    <option value="">Select Answer</option>
                                    @foreach ($questions as $question)
                                        <option value="{{ $question->id }}">{{ $question->question }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Action Yes</label>
                                <select class="form-control" name="question">
                                    <option value="">Select Action</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Action No</label>
                                <select class="form-control" name="question">
                                    <option value="">Select Action</option>
                                </select>
                            </div>
                        </div>
                    </div>
            </div>

            <div class="card-footer clearfix">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
        </div>
    </div>
@stop

@section('css')
    
@stop

@section('js')
@stop