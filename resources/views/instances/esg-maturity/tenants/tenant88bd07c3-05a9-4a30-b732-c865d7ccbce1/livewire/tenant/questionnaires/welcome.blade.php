@push('head')
    <style nonce="{{ csp_nonce() }}">
        .filipIcon {
            transform: scaleX(-1);
        }
    </style>
@endpush
<div>
    <x-slot name="header">
        <x-q-header>
            <x-slot name="left">
                <x-modules.questionnaires.topbar company="{{ $questionnaire->company->name }}" />
            </x-slot>
        </x-q-header>
    </x-slot>


    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-80 bg-gray-200 pt-10">
            @if ($questionnaire->categories)
                <div class="sm:col-span-1">
                    <div class="sticky ">
                        @livewire('questionnaires.menu', ['questionnaire' => $questionnaire->id, 'category' => null], key('menu'))
                    </div>
                </div>
            @endif
        </div>

        <!-- Main Content -->
        <div class="flex-1 px-4 md:px-8 mt-10 ">
            <div class="flex justify-between items-center">
                <div class="flex gap-4 items-center">
                    <h2 class="flex text-lg text-esg29 items-center">
                        @include('icons.building', [
                            'color' => color(5),
                            'height' => '26',
                            'width' => '26',
                            'class' => 'filipIcon px-1',
                        ])
                        {{ __('Company') }} {{ $questionnaire->company->name }}
                    </h2>

                    <p class="flex text-lg items-center text-esg29">
                        @include('icons.calender', [
                            'color' => color(5),
                            'height' => '25',
                            'width' => '25',
                            'class' => 'px-1',
                        ])
                        {{ $questionnaire->from->format('Y-m-d') }} {{ __('to') }}
                        {{ $questionnaire->to->format('Y-m-d') }}
                    </p>
                </div>
                <p class="flex items-center text-lg text-esg29 text-right"> {{ __('Overall Progress') }}
                    @include('icons.checkbox', [
                        'color' => color(5),
                        'height' => '26',
                        'width' => '26',
                        'class' => 'px-1',
                    ])
                    {{ round($questionnaire->progress) }}%
                </p>
            </div>

            <div class="px-4 md:px-8">
                <div class="mt-16">
                    <h2 class="text-esg6 text-lg font-bold text-center">
                        {!! __('Welcome to the start of your journey to sustainability') !!}</h2>
                    <p class="text-esg8 text-lg text-center">
                        {!! __('After submitting this questionnaire, depending on your answers, the :stylePhysical Risk Calculator, Biodiversity Calculator:closestyle and :styleGHG Emissions Calculator:closestyle questionnaires can be generated, which must be answered.',
                        ['style' => '<span class="text-esg5 font-black">', 'closestyle' => '</span>']) !!}
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mt-8">
                    <div class="flex gap-5">
                        <div class="">
                            @include('icons.navigation', ['fill' => color(5)])
                        </div>
                        <div class="">
                            <p class="text-base text-esg6 font-bold"> {!! __('Navigation') !!} </p>
                            <p class="text-base text-esg16">
                                {!! __('You can use the left side menu to choose a category or subcategory to start.') !!} </p>
                        </div>
                    </div>

                    <div class="flex gap-5">
                        <div class="">
                            @include('icons.disk')
                        </div>
                        <div class="">
                            <p class="text-base text-esg6 font-bold"> {!! __('Auto save') !!} </p>
                            <p class="text-base text-esg16">
                                {!! __('Each interaction with a question option is saved automatically') !!} </p>
                        </div>
                    </div>

                    <div class="flex gap-5">
                        <div class="">
                            @include('icons.assign-user')
                        </div>
                        <div class="">
                            <p class="text-base text-esg6 font-bold"> {!! __('Assign users') !!} </p>
                            <p class="text-base text-esg16">
                                {!! __('Make sure that before you start answering the questionnaire, you have assigned it to a user') !!}
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-5">
                        <div class="">
                            @include('icons.attachments')
                        </div>
                        <div class="">
                            <p class="text-base text-esg6 font-bold"> {!! __('Attach files') !!} </p>
                            <p class="text-base text-esg16">
                                {!! __('You can attach files as evidence, this helps the validator cross-check the information') !!}
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-5">
                        <div class="">
                            @include('icons.comments')
                        </div>
                        <div class="">
                            <p class="text-base text-esg6 font-bold"> {!! __('Leave comments') !!} </p>
                            <p class="text-base text-esg16">
                                {!! __('You can communicate with other users on each question or leave notes to yourself') !!} </p>
                        </div>
                    </div>
                </div>

                <div class=" grid justify-center mt-8">
                    <x-inputs.checkbox id="notshow" name="notshow"
                        label="{!! __('Do not show this intro page again') !!}" labelclass="text-esg8 text-sm" />
                </div>

                <div class="flex flex-row gap-6 items-center justify-center mt-4 w-full">
                    @php
                        $category = null;
                        foreach ($questionnaire->categories as $categorie) {
                            if ($categorie['progress'] > 0) {
                                $category = $categorie['id'];
                            }
                        }
                        $children = $categories->first()->children->count() ? $categories->first()->children->first() : $categories->first();
                    @endphp
                    <x-buttons.a
                        href="{{ route('tenant.questionnaires.show', ['questionnaire' => $questionnaire, 'category' => $category]) }}"
                        text="{{ __('Last answered question') }}"
                        class="!text-sm !normal-case !font-normal !w-52 !block !text-center !p-1.5 !rounded-md !border-2 !border-esg5 bg-esg6/80 hover:bg-esg5" />
                    <x-buttons.a
                        href="{{ route('tenant.questionnaires.show', ['questionnaire' => $questionnaire, 'category' => $children]) }}"
                        text="{{ __('Go to the beginning') }}"
                        class="!text-sm !normal-case !font-normal !w-52 !block !text-center !p-1.5 !rounded-md !border-2 !border-esg5 bg-esg6/80 hover:bg-esg5" />
                </div>
            </div>
        </div>
    </div>

</div>
