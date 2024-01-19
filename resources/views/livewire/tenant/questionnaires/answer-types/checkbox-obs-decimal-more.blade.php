<div x-data="{ showDropdown: false }" @click.away="showDropdown = false">

    <div class="grid gap-y-2">

        <div wire:loading.delay.short wire:target="save" class="absolute">
            <x-loading />
        </div>

        <div class="relative inline-block w-full">
            @if (!$questionnaire->isSubmitted())
                <div @click="showDropdown = !showDropdown"
                    class="flex flex-row justify-between cursor-pointer bg-gray-100 text-esg16 px-4 py-2 rounded-md items-center">

                    @if ($value && count($value) > 0)
                        @php
                            $questionOptionCo2Eq = $question->questionOptions
                                ->filter(function ($item, $key) {
                                    return $item->option->is_co2_equivalent;
                                })
                                ->pluck('option.id')
                                ->toArray();

                            $valuesWithoutCo2eq = collect($value)
                                ->filter(function ($item, $key) use ($questionOptionCo2Eq) {
                                    return !in_array($key, $questionOptionCo2Eq);
                                })
                                ->all();
                        @endphp
                        <span class="text-esg-400">{{ count($valuesWithoutCo2eq) }}
                            {{ count($valuesWithoutCo2eq) == 1 ? __('option') : __('options') }}
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
                    class="absolute bg-white border border-gray-200 mt-2 py-2 rounded-md shadow-lg max-h-80 overflow-y-auto p-2 custom-scroll z-10 w-full">
                    @foreach ($question->questionOptions as $j => $question_option)
                        @if (!$question_option->option->is_co2_equivalent)
                            @php
                                $nextOptionIsCo2equiv = isset($question->questionOptions[$j + 1]) ? $question->questionOptions[$j + 1]->option->is_co2_equivalent : false;
                                $nextOption = null;
                                if ($nextOptionIsCo2equiv) {
                                    $nextOption = $question->questionOptions[$j + 1];
                                }
                            @endphp
                            <div class="w-full flex items-center py-2">
                                <label>
                                    <input type="checkbox" {{ $this->answer->validation ? 'disabled' : '' }}
                                        {{ $questionnaire->isSubmitted() ||auth()->user()->cannot('questionnaires.answer')? 'disabled': '' }}
                                        wire:change="checkUncheckDecimalOption({{ $question_option->id }}, {{ $nextOption->id ?? null }});"
                                        wire:model="value.{{ $question_option->option->id }}"
                                        @if ($question_option->children_action) onchange="enableOrDisable((this.checked ? 'enable' : 'disable'), {{ $question->id }})" @endif
                                        class="checked:bg-esg6 mr-3.5"> {{ $question_option->option->label }}
                                </label>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif

            <div>
                @foreach ($question->questionOptions as $i => $question_option)
                    @php
                        if ($question_option->option->is_co2_equivalent) {
                            continue;
                        }
                        $nextOptionIsCo2equiv = isset($question->questionOptions[$i + 1]) ? $question->questionOptions[$i + 1]->option->is_co2_equivalent : false;
                        $nextOption = null;
                        if ($nextOptionIsCo2equiv) {
                            $nextOption = $question->questionOptions[$i + 1];
                        }
                    @endphp


                    @if (isset($value[$question_option->option->id]))
                        @if ($showCustomDataAll === true || in_array($question_option->option->label, $showCustomData))
                            <div x-data="{ showUnitChangedMessage: false, selectUnitActive: false, canEditEmissionFactor: false }">
                                <div class="flex items-center gap-5 mt-3">

                                    <div>
                                        <input type="text" value="{{ $question_option->option->label }}"
                                            class="w-40 bg-esg7/20 p-2.5 px-4 rounded border-esg7" readonly
                                            title="{{ $question_option->option->label }}">
                                    </div>

                                    <div class="flex items-center gap-1">

                                        @php
                                            $showCurrency = $question_option->indicator && $question_option->indicator->unit_qty == 'decimal';
                                        @endphp

                                        @if ($showCurrency)
                                            <div class="flex flex-row mr-2">
                                                <div
                                                    class="inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    {{ tenant()->get_default_currency }}
                                                </div>
                                            </div>
                                        @endif

                                        <div class="flex items-center border border-esg7 rounded-md">
                                            @php
                                                $unitType = $question_option->indicator->unit_qty ?? null;
                                                $unitDefault = $question_option->indicator->unit_default ?? null;
                                                $unitDefault = strtolower($unitDefault);
                                            @endphp

                                            <input type="text"
                                                wire:change="getEmissionFactor({{ $question_option->id }}, {{ $question_option->option->id }}, {{ $nextOption->id ?? null }})"
                                                wire:model.lazy="customData.{{ $question_option->option->id }}"
                                                class="w-32 !border-0 !border-r !border-r-esg7 rounded-none rounded-l-md"
                                                placeholder="{{ __('Value') }}"
                                                {{ $questionnaire->isSubmitted() ||auth()->user()->cannot('questionnaires.answer')? 'disabled': '' }} />

                                            @if ($nextOptionIsCo2equiv && isset($question_option->indicator['emissions']['default_factor']))
                                                <x-inputs.select-unit :disabled="!is_numeric(
                                                    $this->customData[$question_option->option->id],
                                                ) ||
                                                    is_null($this->customData[$question_option->option->id]) ||
                                                    $questionnaire->isSubmitted()"
                                                    id="customDataUnits.{{ $question_option->option->id }}"
                                                    wire:model="customDataUnits.{{ $question_option->option->id }}"
                                                    value="{{ $customDataUnits[$question_option->option->id] }}"
                                                    unitqty="{{ $unitType }}" unitdefault="{{ $unitDefault }}"
                                                    class="!border-esg7 !border-0 bg-esg7/10"
                                                    wire:change="convert({{ $question_option->id }}, {{ $question_option->option->id }}, '{{ $unitType }}.{{ $unitDefault }}', {{ $nextOption->id }})"
                                                    x-on:change="$nextTick(() => { showUnitChangedMessage = true; setTimeout(() => { showUnitChangedMessage = false; }, 3000) })" />
                                            @endif
                                        </div>

                                        @if ($nextOptionIsCo2equiv && isset($question_option->indicator['emissions']['default_factor']))
                                            <x-information id="tooltip-question-option-{{ $question_option->id }}"
                                                model="true">
                                                {{ __('If you select any other type of unit, the value will be automatically converted to the default unit, after clicking in the ok button') }}
                                            </x-information>
                                        @endif
                                    </div>

                                    @if ($nextOptionIsCo2equiv && isset($question_option->indicator['emissions']['default_factor']))
                                        <div class="">
                                            <div>
                                                <div class="flex items-center border border-esg7 rounded-md">
                                                    <x-inputs.text
                                                        id="emissionsFactors.{{ $question_option->option->id }}"
                                                        wire:change="getEmissionFactor({{ $question_option->id }}, {{ $question_option->option->id }}, {{ $nextOption->id ?? null }})"
                                                        modelmodifier=".lazy" placeholder="{{ __('Emission factor') }}"
                                                        x-bind:class="(canEditEmissionFactor ||
                                                            {{ isset($emissionFactorComments[$question_option->option->id]) }}
                                                        ) &&
                                                        {{ empty($emissionFactorComments[$question_option->option->id]) ? 'false' : 'true' }}
                                                            ?
                                                            'cursor-text' : ''"
                                                        class="!w-46 !border-0 !border-r-esg7 rounded-none rounded-l-md cursor-not-allowed"
                                                        :disabled="empty($emissionFactorComments[$question_option->option->id]) || $questionnaire->isSubmitted()" />

                                                    <button x-on:click="canEditEmissionFactor = !canEditEmissionFactor"
                                                        class="w-fit p-2">
                                                        @include('icons.edit-v1', [
                                                            'width' => '18',
                                                            'height' => '18',
                                                        ])
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="">
                                        <label>
                                            <input type="checkbox" {{ $this->answer->validation ? 'disabled' : '' }}
                                                {{ $questionnaire->isSubmitted() ||auth()->user()->cannot('questionnaires.answer')? 'disabled': '' }}
                                                wire:change="checkUncheckDecimalOption({{ $question_option->id }}, {{ $nextOption->id ?? null }});"
                                                wire:model="value.{{ $question_option->option->id }}"
                                                @if ($question_option->children_action) x-on:change="enableOrDisable((this.checked ? 'enable' : 'disable'), {{ $question->id }})" @endif
                                                class="checked:bg-esg6 mr-3.5 hidden">
                                            <div class="block">
                                                @include('icons.trash')
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                @if(isset($question_option->indicator->unit_default))
                                    <div x-show="showUnitChangedMessage">
                                        <p class="mt-2 text-xs text-esg7">
                                            {{ __('The value was successfully changed to :unit.', ['unit' => $question_option->indicator->unit_default]) }}
                                        </p>
                                    </div>
                                @endif

                                <div x-show="canEditEmissionFactor || '{{ isset($emissionFactorComments[$question_option->option->id]) && $emissionFactorComments[$question_option->option->id] }}'"
                                    x-cloak>
                                    <textarea id="emissionFactorComments.{{ $question_option->option->id }}"
                                        wire:model="emissionFactorComments.{{ $question_option->option->id }}"
                                        wire:change="save({{ $question_option->option->id }})" class="w-full text-xs rounded-sm mt-4" required
                                        placeholder="{!! __('Please specify why are you changing the emission factor.') !!}" x-ref="emissionFactorComments"> {{ $emissionFactorComments[$question_option->option->id] ?? '' }} </textarea>

                                    @if (empty($emissionFactorComments[$question_option->option->id]))
                                        <p class="text-xs font-normal text-red-600">{!! __('Please fill in the comment before editing the emission factor.') !!}</p>
                                    @endif
                                </div>

                            </div>

                            @if ($nextOptionIsCo2equiv && isset($question_option->indicator['emissions']['default_factor']))
                                <span class="mt-2 text-xs text-esg7">
                                    {!! __(
                                        'If you want to report in a unit other than the default unit, and for the conversion to be successful, please first insert the value and then select the reporting unit.',
                                    ) !!}
                                </span>
                            @endif
                        @endif
                    @endif
                @endforeach
            </div>

            @error('customData.*')
                <p class="mt-1 text-xs font-bold text-red-600">
                    {{ __('This field only accepts numbers and a dot as decimal separator. Maximum 14 decimal places allowed.') }}
                </p>
            @enderror

        </div>
    </div>
</div>
