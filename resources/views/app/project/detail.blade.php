@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <h1 class="border-bottom">Detail Proyek</h1>
        <div class="row mt-4 align-items-stretch">
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-body d-flex flex-column gap-3">
                        <div>
                            <h6>Nama Proyek</h6>
                            <p class="lead fw-bold m-0">Proyek Lorem Ipsum</p>
                        </div>
                        <div>
                            <h6>Detail Proyek</h6>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Recusandae itaque rerum ex officiis similique, asperiores illo unde, laboriosam quidem molestias repudiandae, fugiat alias. Aliquid neque illum mollitia in fugiat odio.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-8">
                <div class="list-group h-100 w-100 list-group-horizontal-md flex-wrap">
                    <div class="list-group-item flex-grow-1">
                        <div class="h-100 d-flex justify-content-center align-items-center flex-column">
                            <h6>Status</h6>
                            <p class="display-6">Berlangsung</p>
                        </div>
                    </div>
                    <div class="list-group-item flex-grow-1">
                        <div class="h-100 d-flex justify-content-center align-items-center flex-column">
                            <h6>Pekerjaan selesai</h6>
                            <p class="display-6">5 <span class="fs-3">/ 10</span></p>
                        </div>
                    </div>
                    <div class="list-group-item flex-grow-1">
                        <div class="h-100 d-flex justify-content-center align-items-center flex-column">
                            <h6>Budget</h6>
                            <p class="display-6">Rp20.000.000</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4>Daftar Pekerjaan</h4>
                        {{-- <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <button
                                    class="nav-link active"
                                    id="list-tab"
                                    data-bs-toggle="tab"
                                    data-bs-toggle="#list-tab-pane">
                                    <i>List</i>
                                </button>
                            </li>
                            <li class="nav-item">
                                <button
                                    class="nav-link active"
                                    id="table-tab"
                                    data-bs-toggle="tab"
                                    data-bs-toggle="#table-tab-pane">
                                    Tabel
                                </button>
                            </li>
                        </ul> --}}
                        <div class="tab-content">
                            <div class="tab-pane show active" id="list-tab-pane">
                                <div class="row">
                                    @for($i = 0; $i < rand(3,10); $i++)
                                        <div class="col-12 col-md-6 col-lg-4">
                                            <div class="card my-2">
                                                <div class="card-header d-flex justify-content-between align-items-center">
                                                    <h5 class="card-title m-0">Pekerjaan #{{$i + 1}}</h5>
                                                    <div class="d-flex gap-3 align-items-center">
                                                        <img src="https://boredhumans.b-cdn.net/faces2/{{rand(1,500)}}.jpg" alt="pekerja" class="ratio ratio-1x1 rounded-circle" style="width: 48px;">
                                                        Jonah Doe
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="badge bg-primary mb-1">Berlangsung</div>
                                                    <p>
                                                        Deskripsi Pekerjaan #{{$i + 1}}.
                                                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Quos error, obcaecati odit tempore neque ullam ducimus sequi nemo doloremque soluta, minus voluptates, alias ipsum praesentium dolorum nisi magni. Tempora, quae?
                                                    </p>
                                                    <div>
                                                        <b>Budget: </b>
                                                        Rp{{number_format(rand(500, 4500) * 1000, 2, ",", ".")}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
