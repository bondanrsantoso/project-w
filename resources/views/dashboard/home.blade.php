@extends('dashboard.index')

@section('header')
@if(Session::has('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif
<h3>Dashboard</h3>
@endsection

@section('content')
<section class="section">
    <div class="row">
        <div class="col-4">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <h3>Big Data</h3>

                        <form class="form form-horizontal" action="/dashboard/big-data" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" id="exampleFormControlInput1" placeholder="Name">
                            </div>

                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Owner Name</label>
                                <input type="text" name="owner_name" class="form-control" id="exampleFormControlInput1" placeholder="Owner Name">
                            </div>

                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Province</label>
                                <input type="text" name="province" class="form-control" id="exampleFormControlInput1" placeholder="Province">
                            </div>

                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">City</label>
                                <input type="text" name="city" class="form-control" id="exampleFormControlInput1" placeholder="City">
                            </div>

                            <div class="mb-3">
                                <label for="exampleFormControlTextarea1" class="form-label">Address</label>
                                <textarea class="form-control" name="address" id="exampleFormControlTextarea1" placeholder="Address" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">District</label>
                                <input type="text" name="district" class="form-control" id="exampleFormControlInput1" placeholder="District">
                            </div>

                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Revenue</label>
                                <input type="text" name="revenue" class="form-control" id="exampleFormControlInput1" placeholder="Revenue">
                            </div>

                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Type</label>
                                <input type="text" name="type" class="form-control" id="exampleFormControlInput1" placeholder="Type">
                            </div>

                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">scale</label>
                                <input type="text" name="scale" class="form-control" id="exampleFormControlInput1" placeholder="Scale">
                            </div>

                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Data Year</label>
                                <input type="text" name="data_year" class="form-control" id="exampleFormControlInput1">
                            </div>

                            <div class="d-flex justify-content-end">
                                <button class="btn btn-primary" type="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <form action="/dashboard/import/big-data" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="formFile" class="form-label">Import File</label>
                    <input class="form-control" name="file" type="file" id="formFile">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</section>
@endsection