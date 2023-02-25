@extends('dashboard.index')

@section('css')
    <link
        rel="stylesheet"
        type="text/css"
        href="https://cdn.datatables.net/v/bs5/dt-1.13.1/r-2.4.0/sb-1.4.0/datatables.min.css"
    />
    <style>
        #test-item-container>div:first-child .btn-delete {
            display: none;
        }
    </style>
@endsection

@section('header')
    <h3>
        @isset($trainingTest)
            Edit
        @else
            Create
        @endisset
        Training Test
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
        <form
            action="{{ url('/dashboard/training_tests') }}/{{ isset($trainingTest) ? $trainingTest->id : '' }}"
            method="POST"
            enctype="multipart/form-data"
            id="main-form"
        >
            @csrf
            @isset($trainingTest)
                @method('PUT')
            @endisset
            <div class="row">
                <div class="col-12 col-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Training Test Information</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-column gap-2">
                                <div class="form-group">
                                    <label
                                        for="title"
                                        class="form-label"
                                    >Test Title</label>
                                    <input
                                        type="text"
                                        name="title"
                                        value="{{ old('title', isset($trainingTest) ? $trainingTest->title : '') }}"
                                        id="title"
                                        class="form-control"
                                        required
                                    >
                                </div>
                                <input
                                    type="hidden"
                                    name="is_pretest"
                                    value="0"
                                >
                                <div class="form-check">
                                    <input
                                        type="checkbox"
                                        name="is_pretest"
                                        id="is_pretest"
                                        class="form-check-input"
                                        value="1"
                                        @checked(old('is_pretest', isset($trainingTest) ? $trainingTest->is_pretest : false))
                                    >
                                    <label
                                        for="is_pretest"
                                        class="form-check-label"
                                    >
                                        Pretest
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label
                                        for="description"
                                        class="form-label"
                                    >
                                        Test Description
                                    </label>
                                    <textarea
                                        name="description"
                                        id="description"
                                        class="form-control"
                                        required
                                    >{{ old('description', isset($trainingTest) ? $trainingTest->description : '') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label
                                        for="start_time"
                                        class="form-label"
                                    >
                                        Scheduled Test Starting date/time
                                    </label>
                                    <input
                                        type="datetime-local"
                                        name="start_time"
                                        id="start_time"
                                        class="form-control"
                                        value="{{ old('start_time', isset($trainingTest) ? $trainingTest->start_time : '') }}"
                                    >
                                    <span class="form-text">Leave it blank to make the test available anytime</span>
                                </div>
                                <div class="form-group">
                                    <label
                                        for="end_time"
                                        class="form-label"
                                    >
                                        Scheduled Test Ending date/time
                                    </label>
                                    <input
                                        type="datetime-local"
                                        name="end_time"
                                        id="end_time"
                                        class="form-control"
                                        value="{{ old('end_time', isset($trainingTest) ? $trainingTest->end_time : '') }}"
                                    >
                                    <span class="form-text">Leave it blank to make the test available anytime</span>
                                </div>
                                <div class="row row-gap-2">
                                    <div class="col-12 col-lg-6">
                                        <div class="form-group">
                                            <label
                                                for="duration"
                                                class="form-label"
                                            >
                                                Test Duration
                                            </label>
                                            <div class="input-group">
                                                <input
                                                    type="number"
                                                    name="duration"
                                                    id="duration"
                                                    class="form-control"
                                                    min="1"
                                                    value="{{ old('duration', isset($trainingTest) ? $trainingTest->duration : '') }}"
                                                >
                                                <span class="input-group-text">
                                                    minute(s)
                                                </span>
                                            </div>
                                            <span class="form-text">Leave it blank to remove time limit</span>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <div class="form-group">
                                            <label
                                                for="passing_grade"
                                                class="form-label"
                                            >Passing Grade</label>
                                            <div class="input-group">
                                                <input
                                                    type="number"
                                                    name="passing_grade"
                                                    id="passing_grade"
                                                    class="form-control"
                                                    max="100"
                                                    value="{{ old('passing_grade', isset($trainingTest) ? $trainingTest->passing_grade : '') }}"
                                                >
                                                <div class="input-group-text">
                                                    &percnt;
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label
                                        for="attempt_limit"
                                        class="form-label"
                                    >Attempt Limit</label>
                                    <div class="input-group">
                                        <input
                                            type="number"
                                            name="attempt_limit"
                                            id="attempt_limit"
                                            class="form-control"
                                            value="{{ old('attempt_limit', isset($trainingTest) ? $trainingTest->attempt_limit : '') }}"
                                        >
                                        <span class="input-group-text">x</span>
                                    </div>
                                    <span class="form-text">Leave empty for unlimited attempts</span>
                                </div>
                                <div>
                                    <hr>
                                </div>
                                <div class="form-group">
                                    <h6>Associated Training</h6>
                                    <span class="form-text">
                                        <b>Important!</b> Choose one training item from the table below.
                                    </span>
                                    @if (isset($trainingTest))
                                        <span class="form-text">
                                            Otherwise it'll be set to {{ $trainingTest->trainingItem->name }} (unchanged).
                                        </span>
                                        <input
                                            type="hidden"
                                            name="training_id"
                                            value="{{ $trainingTest->training_id }}"
                                        >
                                    @endif
                                </div>
                                <div class="form-group">
                                    <table
                                        class="table"
                                        id="training-table"
                                    >
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Training Name (choose one)</th>
                                                <th>Category</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-7">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Training Test Items</h4>
                        </div>
                        <div class="card-body">
                            @php
                                $i = 0;
                            @endphp
                            <div id="test-item-container">
                                @if (old('items', null))
                                    {
                                    @foreach (old('items') as $testItem)
                                        @include('dashboard.training-tests.fragments.test-item', [
                                            'item' => $testItem,
                                            'i' => $i++,
                                        ])
                                    @endforeach
                                    }
                                @else
                                    @isset($trainingTest)
                                        @foreach ($trainingTest->items as $testItem)
                                            @include('dashboard.training-tests.fragments.test-item', [
                                                'item' => $testItem,
                                                'i' => $i++,
                                            ])
                                        @endforeach
                                    @else
                                        @include('dashboard.training-tests.fragments.test-item', [
                                            'i' => $i++,
                                        ])
                                    @endisset
                                @endif
                            </div>
                            <div class="pt-4">
                                <button
                                    type="button"
                                    class="btn btn-outline-primary w-100"
                                    id="btn-add-item"
                                >
                                    <span>Add Item</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card sticky-bottom">
                        <div class="card-body d-flex flex-row justify-content-end">
                            <button
                                type="submit"
                                class="btn btn-primary w-25"
                            >Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
    <template id="training-test-item">
        @include('dashboard.training-tests.fragments.test-item', [
            'i' => $i++,
        ])
    </template>
@endsection

@section('scripts')
    <script
        type="text/javascript"
        src="https://cdn.datatables.net/v/bs5/dt-1.13.1/r-2.4.0/sb-1.4.0/datatables.min.js"
    ></script>
    <script>
        const oldTrainingId = {{ isset($trainingTest) ? $trainingTest->training_id : 'null' }};
        const oldPrerequisiteId =
            {{ isset($trainingTest) && $trainingTest->prerequisite_test_id ? $trainingTest->prerequisite_test_id : 'null' }};
    </script>
    <script>
        const addItemButton = document.getElementById("btn-add-item");
        const testItemContainer = document.getElementById("test-item-container");
        /**
         * @type {HTMLTemplateElement}
         */
        const testItemTemplate = document.getElementById("training-test-item");

        function handleDeleteItem(e) {
            e.target.parentElement.remove();
        }

        const testItemRemoveBtns = testItemContainer.querySelectorAll(".test-item .btn-delete");
        for (const btn of testItemRemoveBtns) {
            btn.addEventListener("click", handleDeleteItem);
        }

        function rectifyInputNameAndID() {
            const {
                children: testItems
            } = testItemContainer;
            let i = 0;

            for (const item of testItems) {
                const inputs = item.querySelectorAll("input");
                const labels = item.querySelectorAll("label");

                const options = item.querySelectorAll(".list-group-item");

                for (const input of inputs) {
                    input.setAttribute("id", input.getAttribute("id").replace(/item-\d+/gi, `item-${i}`));
                    input.setAttribute("name", input.getAttribute("name").replace(/items\[\d+/gi, `items[${i}`));
                }

                for (const label of labels) {
                    label.setAttribute("for", label.getAttribute("for").replace(/item-\d+/gi, `item-${i}`));
                }

                let j = 0;
                for (const option of options) {
                    const optionInputs = option.querySelectorAll("input");
                    for (const input of optionInputs) {
                        input.setAttribute("id", input.getAttribute("id").replace(/option-\d+/gi, `option-${j}`));
                        input.setAttribute("name", input.getAttribute("name").replace(/options\]\[\d+/gi,
                            `options][${j}`));
                    }
                    j++;
                }
                i++;
            }
        }

        function handleDeleteOption(e) {
            e.target.parentElement.parentElement.remove();
        }

        /**
         * @param item {HTMLElement}
         */
        function addOptionTo(item) {
            return function(e) {
                const listGroup = item.querySelector(".list-group");
                const optionTemplate = item.querySelector("template");

                const newOptionItem = optionTemplate.content.cloneNode(true);

                const statementInput = newOptionItem.querySelector(".form-control");
                const isAnswerCheck = newOptionItem.querySelector(".form-check-input");
                const deleteBtn = newOptionItem.querySelector(".btn-delete-option");

                statementInput.id = "item-0-option-0-statement";
                statementInput.name = "items[0][options][0][statement]";

                isAnswerCheck.id = "item-0-option-0-is_answer";
                isAnswerCheck.name = "items[0][options][0][is_answer]";

                deleteBtn.addEventListener("click", handleDeleteOption);

                listGroup.insertBefore(newOptionItem, e.target);
            }
        }

        addItemButton.addEventListener("click", e => {
            if ("content" in testItemTemplate) {
                const newItem = testItemTemplate.content.cloneNode(true);

                const itemElement = newItem.querySelector(".test-item")
                newItem.querySelector("button.btn-delete").addEventListener("click", handleDeleteItem);
                newItem.querySelector("button.btn-add-option").addEventListener("click", addOptionTo(itemElement));

                testItemContainer.appendChild(newItem);
            }
        })

        for (const item of testItemContainer.children) {
            const addOptionBtn = item.querySelector(".btn-add-option");
            const deleteOptionBtn = item.querySelectorAll(".btn-delete-option");

            addOptionBtn.addEventListener("click", addOptionTo(item));
            for (const deleteBtn of deleteOptionBtn) {
                deleteBtn.addEventListener("click", handleDeleteOption);
            }
        }
    </script>
    <script>
        // Load previously selected training item if existed as Object
        const defaultTrainingObject =
            {!! isset($trainingTest) && isset($trainingTest->trainingItem)
                ? json_encode($trainingTest->trainingItem->load(['category']))
                : 'null' !!};

        if (defaultTrainingObject) {
            defaultTrainingObject.name = defaultTrainingObject.name + " (unchanged)";
        }

        // Datatables
        const trainingTable = new DataTable("#training-table", {
            ajax: "{{ url('/api/training_events/datatables') }}",
            serverSide: true,
            columns: [{
                    name: "id",
                    data: data => `
                        <input
                            type="radio"
                            name="training_id"
                            {{ isset($trainingTest) ? '' : 'required' }}
                            id="training-${data.id}"
                            class="form-check-input"
                            ${oldTrainingId && oldTrainingId == data.id ? 'checked' : ''}
                            required
                            value="${data.id}" >
                    `,
                    sortable: false,
                },
                {
                    name: "name",
                    data: data => `
                        <label for="training-${data.id}" class="form-check-label">
                            ${data.name}
                        </label>
                    `
                },
                {
                    name: "category_id",
                    data: data => `
                        <label for="training-${data.id}" class="form-check-label">
                            ${data.category.name}
                        </label>
                    `
                }
            ]
        });

        trainingTable.on("xhr.dt", (e, settings, json) => {
            console.log(json);
            if (defaultTrainingObject) {
                // Load previously selected training object as the first result
                json.data = [defaultTrainingObject, ...json.data]
            }
        })
    </script>
    {{-- <script>
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
    </script> --}}
    <script>
        /**
         * @type {HTMLFormElement}
         */
        const form = document.getElementById("main-form");
        let isFormClean = false;

        form.addEventListener("submit", e => {
            if (!isFormClean) {
                e.preventDefault();
                // if (isLastInputEmpty()) {
                //     benefitsWrapper.lastElementChild.remove();
                // }
                rectifyInputNameAndID();
                isFormClean = true;
                form.submit();
            }
        })
    </script>
    {{-- <script>
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
    </script> --}}
    {{-- <script>
        $(document).ready(function() {
            console.log("aaaa");
            $('#body').css('background-color', '#678983');
        });
    </script> --}}
@endsection
