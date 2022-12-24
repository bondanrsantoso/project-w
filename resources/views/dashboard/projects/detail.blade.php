@extends('dashboard.index')

@section('header')
<h3>Update Project</h3>
@endsection

@section('content')
<section id="basic-horizontal-layouts">
    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <form class="form form-horizontal" action="{{ env('APP_DOMAIN_PM','http://pm-admin.docu.web.id') }}/dashboard/projects/{{ $project->id }}" method="POST">
                    @csrf

                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Service Pack ID (optional)</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" id="first-name" class="form-control" name="service_pack_id"
                                    placeholder="Service Pack ID" value="{{ $project->service_pack_id }}">
                            </div>
                            <div class="col-md-4">
                                <label>Name</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" id="name" class="form-control" name="name" placeholder="name" value="{{ $project->name }}">
                            </div>
                            <div class="col-md-4">
                                <label>Description</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <textarea class="form-control" id="exampleFormControlTextarea1" name="description"
                                    placeholder="Description" rows="5">{{ $project->description }}</textarea>
                            </div>
                            <div class="col-md-4">
                              <label>Budget</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" id="name" class="form-control" name="budget" placeholder="Budget" value="{{ $project->budget }}">
                            </div>
                            <div class="col-md-4">
                              <label>Approved By Admin</label>
                            </div>
                            <div class="col-md-8 form-group">
                              <div class="form-check">
                                <input class="form-check-input" {{ $project->approved_by_admin == 1 ? "checked" : "" }} value="1" type="radio" name="approved_by_admin" id="flexRadioDefault1">
                                <label class="form-check-label" for="flexRadioDefault1">
                                  Approved
                                </label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" {{ $project->approved_by_admin == 0 ? "checked" : "" }} type="radio" value="0" name="approved_by_admin" id="flexRadioDefault2">
                                <label class="form-check-label" for="flexRadioDefault2">
                                  Not Approved
                                </label>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <label>Approved By Client</label>
                            </div>
                            <div class="col-md-8 form-group">
                              <div class="form-check">
                                <input class="form-check-input" {{ $project->approved_by_client == 1 ? "checked" : "" }} type="radio" value="1" name="approved_by_client" id="flexRadioDefault1">
                                <label class="form-check-label" for="flexRadioDefault1">
                                  Approved
                                </label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" {{ $project->approved_by_client == 0 ? "checked" : "" }} type="radio" value="0" name="approved_by_client" id="flexRadioDefault2">
                                <label class="form-check-label" for="flexRadioDefault2">
                                  Not Approved
                                </label>
                              </div>
                            </div>
                            <div class="col-sm-12 d-flex align-items-center justify-content-between">
                              <div>
                                <a href="tel:{{ $project->company->phone_number }}" class="btn btn-success me-1 mb-1">
                                  <span>
                                    <i class="bi bi-telephone"></i>
                                  </span>
                                  Telephone
                                </a>
                                <a href="https://wa.me/{{ $project->company->phone_number }}" class="btn btn-success me-1 mb-1">
                                  <span>
                                    <i class="bi bi-whatsapp"></i>
                                    Whatsapp
                                  </span>
                                </a>
                                <a href="mailto:{{ $project->company->user->email }}" class="btn btn-success me-1 mb-1">
                                  <span>
                                    <i class="bi bi-envelope-plus"></i>
                                    Email
                                  </span>
                                </a>
                              </div>
                              <button type="submit" class="btn btn-primary btn-lg me-1 px2 mb-1">Submit</button>
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

