@extends('dashboard.index')

@section('css')
    <link
        rel="stylesheet"
        type="text/css"
        href="https://cdn.datatables.net/v/bs5/dt-1.13.1/r-2.4.0/sb-1.4.0/datatables.min.css"
    />
@endsection

@section('header')
    <h1>Participant Detail</h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">User Data</h2>
                    <div class="d-flex justify-content-center my-4">
                        <div
                            class="ratio ratio-1x1"
                            style="max-width:200px"
                        >
                            <img
                                src="{{ $participant->user->image_url }}"
                                alt="User's photo"
                                class="rounded-circle"
                            >
                        </div>
                    </div>
                    <div>
                        <p class="fs-4 m-2">
                            {{ $participant->user->name }}
                        </p>
                        <p class="m-1">
                            <span class="bi bi-file-person me-2"></span>
                            {{ $participant->user->username }}
                        </p>
                        <p class="m-1">
                            <span class="bi bi-envelope me-2"></span>
                            {{ $participant->user->email }}
                        </p>
                        <p class="m-1">
                            <span class="bi bi-telephone me-2"></span>
                            {{ $participant->user->phone_number ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-8">
            <div class="row row-gap-2">
                <div class="col-12">
                    <div
                        class="card"
                        style="overflow: hidden"
                    >
                        <div
                            class="card-body p-0"
                            style="overflow: hidden"
                        >
                            <div class="d-flex">
                                <div class="w-25">
                                    <img
                                        src="{{ $participant->event->image_url }}"
                                        alt="Training"
                                        style="object-fit: cover;"
                                        class="w-100 h-100"
                                    >
                                </div>
                                <div class="w-75 p-3">
                                    <h2 class="card-title">{{ $participant->event->name }}</h2>
                                    <p class="my-1">
                                        <span class="bi bi-clock me-2"></span>
                                        {{ (new DateTime($participant->event->start_date))->format('d M Y H:i') }}
                                        -
                                        {{ (new DateTime($participant->event->end_date))->format('d M Y H:i') }}
                                    </p>
                                    <p class="my-1">
                                        <span class="bi bi-geo me-2"></span>
                                        {{ $participant->event->location }}
                                    </p>
                                    {{-- <hr>
                            <p class="my-1">
                                {{ $participant->event->description }}
                            </p> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="card-title">
                                Pretest Score
                            </h2>
                            <p class="fs-3">
                                {{ $pretestScore }}%
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="card-title">Test(s) taken</h2>
                            <p class="fs-3">
                                {{ $sessions->count() ?? '0' }}x
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">
                        Test History
                    </h2>
                    <table
                        class="table"
                        id="test-table"
                    >
                        <thead>
                            <tr>
                                <th>Test Name</th>
                                <th>Pretest</th>
                                <th>Grade</th>
                                <th>Grade (corrected)</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- <script>
        $(document).ready(function() {
            console.log("aaaa");
            $('#body').css('background-color', '#678983');
        });
    </script> --}}
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
    <script
        type="text/javascript"
        src="https://cdn.datatables.net/v/bs5/dt-1.13.1/r-2.4.0/sb-1.4.0/datatables.min.js"
    ></script>
    <script>
        const testTable = new DataTable("#test-table", {
            data: {!! json_encode($sessions ?? []) !!},
            columns: [{
                    name: "test_title",
                    data: "test_title"
                },
                {
                    name: "is_pretest",
                    data: data => data.is_pretest ?
                        `<span class="badge rounded-pill text-bg-primary">Pretest</span>` : '',
                },
                {
                    name: "raw_grade",
                    data: data => `${data.raw_grade ?? 0}%`
                },
                {
                    name: "grade_override",
                    data: data => data.grade_override ?
                        `${data.grade_override}%` : '-',
                },
                {
                    name: "created_at",
                    data: data => dayjs(data.creted_at).format("DD MMMM YYYY HH:mm")
                },
                {
                    name: "id",
                    data: data => `
                        <a href="{{ url('dashboard/training_test_sessions') }}/${data.id}" class="btn btn-outline-primary">
                            <span class="bi bi-eye"></span>
                        </a>
                    `,
                    sortable: false
                },
            ]
        })
    </script>
@endsection
