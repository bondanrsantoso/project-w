@extends('dashboard.index')

@section('css')
    {{-- CSS here --}}
@endsection

@section('header')
    <h1>Test Detail</h1>
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
            <div class="row row-gap-2">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="card-title">
                                Test Information: {{ $test->title }}
                            </h2>
                            <p class="my-1">
                                <span class="bi bi-clock me-2"></span>
                                {{ (new DateTime($test->start_time))->format('d M Y H:i') }}
                                -
                                {{ (new DateTime($test->end_time))->format('d M Y H:i') }}
                            </p>
                            <p class="my-1">
                                <span class="bi bi-clipboard me-2"></span>
                                Passing Grade: {{ $test->passing_grade }}%
                            </p>
                            <p class="lead">
                                {{ $test->description }}
                            </p>
                            <p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <label
                                for="raw_grade"
                                class="card-title"
                            >
                                Raw Grade (computer generated)
                            </label>
                            <div class="input-group">
                                <input
                                    type="number"
                                    name="raw_grade"
                                    id="raw_grade"
                                    class="form-control fs-3 text-end"
                                    disabled="disabled"
                                    value="{{ $session->raw_grade }}"
                                >
                                <span class="input-group-text fs-3">%</span>
                            </div>
                            {{-- <span class="form-text">&nbsp;</span> --}}
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <label
                                for="grade_override"
                                class="card-title"
                            >
                                Grade Override
                            </label>
                            <div class="input-group">
                                <input
                                    type="number"
                                    name="grade_override"
                                    id="grade_override"
                                    max=100
                                    class="form-control fs-3 text-end"
                                    value="{{ $session->grade_override }}"
                                    disabled
                                >
                                <span class="input-group-text fs-3">%</span>
                            </div>
                            {{-- <span class="form-text">Fill this if you think that the raw grade is incorrect</span> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <h2>
                Answers
            </h2>
            <div class="row">
                @php
                    $i = 0;
                @endphp
                @foreach ($session->answers as $answer)
                    <div class="col-12 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h2 class="card-title">
                                    {{ $i + 1 }}.
                                    {{ $answer->trainingTestItem->statement }}
                                </h2>
                                <p>Weight: {{ $answer->trainingTestItem->weight }}</p>
                                <input
                                    type="hidden"
                                    name="answers[{{ $i }}][id]"
                                    value="{{ $answer->id }}"
                                    class="form-check-input"
                                >
                                <input
                                    type="hidden"
                                    name="answers[{{ $i }}][is_correct]"
                                    value="0"
                                    class="form-check-input"
                                >
                                <div class="form-check my-2">
                                    <input
                                        type="checkbox"
                                        name="answers[{{ $i }}][is_correct]"
                                        id="answers-{{ $i }}-is_correct"
                                        value="1"
                                        @checked($answer->is_correct)
                                        class="form-check-input"
                                    >
                                    <label
                                        for="answers-{{ $i }}-is_correct"
                                        class="form-check-label"
                                    >
                                        Mark as correct
                                    </label>
                                </div>
                                @if ($answer->trainingTestItem->answer_literal)
                                    <div class="form-group">
                                        <label class="form-label">Expected text answer</label>
                                        <input
                                            type="text"
                                            disabled
                                            class="form-control"
                                            value="{{ $answer->trainingTestItem->answer_literal }}"
                                        >
                                    </div>
                                @endif
                                @if ($answer->answer_literal)
                                    <div class="form-group">
                                        <label class="form-label">Expected text answer</label>
                                        <input
                                            type="text"
                                            disabled
                                            class="form-control"
                                            value="{{ $answer->trainingTestItem->answer_literal }}"
                                        >
                                    </div>
                                @endif
                                @if ($answer->trainingTestItem->options->first())
                                    <ul class="list-group">
                                        @foreach ($answer->trainingTestItem->options as $option)
                                            <li class="list-group-item">
                                                <div class="form-check w-100">
                                                    <input
                                                        type="checkbox"
                                                        class="form-check-input"
                                                        disabled="disabled"
                                                        @checked($option->id == $answer->answer_option_id)
                                                    >
                                                    <label class="form-check-label">
                                                        {{ $option->statement }}
                                                        @if ($option->is_answer)
                                                            <span class="badge rounded-pill text-bg-success">
                                                                Expected answer
                                                            </span>
                                                        @endif
                                                    </label>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                    @php
                        $i++;
                    @endphp
                @endforeach
            </div>
        </div>
        <div class="col-12 sticky-bottom">
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
        </div>
    </form>
@endsection
