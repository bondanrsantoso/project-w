@extends('dashboard.index')

@section('header')
    <h3>Update Project</h3>
@endsection

@section('content')
    <section id="basic-horizontal-layouts">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <form
                        class="form form-horizontal"
                        action="{{ env('APP_DOMAIN_PM', 'http://pm-admin.docu.web.id') }}/dashboard/projects/{{ $project->id }}"
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
                                    <label>Service Pack ID (optional)</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input
                                        type="text"
                                        id="first-name"
                                        class="form-control"
                                        name="service_pack_id"
                                        placeholder="Service Pack ID"
                                        value="{{ $project->service_pack_id }}"
                                    >
                                </div>
                                <div class="col-md-4">
                                    <label>Name</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input
                                        type="text"
                                        id="name"
                                        class="form-control"
                                        name="name"
                                        placeholder="name"
                                        value="{{ $project->name }}"
                                    >
                                </div>
                                <div class="col-md-4">
                                    <label>Description</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <textarea
                                        class="form-control"
                                        id="exampleFormControlTextarea1"
                                        name="description"
                                        placeholder="Description"
                                        rows="5"
                                    >{{ $project->description }}</textarea>
                                </div>
                                <div class="col-md-4">
                                    <label>Budget</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">Rp.</span>
                                        <input
                                            type="text"
                                            name="budget"
                                            placeholder="Budget"
                                            class="form-control"
                                            aria-label="Amount (to the nearest dollar)"
                                            value="{{ $project->budget }}"
                                        >
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label>Allocated Budget</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">Rp.</span>
                                        <input
                                            disabled
                                            type="text"
                                            placeholder="Budget"
                                            class="form-control"
                                            value="{{ $project->allocated_fund }}"
                                        >
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label>Approved By Admin</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            @checked($project->approved_by_admin)
                                            value="1"
                                            type="radio"
                                            name="approved_by_admin"
                                            id="flexRadioDefault1"
                                        >
                                        <label
                                            class="form-check-label"
                                            for="flexRadioDefault1"
                                        >
                                            Approved
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            @checked(!$project->approved_by_admin)
                                            type="radio"
                                            value="0"
                                            name="approved_by_admin"
                                            id="flexRadioDefault2"
                                        >
                                        <label
                                            class="form-check-label"
                                            for="flexRadioDefault2"
                                        >
                                            Not Approved
                                        </label>
                                    </div>
                                </div>
                                {{-- <div class="col-md-4">
                                    <label>Approved By Client</label>
                                </div> --}}
                                {{-- <div class="col-md-8 form-group">
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            {{ $project->approved_by_client == 1 ? 'checked' : '' }}
                                            type="radio"
                                            value="1"
                                            name="approved_by_client"
                                            id="flexRadioDefault1"
                                        >
                                        <label
                                            class="form-check-label"
                                            for="flexRadioDefault1"
                                        >
                                            Approved
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            {{ $project->approved_by_client == 0 ? 'checked' : '' }}
                                            type="radio"
                                            value="0"
                                            name="approved_by_client"
                                            id="flexRadioDefault2"
                                        >
                                        <label
                                            class="form-check-label"
                                            for="flexRadioDefault2"
                                        >
                                            Not Approved
                                        </label>
                                    </div>
                                </div> --}}
                                <div class="col-sm-12 d-flex align-items-center justify-content-between">
                                    <div>
                                        <a
                                            href="tel:{{ $project->company->phone_number }}"
                                            class="btn btn-success me-1 mb-1"
                                        >
                                            <span>
                                                <i class="bi bi-telephone"></i>
                                            </span>
                                            Telephone
                                        </a>
                                        <a
                                            href="https://wa.me/{{ $project->company->phone_number }}"
                                            class="btn btn-success me-1 mb-1"
                                        >
                                            <span>
                                                <i class="bi bi-whatsapp"></i>
                                                Whatsapp
                                            </span>
                                        </a>
                                        <a
                                            href="mailto:{{ $project->company->user->email }}"
                                            class="btn btn-success me-1 mb-1"
                                        >
                                            <span>
                                                <i class="bi bi-envelope-plus"></i>
                                                Email
                                            </span>
                                        </a>
                                    </div>
                                    <button
                                        type="submit"
                                        class="btn btn-primary btn-lg me-1 px2 mb-1"
                                    >Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <section class="basic-horizontal-layout mt-2">
        <div class="row row-gap-2">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header d-flex justify-content-between">
                            <h3 class="card-title">Workgroup List</h3>
                            <a
                                class="btn btn-primary"
                                href="{{ url('/dashboard/projects/' . $project->id . '/workgroups/create') }}"
                            >
                                <i class="bi bi-pencil me-2"></i>
                                <span>
                                    Create Workgroup
                                </span>
                            </a>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Created at</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($project->workgroups as $wg)
                                        <tr>
                                            <td>
                                                <a
                                                    href="{{ env('APP_DOMAIN_PM', 'http://pm-admin.docu.web.id') }}/dahsboard/workgroups/{{ $wg->id }}/edit">
                                                    {{ $wg->name }}
                                                </a>
                                            </td>
                                            <td>
                                                {{ $wg->created_at }}
                                            </td>
                                            <td>
                                                <form
                                                    action="{{ env('APP_DOMAIN_PM', 'http://pm-admin.docu.web.id') }}/dashboard/wgs/{{ $wg->id }}"
                                                    method="POST"
                                                    id="form-wg-delete-{{ $wg->id }}"
                                                >
                                                    @csrf
                                                    <input
                                                        type="hidden"
                                                        name="_method"
                                                        value="DELETE"
                                                    />
                                                </form>
                                                <div class="btn-group">
                                                    <a
                                                        href="{{ env('APP_DOMAIN_PM', 'http://pm-admin.docu.web.id') }}/dashboard/workgroups/{{ $wg->id }}/jobs"
                                                        class="btn btn-primary  me-2"
                                                    >
                                                        <span>
                                                            <i class="bi bi-briefcase"></i>
                                                        </span>
                                                    </a>
                                                    <a
                                                        href="{{ env('APP_DOMAIN_PM', 'http://pm-admin.docu.web.id') }}/dashboard/workgroups/{{ $wg->id }}/edit"
                                                        class="btn btn-success  me-2"
                                                    >
                                                        <span>
                                                            <i class="bi bi-pencil-square"></i>
                                                        </span>
                                                    </a>
                                                    <button
                                                        type="submit"
                                                        class="btn btn-danger"
                                                        form="form-wg-delete-{{ $wg->id }}"
                                                    >
                                                        <i class="bi bi-trash3"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
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
