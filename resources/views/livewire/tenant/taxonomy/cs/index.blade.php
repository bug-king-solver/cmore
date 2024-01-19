<div class="px-4 md:px-0">

    <x-slot name="header">""
        <x-header title="{{ __('Substantial contribute') }}" data-test="taxonomy-header"
            click="{{ route('tenant.taxonomy.show', ['questionnaire' => $questionnaire]) }}"
            textcolor="text-esg5">
            <x-slot name="left"></x-slot>
        </x-header>
    </x-slot>

    <div class="pb-20">

        <div class="mt-6">
            <div class="flex gap-5 items-center">
                <label class="text-xl text-esg6 font-bold uppercase">
                    {{ $questionnaire->company->name }} : {{ $activity->name }}
                </label>
            </div>
        </div>

        <div class="">
            <p class="mt-6 text-esg16">
                {{ __('Is required') }}
                <span
                    class="font-extrabold">{{ __('check whether or not the activity contributes substantially') }}</span>
                {{ __('for one of the environmental objectives listed below. Fill in the percentage to enable verification of an objective.') }}
            </p>

            <div class="mt-10">
                <div class="grid grid-cols-6 items-center gap-5">
                    <div class="font-normal text-esg8 text-base col-span-2"> </div>
                    {{-- <div> {{ __('Yes / No') }} </div> --}}
                    <div class="text-esg8">
                        {{ __('Percentage') }}
                        @if ($hasNPS && !$editView)
                            <x-buttons.edit wire:click="openModal()" class="cursor-pointer" :param="json_encode(['color' => color(16), 'width' => 14, 'height' => 14])" />
                        @endif
                    </div>
                </div>

                @foreach ($objectives as $objective)
                    <div class="grid grid-cols-6 items-center gap-5 mt-6">
                        <div class="font-normal text-esg8 text-base col-span-2">
                            {{ translateJson($objective['name']) }}
                        </div>

                        <div class="">
                            @php $maxPercent = $objective['percentage'] >= $percentageToFullfill ? 100 : $percentageToFullfill; @endphp
                            @if ($hasNPS && !$editView)
                                <label class="text-esg8"
                                    for="objectives.{{ $objective['arrayPosition'] }}.percentage">{{ $objective['percentage'] }}</label>
                            @else
                                <x-questionnaire.taxonomy.input-volumes title=""
                                    placeholder="{{ __('Enter the value') }}"
                                    id="objectives.{{ $objective['arrayPosition'] }}.percentage" unit="%"
                                    maxlength="3" class="text-esg8"
                                    oninput="if (this.value > {{ $maxPercent }}) this.value = {{ $maxPercent }};"
                                    modelmodifier=".debounce" />
                            @endif
                        </div>
                        {{-- @if ($objective['percentage'] > 0) --}}
                        <div class="text-right">
                            @if ($hasNPS && !$editView)
                                <x-buttons.a class="!bg-[#B1B1B1] !rounded-md !p-2 cursor-not-allowed"
                                    text="{{ __('Verify') }}" />
                            @else
                                @if ($objective['percentage'] > 0)
                                    <x-buttons.a
                                        class="duration-300 !bg-esg4 !text-[#B1B1B1] !border !border-[#B1B1B1] !normal-case !text-sm !font-medium !rounded-md !p-2 duration-300 hover:!bg-[#D2EAD7] hover:!border-[#44724D] hover:!text-esg6"
                                        text="{{ __('Verify') }}"
                                        href="{{ route('tenant.taxonomy.substantial.questionnaire', [
                                            'questionnaire' => $questionnaire,
                                            'code' => $activity->id,
                                            'objective' => $objective['arrayPosition'],
                                        ]) }}" />
                                @else
                                    <x-buttons.a
                                        class="duration-300 !bg-white !text-esg16/20 !border !border-esg16/20 !normal-case !text-sm !font-medium !rounded-md !p-2 cursor-not-allowed"
                                        text="{{ __('Verify') }}" />
                                @endif
                            @endif
                        </div>
                        {{-- @endif  --}}
                    </div>
                @endforeach

                <div class="my-10 border-b border-b-esg7/40"></div>

                <div class="grid grid-cols-6 items-center gap-5 mt-14">
                    <div class="font-normal text-esg8 text-base col-span-2"> </div>
                    <div class="w-28">
                    </div>
                    <div class="">
                        <x-buttons.a class="bg-esg6 !text-esg4 !rounded-md !p-2 !w-full !text-center cursor-pointer"
                            text="{{ __('Conclude') }}" wire:click='completeActivity' />
                    </div>
                </div>
            </div>
        </div>

        <div class="fixed inset-0 z-10 {{ $showModal ? 'visible' : 'hidden' }} overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 transition-opacity">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <div
                    class="overflow-hidden transition-all transform bg-white rounded-lg shadow-xl sm:max-w-lg sm:w-full p-4">
                    <div class="modal-header flex flex-row">
                        <div class="text-esg6 text-xl font-bold flex-grow">
                            {!! __('Attention') !!}
                        </div>
                        <div wire:click="closeModal()"
                            class="bg-esg7/50 rounded-full w-6 h-6 flex items-center cursor-pointer">
                            <div class="m-auto">X</div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm text-esg8">
                            {!! __(
                                'Modifying the information of <strong>Substantial contribution</strong> will invalidate and reset what was filled in the box of <strong>Does Not Significantly Harm.</strong> Are you sure you want to proceed?',
                            ) !!}
                        </p>
                    </div>
                    <div class="mt-4 flex gap-4 justify-end">
                        <x-buttons.btn
                            class="!bg-esg4 !text-[#757575] !border !border-[#757575]  !block !text-sm !font-medium !normal-case"
                            wire:click="closeModal()" text="{!! __('Cancel') !!}" />

                        <x-buttons.btn
                            class="bg-esg6 !text-esg4 !block !text-sm !font-medium !normal-case cursor-pointer"
                            text="{{ __('Confirm') }}" wire:click="enabledEdit" />
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
