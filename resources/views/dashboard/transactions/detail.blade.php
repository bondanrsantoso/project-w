@extends('dashboard.index')

@section('header')
<h3>Update Transaction</h3>
@endsection

@section('content')
<section id="basic-horizontal-layouts">
    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <form class="form form-horizontal" action="{{ env('APP_DOMAIN_PM','http://pm-admin.docu.web.id') }}/dashboard/projects/{{ $project['id'] }}" method="POST">
                    @csrf

                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Service Pack ID (optional)</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" id="first-name" class="form-control" name="service_pack_id"
                                    placeholder="Service Pack ID" value="{{ $project['service_pack_id'] }}">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
