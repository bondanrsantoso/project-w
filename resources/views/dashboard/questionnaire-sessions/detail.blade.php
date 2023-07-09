@extends('dashboard.index')

@section('css')
    {{-- CSS here --}}
@endsection

@section('header')
    <h1>Questionnaire Detail</h1>
@endsection

@section('content')
    <form
        action="{{ url()->current() }}"
        class="row row-gap-2"
        method="POST"
    >
        @csrf
        @method('PUT')
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
                                src="{{ $session->user->image_url }}"
                                alt="User's photo"
                                class="rounded-circle"
                            >
                        </div>
                    </div>
                    <div>
                        <p class="fs-4 m-2">
                            {{ $session->user->name }}
                        </p>
                        <p class="m-1">
                            <span class="bi bi-file-person me-2"></span>
                            {{ $session->user->username }}
                        </p>
                        <p class="m-1">
                            <span class="bi bi-envelope me-2"></span>
                            {{ $session->user->email }}
                        </p>
                        <p class="m-1">
                            <span class="bi bi-telephone me-2"></span>
                            {{ $session->user->phone_number ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-8">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="card-title">
                                Suggested Service Packs
                            </h2>
                            <table
                                class="table"
                                id="suggestion-table"
                            >
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Will be populated with datatable --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="card-title">
                                Questionnaire Answers
                            </h2>
                            <table
                                class="table"
                                id="question-table"
                            >
                                <thead>
                                    <tr>
                                        <th>Question</th>
                                        <th>Answer</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Will be populated with datatable --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="col-12 sticky-bottom">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-end">
                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div> --}}
    </form>
@endsection
@section('scripts')
    <script
        type="text/javascript"
        src="https://cdn.datatables.net/v/bs5/dt-1.13.1/r-2.4.0/sb-1.4.0/datatables.min.js"
    ></script>
    <script>
        const suggestionTable = new DataTable(
            "#suggestion-table", {
                data: {!! json_encode($session->suggestions) !!},
                columns: [{
                        name: "name",
                        data: "name"
                    },
                    {
                        name: "description",
                        data: "description"
                    },
                ]
            }
        );
    </script>
    <script>
        const answerTable = new DataTable(
            "#question-table", {
                data: {!! json_encode($session->questions) !!},
                columns: [{
                        name: "statement",
                        data: "statement"
                    },
                    {
                        name: "answered.answer",
                        data: data => data.answered?.answer ? "Yes" : "No"
                    }
                ]
            }
        );
    </script>
@endsection
