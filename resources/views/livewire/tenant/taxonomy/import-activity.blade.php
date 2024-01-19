<div class="p-3 border border-esg61 rounded">
    <div class="h-auto border border-[#E1E6EF] mt-6 rounded-md">
        <div class="flex flex-row items-center justify-between w-full p-4 border-b border-[#E1E6EF] bg-esg7/10">
            <x-form.form-col input="text" id="importedActivity.name"
                class="after:content-['*'] after:text-red-500 border !border-[#E1E6EF]" form_div_size="w-full"
                placeholder="{{ __('Activity name') }}" wire:ignore />
        </div>

        <div class="grid grid-cols-4 gap-5 border-b border-[#E1E6EF] w-full px-4 py-4 items-center">

            <x-form.form-col input="tomselect" id="importedActivity.activity" label="{{ __('Sector') }}"
                modelmodifier=".lazy"
                class="after:content-['*'] after:text-red-500 !font-normal !text-black !text-base !-mt-1"
                :options="$activityList" items="{{ $importedActivity['activity'] ?? '' }}" limit="1"
                placeholder="{{ __('Select the activity') }}" form_div_size="w-full" />

            <x-questionnaire.taxonomy.input-volumes title="{{ __('Business volume') }}"
                placeholder="{{ __('Volume') }}" name="importedActivity.volume.volume.value" type="number"
                class="after:content-['*'] after:text-red-500" />

            <x-questionnaire.taxonomy.input-volumes title="{{ __('CAPEX') }}" placeholder="{{ __('Volume') }}"
                name="importedActivity.volume.capex.value" type="number"
                class="after:content-['*'] after:text-red-500" />

            <x-questionnaire.taxonomy.input-volumes title="{{ __('OPEX') }}" placeholder="{{ __('Volume') }}"
                name="importedActivity.volume.opex.value" type="number"
                class="after:content-['*'] after:text-red-500" />
        </div>

        @if (count($this->objectiveFormList) > 0)
            <div class="grid grid-cols-5 h-full">
                <div class="flex flex-row items-center justify-center border-r  border-[#E1E6EF] col-span-2">
                    <span class="text-esg6 text-base font-bold p-2">
                        {{ __('Criteria') }}
                    </span>
                </div>
                <div class="col-span-2 flex flex-col items-center justify-center border-r border-[#E1E6EF]">
                    <div class="flex flex-row items-center justify-center border-b border-[#E1E6EF] w-full">
                        <span class="text-esg6 text-base font-bold p-2">
                            {{ __('Substantial contribute') }}
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-2 items-center justify-center  w-full">
                        <span class="text-esg6 p-2 text-base font-bold border-r border-[#E1E6EF] text-center ">
                            {{ __('Percentage') }}
                        </span>
                        <span class="text-esg6 p-2 text-base font-bold text-center">
                            {{ __('Contribute Verification') }}
                        </span>
                    </div>
                </div>
                <div class="col-span-1 flex flex-row items-center justify-center border-r border-[#E1E6EF]">
                    <span class="text-esg6 text-base font-bold p-2 text-center">
                        {{ __('Does not significantly harm') }}
                    </span>
                </div>
            </div>

            <div
                class="grid grid-cols-5 grid-rows-{{ count($this->objectiveFormList) }} border-t border-[#E1E6EF] h-full">

                @foreach ($this->objectiveFormList as $arrayPosition => $objective)
                    <div class="flex flex-row items-center border-b border-r border-[#E1E6EF] min-h-[48px] col-span-2">
                        <div class="text-esg6 text-base font-bold flex items-center justify-center px-4 py-2">
                            {{ translateJson($objective['name']) }}
                        </div>
                    </div>

                    {{-- contribute percent and checkbox --}}
                    <div
                        class="col-span-2 flex flex-col items-center justify-center border-b border-r border-[#E1E6EF] h-full">
                        <div class="grid grid-cols-2 gap-2 items-center justify-center w-full h-full">
                            <div
                                class="flex items-center justify-center p-2 border-r border-[#E1E6EF] text-center h-full">
                                @if ($objective['hasPercentage'])
                                    <x-questionnaire.taxonomy.input-volumes placeholder="{{ __('Volume') }}"
                                        id="importedActivity.objective.{{ $arrayPosition }}.percentage" type="number"
                                        unit="%"
                                        readonly="{{ $objective['hasPercentage'] ? 'false' : 'true' }}" />
                                @endif
                            </div>
                            <div
                                class="flex items-center justify-center text-esg6 text-base font-bold text-center h-full">
                                @if ($objective['hasPercentage'])
                                    <input type="checkbox" id="importedActivity.objective.{{ $arrayPosition }}.aligned"
                                        wire:model="importedActivity.objective.{{ $arrayPosition }}.aligned"
                                        class="checked:bg-esg6 mr-3.5" />
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- not significant checkbox --}}
                    <div
                        class="col-span-1 flex flex-row items-center justify-center border-b border-r border-[#E1E6EF]">
                        <span class="text-esg6 text-base font-bold">
                            @if ($this->importedActivity['objective'][$arrayPosition]['aligned'] === false)
                                <x-inputs.select id="importedActivity.objective.{{ $arrayPosition }}.dnsh"
                                    :extra="['options' => [__('No'), __('Yes')], 'show_blank_opt' => false]" class="py-0.5 pr-7 text-sm ts-wrapper"
                                    wire:model="importedActivity.objective.{{ $arrayPosition }}.dnsh" />
                            @else
                                <span>
                                    {{ __('Not Applicable') }}
                                </span>
                            @endif
                        </span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="flex justify-center gap-8 m-5" x-cloak>
        <button wire:click='createAndContinue'
            class="{{ $isFormFilled ? '!bg-esg5/10 text-esg5 border border-esg5 !border-esg5' : 'bg-esg16/70 cursor-not-allowed text-bold text-esg27' }} rounded h-8 w-40 text-center text-sm"
            data-test="add-btn" {{ $isFormFilled ? '' : 'disabled' }}>
            {{ __('Import Another') }}
        </button>

        <button wire:click='createAndClose'
            class="{{ $isFormFilled ? '!bg-esg6/10 text-esg5 border border-esg5 !border-esg5' : 'bg-esg16/70 cursor-not-allowed text-bold text-esg27' }} rounded h-8 w-40 text-center text-sm"
            data-test="add-btn" {{ $isFormFilled ? '' : 'disabled' }}>
            {{ __('Import') }}
        </button>
    </div>
</div>
