@extends('adminlte::page')

@section('title', 'Question')

@section('content_header')
<h1>Question</h1>
@stop

@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-4">
                    <h3 class="card-title">List Question</h3>
                </div>
                <div class="col-8 d-flex justify-content-end">
                    <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#addModalQuestion"><i class="fas fa-plus-circle"></i> Add</button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Question</th>
                        <th style="width: 40px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($questions as $index=>$question)
                    <tr>
                        <td>{{ $index+1 }}.</td>
                        <td>{{$question->question}}</td>
                        <td>
                            <!-- <a href="#" data-toggle="tooltip" data-placement="top" title="Show"><i class="fas fa-eye text-success"></i></a> -->
                            <a href="#" data-toggle="tooltip" data-placement="top" title="Delete"><div align="center"><i class="fas fa-trash-alt text-danger mx-1"></i></div></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- <div id="loading" class="overlay">
            <i class="fas fa-2x fa-sync-alt fa-spin"></i>
        </div> -->

        <div class="card-footer clearfix">
            {{ $questions->links() }}
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="addModalQuestion" tabindex="-1" role="dialog" aria-labelledby="addModalQuestionLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalQuestionLabel">Add New Question</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="exampleInputEmail1">Question</label>
                    <input type="text" class="form-control" placeholder="Enter question" name="question">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" id="btn-submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')

@stop

@section('js')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $("#btn-submit").click(function(e){
        e.preventDefault();
        const question = $("input[name=question]").val();
        $.ajax({
            type:'POST',
            url:"/dashboard/questions",
            data:{question: question},
            success:function(data){
                location.reload();
            },
            error: function(error){
                alert(error.message);
            }
        });
    });
</script>
@stop