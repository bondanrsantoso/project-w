<div class="py-4 border-top border-bottom test-item position-relative">
    <button
        type="button"
        class="btn btn-danger position-absolute top-0 end-0 btn-delete mt-2 fw-bold"
    >&times;</button>
    <input
        type="hidden"
        class="item-order"
        name="items[{{ $i }}][order]"
        id="item-{{ $i }}-order"
        value="{{ $i }}"
    >
    @isset($item['id'])
        <input
            type="hidden"
            class="item-id"
            name="items[{{ $i }}][id]"
            id="item-{{ $i }}-id"
            value="{{ $item['id'] }}"
        >
    @endisset
    @isset($item->id)
        <input
            type="hidden"
            class="item-id"
            name="items[{{ $i }}][id]"
            id="item-{{ $i }}-id"
            value="{{ $item->id }}"
        >
    @endisset
    <div class="form-group">
        <label
            class="form-label"
            for="item-{{ $i }}-statement"
        >
            Question/Statement
        </label>
        <input
            type="text"
            name="items[{{ $i }}][statement]"
            id="item-{{ $i }}-statement"
            class="form-control item-statement"
            placeholder="Why does the chicken cross the road?"
            @isset($item)
                @if (is_array($item))
                    value="{{ isset($item['statement']) ? $item['statement'] : '' }}"
                @else
                    value="{{ isset($item->statement) ? $item->statement : '' }}"
                @endif
            required
            @endisset
        >
    </div>
    <div class="row">
        <div class="form-group col-12 col-lg-4">
            <label
                for="item-{{ $i }}-weight"
                class="form-label"
            >
                Proportional Weight
            </label>
            <input
                type="number"
                name="items[{{ $i }}][weight]"
                id="item-{{ $i }}-weight"
                class="form-control item-weight"
                min="1"
                @isset($item)
                    @if (is_array($item))
                        value="{{ isset($item['weight']) ? $item['weight'] : '' }}"
                    @else
                        value="{{ isset($item->weight) ? $item->weight : '' }}"
                    @endif
                @else
                    value="1"
                @endisset
            >
            <span class="form-text">Proportional weight/value of the test item (min:1)</span>
        </div>
        <div class="form-group col-12 col-lg-8">
            <label
                for="item-{{ $i }}-answer_literal"
                class="form-label"
            >
                Text Answer
            </label>
            <input
                type="text"
                name="items[{{ $i }}][answer_literal]"
                id="item-{{ $i }}-answer_literal"
                class="form-control item-answer_literal"
                @isset($item)
                    @if (is_array($item))
                        value="{{ isset($item['answer_literal']) ? $item['answer_literal'] : '' }}"
                    @else
                        value="{{ isset($item->answer_literal) ? $item->answer_literal : '' }}"
                    @endif
                @endisset
            >
            <span class="form-text">Literal text answer to check against (case-insensitive)</span>
        </div>
        <div class="col-12 pt-2">
            <h6 class="mb-2">Multiple choices answers</h6>
            @php
                $j = 0;
            @endphp
            <div class="list-group">
                @isset($item['options'])
                    @foreach ($item['options'] as $option)
                        <div class="list-group-item answer-options">
                            @isset($option['id'])
                                <input
                                    type="hidden"
                                    name="items[{{ $i }}][options][{{ $j }}][id]"
                                    id="item-{{ $i }}-option-{{ $j }}-id"
                                    value="{{ $option['id'] }}"
                                >
                            @endisset
                            @isset($option->id)
                                <input
                                    type="hidden"
                                    name="items[{{ $i }}][options][{{ $j }}][id]"
                                    id="item-{{ $i }}-option-{{ $j }}-id"
                                    value="{{ $option->id }}"
                                >
                            @endisset
                            <div class="input-group">
                                <input
                                    type="text"
                                    name="items[{{ $i }}][options][{{ $j }}][statement]"
                                    id="item-{{ $i }}-option-{{ $j }}-statement"
                                    value="{{ $option['statement'] }}"
                                    placeholder="Answer option"
                                    class="form-control"
                                >
                                <div class="input-group-text">
                                    <input
                                        type="checkbox"
                                        value="1"
                                        @checked(isset($option['is_answer']) && $option['is_answer'])
                                        name="items[{{ $i }}][options][{{ $j }}][is_answer]"
                                        id="item-{{ $i }}-option-{{ $j }}-is_answer"
                                        class="form-check-input mt-0"
                                    >
                                </div>
                                <button
                                    class="btn btn-outline-secondary btn-delete-option"
                                    type="button"
                                >&times;</button>
                            </div>
                        </div>
                    @endforeach
                @else
                    @isset($item->options)
                        @foreach ($item->options as $option)
                            @isset($option->id)
                                <input
                                    type="hidden"
                                    name="items[{{ $i }}][options][{{ $j }}][id]"
                                    id="item-{{ $i }}-option-{{ $j }}-id"
                                    value="{{ $option->id }}"
                                >
                            @endisset
                            <div class="list-group-item answer-options">
                                <div class="input-group">
                                    <input
                                        type="text"
                                        name="items[{{ $i }}][options][{{ $j }}][statement]"
                                        id="item-{{ $i }}-option-{{ $j }}-statement"
                                        value="{{ $option->statement }}"
                                        placeholder="Answer option"
                                        class="form-control"
                                    >
                                    <div class="input-group-text">
                                        <input
                                            type="checkbox"
                                            value="1"
                                            @checked(isset($option->is_answer) && $option->is_answer)
                                            name="items[{{ $i }}][options][{{ $j }}][is_answer]"
                                            id="item-{{ $i }}-option-{{ $j }}-is_answer"
                                            class="form-check-input mt-0"
                                        >
                                    </div>
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary btn-delete-option"
                                    >&times;</button>
                                </div>
                            </div>
                        @endforeach
                    @endisset
                @endisset
                <button
                    type="button"
                    class="list-group-item list-group-item-action btn-add-option"
                >
                    Add option
                </button>
            </div>
            <template>
                <div class="list-group-item answer-options">
                    <div class="input-group">
                        <input
                            type="text"
                            {{-- name="items[{{ $i }}][options][{{ $j }}][statement]"
                            id="item-{{ $i }}-option-{{ $j }}-statement" --}}
                            placeholder="Answer option"
                            class="form-control"
                        >
                        <div class="input-group-text">
                            <input
                                type="checkbox"
                                value="1"
                                {{-- name="items[{{ $i }}][options][{{ $j }}][is_answer]"
                                id="item-{{ $i }}-option-{{ $j }}-is_answer" --}}
                                class="form-check-input mt-0"
                            >
                        </div>
                        <button
                            type="button"
                            class="btn btn-outline-secondary btn-delete-option"
                        >&times;</button>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
