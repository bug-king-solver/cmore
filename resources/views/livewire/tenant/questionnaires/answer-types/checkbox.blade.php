<div x-data="{ showDropdown: false }" @click.away="showDropdown = false">
    <div wire:loading.delay.short wire:target="save" class="absolute">
        <x-loading />
    </div>

    @php
        $optionsCount = count($question->questionOptions);
        $showOptionsAsDropdown = $optionsCount >= 10;
    @endphp

    @if ($showOptionsAsDropdown)
        <div class="relative inline-block w-full">
            <input type="checkbox" id="showDropdown" class="hidden">
            <div @click="showDropdown = !showDropdown"
                class="flex flex-row justify-between cursor-pointer bg-gray-100 text-gray-500 px-4 py-2 rounded-md">

                @if ($value && count(array_filter($value)) > 0)
                    <span class="text-esg-400">{{ count(array_filter($value)) }}
                        {{ count(array_filter($value)) == 1 ? __('option') : __('options') }}
                        {{ __('selected') }}</span>
                @else
                    <span>{{ __('Click to select') }}</span>
                @endif

                <div>
                    <div x-show="!showDropdown" class="my-3">
                        @include('icons.arrow-menu')</div>
                    <div x-cloak x-show="showDropdown" class="my-3">
                        @include('icons.arrow-up')
                    </div>
                </div>
            </div>

            <div x-show="showDropdown" @click="showDropdown = true" x-cloak
                class="absolute bg-white border border-gray-200 mt-2 py-2 mb-7 rounded-md shadow-lg max-h-80 overflow-y-auto custom-scroll w-full z-10">

                @foreach ($question->questionOptions as $question_option)
                    <label class="block px-4 py-2">
                        <input type="checkbox" {{ $this->answer->validation ? 'disabled' : '' }}
                            {{ $questionnaire->isSubmitted() ||auth()->user()->cannot('questionnaires.answer')? 'disabled': '' }}
                            wire:change="save({{ $question_option->id }});"
                            wire:model="value.{{ $question_option->option->id }}"
                            @if ($question_option->children_action) onchange="enableOrDisable((this.checked ? 'enable' : 'disable'), {{ $question->id }})" @endif
                            class="checked:bg-esg6 mr-3.5"> {{ $question_option->option->label }}
                    </label>
                    @if (
                        ($showCustomDataAll === true || in_array($question_option->option->label, $showCustomData)) &&
                            isset($value[$question_option->option->id]) &&
                            $value[$question_option->option->id]
                    )
                        <input type="text" class="ml-4 rounded-lg w-96"
                            wire:change="save({{ $question_option->id }});"
                            wire:model.lazy="customData.{{ $question_option->option->id }}">
                    @endif
                @endforeach
            </div>

            <div class="mt-2">
                @foreach ($question->questionOptions as $question_option)
                    @if (isset($value[$question_option->option->id]) && $value[$question_option->option->id])
                        <p class="p-2">
                            <label><input type="checkbox" {{ $this->answer->validation ? 'disabled' : '' }}
                                    {{ $questionnaire->isSubmitted() ||auth()->user()->cannot('questionnaires.answer')? 'disabled': '' }}
                                    wire:change="save({{ $question_option->id }});"
                                    wire:model="value.{{ $question_option->option->id }}"
                                    @if ($question_option->children_action) onchange="enableOrDisable((this.checked ? 'enable' : 'disable'), {{ $question->id }})" @endif
                                    class="checked:bg-esg6 mr-3.5" onclick="this.blur();">
                                {{ $question_option->option->label }}</label>
                            @if (
                                ($showCustomDataAll === true || in_array($question_option->option->label, $showCustomData)) &&
                                    isset($value[$question_option->option->id]) &&
                                    $value[$question_option->option->id]
                            )
                                <input type="text" class="ml-4 rounded-lg w-96"
                                    wire:change="save({{ $question_option->id }});"
                                    wire:model.lazy="customData.{{ $question_option->option->id }}">
                            @endif
                        </p>
                    @endif
                @endforeach
            </div>
        </div>
    @else
        @foreach ($question->questionOptions as $question_option)
            <p>
                <label><input type="checkbox" {{ $this->answer->validation ? 'disabled' : '' }}
                        {{ $questionnaire->isSubmitted() ||auth()->user()->cannot('questionnaires.answer')? 'disabled': '' }}
                        wire:change="save({{ $question_option->id }});"
                        wire:model="value.{{ $question_option->option->id }}"
                        @if ($question_option->children_action) onchange="enableOrDisable((this.checked ? 'enable' : 'disable'), {{ $question->id }})" @endif
                        class="checked:bg-esg6 mr-3.5"> {{ $question_option->option->label }}</label>
                @if (
                    ($showCustomDataAll === true || in_array($question_option->option->label, $showCustomData)) &&
                        isset($value[$question_option->option->id]) &&
                        $value[$question_option->option->id]
                )
                    <input type="text" class="ml-4 rounded-lg w-96" wire:change="save({{ $question_option->id }});"
                        wire:model.lazy="customData.{{ $question_option->option->id }}">
                @endif
            </p>
        @endforeach
    @endif
</div>
