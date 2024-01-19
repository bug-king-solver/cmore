<div x-data="{ unitValueChanged: false, showDropdown: false }" @click.away="showDropdown = false">
    <div class="grid gap-y-2">
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

                    @foreach ($question->questionOptions as $i => $question_option)
                        @php
                            //TODO Fix the first co2 calculator
                            $isCO2equiv = false; //$question_option->option->is_co2_equivalent ?? false;
                            $nextOptionIsCo2equiv = isset($question->questionOptions[$i + 1]) ? $question->questionOptions[$i + 1]->option->is_co2_equivalent : false;
                            $showOptionDiv = $this->optionsConfig[$question->id][$question_option->id][$question_option->option->id]['shouldShow'];
                        @endphp

                        <div class="{{ $showOptionDiv == 0 ? 'hidden' : 'show' }}">
                            <div class="w-full flex items-center p-2">
                                <label>
                                    <input type="checkbox" {{ $this->answer->validation ? 'disabled' : '' }}
                                        {{ $questionnaire->isSubmitted() ||auth()->user()->cannot('questionnaires.answer')? 'disabled': '' }}
                                        wire:change="save({{ $question_option->id }});"
                                        wire:model="value.{{ $question_option->option->id }}"
                                        @if ($question_option->children_action) x-on:change="enableOrDisable((this.checked ? 'enable' : 'disable'), {{ $question->id }})" @endif
                                        @if ($isCO2equiv) checked  disabled @endif
                                        class="checked:bg-esg6 mr-3.5">
                                    {{ $question_option->option->label }}
                                </label>
                            </div>
                        </div>
                    @endforeach


                </div>
            </div>

            @foreach ($question->questionOptions as $i => $question_option)
                @if (isset($value[$question_option->option->id]) && $value[$question_option->option->id])
                    @php
                        //TODO Fix the first co2 calculator
                        $isCO2equiv = false; //$question_option->option->is_co2_equivalent ?? false;
                        $nextOptionIsCo2equiv = isset($question->questionOptions[$i + 1]) ? $question->questionOptions[$i + 1]->option->is_co2_equivalent : false;
                        $showOptionDiv = $this->optionsConfig[$question->id][$question_option->id][$question_option->option->id]['shouldShow'];
                    @endphp

                    <div class="{{ $showOptionDiv == 0 ? 'hidden' : 'show' }}">
                        <div class="w-full flex items-center p-2">
                            <label>
                                <input type="checkbox" {{ $this->answer->validation ? 'disabled' : '' }}
                                    {{ $questionnaire->isSubmitted() ||auth()->user()->cannot('questionnaires.answer')? 'disabled': '' }}
                                    wire:change="save({{ $question_option->id }});"
                                    wire:model="value.{{ $question_option->option->id }}"
                                    @if ($question_option->children_action) x-on:change="enableOrDisable((this.checked ? 'enable' : 'disable'), {{ $question->id }})" @endif
                                    @if ($isCO2equiv) checked  disabled @endif
                                    class="checked:bg-esg6 mr-3.5">
                                {{ $question_option->option->label }}
                            </label>
                        </div>
                        @if (
                            ($showCustomDataAll === true || in_array($question_option->option->label, $showCustomData)) &&
                                isset($value[$question_option->option->id]) &&
                                $value[$question_option->option->id]
                        )
                            <div class="w-full flex items-center py-2 ">
                                @php
                                    $showCurrency = $question_option->indicator && in_array($question_option->indicator->unit_qty, ['decimal']);
                                @endphp

                                @if ($showCurrency)
                                    <div class="flex flex-row mr-2">
                                        <div class="inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            {{ tenant()->get_default_currency }}
                                        </div>
                                    </div>
                                @endif

                                <input type="text" class="ml-4 rounded-lg mr-2"
                                    wire:change="save({{ $question_option->id }});"
                                    wire:model.lazy="customData.{{ $question_option->option->id }}"
                                    id="questionOption_{{ $question_option->id }}">

                                @if ($question_option->indicator && $question_option->indicator->unit_qty)
                                    @php
                                        $unitType = $question_option->indicator->unit_qty ?? null;
                                        $unitDefault = $question_option->indicator->unit_default ?? null;
                                        $unitDefault = strtolower($unitDefault);
                                    @endphp

                                    @if (!$isCO2equiv)
                                        <x-inputs.select-unit id="customDataUnits.{{ $question_option->option->id }}"
                                            unitqty="{{ $nextOptionIsCo2equiv ? $question->questionOptions[$i + 1]->indicator->unit_qty : $unitType }}"
                                            unitdefault="{{ $unitDefault }}"
                                            wire:model="customDataUnits.{{ $question_option->option->id }}" />
                                    @endif

                                    @if ($customDataUnits[$question_option->option->id] != $unitType . '.' . $unitDefault && !$isCO2equiv)
                                        <x-buttons.btn text="OK"
                                            wire:click="convert({{ $question_option->id }}, {{ $question_option->option->id }})"
                                            class="ml-2 bg-esg6 text-esg4" />
                                    @endif

                                    @if ($isCO2equiv)
                                        @php $data = json_encode(['questionOption' => $question_option->id, 'value' => $customData[$question->questionOptions[$i - 1]->option->id] ?? null, 'answer' => $this->answer->id]); @endphp
                                        <x-buttons.btn text="{!! __('Calc') !!}"
                                            modal="questionnaires.answer-types.modals.co2-calculator"
                                            class="ml-2 bg-esg6 text-esg4" :data="$data" />
                                    @endif

                                    @if (!$isCO2equiv)
                                        <x-information id="tooltip-question-option-{{ $question_option->id }}"
                                            model="true">
                                            {{ __('If you select any other type of unit, the value will be automatically converted to the default unit, after clicking in the ok button') }}
                                        </x-information>
                                    @endif
                                @endif

                                @if ($question_option->indicator && $unitChanged)
                                    <div x-cloak x-show="unitValueChanged" x-init="setTimeout(() => unitValueChanged = false, 3000)">
                                        <p class="mt-2 text-xs text-esg7">
                                            {{ __('The value was successfully changed to :unit.', ['unit' => $question_option->indicator->unit_default]) }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @endif
            @endforeach
        @else
            @foreach ($question->questionOptions as $i => $question_option)
                @php
                    //TODO Fix the first co2 calculator
                    $isCO2equiv = false; //$question_option->option->is_co2_equivalent ?? false;
                    $nextOptionIsCo2equiv = isset($question->questionOptions[$i + 1]) ? $question->questionOptions[$i + 1]->option->is_co2_equivalent : false;
                    $showOptionDiv = $this->optionsConfig[$question->id][$question_option->id][$question_option->option->id]['shouldShow'];
                @endphp

                <div class="{{ $showOptionDiv == 0 ? 'hidden' : 'show' }}">
                    <div class="w-full flex items-center py-2 ">
                        <label>
                            <input type="checkbox" {{ $this->answer->validation ? 'disabled' : '' }}
                                {{ $questionnaire->isSubmitted() ||auth()->user()->cannot('questionnaires.answer')? 'disabled': '' }}
                                wire:change="save({{ $question_option->id }});"
                                wire:model="value.{{ $question_option->option->id }}"
                                @if ($question_option->children_action) x-on:change="enableOrDisable((this.checked ? 'enable' : 'disable'), {{ $question->id }})" @endif
                                @if ($isCO2equiv) checked  disabled @endif
                                class="checked:bg-esg6 mr-3.5">
                            {{ $question_option->option->label }}
                        </label>
                    </div>
                    @if (
                        ($showCustomDataAll === true || in_array($question_option->option->label, $showCustomData)) &&
                            isset($value[$question_option->option->id]) &&
                            $value[$question_option->option->id]
                    )
                        <div class="w-full flex items-center py-2 ">
                            @php
                                $showCurrency = $question_option->indicator && in_array($question_option->indicator->unit_qty, ['decimal']);
                            @endphp

                            @if ($showCurrency)
                                <div class="flex flex-row mr-2">
                                    <div class="inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        {{ tenant()->get_default_currency }}
                                    </div>
                                </div>
                            @endif

                            <input type="text" class="ml-4 rounded-lg mr-2"
                                wire:change="save({{ $question_option->id }});"
                                wire:model.lazy="customData.{{ $question_option->option->id }}"
                                id="questionOption_{{ $question_option->id }}">

                            @if ($question_option->indicator && $question_option->indicator->unit_qty)
                                @php
                                    $unitType = $question_option->indicator->unit_qty ?? null;
                                    $unitDefault = $question_option->indicator->unit_default ?? null;
                                    $unitDefault = strtolower($unitDefault);
                                @endphp

                                @if (!$isCO2equiv)
                                    <x-inputs.select-unit id="customDataUnits.{{ $question_option->option->id }}"
                                        unitqty="{{ $nextOptionIsCo2equiv ? $question->questionOptions[$i + 1]->indicator->unit_qty : $unitType }}"
                                        unitdefault="{{ $unitDefault }}"
                                        wire:model="customDataUnits.{{ $question_option->option->id }}" />
                                @endif

                                @if ($customDataUnits[$question_option->option->id] != $unitType . '.' . $unitDefault && !$isCO2equiv)
                                    <x-buttons.btn text="OK"
                                        wire:click="convert({{ $question_option->id }}, {{ $question_option->option->id }})"
                                        class="ml-2 bg-esg6 text-esg4" />
                                @endif

                                @if ($isCO2equiv)
                                    @php $data = json_encode(['questionOption' => $question_option->id, 'value' => $customData[$question->questionOptions[$i - 1]->option->id] ?? null, 'answer' => $this->answer->id]); @endphp
                                    <x-buttons.btn text="{!! __('Calc') !!}"
                                        modal="questionnaires.answer-types.modals.co2-calculator"
                                        class="ml-2 bg-esg6 text-esg4" :data="$data" />
                                @endif

                                @if (!$isCO2equiv)
                                    <x-information id="tooltip-question-option-{{ $question_option->id }}"
                                        model="true">
                                        {{ __('If you select any other type of unit, the value will be automatically converted to the default unit, after clicking in the ok button') }}
                                    </x-information>
                                @endif

                                <div x-cloak x-show="unitValueChanged" x-init="setTimeout(() => unitValueChanged = false, 3000)">
                                    <p class="mt-2 text-xs text-esg7">
                                        {{ __('The value was successfully changed to :unit.', ['unit' => $question_option->indicator->unit_default]) }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach

        @endif


        @error('customData.*')
            <p class="mt-1 text-xs font-bold text-red-600">
                {{ __('This field only accepts numbers and a dot as decimal separator. Maximum 14 decimal places allowed.') }}
            </p>
        @enderror

    </div>
</div>
