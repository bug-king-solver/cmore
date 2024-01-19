<div class="p-3 border border-esg61 rounded">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

        <div>
            <p class="uppercase text-sm font-bold text-esg6">{{ __('identify activity') }}</p>

            <x-form.form-col input="text" id="name" label="{{ __('Name') }}"
                class="after:content-['*'] after:text-red-500 border-esg67" form_div_size="w-full"
                placeholder="{{ __('Enter activity name') }}" />

            <x-form.form-col input="tomselect" id="sector" label="{{ __('Sector') }}"
                class="after:content-['*'] after:text-red-500" :options="$sectorList" items="{{ $sector ?? '' }}"
                limit="1" placeholder="{{ __('Select the sector') }}" form_div_size="w-full" modelmodifier=".lazy" />

            <div>
                <x-form.form-col input="tomselect" id="activity" label="{{ __('Activity') }}"
                    class="after:content-['*'] after:text-red-500" :options="$activityListForm" items="{{ $activity ?? '' }}"
                    limit="1" placeholder="{{ __('Select the activity') }}" form_div_size="w-full"
                    :wire_ignore="false" />
            </div>
        </div>

        <div>
            <p class="uppercase text-sm font-bold text-esg6">{{ __('check eligibility') }}</p>

            <div class="mt-4 flex w-2/3 items-center">
                <label class='text-esg29 block text-lg font-bold'>
                    {{ __('Description') }}
                </label>
            </div>

            <div class="flex items-center mr-4 w-[25rem] mt-2">
                <input type="checkbox" id="confirm-description" wire:model="confirmDescription"
                    wire:click='{{ !$confirmDescription ? 'showModal()' : '' }}'
                    class="w-4 h-4 text-esg8 bg-esg4 border-gray-300 cursor-pointer">
                <label for="confirm-description" class="text-sm text-esg8 ml-2">
                    {{ __("I confirm that I've read the ") }}
                    <span class="underline font-bold cursor-pointer"
                        wire:click.prevent='showModal()'>{{ __('full description') }}</span>
                    {{ __(' of the activity') }}
                </label>
            </div>

            @if ($showModal)
                <x-modals.viewText title="Description" :text="$activityDescription" placeholder="Select an activity" />
            @endif

            <label for="eligibility"
                class="text-esg29 block text-lg font-bold mt-5 after:content-['*'] after:text-red-500">
                {{ __('Eligibility') }}
            </label>

            <div class="mt-4">
                <div class="flex items-center mr-4">
                    <input id="inline-radio" type="radio" value="1" name="eligibility" id="eligibility"
                        wire:model="eligibility"
                        class="w-4 h-4 text-esg8 bg-esg4 {{ !$confirmDescription ? 'cursor-not-allowed border-[#B1B1B1]' : 'border-gray-300  cursor-pointer ' }}"
                        {{ !$confirmDescription ? 'disabled' : '' }}>
                    <label for="inline-radio"
                        class="ml-2 text-sm font-medium {{ !$confirmDescription ? 'text-[#B1B1B1]' : 'text-esg8 ' }}"
                        {{ !$confirmDescription ? 'disabled' : '' }}>
                        {{ __("I confirm the company's activity description") }}
                    </label>
                </div>

                <div class="flex items-center mr-4">
                    <input id="inline-radiobutton" type="radio" name="eligibility" value="0"
                        wire:model="eligibility"
                        class="w-4 h-4 text-esg8 bg-esg4 {{ !$confirmDescription ? 'cursor-not-allowed border-[#B1B1B1]' : 'border-gray-300  cursor-pointer ' }}"
                        {{ !$confirmDescription ? 'disabled' : '' }}>
                    <label for="inline-radiobutton"
                        class="ml-2 text-sm font-medium {{ !$confirmDescription ? 'text-[#B1B1B1]' : 'text-esg8 ' }}"
                        {{ !$confirmDescription ? 'disabled' : '' }}>
                        {{ __("Does not correspond to the company's activity") }}
                    </label>
                </div>
            </div>

            @error('eligibility')
                <p class="mt-2 text-sm text-red-500">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <p class="uppercase text-sm font-bold text-esg6">{{ __('Activity KPIs') }}</p>

            <div class="grid grid-cols-1 md:grid-cols-1 gap-5 pr-10">
                <x-questionnaire.taxonomy.input-volumes title="{{ __('Business volume') }}"
                    placeholder="{{ __('Volume') }}" id="activityVolume.volume.value" type="number" />

                <x-questionnaire.taxonomy.input-volumes title="{{ __('CAPEX') }}" placeholder="{{ __('CAPEX') }}"
                    id="activityVolume.capex.value" type="number" />

                <x-questionnaire.taxonomy.input-volumes title="{{ __('OPEX') }}" placeholder="{{ __('OPEX') }}"
                    id="activityVolume.opex.value" type="number" />
            </div>
        </div>
    </div>

    <div class="flex justify-center gap-8 m-5">
        <button wire:click='createAndContinue'
            class="{{ $isFormFilled ? 'bg-esg27 text-esg16/70 border border-esg16/70' : 'bg-esg27 cursor-not-allowed text-bold text-esg16 border border-esg16' }} rounded h-8 w-40 text-center text-sm"
            data-test="add-btn" {{ $isFormFilled ? '' : 'disabled' }}>
            {{ __('Add Another') }}
        </button>

        <button wire:click='createAndClose'
            class="{{ $isFormFilled ? 'bg-esg6 text-white border border-esg6' : 'bg-esg16/70 cursor-not-allowed text-bold text-esg27' }} rounded h-8 w-40 text-center text-sm"
            data-test="add-btn" {{ $isFormFilled ? '' : 'disabled' }}>
            {{ __('Add') }}
        </button>

    </div>
</div>
