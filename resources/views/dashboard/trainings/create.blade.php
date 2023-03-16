@extends('dashboard.index')

@section('css')
    <style>
        #training-benefits-wrapper>div:last-child .close {
            display: none;
        }
    </style>
@endsection

@section('header')
    <h3>
        @isset($training)
            Edit
        @else
            Create
        @endisset
        Training
    </h3>
@endsection

@section('content')
    <section id="basic-horizontal-layouts">
        @if ($errors->any())
            <div class="alert alert-danger">
                <h3>Error</h3>
                <ul>
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <form
                        class="form form-horizontal"
                        {{--
                    action="{{ env('APP_DOMAIN_PM', 'http://pm-admin.docu.web.id') }}/dashboard/jobs" --}}
                        action="{{ url('/dashboard/training_events') }}/{{ isset($training) ? $training->id : '' }}"
                        method="POST"
                        enctype="multipart/form-data"
                        id="main-form"
                    >
                        @csrf
                        @isset($training)
                            @method('PUT')
                        @endisset
                        <div class="row row-gap-2">
                            <div class="col-12 form-group">
                                <label
                                    for="image"
                                    class="d-flex justify-content-center w-100"
                                >
                                    <img
                                        src="{{ old('image_url', isset($training) ? $training->image_url : 'https://placehold.jp/3d4070/ffffff/600x600.jpg?text=Upload%20image') }}"
                                        alt="Event image thumbnail"
                                        class="w-100"
                                        id="preview-image"
                                        style="max-width: 600px;"
                                    >
                                </label>
                                <label
                                    for="image"
                                    class="form-label"
                                >Training Image</label>
                                <input
                                    type="file"
                                    name="image"
                                    id="image"
                                    class="form-control"
                                    accept="image/*"
                                    data-preview-to="#preview-image"
                                >
                            </div>
                            <div class="col-12 form-group">
                                <label
                                    for="name"
                                    class="form-label"
                                >Training Name</label>
                                <input
                                    type="text"
                                    name="name"
                                    id="name"
                                    class="form-control"
                                    required
                                    value="{{ old('name', isset($training) ? $training->name : '') }}"
                                >
                            </div>
                            <div class="col-12 form-group">
                                <label
                                    for="description"
                                    class="form-label"
                                >Description</label>
                                <textarea
                                    name="description"
                                    id="description"
                                    class="form-control"
                                    required
                                >{{ old('description', isset($training) ? $training->description : '') }}</textarea>
                            </div>
                            <div class="col-12 col-md-6 form-group">
                                <label
                                    for="start_date"
                                    class="form-label"
                                >Starting Date</label>
                                <input
                                    type="datetime-local"
                                    name="start_date"
                                    id="start_date"
                                    class="form-control"
                                    value="{{ old('start_date', isset($training) ? $training->start_date : date('Y-m-d H:i:s')) }}"
                                    required
                                >
                            </div>
                            <div class="col-12 col-md-6 form-group">
                                <label
                                    for="end_date"
                                    class="form-label"
                                >Ending Date</label>
                                <input
                                    type="datetime-local"
                                    name="end_date"
                                    id="end_date"
                                    class="form-control"
                                    value="{{ old('end_date', isset($training) ? $training->end_date : date('Y-m-d H:i:s')) }}"
                                    required
                                >
                            </div>
                            <div class="col-12 form-group">
                                <label
                                    for="location"
                                    class="form-label"
                                >Location Address</label>
                                <input
                                    type="text"
                                    name="location"
                                    id="location"
                                    class="form-control"
                                    value="{{ old('location', isset($training) ? $training->location : '') }}"
                                >
                            </div>
                            <div class="col-12 col-md-6 form-group">
                                <label
                                    for="sessions"
                                    class="form-label"
                                >Session</label>
                                <div class="input-group">
                                    <input
                                        type="number"
                                        name="sessions"
                                        id="sessions"
                                        class="form-control"
                                        min="1"
                                        required
                                        value="{{ old('sessions', isset($training) ? $training->sessions : 1) }}"
                                    >
                                    <span class="input-group-text">session(s)</span>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 form-group">
                                <label
                                    for="seat"
                                    class="form-label"
                                ># of Seats</label>
                                <div class="input-group">
                                    <input
                                        type="number"
                                        name="seat"
                                        id="seat"
                                        class="form-control"
                                        min="1"
                                        required
                                        value="{{ old('seat', isset($training) ? $training->seat : 1) }}"
                                    >
                                    <span class="input-group-text">seats</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="category_id">Category</label>
                                <select
                                    name="category_id"
                                    id="category_id"
                                    class="form-select"
                                    required="required"
                                >
                                    @foreach ($categories as $cat)
                                        <option
                                            value="{{ $cat->id }}"
                                            @selected(old('category_id', isset($training) ? $training->category_id : null) == $cat->id)
                                        >
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <hr>
                            </div>
                            <div class="col-12">
                                <h4>Training benefits</h4>
                                <div
                                    class="row"
                                    id="training-benefits-wrapper"
                                >
                                    @php
                                        $i = 0;
                                    @endphp
                                    @if (sizeof(old('benefits', [])))
                                        @foreach (old('benefits') as $benefit)
                                            <div class="col-12 col-md-6 col-lg-4">
                                                <div class="card border position-relative">
                                                    <button
                                                        class="btn btn-secondary position-absolute close"
                                                        type="button"
                                                        style="top: -0.5em; right: -0.5em;"
                                                    >
                                                        &times;
                                                    </button>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-12 form-group">
                                                                <label for="benefit-{{ $i }}-title">
                                                                    Benefit
                                                                    Title
                                                                </label>
                                                                <input
                                                                    type="text"
                                                                    name="benefits[{{ $i }}][title]"
                                                                    id="benefit-{{ $i }}-title"
                                                                    value="{{ $benefit['title'] }}"
                                                                    class="form-control benefit-title-input"
                                                                    required
                                                                >
                                                            </div>
                                                            <div class="col-12 form-group">
                                                                <label for="benefit-{{ $i }}-description">
                                                                    Benefit
                                                                    Description
                                                                </label>
                                                                <textarea
                                                                    name="benefits[{{ $i }}][description]"
                                                                    id="benefit-{{ $i++ }}-description"
                                                                    class="form-control"
                                                                    required
                                                                >{{ $benefit['description'] }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        @isset($training)
                                            @foreach ($training->benefits as $benefit)
                                                <div class="col-12 col-md-6 col-lg-4">
                                                    <input
                                                        type="hidden"
                                                        name="benefits[{{ $i }}][title]"
                                                        value="{{ $benefit->id }}"
                                                    >
                                                    <div class="card border position-relative">
                                                        <button
                                                            class="btn btn-secondary position-absolute close"
                                                            type="button"
                                                            style="top: -0.5em; right: -0.5em;"
                                                        >
                                                            &times;
                                                        </button>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-12 form-group">
                                                                    <label for="benefit-{{ $i }}-title">
                                                                        Benefit
                                                                        Title
                                                                    </label>
                                                                    <input
                                                                        type="text"
                                                                        name="benefits[{{ $i }}][title]"
                                                                        id="benefit-{{ $i }}-title"
                                                                        value="{{ $benefit->title }}"
                                                                        class="form-control benefit-title-input"
                                                                        required
                                                                    >
                                                                </div>
                                                                <div class="col-12 form-group">
                                                                    <label for="benefit-{{ $i }}-description">
                                                                        Benefit
                                                                        Description
                                                                    </label>
                                                                    <textarea
                                                                        name="benefits[{{ $i }}][description]"
                                                                        id="benefit-{{ $i++ }}-description"
                                                                        class="form-control"
                                                                        required
                                                                    >{{ $benefit->description }}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endisset
                                    @endif
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="card border position-relative">
                                            <button
                                                class="btn btn-secondary position-absolute close"
                                                type="button"
                                                style="top: -0.5em; right: -0.5em;"
                                            >
                                                &times;
                                            </button>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 form-group">
                                                        <label for="benefit-{{ $i }}-title">
                                                            Benefit
                                                            Title
                                                        </label>
                                                        <input
                                                            type="text"
                                                            name="benefits[{{ $i }}][title]"
                                                            id="benefit-{{ $i }}-title"
                                                            class="form-control benefit-title-input"
                                                        >
                                                    </div>
                                                    <div class="col-12 form-group">
                                                        <label for="benefit-{{ $i }}-description">
                                                            Benefit
                                                            Description
                                                        </label>
                                                        <textarea
                                                            name="benefits[{{ $i }}][description]"
                                                            id="benefit-{{ $i }}-description"
                                                            class="form-control benefit-description-input"
                                                        ></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >Save</button>
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
        const benefitTitleInputs = document.querySelectorAll(".benefit-title-input");
        const benefitDescriptionInputs = document.querySelectorAll(".benefit-description-input");

        const benefitsWrapper = document.getElementById("training-benefits-wrapper");
        const removeBtns = benefitsWrapper.querySelectorAll(".close");

        function isLastInputEmpty() {
            const inputs = benefitsWrapper.lastElementChild.querySelectorAll(
                ".benefit-title-input, .benefit-description-input")
            const filled = [...inputs].find(i => i.value.trim()); // find first filled input

            return !filled;
        }
    </script>
    <script>
        /**
         * @type {HTMLFormElement}
         */
        const form = document.getElementById("main-form");
        let isFormClean = false;

        form.addEventListener("submit", e => {
            if (!isFormClean) {
                e.preventDefault();
                if (isLastInputEmpty()) {
                    benefitsWrapper.lastElementChild.remove();
                }
                isFormClean = true;
                form.submit();
            }
        })
    </script>
    <script>
        // This is a script to handle dynamic benefit input allocation
        function rectifyIdAndName() {
            let i = 0;
            for (const benefitItem of benefitsWrapper.children) {
                const inputs = benefitItem.querySelectorAll("input, textarea");
                const labels = benefitItem.querySelectorAll("label");
                for (const input of inputs) {
                    input.name = input.name.replace(/\d+/, i);
                    input.id = input.id.replace(/\d+/, i);
                }

                for (const label of labels) {
                    label.setAttribute("for", label.getAttribute("for").replace(/\d+/, i));
                }

                i++;
            }
        }
        /**
         * @param {MouseEvent} e
         */
        function handleDeleteBenefit(e) {
            if (benefitsWrapper.children.length > 1) {
                e.target.parentElement.parentElement.remove();
                rectifyIdAndName();
            }
        }
        for (const btn of removeBtns) {
            btn.addEventListener("click", handleDeleteBenefit);
        }

        /**
         * @param {InputEvent} e
         */
        function benefitInputChangeHandler(e) {
            e.target.required = !!e.target.value.trim();
            isFormClean = false;

            const benefitInputGroup = e.target.parentElement.parentElement;
            const relevantInputs = benefitInputGroup.querySelectorAll(".benefit-title-input, .benefit-description-input");

            const filled = [...relevantInputs].find(i => i.value.trim()); // find first filled input
            const isFilled = !!filled;

            if (isFilled && !isLastInputEmpty()) {
                const lastBenefitItem = benefitsWrapper.lastElementChild.cloneNode(true);
                benefitsWrapper.appendChild(lastBenefitItem);

                // select newly created inputs
                const lastInputs = benefitsWrapper.lastElementChild.querySelectorAll(
                    ".benefit-title-input, .benefit-description-input");
                for (const i of lastInputs) {
                    i.value = "";
                    i.addEventListener("input", benefitInputChangeHandler);
                    i.required = false;
                }

                // clean up any hidden inputs (usually for ID-ing)
                for (const hidden of benefitsWrapper.lastElementChild.querySelectorAll("input[type=hidden]")) {
                    hidden.remove();
                }

                // select newly created close button
                benefitsWrapper.lastElementChild.querySelector(".close").addEventListener("click", handleDeleteBenefit);

                rectifyIdAndName();
            } else if (!isFilled && benefitsWrapper.children.length > 1) {
                benefitInputGroup.parentElement.parentElement.parentElement.remove();
                rectifyIdAndName();
            }
        }

        for (const benefitTitleInput of benefitTitleInputs) {
            benefitTitleInput.addEventListener("input", benefitInputChangeHandler);
        }
        for (const benefitDescriptionInput of benefitDescriptionInputs) {
            benefitDescriptionInput.addEventListener("input", benefitInputChangeHandler);
        }
    </script>
    <script>
        $(document).ready(function() {
            console.log("aaaa");
            $('#body').css('background-color', '#678983');
        });
    </script>
@endsection
