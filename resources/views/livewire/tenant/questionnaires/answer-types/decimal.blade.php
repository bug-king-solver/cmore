<div>
    <div wire:loading.delay.short wire:target="save" class="absolute">
        <x-loading />
    </div>

    @foreach ($question->questionOptions as $question_option)
        <div class="w-full flex items-center py-2" x-data="{ unitValueChanged: false }">
            @php
                $showCurrency = $question_option->indicator && $question_option->indicator->is_financial;
            @endphp

            @if ($showCurrency)
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        {{ tenant()->get_default_currency }}
                    </div>
            @endif

                    <input type="text" {{ $this->answer->validation ? 'disabled' : '' }}
                        {{ $questionnaire->isSubmitted() ||auth()->user()->cannot('questionnaires.answer')? 'disabled': '' }}
                        wire:change="save({{ $question_option->id }})" wire:model.lazy="value.{{ $question_option->option->id }}"
                        class=" {{ $showCurrency ? 'pl-12' : '' }} focus:border-esg6 mr-2 w-48 rounded-lg focus:ring-0">

            @if ($showCurrency)
                </div>
            @endif

            @if ($question_option->indicator && $question_option->indicator->unit_qty)
                @php
                    $unitType = $question_option->indicator->unit_qty ?? null;
                    $unitDefault = $question_option->indicator->unit_default ?? null;
                    $unitDefault = strtolower($unitDefault);
                @endphp

                <x-inputs.select-unit id="customDataUnits.{{ $question_option->option->id }}" unitqty="{{ $unitType }}"
                    unitdefault="{{ $unitDefault }}" wire:model="customDataUnits.{{ $question_option->option->id }}" />

                @if (false && $question_option->indicator && $question_option->indicator->is_financial)
                    <x-inputs.select-unit id="currency" unitqty="currency" unitdefault="un" class="ml-2" />
                @endif

                @if ($customDataUnits[$question_option->option->id] != $unitType . '.' . $unitDefault)
                    <x-buttons.btn text="{{ __('OK') }}"
                        wire:click="convert({{ $question_option->id }}, {{ $question_option->option->id }})"
                        class="ml-2 bg-esg6 text-esg4"
                        @click="unitValueChanged = true" />
                @endif

                <x-information id="tooltip-question-option-{{ $question_option->id }}" model="true">
                    {{ __('If you select any other type of unit, the value will be automatically converted to the default unit, after clicking in the ok button') }}
                </x-information>
            @endif

            <div class="ml-2">{{ $question_option->option->label }}</div>

            @if(isset($question_option->indicator->unit_default))
                <div x-cloak  x-show="unitValueChanged" x-init="setTimeout(() => unitValueChanged = false, 3000)">
                    <p class="mt-2 text-xs text-esg7">
                        {{ __('The value was successfully changed to :unit.', ['unit' => $question_option->indicator->unit_default]) }}
                    </p>
                </div>
            @endif

        </div>
    @endforeach

    <p class="text-xs text-gray-400 @error('value.*') text-red-600 @enderror">
        {{ __('This field only accepts numbers and a dot as decimal separator. Maximum 14 decimal places allowed.') }}
    </p>
</div>
