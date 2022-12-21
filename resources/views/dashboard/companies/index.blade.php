@extends('dashboard.index')

@section('header')
@if(Session::has('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif
<h3>Projects</h3>
@endsection

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5>Data Companies</h5>
        </div>
        <div class="card-body">
            <table class="table" id="table1">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Phone Number</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($companies as $company)
                        <tr>
                            <td>{{ $company->name }}</td>
                            <td>{{ $company->address }}</td>
                            <td>{{ $company->phone_number }}</td>
                            <td>{{ $company->created_at }}</td>
                            <td class="d-flex justify-content-start align-items-center">
                                {{-- <a href="{{ env('APP_DOMAIN_PM','http://pm-admin.docu.web.id') }}/dashboard/companies/{{ $company->id }}/edit" class="btn btn-success  me-2">
                                    <span>
                                        <i class="bi bi-pencil-square"></i>
                                    </span>
                                </a> --}}
                                <form action="{{ env('APP_DOMAIN_PM','http://pm-admin.docu.web.id') }}/dashboard/companies/{{ $company->id }}" method="POST">
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

            {{ $companies->links() }}
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
