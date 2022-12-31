@extends('dashboard.index')

@section('header')
@if(Session::has('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif
<h3>Questions</h3>
@endsection

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5>Data Questions</h5>

            <a href="{{ env('APP_DOMAIN_JOB','http://job-admin.docu.web.id') }}/dashboard/questions/create" class="btn btn-primary me-2">
                <span class="me-2">
                    <i class="bi bi-plus"></i>
                </span>
                Create Question
            </a>
        </div>
        <div class="card-body">
            <table class="table" id="table1">
                <thead>
                    <tr>
                        <th>Statement</th>
                        <th>Next On Yes</th>
                        <th>Next On No</th>
                        <th>Answer No</th>
                        <th>Answer Yes</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($questions as $question)
                        <tr>
                            <td>{{ $question->statement }}</td>
                            <td>{{ $question->next_on_yes }}</td>
                            <td>{{ $question->next_on_no }}</td>
                            <td>{{ $question->answer_no }}</td>
                            <td>{{ $question->answer_yes }}</td>
                            <td>{{ $question->created_at }}</td>
                            <td class="d-flex justify-content-start align-items-center">
                                <a href="{{ env('APP_DOMAIN_JOB','http://job-admin.docu.web.id') }}/dashboard/questions/{{ $question->id }}/edit" class="btn btn-success  me-2">
                                    <span>
                                        <i class="bi bi-pencil-square"></i>
                                    </span>
                                </a>
                                <form action="{{ env('APP_DOMAIN_JOB','http://job-admin.docu.web.id') }}/dashboard/questions/{{ $question->id }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="_method" value="DELETE" />
                                    <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $questions->links() }}
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
