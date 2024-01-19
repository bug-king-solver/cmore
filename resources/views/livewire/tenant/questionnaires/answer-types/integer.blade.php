<div>
    <div wire:loading.delay.short wire:target="save" class="absolute">
        <x-loading />
    </div>
    @foreach ($question->questionOptions as $question_option)
        @php
            // TODO :: Remove this logic and improve the component
            if (!$this->unitFrom && $question_option->indicator) {
                $this->unitFrom = $this->unitTo = "{$question_option->indicator->unit_qty}.{$question_option->indicator->unit_default}";
            }
        @endphp
        <div class="w-full flex items-center py-2">
            <div class="relative">
                @error('value.*')
                    <span class="absolute inset-y-0 right-0 flex items-center pr-4">
                        @include('icons/alert-circle')
                    </span>
                @enderror
            </div>

            <input type="text" {{ $this->answer->validation ? 'disabled' : '' }}
                {{ $questionnaire->isSubmitted() ||auth()->user()->cannot('questionnaires.answer')? 'disabled': '' }}
                wire:change="save({{ $question_option->id }})" wire:model.lazy="value.{{ $question_option->option->id }}"
                class="focus:border-esg6 mr-2 w-48 rounded-lg focus:ring-0">

            @if ($question_option->indicator && $question_option->indicator->unit_qty)
                @php
                    $unitType = $question_option->indicator->unit_qty ?? null;
                    $unitDefault = $question_option->indicator->unit_default ?? null;
                    $unitDefault = strtolower($unitDefault);
                @endphp

                <x-inputs.select-unit id="customDataUnits.{{ $question_option->option->id }}"
                    unitqty="{{ $unitType }}" unitdefault="{{ $unitDefault }}"
                    wire:model="customDataUnits.{{ $question_option->option->id }}" />

                @if ($customDataUnits[$question_option->option->id] != $unitType . '.' . $unitDefault)
                    <x-buttons.btn text="{{ __('OK') }}"
                        wire:click="convert({{ $question_option->id }}, {{ $question_option->option->id }})"
                        class="ml-2 bg-esg6 text-esg4" />
                @endif

                <x-information id="tooltip-question-option-{{ $question_option->id }}" model="true">
                    {{ __('If you select any other type of unit, the value will be automatically converted to the default unit, after clicking in the ok button') }}
                </x-information> 
                
            @endif

            <div class="ml-2">{{ $question_option->option->label }}</div>
        </div>
    @endforeach

    <p class="text-xs text-gray-400 @error('value.*') text-red-600 @enderror">
        {{ __('This field only accepts numbers.') }}
    </p>

</div>
