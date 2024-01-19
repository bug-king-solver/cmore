<div class="px-4 md:px-0">

    <x-slot name="header">""
        <x-header title="{{ __('Does not significantly harm') }}" data-test="taxonomy-header"
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
                {{ __('Now it is necessary to check whether the activity') }}
                <span
                    class="font-extrabold">{{ __('does not significantly harm any of the other environmental objectives') }}</span>
                {{ __('beyond that to which it substantially contributes.') }}
            </p>

            <div class="mt-10">
                <div class="grid grid-cols-6 items-center gap-5">
                    <div class="font-normal text-esg8 text-base col-span-2"> </div>
                    {{-- <div class="text-center"> {{ __('Yes') }}/{{ __('No') }} </div> --}}
                    <div></div>
                </div>

                @foreach ($objectives as $objective)
                    <div class="grid grid-cols-6 items-center gap-5 mt-6">
                        <div class="w-28 text-center">
                            @if ($objective['verified'] === 1)
                                @include('icons.taxonomie.checked')
                            @elseif($objective['verified'] === 0)
                                @include('icons.taxonomie.cancle')
                            @else
                                -
                            @endif
                        </div>
                        <div class="font-normal text-esg8 text-base col-span-2">
                            {{ translateJson($objective['name']) }}
                        </div>
                        <div class="text-right">
                            <x-buttons.a
                                class="!bg-esg4 !text-black !border border-esg6 !normal-case !text-sm !font-medium !rounded-md !p-2 duration-300"
                                text="{{ __('Verify') }}"
                                href="{{ route('tenant.taxonomy.dnsh.questionnaire', [
                                    'questionnaire' => $questionnaire,
                                    'code' => $activity->id,
                                    'objective' => $objective['arrayPosition'],
                                ]) }}" />
                        </div>
                    </div>
                @endforeach

                <div class="my-10 border-b border-b-esg7/40"></div>

                <div class="grid grid-cols-6 items-center gap-5 mt-14">
                    <div class="font-normal text-esg8 text-base col-span-2"> </div>
                    <div class="w-28">
                    </div>
                    <div class="">
                        <x-buttons.a
                            class="bg-esg6 !text-esg4 !rounded-md !p-2 !w-full !text-center cursor-pointer duration-300 hover:brightness-200"
                            text="{{ __('Conclude') }}" wire:click='completeActivity' />
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
