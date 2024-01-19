<div class="flex place-content-center w-full">

    <div class="mt-10 w-6/12">
        <p class="text-4xl font-bold text-esg5 text-center">{{ __('Taxonomy Questionnaire') }}</p>

        <div class="mt-8 grid grid-cols-1 gap-5 accordion-container">
            <x-accordian.taxonomie title="{!! __('What is it?') !!}" defaulthide="false">
                <div class="">
                    <p class="text-base text-esg8">
                        {!! __('The European Environmental Taxonomy is') !!}
                        <span class="font-bold">{!! __('an ecological classification system for economic activities.') !!}</span>
                        {!! __('Each company must do the exercise to individually classify each of the activities it carries out.') !!}
                    </p>
                </div>
            </x-accordian.taxonomie>

            <x-accordian.taxonomie title="{!! __('Perform a new exercise or import an exercise?') !!}" defaulthide="true">
                <div class="">
                    <p class="text-base text-esg8">
                        {!! __(
                            'Companies that have already carried out the taxonomy exercise can choose to import the results they already have. Companies that have not yet carried out the exercise can do so through this portal.',
                        ) !!}
                    </p>
                </div>
            </x-accordian.taxonomie>

            <x-accordian.taxonomie title="{!! __('What if my activities are not included in the taxonomy?') !!}" defaulthide="true">
                <div class="">
                    <p class="text-base text-esg8">
                        {!! __(
                            'Not all activities are currently covered by the classification system, so it may happen that a company does not have any activities covered by the Taxonomy. If you do not have any eligible activity, you should complete the exercise, without entering activities.',
                        ) !!}
                    </p>
                </div>
            </x-accordian.taxonomie>
        </div>

        <div class="mt-8 grid justify-center">
            <x-buttons.btn-icon-text class="bg-esg6 !normal-case !text-sm !font-medium !rounded-md"
                wire:click="start">
                <x-slot:buttonicon>
                    <span class="mr-2">{!! __('Start') !!}</span>
                </x-slot:buttonicon>
                <x-slot:slot>
                    @include('icons.arrow_right_round')
                </x-slot:slot>
            </x-buttons.btn-icon-text>
        </div>
    </div>
</div>
