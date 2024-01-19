@push('body')
    <script nonce="{{ csp_nonce() }}">
        document.addEventListener("chartUpdated", event => {
        });
    </script>
@endpush
<div class="tdp-dashboard w-full">
    <x-slot name="header">
        <x-header title="{!! __('Dashboard') !!}" class="w-full" extraclass="!px-6" data-test="dashboard-header" click="{{ route('tenant.home') }}" >
            <x-slot name="left">
            </x-slot>
            <x-slot name="right">
                @if($charts != null)
                        <div>
                            <x-buttons.btn-icon-text class="bg-esg5 text-esg4 {{ $printView === 0 ? '' : 'hidden' }}" x-on:click="location.href = '{{ route('tenant.dashboard', ['questionnaire' => $selectedQuestionnaire->id]) . '?report=true' }}'">
                                <x-slot name="buttonicon">
                                    @include(tenant()->views .'icons.download', ['class' => 'inline'])
                                </x-slot>
                                <span class="ml-2">View report</span>
                            </x-buttons.btn-icon-text>

                        </div>
                    @endif
            </x-slot>
        </x-header>
    </x-slot>

    <div class="px-4 lg:px-0" >
        <div class="max-w-7xl mx-auto">
            <div class="mt-10">
                <div class="flex items-center gap-3">
                    <div class="text-esg8 text-lg font-semibold flex gap-2">
                        <x-inputs.select
                            modelmodifier="defer"
                            id="period"
                            :items="$period"
                            class="border border-esg10 py-1.5 px-4 !w-36"
                            :extra="['options' => $periodList, 'show_blank_opt' => false ]"
                        />
                    </div>
                    <div class="grow">
                        <x-inputs.tomselect
                            wire:model.defer="search.questionnaire"
                            :options="$questionnaireList"
                            plugins="['no_backspace_delete', 'remove_button', 'checkbox_options']"
                            :items="$search['questionnaire']"
                            class="w-full "
                            multiple
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="" x-data="{ main: true, environment: false, social: false, governance: false }">
        {{-- Tabs --}}
        <div class="my-8 grid grid-cols-1 md:grid-cols-4 gap-5">
            <div class="grid place-content-center border rounded-md w-full shadow cursor-pointer transition duration-300 "
                x-on:click="main= true, environment= false, social=false, governance=false"
                :class="main ? 'bg-esg6/10 border-esg6 text-esg6 font-bold' : 'bg-esg16/10 border-esg16/50 text-esg16 hover:bg-esg6/10 font-medium'">
                <div class="flex items-center cursor-pointer">
                    <label for="main" class="w-full py-4 ml-2 text-base cursor-pointer">{{ __('Main') }}</label>
                </div>
            </div>

            <div class="grid place-content-center border rounded-md shadow cursor-pointer transition duration-300"
                x-on:click="main= false, environment= true, social=false, governance=false"
                :class="environment ? 'bg-esg2/10 border-esg2 text-esg2 font-bold' : 'bg-esg16/10 border-esg16/50 text-esg16 hover:bg-esg2/10 font-medium'">
                <div class="flex items-center cursor-pointer">
                    <label for="Environment" class="w-full py-4 ml-2 text-base cursor-pointer ">{{ __('Environment') }}</label>
                </div>
            </div>

            <div class="grid place-content-center border  rounded-md shadow cursor-pointer transition duration-300"
                x-on:click="main= false, environment= false, social=true, governance=false"
                :class="social ? 'bg-esg1/10 border-esg1 text-esg1 font-bold' : 'bg-esg16/10 border-esg16/50 text-esg16 hover:bg-esg1/10 font-medium'">
                <div class="flex items-center cursor-pointer">
                    <label for="Social" class="w-full py-4 ml-2 text-base cursor-pointer ">{{ __('Social') }}</label>
                </div>
            </div>

            <div class="grid place-content-center border  rounded-md shadow cursor-pointer transition duration-300"
                x-on:click="main= false, environment= false, social=false, governance=true"
                :class="governance ? 'bg-esg3/10 border-esg3 text-esg3 font-bold' : 'bg-esg16/10 border-esg16/50 text-esg16 hover:bg-esg3/10 font-medium'">
                <div class="flex items-center cursor-pointer">
                    <label for="Governance" class="w-full py-4 ml-2 text-base cursor-pointer">{{ __('Governance') }}</label>
                </div>
            </div>
        </div>

        <div class="my-4 border border-esg7/30 rounded-md"></div>

        {{-- SECTION: Main --}}
        <div class="" x-show="main" x-data="{ overview: true, profile: false, chain: false, sdgs: false, performance: false }">
            <div class="my-8 grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="grid place-content-center border rounded-md w-full shadow cursor-pointer transition duration-300"
                    x-on:click="overview= true, profile= false, chain=false, sdgs=false, performance=false"
                    :class="overview ? 'bg-esg6/10 border-esg6 text-esg6 font-bold' : 'text-esg16 bg-white hover:bg-esg6/10 font-medium'">
                    <div class="flex items-center cursor-pointer">
                        <label for="main" class="w-full py-4 ml-2 text-base cursor-pointer">{{ __('Company  overview') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border rounded-md w-full shadow cursor-pointer transition duration-300"
                    x-on:click="overview= false, profile= true, chain=false, sdgs=false, performance=false"
                    :class="profile ? 'bg-esg6/10 border-esg6 text-esg6 font-bold' : 'text-esg16 bg-white hover:bg-esg6/10 font-medium'">
                    <div class="flex items-center cursor-pointer">
                        <label for="main" class="w-full py-4 ml-2 text-base cursor-pointer">{{ __('Customer profile') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border rounded-md w-full shadow cursor-pointer transition duration-300"
                    x-on:click="overview= false, profile= false, chain=true, sdgs=false, performance=false"
                    :class="chain ? 'bg-esg6/10 border-esg6 text-esg6 font-bold' : 'text-esg16 bg-white hover:bg-esg6/10 font-medium'">
                    <div class="flex items-center cursor-pointer">
                        <label for="main" class="w-full py-4 ml-2 text-base cursor-pointer">{{ __('Supply chain') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border rounded-md w-full shadow cursor-pointer transition duration-300"
                    x-on:click="overview= false, profile= false, chain=false, sdgs=true, performance=false"
                    :class="sdgs ? 'bg-esg6/10 border-esg6 text-esg6 font-bold' : 'text-esg16 bg-white hover:bg-esg6/10 font-medium'">
                    <div class="flex items-center cursor-pointer">
                        <label for="main" class="w-full py-4 ml-2 text-base cursor-pointer">{{ __('2030 Agenda and the SDGs') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border rounded-md w-full shadow cursor-pointer transition duration-300"
                    x-on:click="overview= false, profile= false, chain=false, sdgs=false, performance=true"
                    :class="performance ? 'bg-esg6/10 border-esg6 text-esg6 font-bold' : 'text-esg16 bg-white hover:bg-esg6/10 font-medium'">
                    <div class="flex items-center cursor-pointer">
                        <label for="main" class="w-full py-4 ml-2 text-base cursor-pointer">{{ __('Economic performance') }}</label>
                    </div>
                </div>
            </div>

            <div class="mt-8 bg-esg6/5 rounded">
                {{-- SECTION MAIN : Company overview --}}
                <div class="p-8" x-show="overview">
                    <x-cards.card-dashboard-version1-withshadow text="{!! __('Activities, brands, products and services') !!}"
                        titleclass="!text-sm"
                        class="!h-auto"
                        contentplacement="none">
                        <p class="text-sm text-esg8">{!! __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer in dignissim lorem. Nunc finibus tincidunt mollis. In pretium justo vitae magna vestibulum tristique. Vivamus rutrum ante eget auctor ullamcorper. Sed ac tempus tortor. Morbi sed nisi ut urna ultricies convallis non vel enim. Ut cursus interdum pellentesque.') !!}</p>
                    </x-cards.card>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-cards.card-icon-number text="{!! __('Head office location') !!}">
                            <x-slot:data>
                                <div class="flex items-center gap-2">
                                    <div class="">@include('icons.flag.pt', ['width' => 24, 'height' => 20])</div>
                                    <div class="text-xl">{!! __('Portugal') !!}</div>
                                </div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.location.pin', ['fill' => color(6)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        <x-cards.card-icon-number text="{!! __('Number of employees and other workers') !!}">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">1.234</div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.8.workers', ['color' => color(6)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>
                    </div>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-cards.card-icon-number text="{!! __('Head office location') !!}">
                            <x-slot:data>
                                <div class="flex items-center gap-2">
                                    <div class="">@include('icons.flag.pt', ['width' => 24, 'height' => 20])</div>
                                    <div class="text-xl">{!! __('Portugal') !!}</div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <div class="">@include('icons.flag.br', ['width' => 24, 'height' => 20])</div>
                                    <div class="text-xl">{!! __('Brazil') !!}</div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <div class="">@include('icons.flag.uk', ['width' => 24, 'height' => 20])</div>
                                    <div class="text-xl">{!! __('United Kindom') !!}</div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <div class="">@include('icons.flag.pt', ['width' => 24, 'height' => 20])</div>
                                    <div class="text-xl">{!! __('Portugal') !!}</div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <div class="">@include('icons.flag.pt', ['width' => 24, 'height' => 20])</div>
                                    <div class="text-xl">{!! __('Portugal') !!}</div>
                                </div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.location.pin', ['fill' => color(6)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        <div class="grid grid-cols-1 gap-6">
                            <x-cards.card-icon-number text="{!! __('Number of employees and other workers') !!}">
                                <x-slot:data>
                                    <div class="text-4xl text-esg8">1.234</div>
                                </x-slot:data>
                                <x-slot:icon>
                                    @include('icons.building', ['color' => color(6), 'width' => 48])
                                </x-slot:icon>
                            </x-cards.card-icon-number>

                            <x-cards.card-icon-number text="{!! __('Number of employees and other workers') !!}">
                                <x-slot:data>
                                    <div class="text-4xl text-esg8">1.234</div>
                                </x-slot:data>
                                <x-slot:icon>
                                    @include('icons.calender-v1', ['color' => color(6)])
                                </x-slot:icon>
                            </x-cards.card-icon-number>
                        </div>
                    </div>

                    @if ($selectedQuestionnaire->childrenSubmitted->count() > 0)
                    <div class="mt-6">
                        @foreach($selectedQuestionnaire->childrenSubmitted as $childQuestionnaire)
                        <x-cards.card-dashboard-version1-withshadow
                            text="{!! __($childQuestionnaire->type->name) !!}"
                            titleclass="!text-sm"
                            class="!h-auto mb-4"
                            contentplacement="none">
                            @livewire("dashboard.full-dashboard.dashboard{$childQuestionnaire->questionnaire_type_id}", ['questionnaireId' => $childQuestionnaire->id])
                        </x-cards.card-dashboard-version1-withshadow>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- SECTION MAIN : Customer profile --}}
                <div class="p-8" x-show="profile">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('Level of satisfaction on digital platforms') !!}"
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">
                            <div class="flex justify-between items-center border-b border-b-esg7/40 py-2">
                                <div class="text-sm text-esg8">{!! __('Airbnb') !!}</div>
                                <div class="text-base font-bold text-esg8 tracking-widest">4<span class="text-xs text-esg16 font-normal tracking-widest">/5</span></div>
                            </div>
                            <div class="flex justify-between items-center border-b border-b-esg7/40 py-2">
                                <div class="text-sm text-esg8">{!! __('Booking') !!}</div>
                                <div class="text-base font-bold text-esg8 tracking-widest">4<span class="text-xs text-esg16 font-normal tracking-widest">/5</span></div>
                            </div>
                            <div class="flex justify-between items-center border-b border-b-esg7/40 py-2">
                                <div class="text-sm text-esg8">{!! __('Edreams') !!}</div>
                                <div class="text-base font-bold text-esg8 tracking-widest">4<span class="text-xs text-esg16 font-normal tracking-widest">/5</span></div>
                            </div>
                            <div class="flex justify-between items-center border-b border-b-esg7/40 py-2">
                                <div class="text-sm text-esg8">{!! __('Expedia') !!}</div>
                                <div class="text-base font-bold text-esg8 tracking-widest">4<span class="text-xs text-esg16 font-normal tracking-widest">/10</span></div>
                            </div>
                            <div class="flex justify-between items-center border-b border-b-esg7/40 py-2">
                                <div class="text-sm text-esg8">{!! __('Google') !!}</div>
                                <div class="text-base font-bold text-esg8 tracking-widest">4<span class="text-xs text-esg16 font-normal tracking-widest">/10</span></div>
                            </div>
                            <div class="flex justify-between items-center border-b border-b-esg7/40 py-2">
                                <div class="text-sm text-esg8">{!! __('Google My Business') !!}</div>
                                <div class="text-base font-bold text-esg8 tracking-widest">@include('icons.checkbox-no')</div>
                            </div>
                            <div class="flex justify-between items-center border-b border-b-esg7/40 py-2">
                                <div class="text-sm text-esg8">{!! __('Hotels.com') !!}</div>
                                <div class="text-base font-bold text-esg8 tracking-widest">4<span class="text-xs text-esg16 font-normal tracking-widest">/10</span></div>
                            </div>
                            <div class="flex justify-between items-center border-b border-b-esg7/40 py-2">
                                <div class="text-sm text-esg8">{!! __('OpenTable') !!}</div>
                                <div class="text-base font-bold text-esg8 tracking-widest">4<span class="text-xs text-esg16 font-normal tracking-widest">/5</span></div>
                            </div>
                            <div class="flex justify-between items-center border-b border-b-esg7/40 py-2">
                                <div class="text-sm text-esg8">{!! __('The Fork') !!}</div>
                                <div class="text-base font-bold text-esg8 tracking-widest">4<span class="text-xs text-esg16 font-normal tracking-widest">/10</span></div>
                            </div>
                            <div class="flex justify-between items-center border-b border-b-esg7/40 py-2">
                                <div class="text-sm text-esg8">{!! __('Tripadvisor') !!}</div>
                                <div class="text-base font-bold text-esg8 tracking-widest">4<span class="text-xs text-esg16 font-normal tracking-widest">/5</span></div>
                            </div>
                            <div class="flex justify-between items-center border-b border-b-esg7/40 py-2">
                                <div class="text-sm text-esg8">{!! __('Trivago') !!}</div>
                                <div class="text-base font-bold text-esg8 tracking-widest">4<span class="text-xs text-esg16 font-normal tracking-widest">/10</span></div>
                            </div>
                            <div class="flex justify-between items-center border-b border-b-esg7/40 py-2">
                                <div class="text-sm text-esg8">{!! __('Yelp') !!}</div>
                                <div class="text-base font-bold text-esg8 tracking-widest">4<span class="text-xs text-esg16 font-normal tracking-widest">/5</span></div>
                            </div>
                            <div class="flex justify-between items-center border-b border-b-esg7/40 py-2">
                                <div class="text-sm text-esg8">{!! __('Zomato') !!}</div>
                                <div class="text-base font-bold text-esg8 tracking-widest">4<span class="text-xs text-esg16 font-normal tracking-widest">/5</span></div>
                            </div>
                        </x-cards.card>

                        <x-cards.card-dashboard-version1-withshadow text="{!! __('Number of complaints and praises') !!}"
                            titleclass="!text-sm"
                            class="!h-min"
                            contentplacement="none">

                            <x-charts.chartjs id="number_of_complaints_and_praises" class="" x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Complaints'), __('Praises')]) }},
                                        {{ json_encode([ '513', '800' ]) }},
                                        'number_of_complaints_and_praises',
                                        ['{{ color(6) }}'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });" />

                        </x-cards.card>
                    </div>
                </div>

                {{-- SECTION MAIN : Supply chain --}}
                <div class="p-8" x-show="chain">
                    <x-cards.card-dashboard-version1-withshadow text="{!! __('Characterisation of suppliers - main brands, products and services') !!}"
                        titleclass="!text-sm"
                        class="!h-auto"
                        contentplacement="none">
                        <p class="text-sm text-esg8">{!! __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer in dignissim lorem. Nunc finibus tincidunt mollis. In pretium justo vitae magna vestibulum tristique. Vivamus rutrum ante eget auctor ullamcorper. Sed ac tempus tortor. Morbi sed nisi ut urna ultricies convallis non vel enim. Ut cursus interdum pellentesque.') !!}</p>
                    </x-cards.card>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-cards.card-icon-number text="{!! __('Geographical location of suppliers') !!}" class="items-center">
                            <x-slot:data>
                                <div class="flex items-center gap-2">
                                    <div class="">@include('icons.flag.pt', ['width' => 24, 'height' => 20])</div>
                                    <div class="text-xl">{!! __('Portugal') !!}</div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <div class="">@include('icons.flag.pt', ['width' => 24, 'height' => 20])</div>
                                    <div class="text-xl">{!! __('Portugal') !!}</div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <div class="">@include('icons.flag.pt', ['width' => 24, 'height' => 20])</div>
                                    <div class="text-xl">{!! __('Portugal') !!}</div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <div class="">@include('icons.flag.pt', ['width' => 24, 'height' => 20])</div>
                                    <div class="text-xl">{!! __('Portugal') !!}</div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <div class="">@include('icons.flag.pt', ['width' => 24, 'height' => 20])</div>
                                    <div class="text-xl">{!! __('Portugal') !!}</div>
                                </div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.location.pin', ['fill' => color(6)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        <div class="grid grid-cols-1 gap-6">
                            <x-cards.card-icon-number text="{!! __('Total number of suppliers') !!}">
                                <x-slot:data>
                                    <div class="text-4xl text-esg8">1.234</div>
                                </x-slot:data>
                                <x-slot:icon>
                                    @include( tenant()->views . 'icons.supplier', ['color' => color(6), 'width' => 48])
                                </x-slot:icon>
                            </x-cards.card-icon-number>

                            <x-cards.card-icon-number text="{!! __('First tier or direct suppliers') !!}">
                                <x-slot:data>
                                    <div class="text-4xl text-esg8">1.234</div>
                                </x-slot:data>
                                <x-slot:icon>
                                    @include( tenant()->views . 'icons.direct_supplier', ['color' => color(6)])
                                </x-slot:icon>
                            </x-cards.card-icon-number>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-cards.card-icon-number text="{!! __('suppliers of reporting units at risk of modern slavery incidents') !!}">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">1.234</div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include( tenant()->views . 'icons.slavery', ['fill' => color(6)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        <x-cards.card-icon-number text="{!! __('suppliers of reporting units at risk of child labour and exploitation incidents') !!}">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">1.234</div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include( tenant()->views . 'icons.child_labour', ['fill' => color(6)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>
                    </div>
                </div>

                {{-- SECTION MAIN : 2030 Agenda and the SDGs --}}
                <div class="p-8" x-show="sdgs">
                    <x-cards.card-dashboard-version1-withshadow text="{!! __('Sustainable Development Goals') !!}"
                        titleclass="!text-sm"
                        class="!h-auto"
                        contentplacement="none">
                        <div class="text-esg25 font-encodesans text-5xl font-bold">
                            <div class="grid grid-cols-4 md:grid-cols-9 gap-3">
                                <div class="w-full">
                                    @include('icons.goals.1', ['class' => 'inline-block', 'color' => $charts['sdg'][1] ? '#EA1D2D' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.2', ['class' => 'inline-block', 'color' => $charts['sdg'][2] ? '#D19F2A' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.3', ['class' => 'inline-block', 'color' => $charts['sdg'][3] ? '#2D9A47' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.4', ['class' => 'inline-block', 'color' => $charts['sdg'][4] ? '#C22033' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.5', ['class' => 'inline-block', 'color' => $charts['sdg'][5] ? '#EF412A' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.6', ['class' => 'inline-block', 'color' => $charts['sdg'][6] ? '#00ADD8' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.7', ['class' => 'inline-block', 'color' => $charts['sdg'][7] ? '#FDB714' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.8', ['class' => 'inline-block', 'color' => $charts['sdg'][8] ? '#8F1838' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.9', ['class' => 'inline-block', 'color' => $charts['sdg'][9] ? '#F36E24' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.10', ['class' => 'inline-block', 'color' => $charts['sdg'][10] ? '#E01A83' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.11', ['class' => 'inline-block', 'color' => $charts['sdg'][11] ? '#F99D25' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.12', ['class' => 'inline-block', 'color' => $charts['sdg'][12] ? '#CD8B2A' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.13', ['class' => 'inline-block', 'color' => $charts['sdg'][13] ? '#48773C' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.14', ['class' => 'inline-block', 'color' => $charts['sdg'][14] ? '#007DBB' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.15', ['class' => 'inline-block', 'color' => $charts['sdg'][15] ? '#40AE49' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.16', ['class' => 'inline-block', 'color' => $charts['sdg'][16] ? '#00558A' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.17', ['class' => 'inline-block', 'color' => $charts['sdg'][17] ? '#1A3668' : '#DCDCDC'])
                                </div>
                            </div>
                        </div>
                    </x-cards.card>
                </div>

                {{-- SECTION MAIN : Economic performance --}}
                <div class="p-8" x-show="performance">
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-cards.card-icon-number text="{!! __('total of Direct economic value generated') !!}">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base">€</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include(tenant()->views . 'icons.investment', ['fill' => color(6)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        <x-cards.card-icon-number text="{!! __('total of Accumulated economic value') !!}">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base">€</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include(tenant()->views . 'icons.salary', ['fill' => color(6)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        <x-cards.card-icon-number text="{!! __('total of Economic value distributed') !!}">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base">€</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include(tenant()->views . 'icons.salary', ['fill' => color(6)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        <x-cards.card-icon-number text="{!! __('total of Investment expenditure') !!}">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base">€</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include(tenant()->views . 'icons.investment', ['fill' => color(6)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        <x-cards.card-icon-number text="{!! __('total of Financial support received from the State') !!}">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base">€</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include(tenant()->views . 'icons.investment', ['fill' => color(6)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        <x-cards.card-icon-number text="{!! __('Eligible turnover') !!}">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base">€</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include(tenant()->views . 'icons.salary', ['fill' => color(6)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        <x-cards.card-icon-number text="{!! __('Eligible CapEx') !!}">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base">€</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include(tenant()->views . 'icons.capx', ['fill' => color(6)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        <x-cards.card-icon-number text="{!! __('Eligible OpEx') !!}">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base">€</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include(tenant()->views . 'icons.capx', ['fill' => color(6)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION: Environment --}}
        <div class="" x-show="environment" x-data="{ water: true, energy: false, gas: false, biodiversity: false, climate: false, waste: false, economy: false, policies: false }">
            <div class="my-8 grid grid-cols-1 md:grid-cols-4 gap-5">
                <div class="grid place-content-center border rounded-md shadow cursor-pointer transition duration-300"
                    x-on:click="water=true, energy=false, gas=false, biodiversity=false, climate=false, waste=false, economy=false, policies=false"
                    :class="water ? 'bg-esg2/10 border-esg2 text-esg2 font-bold' : 'text-esg16 hover:bg-esg2/10 font-medium bg-white'">
                    <div class="flex items-center cursor-pointer">
                        <label for="Environment" class="w-full py-4 ml-2 text-base cursor-pointer ">{{ __('Water consumption') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border rounded-md shadow cursor-pointer transition duration-300"
                    x-on:click="water=false, energy=true, gas=false, biodiversity=false, climate=false, waste=false, economy=false, policies=false"
                    :class="energy ? 'bg-esg2/10 border-esg2 text-esg2 font-bold' : 'text-esg16 hover:bg-esg2/10 font-medium bg-white'">
                    <div class="flex items-center cursor-pointer">
                        <label for="Environment" class="w-full py-4 ml-2 text-base cursor-pointer ">{{ __('Energy consumption') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border rounded-md shadow cursor-pointer transition duration-300"
                    x-on:click="water=false, energy=false, gas=true, biodiversity=false, climate=false, waste=false, economy=false, policies=false"
                    :class="gas ? 'bg-esg2/10 border-esg2 text-esg2 font-bold' : 'text-esg16 hover:bg-esg2/10 font-medium bg-white'">
                    <div class="flex items-center cursor-pointer">
                        <label for="Environment" class="w-full py-4 ml-2 text-base cursor-pointer ">{{ __('Atmospheric emissions') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border rounded-md shadow cursor-pointer transition duration-300"
                    x-on:click="water=false, energy=false, gas=false, biodiversity=true, climate=false, waste=false, economy=false, policies=false"
                    :class="biodiversity ? 'bg-esg2/10 border-esg2 text-esg2 font-bold' : 'text-esg16 hover:bg-esg2/10 font-medium bg-white'">
                    <div class="flex items-center cursor-pointer">
                        <label for="Environment" class="w-full py-4 ml-2 text-base cursor-pointer ">{{ __('Pressure on biodiversity') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border rounded-md shadow cursor-pointer transition duration-300"
                    x-on:click="water=false, energy=false, gas=false, biodiversity=false, climate=true, waste=false, economy=false, policies=false"
                    :class="climate ? 'bg-esg2/10 border-esg2 text-esg2 font-bold' : 'text-esg16 hover:bg-esg2/10 font-medium bg-white'">
                    <div class="flex items-center cursor-pointer">
                        <label for="Environment" class="w-full py-4 ml-2 text-base cursor-pointer ">{{ __('Climate risks') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border rounded-md shadow cursor-pointer transition duration-300"
                    x-on:click="water=false, energy=false, gas=false, biodiversity=false, climate=false, waste=true, economy=false, policies=false"
                    :class="waste ? 'bg-esg2/10 border-esg2 text-esg2 font-bold' : 'text-esg16 hover:bg-esg2/10 font-medium bg-white'">
                    <div class="flex items-center cursor-pointer">
                        <label for="Environment" class="w-full py-4 ml-2 text-base cursor-pointer ">{{ __('Waste management') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border rounded-md shadow cursor-pointer transition duration-300"
                    x-on:click="water=false, energy=false, gas=false, biodiversity=false, climate=false, waste=false, economy=true, policies=false"
                    :class="economy ? 'bg-esg2/10 border-esg2 text-esg2 font-bold' : 'text-esg16 hover:bg-esg2/10 font-medium bg-white'">
                    <div class="flex items-center cursor-pointer">
                        <label for="Environment" class="w-full py-4 ml-2 text-base cursor-pointer ">{{ __('Circular economy') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border rounded-md shadow cursor-pointer transition duration-300"
                    x-on:click="water=false, energy=false, gas=false, biodiversity=false, climate=false, waste=false, economy=false, policies=true"
                    :class="policies ? 'bg-esg2/10 border-esg2 text-esg2 font-bold' : 'text-esg16 hover:bg-esg2/10 font-medium bg-white'">
                    <div class="flex items-center cursor-pointer">
                        <label for="Environment" class="w-full py-4 ml-2 text-base cursor-pointer ">{{ __('Environmental policies') }}</label>
                    </div>
                </div>
            </div>

            <div class="mt-8 bg-esg2/5 rounded">
                {{-- SECTION Environment : Water consumption --}}
                <div class="p-8" x-show="water">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Chart: Water consumption --}}
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('Water consumption') !!}"
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">

                            <x-charts.bar id="water_consumption"
                                class="m-auto relative !h-full !w-full"
                                unit="{{ 'm3' }}"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Acquired from third parties'), __('From water resources (sea/river)'), __('Borehole / well water')]) }},
                                        {{ json_encode([ '513', '613', '800' ]) }},
                                        'water_consumption',
                                        ['{{ color(2) }}'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });" />
                        </x-cards.card>

                        {{-- List: Water efficiency measures --}}
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('Water efficiency measures') !!}"
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">

                            <x-list.bullet title="{!! __('Replacement of taps, shower systems, flow meters, economizers, toilet cisterns and other efficient products;') !!}" />
                            <x-list.bullet class="mt-2" title="{!! __('Replacement of washing equipment, such as washing machines and dishwashers, with more efficient equipment;') !!}" />
                            <x-list.bullet class="mt-2" title="{!! __('Maintenance interventions and prevention of leaks in the water supply and distribution network;') !!}" />
                            <x-list.bullet class="mt-2" title="{!! __('Implementation of partial meters per building and/or types of water use;') !!}" />
                            <x-list.bullet class="mt-2" title="{!! __('Rainwater harvesting systems;') !!}" />
                            <x-list.bullet class="mt-2" title="{!! __('Installation of infrastructure that allows new water sources to be used;') !!}" />
                            <x-list.bullet class="mt-2" title="{!! __('Installation of distribution infrastructures between WWTP and golf courses that allow the use of water for reuse in the aforementioned establishments.') !!}" />
                        </x-cards.card-dashboard-version1-withshadow>

                        {{-- Checkbox: Waste water discharges --}}
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('Waste water discharges') !!}"
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">
                            @foreach($charts['waste_water_discharges'] as $row)
                                <x-list.checkbox label="{{ $row['label'] }}" status="{{ $row['status'] }}" color="2" />
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>

                {{-- SECTION Environment : Energy consumption --}}
                <div class="p-8" x-show="energy">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="grid grid-cols-1 gap-6">
                            <x-cards.card-icon-number text="{!! __('Energy consumption') !!}">
                                <x-slot:data>
                                    <div class="text-4xl text-esg8">00.000 <span class="text-base">kWh</span></div>
                                </x-slot:data>
                                <x-slot:icon>
                                    @include('icons.dashboard.gestao-energia', ['color' => color(2)])
                                </x-slot:icon>
                            </x-cards.card-icon-number>

                            <x-cards.card-icon-number text="{!! __('Energy intensity') !!}">
                                <x-slot:data>
                                    <div class="text-4xl text-esg8">00.000 <span class="text-base">MWh / €</span></div>
                                </x-slot:data>
                                <x-slot:icon>
                                    @include('icons.dashboard.gestao-energia', ['color' => color(2)])
                                </x-slot:icon>
                            </x-cards.card-icon-number>

                            <x-cards.card-icon-number text="{!! __('Carbon intensity') !!}">
                                <x-slot:data>
                                    <div class="text-4xl text-esg8">00.000 <span class="text-base"> tCO2e / €</span></div>
                                </x-slot:data>
                                <x-slot:icon>
                                    @include('icons.dashboard.emission', ['fill' => color(2)])
                                </x-slot:icon>
                            </x-cards.card-icon-number>
                        </div>

                        <div class="">
                            @php
                                $subpoint = json_encode([
                                    [ 'color' => 'bg-[#008131]', 'text' => __('Renewable')],
                                    [ 'color' => 'bg-[#66CE03]', 'text' => __('Non-renewable')]
                                ]);
                            @endphp
                            {{-- Chart: energy consumption --}}
                            <x-cards.card-dashboard-version1-withshadow text="{!! __('energy consumption') !!}"
                                subpoint="{{ $subpoint }}"
                                titleclass="!text-sm"
                                class="!h-full"
                                contentplacement="none">

                                <x-charts.chartjs id="energy_consumption" class="" width="350"
                                    height="150" x-init="$nextTick(() => {
                                        tenantDoughnutChart(
                                            ['{{ __('Renewable') }}', '{{ __('Non-renewable') }}'],
                                            [51.25, 153.75],
                                            'energy_consumption',
                                            ['#008131', '#66CE03'], {
                                                showTooltips: true, // toogle tolltips visibility
                                                percentagem: true, // Add the percentage value to the labels
                                                legend: {
                                                    display: true,  // toogle legend visibility
                                                    position: 'right', // show the legend on the right side
                                                },
                                                plugins: {
                                                    legendOnCenter: true,  // custom plugin to show a number at the center of the chart
                                                }
                                            }
                                        );
                                    });" />
                            </x-cards.card>
                        </div>

                        {{-- List: Energy Efficiency Measures --}}
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('Energy Efficiency Measures') !!}"
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">

                            <x-list.bullet bgcolor="bg-esg2" title="{!! __('Replacement of taps, shower systems, flow meters, economizers, toilet cisterns and other efficient products;') !!}" />
                            <x-list.bullet bgcolor="bg-esg2" class="mt-2" title="{!! __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer in dignissim lore;') !!}" />
                            <x-list.bullet bgcolor="bg-esg2" class="mt-2" title="{!! __('Nunc finibus tincidunt mollis. In pretium justo vitae magna vestibulum tristique;') !!}" />
                            <x-list.bullet bgcolor="bg-esg2" class="mt-2" title="{!! __('Vivamus rutrum ante eget auctor ullamcorper. Sed ac tempus tortor;') !!}" />
                            <x-list.bullet bgcolor="bg-esg2" class="mt-2" title="{!! __('Morbi sed nisi ut urna ultricies convallis non vel enim. Ut cursus interdum pellentesque.') !!}" />
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>

                {{-- SECTION Environment : Greenhouse gas emissions --}}
                <div class="p-8" x-show="gas">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Checkbox: Emission gas --}}
                        <x-cards.card-dashboard-version1-withshadow
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">
                            @foreach($charts['emission_gas'] as $row)
                                <x-list.checkbox label="{{ $row['label'] }}" status="{{ $row['status'] }}" color="2" />
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>

                        {{-- Icon: Carbon intensity --}}
                        <x-cards.card-icon-number text="{!! __('Carbon intensity') !!}" class="items-center">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base"> kgCO2e / €</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.emission', ['fill' => color(2)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        {{-- Chart: GHG emissions by category --}}
                        @php
                            $subpoint = json_encode([
                                    [ 'color' => 'bg-[#008131]', 'text' => __('Scope 1')],
                                    [ 'color' => 'bg-[#66CE03]', 'text' => __('Scope 2')],
                                    [ 'color' => 'bg-[#98BDA6]', 'text' => __('Scope 3')]
                                ]);

                            $subinfo = json_encode([
                                ['value' => '2.064,13', 'unit' => 't CO2 eq'],
                            ]);
                        @endphp
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('GHG emissions by category') !!}"
                            subpoint="{{ $subpoint }}"
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">

                            <x-charts.bar id="emissions_by_category"
                                class="m-auto relative !h-full !w-full"
                                unit="{{ 'tCO2eq' }}"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Scope 1'), __('Scope 2'), __('Scope 3')]) }},
                                        {{ json_encode([ '513.10', '751.03', '800.00' ]) }},
                                        'emissions_by_category',
                                        ['#008131', '#66CE03', '#98BDA6'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });"
                                subinfo="{{ $subinfo }}" />
                        </x-cards.card>

                        {{-- Chart: air pollutant emissions --}}
                        @php
                            $subinfo = json_encode([
                                ['value' => '2.064,13', 'unit' => 't'],
                            ]);
                        @endphp
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('air pollutant emissions') !!}"
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">

                            <x-charts.bar id="air_pollutant_emissions"
                                class="m-auto relative !h-full !w-full"
                                unit="{{ 't' }}"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('SO2'), __('NOx'), __('NMVOC'), __('PM2,5'), __('PM2,5'), __('Heavy Metals'), __('O3 depleting substances')]) }},
                                        {{ json_encode([ '800.00','800.00','800.00','800.00','800.00','800.00', '800.00' ]) }},
                                        'air_pollutant_emissions',
                                        ['#008131'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });"
                                subinfo="{{ $subinfo }}" />
                        </x-cards.card>
                    </div>
                </div>

                {{-- SECTION Environment : Pressure on biodiversity --}}
                <div class="p-8" x-show="biodiversity">
                    <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                        {{-- Checkbox: Enviroment impact --}}
                        <x-cards.card-dashboard-version1-withshadow
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">
                            @foreach($charts['enviroment_impact'] as $row)
                                <x-list.checkbox label="{{ $row['label'] }}" status="{{ $row['status'] }}" color="2" />
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>

                        {{-- Location: Locations in protected areas or areas of high biodiversity value --}}
                        <x-cards.card-dashboard-version1-withshadow
                            text="{!! __('Locations in protected areas or areas of high biodiversity value') !!}"
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">

                            <div class="flex items-center gap-2">
                                <div class="px-20">
                                    @include(tenant()->views .'icons.map')
                                </div>
                                <div class="grow">
                                    <x-tables.table class="!min-w-full">
                                        <x-slot name="thead">
                                            <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left">{{ __('Location') }}</x-tables.th>
                                            <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left">{{ __('Protected Area') }}</x-tables.th>
                                            <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left">{{ __('Type') }}</x-tables.th>
                                        </x-slot>
                                        <x-tables.tr>
                                            <x-tables.td>
                                                <div class="flex items-start gap-2.5">
                                                    <div class="w-2">
                                                        <div class="bg-[#FF3E3A] w-2 h-2 rounded-full mt-1.5"></div>
                                                    </div>
                                                    <div class="text-sm text-esg8 grow">
                                                        {!! __('Location Name 01') !!}
                                                    </div>
                                                </div>
                                            </x-tables.td>
                                            <x-tables.td>{!! __('Serra da Estrela') !!}</x-tables.td>
                                            <x-tables.td>{!! __('Natural Park') !!}</x-tables.td>
                                        </x-tables.tr>
                                        <x-tables.tr>
                                            <x-tables.td>
                                                <div class="flex items-start gap-2.5">
                                                    <div class="w-2">
                                                        <div class="bg-[#FBC02D] w-2 h-2 rounded-full mt-1.5"></div>
                                                    </div>
                                                    <div class="text-sm text-esg8 grow">
                                                        {!! __('Location Name 02') !!}
                                                    </div>
                                                </div>
                                            </x-tables.td>
                                            <x-tables.td>{!! __('Albufeira do Azibo') !!}</x-tables.td>
                                            <x-tables.td>{!! __('Regional Protected Landscape') !!}</x-tables.td>
                                        </x-tables.tr>
                                    </x-tables.table>
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>

                {{-- SECTION Environment : Climate risks --}}
                <div class="p-8" x-show="climate">
                    <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                        {{-- physical risks --}}
                        <x-cards.card-dashboard-version1-withshadow
                            text="{!! __('physical risks') !!}"
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">
                            <div class="overflow-y-auto">
                                @include(tenant()->views . 'icons.physical_risk', ['class' => 'md:w-full'])
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>

                    {{-- Transitional risks --}}
                    <x-cards.card-dashboard-version1-withshadow
                        text="{!! __('Transitional risks') !!}"
                        titleclass="!text-sm"
                        class="!h-auto !mt-6"
                        contentplacement="none">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="">
                                {{-- Icon: Greenhouse gas emissions --}}
                                <x-cards.card-icon-number text="{!! __('Atmospheric emissions') !!}" class="items-center !shadow-none border border-esg7/40 !h-min">
                                    <x-slot:data>
                                        <div class="text-4xl text-esg8">00.000 <span class="text-base">tCO<sub>2</sub></span></div>
                                    </x-slot:data>
                                    <x-slot:icon>
                                        @include('icons.dashboard.emission', ['fill' => color(2)])
                                    </x-slot:icon>
                                </x-cards.card-icon-number>

                                {{-- Icon: Investment in Research and Development --}}
                                <x-cards.card-icon-number text="{!! __('Investment in Research and Development') !!}" class="items-center !shadow-none border border-esg7/40 !h-min !mt-6">
                                    <x-slot:data>
                                        <div class="text-4xl text-esg8">00.000 <span class="text-base">€</span></div>
                                    </x-slot:data>
                                    <x-slot:icon>
                                        @include(tenant()->views . 'icons.investment', ['fill' => color(2)])
                                    </x-slot:icon>
                                </x-cards.card-icon-number>
                            </div>

                            <div>
                                {{-- Chart | BAR : Energy costs --}}
                                @php
                                    $subinfo = json_encode([
                                        ['value' => '1.313,00', 'unit' => '€'],
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1-withshadow text="{!! __('Energy costs') !!}"
                                    titleclass="!text-sm"
                                    class="!h-auto !shadow-none border border-esg7/40"
                                    contentplacement="none">

                                    <x-charts.bar id="energy_costs"
                                        class="m-auto relative !h-full !w-full"
                                        unit="{{ '€' }}"
                                        x-init="$nextTick(() => {
                                            tenantBarChart(
                                                {{ json_encode([ __('Electricity'), __('Fuel')]) }},
                                                {{ json_encode([ '513', '800' ]) }},
                                                'energy_costs',
                                                ['#008131'],
                                                null,
                                                {
                                                    showTitle: true,
                                                    simplifiedGrid: true
                                                }
                                            );
                                        });"
                                        subinfo="{{ $subinfo }}" />
                                </x-cards.card>
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>
                </div>

                {{-- SECTION Environment : Waste management --}}
                <div class="p-8" x-show="waste">
                    {{-- Checkbox: Waste management --}}
                    <x-cards.card-dashboard-version1-withshadow
                        titleclass="!text-sm"
                        class="!h-auto"
                        contentplacement="none">
                        @foreach($charts['waste_management'] as $row)
                            <x-list.checkbox label="{{ $row['label'] }}" status="{{ $row['status'] }}" color="2" />
                        @endforeach
                    </x-cards.card-dashboard-version1-withshadow>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        {{-- Icon: Proportion of waste sent for recycling --}}
                        <x-cards.card-icon-number text="{!! __('Proportion of waste sent for recycling') !!}" class="items-center">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base">%</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.recicle-residue', ['color' => color(2)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        {{-- Icon: Quantity of hazardous waste generated  --}}
                        <x-cards.card-icon-number text="{!! __('Quantity of hazardous waste generated') !!}" class="items-center">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base">kg</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.radioactive-residue', ['fill' => color(2)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>
                    </div>

                    <div class="mt-6">
                        {{-- Chart | BAR : non-hazardous waste generated by type --}}
                        @php
                            $subinfo = json_encode([
                                ['value' => '00.000', 'unit' => 'kg'],
                            ]);
                        @endphp
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('non-hazardous waste generated by type') !!}"
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">

                            <x-charts.bar id="non-hazardous_waste_generated"
                                class="m-auto relative !h-full !w-full"
                                unit="{{ 'kg' }}"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Paper/card box'), __('Plastic/metal'), __('Glass'), __('Organic'), __(' Undifferentiated'), __('Construction and demolition'), __('Health care')]) }},
                                        {{ json_encode([ '800', '800', '800', '800', '800', '800', '800' ]) }},
                                        'non-hazardous_waste_generated',
                                        ['#008131'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });"
                                subinfo="{{ $subinfo }}" />
                        </x-cards.card>
                    </div>
                </div>

                {{-- SECTION Environment : Circular economy --}}
                <div class="p-8" x-show="economy">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Checkbox: moniter --}}
                        <x-cards.card-dashboard-version1-withshadow
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">
                            @foreach($charts['moniter'] as $row)
                                <x-list.checkbox label="{{ $row['label'] }}" status="{{ $row['status'] }}" color="2" />
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>

                        {{-- Icon: Quantity of reused materials  --}}
                        <x-cards.card-icon-number text="{!! __('Quantity of reused materials') !!}" class="items-center">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base">kg</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.recicle-residue', ['color' => color(2)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        {{-- Icon: food made available and meals cooked  --}}
                        <x-cards.card-icon-number text="{!! __('food made available and meals cooked') !!}" class="items-center">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base">kg</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.iftar', ['fill' => color(2)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        {{-- Icon: food and meals wasted   --}}
                        <x-cards.card-icon-number text="{!! __('food and meals wasted ') !!}" class="items-center">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base">kg</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.residues', ['color' => color(2)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        {{-- Icon: food and meals redistributed  --}}
                        <x-cards.card-icon-number text="{!! __('food and meals redistributed') !!}" class="items-center">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base">kg</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.food-donation', ['fill' => color(2)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        {{-- Icon: cooking oil put into recycling  --}}
                        <x-cards.card-icon-number text="{!! __('cooking oil put into recycling') !!}" class="items-center">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base">l</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.oil', ['fill' => color(2)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>
                    </div>
                </div>

                {{-- SECTION Environment : Environmental policies --}}
                <div class="p-8" x-show="policies">
                    <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                        {{-- Checkbox: policies --}}
                        <x-cards.card-dashboard-version1-withshadow
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">
                            @foreach($charts['policies'] as $row)
                                <x-list.checkbox label="{{ $row['label'] }}" status="{{ $row['status'] }}" color="2" />
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION: Social --}}
        <div class="" x-show="social" x-data="{ hiring: true, pay: false, workforce: false, qualifications: false, health: false, family: false, communities: false, policies: false }">
            <div class="my-8 grid grid-cols-1 md:grid-cols-4 gap-5">
                <div class="grid place-content-center border  rounded-md shadow cursor-pointer transition duration-300"
                    x-on:click="hiring=true, pay=false, workforce=false, qualifications=false, health=false, family=false, communities=false, policies=false"
                    :class="hiring ? 'bg-esg1/10 border-esg1 text-esg1 font-bold' : 'text-esg16 hover:bg-esg1/10 font-medium bg-white'">
                    <div class="flex items-center cursor-pointer">
                        <label for="Social" class="w-full py-4 ml-2 text-base cursor-pointer ">{{ __('Contracting Model') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border  rounded-md shadow cursor-pointer transition duration-300"
                    x-on:click="hiring=false, pay=true, workforce=false, qualifications=false, health=false, family=false, communities=false, policies=false"
                    :class="pay ? 'bg-esg1/10 border-esg1 text-esg1 font-bold' : 'text-esg16 hover:bg-esg1/10 font-medium bg-white'">
                    <div class="flex items-center cursor-pointer">
                        <label for="Social" class="w-full py-4 ml-2 text-base cursor-pointer ">{{ __('Equal Pay') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border  rounded-md shadow cursor-pointer transition duration-300"
                    x-on:click="hiring=false, pay=false, workforce=true, qualifications=false, health=false, family=false, communities=false, policies=false"
                    :class="workforce ? 'bg-esg1/10 border-esg1 text-esg1 font-bold' : 'text-esg16 hover:bg-esg1/10 font-medium bg-white'">
                    <div class="flex items-center cursor-pointer">
                        <label for="Social" class="w-full py-4 ml-2 text-base cursor-pointer ">{{ __('Workforce Diversity') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border  rounded-md shadow cursor-pointer transition duration-300"
                    x-on:click="hiring=false, pay=false, workforce=false, qualifications=true, health=false, family=false, communities=false, policies=false"
                    :class="qualifications ? 'bg-esg1/10 border-esg1 text-esg1 font-bold' : 'text-esg16 hover:bg-esg1/10 font-medium bg-white'">
                    <div class="flex items-center cursor-pointer">
                        <label for="Social" class="w-full py-4 ml-2 text-base cursor-pointer ">{{ __('Workers Qualifications') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border  rounded-md shadow cursor-pointer transition duration-300"
                    x-on:click="hiring=false, pay=false, workforce=false, qualifications=false, health=true, family=false, communities=false, policies=false"
                    :class="health ? 'bg-esg1/10 border-esg1 text-esg1 font-bold' : 'text-esg16 hover:bg-esg1/10 font-medium bg-white'">
                    <div class="flex items-center cursor-pointer">
                        <label for="Social" class="w-full py-4 ml-2 text-base cursor-pointer ">{{ __('Occupational health and safety') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border  rounded-md shadow cursor-pointer transition duration-300"
                    x-on:click="hiring=false, pay=false, workforce=false, qualifications=false, health=false, family=true, communities=false, policies=false"
                    :class="family ? 'bg-esg1/10 border-esg1 text-esg1 font-bold' : 'text-esg16 hover:bg-esg1/10 font-medium bg-white'">
                    <div class="flex items-center cursor-pointer">
                        <label for="Social" class="w-full py-4 ml-2 text-base cursor-pointer text-center">{{ __('Balancing work, personal and family life') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border  rounded-md shadow cursor-pointer transition duration-300"
                    x-on:click="hiring=false, pay=false, workforce=false, qualifications=false, health=false, family=false, communities=true, policies=false"
                    :class="communities ? 'bg-esg1/10 border-esg1 text-esg1 font-bold' : 'text-esg16 hover:bg-esg1/10 font-medium bg-white'">
                    <div class="flex items-center cursor-pointer">
                        <label for="Social" class="w-full py-4 ml-2 text-base cursor-pointer ">{{ __('Local communities') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border  rounded-md shadow cursor-pointer transition duration-300"
                    x-on:click="hiring=false, pay=false, workforce=false, qualifications=false, health=false, family=false, communities=false, policies=true"
                    :class="policies ? 'bg-esg1/10 border-esg1 text-esg1 font-bold' : 'text-esg16 hover:bg-esg1/10 font-medium bg-white'">
                    <div class="flex items-center cursor-pointer">
                        <label for="Social" class="w-full py-4 ml-2 text-base cursor-pointer ">{{ __('Social policies') }}</label>
                    </div>
                </div>
            </div>

            <div class="mt-8 bg-esg1/5 rounded">
                {{-- SECTION Social : Hiring Model --}}
                <div class="p-8" x-show="hiring">
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Chart | BAR : Employees by type of working hours --}}
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('Employees by type of working hours') !!}"
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">

                            <x-charts.bar id="employees_working_hours"
                                class="m-auto relative !h-full !w-full"
                                data-json
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Full-time'), __('Part-time'), __('Other')]) }},
                                        {{ json_encode([20, 20, 20])}},
                                        'employees_working_hours',
                                        ['{{ color(1) }}'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });" />
                        </x-cards.card>

                        {{-- Chart | BAR : Employees by type of labour contract --}}
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('Employees by type of labour contract') !!}"
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">

                            <x-charts.bar id="employees_labour_contract"
                                class="m-auto relative !h-full !w-full"
                                data-json
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Fixed-term'), __('No term'), __('Other')]) }},
                                        {{ json_encode([20, 20, 20]) }},
                                        'employees_labour_contract',
                                        ['{{ color(1) }}'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });" />
                        </x-cards.card>

                        {{-- Chart | BAR : New hires by type of employment contract --}}
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('New hires by type of employment contract') !!}"
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">

                            <x-charts.bar id="employees_local_labour_contract"
                                class="m-auto relative !h-full !w-full"
                                data-json
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Fixed-term'), __('No term'), __('Other')]) }},
                                        {{ json_encode([20, 20, 20]) }},
                                        'employees_local_labour_contract',
                                        ['{{ color(1) }}'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });"/>
                        </x-cards.card>

                        <div class="grid grid-cols-1 gap-6">
                            {{-- Icon: Net job creation --}}
                            <x-cards.card-icon-number text="{!! __('Net job creation') !!}" class="items-center">
                                <x-slot:data>
                                    <div class="text-4xl text-esg8">00.000</div>
                                </x-slot:data>
                                <x-slot:icon>
                                    @include('icons.dashboard.id-card', ['fill' => color(1)])
                                </x-slot:icon>
                            </x-cards.card-icon-number>

                            {{-- Icon: Average turnover rate --}}
                            <x-cards.card-icon-number text="{!! __('Average turnover rate') !!}" class="items-center">
                                <x-slot:data>
                                    <div class="text-4xl text-esg8">00.000</div>
                                </x-slot:data>
                                <x-slot:icon>
                                    @include('icons.dashboard.community-program', ['fill' => color(1)])
                                </x-slot:icon>
                            </x-cards.card-icon-number>

                            {{-- Icon: Average rate of absenteeism --}}
                            <x-cards.card-icon-number text="{!! __('Average rate of absenteeism') !!}" class="items-center">
                                <x-slot:data>
                                    <div class="text-4xl text-esg8">00.000</div>
                                </x-slot:data>
                                <x-slot:icon>
                                    @include('icons.dashboard.work', ['fill' => color(1)])
                                </x-slot:icon>
                            </x-cards.card-icon-number>
                        </div>

                        {{-- List: Employee benefits --}}
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('Employee benefits') !!}"
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">

                            <x-list.bullet bgcolor="bg-esg1" title="{!! __('Replacement of taps, shower systems, flow meters, economizers, toilet cisterns and other efficient products;') !!}" />
                            <x-list.bullet bgcolor="bg-esg1" class="mt-2" title="{!! __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer in dignissim lore;') !!}" />
                            <x-list.bullet bgcolor="bg-esg1" class="mt-2" title="{!! __('Nunc finibus tincidunt mollis. In pretium justo vitae magna vestibulum tristique;') !!}" />
                            <x-list.bullet bgcolor="bg-esg1" class="mt-2" title="{!! __('Vivamus rutrum ante eget auctor ullamcorper. Sed ac tempus tortor;') !!}" />
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>

                {{-- SECTION Social : Equal Pay --}}
                <div class="p-8" x-show="pay">
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Chart | BAR : Average basic salary ratio, by funcional category --}}
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('Average basic salary ratio, by funcional category') !!}"
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">

                            <x-charts.bar id="basic_salary_ratio"
                                class="m-auto relative !h-full !w-full"
                                unit="€"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Administration'), __('Management'), __('Technicians'), __('Administrative')]) }},
                                        {{ json_encode([80, 60, 40, 20]) }},
                                        'basic_salary_ratio',
                                        ['{{ color(1) }}'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });"/>
                        </x-cards.card>

                        {{-- Chart | BAR : Average basic salary ratio, by gender --}}
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('Average basic salary ratio, by gender') !!}"
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">

                            <x-charts.bar id="basic_salary_ratio_gender"
                                class="m-auto relative !h-full !w-full"
                                unit="€"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Man'), __('Woman'), __('Other')]) }},
                                        {{ json_encode([80, 60, 40]) }},
                                        'basic_salary_ratio_gender',
                                        ['{{ color(1) }}'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });"/>
                        </x-cards.card>

                        {{-- Chart | BAR : Gross pay ratio, by functional category --}}
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('Gross pay ratio, by functional category') !!}"
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">

                            <x-charts.bar id="gross_pay_ratio"
                                class="m-auto relative !h-full !w-full"
                                unit="€"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Administration'), __('Management'), __('Technicians'), __('Administrative')]) }},
                                        {{ json_encode([80, 60, 40, 20]) }},
                                        'gross_pay_ratio',
                                        ['{{ color(1) }}'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });"/>
                        </x-cards.card>

                        {{-- Chart | BAR : Gross pay ratio, by gender --}}
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('Gross pay ratio, by gender') !!}"
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">

                            <x-charts.bar id="gross_pay_gender"
                                class="m-auto relative !h-full !w-full"
                                unit="€"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Man'), __('Woman'), __('Other')]) }},
                                        {{ json_encode([80, 60, 40]) }},
                                        'gross_pay_gender',
                                        ['{{ color(1) }}'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });"/>
                        </x-cards.card>

                        {{-- Chart | BAR : Proportion of employees with a basic salary above the national minimum wage, by gender --}}
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('Proportion of employees with a basic salary above the national minimum wage, by gender') !!}"
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">

                            <x-charts.bar id="employee_basic_salary_national"
                                class="m-auto relative !h-full !w-full"
                                unit="%"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Man'), __('Woman'), __('Other')]) }},
                                        {{ json_encode([80, 60, 40]) }},
                                        'employee_basic_salary_national',
                                        ['{{ color(1) }}'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });"/>
                        </x-cards.card>

                        {{-- Chart | BAR : Ratio between the lowest salary by gender, compared to the national minimum wage --}}
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('Ratio between the lowest salary by gender, compared to the national minimum wage') !!}"
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">

                            <x-charts.bar id="employee_basic_salary_national_minimum"
                                class="m-auto relative !h-full !w-full"
                                unit="%"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Man'), __('Woman'), __('Other')]) }},
                                        {{ json_encode([80, 60, 40]) }},
                                        'employee_basic_salary_national_minimum',
                                        ['{{ color(1) }}'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });"/>
                        </x-cards.card>

                        {{-- Chart | BAR : Ratio between the entry wage and the national minimum wage, by gender --}}
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('Ratio between the entry wage and the national minimum wage, by gender') !!}"
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">

                            <x-charts.bar id="employee_basic_ration_between_salary_national_minimum"
                                class="m-auto relative !h-full !w-full"
                                unit="%"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Man'), __('Woman'), __('Other')]) }},
                                        {{ json_encode([80, 60, 40]) }},
                                        'employee_basic_ration_between_salary_national_minimum',
                                        ['{{ color(1) }}'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });"/>
                        </x-cards.card>

                    </div>
                </div>

                {{-- SECTION Social : Diversity in the workforce --}}
                <div class="p-8" x-show="workforce">
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Chart | BAR : number of employees by age --}}
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('number of employees by age') !!}"
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">

                            <x-charts.bar id="number_employees_age"
                                class="m-auto relative !h-full !w-full"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('< 30'), __('30 - 50'), __('> 50')]) }},
                                        {{ json_encode([80, 60, 40]) }},
                                        'number_employees_age',
                                        ['{{ color(1) }}'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });"/>
                        </x-cards.card>

                        {{-- Chart | BAR : number of employees by functional category --}}
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('number of employees by functional category') !!}"
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">

                            <x-charts.bar id="number_employees_category"
                                class="m-auto relative !h-full !w-full"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Administration'), __('Management'), __('Technicians'), __('Technicians')]) }},
                                        {{ json_encode([80, 60, 40, 20]) }},
                                        'number_employees_category',
                                        ['{{ color(1) }}'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });"/>
                        </x-cards.card>

                        {{-- Chart | BAR : number of employees by gender --}}
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('number of employees by gender') !!}"
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">

                            <x-charts.bar id="number_employees_gender"
                                class="m-auto relative !h-full !w-full"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Man'), __('Woman'), __('Other')]) }},
                                        {{ json_encode([80, 60, 40, 20]) }},
                                        'number_employees_gender',
                                        ['{{ color(1) }}'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });"/>
                        </x-cards.card>

                        {{-- Icon: Proportion of employees of foreign nationality --}}
                        <x-cards.card-icon-number text="{!! __('Proportion of employees of foreign nationality') !!}" class="items-center !h-min">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base"> %</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.planet', ['fill' => color(1)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>
                    </div>
                </div>

                {{-- SECTION Social : Employee qualifications --}}
                <div class="p-8" x-show="qualifications">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                            {{-- Icon: Training hours undertaken --}}
                            <x-cards.card-icon-number text="{!! __('Training hours undertaken') !!}" class="items-center">
                                <x-slot:data>
                                    <div class="text-4xl text-esg8">00.000</div>
                                </x-slot:data>
                                <x-slot:icon>
                                    @include('icons.dashboard.qualification', ['color' => color(1)])
                                </x-slot:icon>
                            </x-cards.card-icon-number>

                            {{-- Icon: Investment in training, per employee --}}
                            <x-cards.card-icon-number text="{!! __('Investment in training, per employee') !!}" class="items-center">
                                <x-slot:data>
                                    <div class="text-4xl text-esg8">00.000</div>
                                </x-slot:data>
                                <x-slot:icon>
                                    @include('icons.dashboard.qualification', ['color' => color(1)])
                                </x-slot:icon>
                            </x-cards.card-icon-number>

                            {{-- Icon: Partnerships with universities, vocational schools, study centres or others --}}
                            <x-cards.card-icon-number text="{!! __('Partnerships with universities, vocational schools, study centres or others') !!}" class="items-center">
                                <x-slot:data>
                                    <div class="text-4xl text-esg8">00.000</div>
                                </x-slot:data>
                                <x-slot:icon>
                                    @include('icons.dashboard.course', ['fill' => color(1)])
                                </x-slot:icon>
                            </x-cards.card-icon-number>
                        </div>

                        {{-- Chart | BAR : Average number of training hours per employee, by gender --}}
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('Average number of training hours per employee, by gender') !!}"
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">

                            <x-charts.bar id="avg_training_hours"
                                class="m-auto relative !h-full !w-full"
                                unit="{!! __('hours') !!}"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Man'), __('Woman'), __('Other')]) }},
                                        {{ json_encode([20, 20, 20])}},
                                        'avg_training_hours',
                                        ['#006CB7'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });" />
                        </x-cards.card>

                        {{-- Chart | BAR : Number of employees who have received training, by functional category --}}
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('Number of employees who have received training, by functional category') !!}"
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">

                            <x-charts.bar id="number_training_received"
                                class="m-auto relative !h-full !w-full"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Administration'), __('Management'), __('Technicians'), __('Administrative')]) }},
                                        {{ json_encode([80, 60, 40, 20])}},
                                        'number_training_received',
                                        ['{{ color(1) }}'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });" />
                        </x-cards.card>

                        {{-- Chart | BAR : Percentage of employees who receive regular performance and career development appraisals, by job category --}}
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('Percentage of employees who receive regular performance and career development appraisals, by job category') !!}"
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">

                            <x-charts.bar id="performance_and_career_development"
                                class="m-auto relative !h-full !w-full"
                                unit="%"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Administration'), __('Administration'), __('Technicians'), __('Administrative')]) }},
                                        {{ json_encode([80, 60, 40, 20]) }},
                                        'performance_and_career_development',
                                        ['{{ color(1) }}'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });"/>
                        </x-cards.card>

                        {{-- Chart | BAR : Percentage of employees who receive regular performance and career development appraisals, by gender --}}
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('Percentage of employees who receive regular performance and career development appraisals, by gender') !!}"
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">

                            <x-charts.bar id="performance_and_career_development_gender"
                                class="m-auto relative !h-full !w-full"
                                unit="%"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Man'), __('Woman'), __('Other')]) }},
                                        {{ json_encode([80, 60, 40]) }},
                                        'performance_and_career_development_gender',
                                        ['{{ color(1) }}'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });"/>
                        </x-cards.card>
                    </div>
                </div>

                {{-- SECTION Social : Occupational health and safety --}}
                <div class="p-8" x-show="health">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Icon: number of hours of occupational health and safety training --}}
                        <x-cards.card-icon-number text="{!! __('number of hours of occupational health and safety training') !!}" class="items-center">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000<span class="text-base">{!! __('hours') !!}</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.qualification', ['color' => color(1)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        {{-- Icon: percentage of positions with a risk assessment carried out --}}
                        <x-cards.card-icon-number text="{!! __('percentage of positions with a risk assessment carried out') !!}" class="items-center">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000<span class="text-base"> %</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.caution', ['fill' => color(1)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        {{-- Icon: Work-related accidents --}}
                        <x-cards.card-icon-number text="{!! __('Work-related accidents') !!}" class="items-center">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000<span class="text-base">{!! __('accidents') !!}</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.employee', ['fill' => color(1)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        {{-- Icon: Number of working days lost --}}
                        <x-cards.card-icon-number text="{!! __('Number of working days lost') !!}" class="items-center">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000<span class="text-base">{!! __('accidents') !!}</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.employee', ['fill' => color(1)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        {{-- Icon: Number of days off work --}}
                        <x-cards.card-icon-number text="{!! __('Number of days off work') !!}" class="items-center">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000<span class="text-base">{!! __('days') !!}</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.money-bills', ['fill' => color(1)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>
                    </div>
                </div>

                {{-- SECTION Social : Reconciling professional, personal and family life --}}
                <div class="p-8" x-show="family">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- List: Existing types of working schedules --}}
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('Existing types of working schedules') !!}"
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">

                            <x-list.bullet bgcolor="bg-esg1" title="{!! __('Adaptability') !!}" />
                            <x-list.bullet bgcolor="bg-esg1" class="mt-2" title="{!! __('Annual leave') !!}" />
                            <x-list.bullet bgcolor="bg-esg1" class="mt-2" title="{!! __('Concentrated schedule') !!}" />
                            <x-list.bullet bgcolor="bg-esg1" class="mt-2" title="{!! __('Exemption schedule') !!}" />
                            <x-list.bullet bgcolor="bg-esg1" class="mt-2" title="{!! __('Shift work') !!}" />
                            <x-list.bullet bgcolor="bg-esg1" class="mt-2" title="{!! __('Night shift') !!}" />
                            <x-list.bullet bgcolor="bg-esg1" class="mt-2" title="{!! __('Extra work') !!}" />
                        </x-cards.card-dashboard-version1-withshadow>

                        {{-- List: Promoting conciliation measures --}}
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('Promoting conciliation measures') !!}"
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">

                            <x-list.bullet bgcolor="bg-esg1" title="{!! __('Flexibility/adaptability of schedule') !!}" />
                            <x-list.bullet bgcolor="bg-esg1" class="mt-2" title="{!! __('Rotating shifts') !!}" />
                            <x-list.bullet bgcolor="bg-esg1" class="mt-2" title="{!! __('Teleworking') !!}" />
                            <x-list.bullet bgcolor="bg-esg1" class="mt-2" title="{!! __('Extra days off') !!}" />
                            <x-list.bullet bgcolor="bg-esg1" class="mt-2" title="{!! __('Sharing tuition fees for employees’ children') !!}" />
                            <x-list.bullet bgcolor="bg-esg1" class="mt-2" title="{!! __('Holiday camps') !!}" />
                            <x-list.bullet bgcolor="bg-esg1" class="mt-2" title="{!! __('Sports and cultural activities') !!}" />
                            <x-list.bullet bgcolor="bg-esg1" class="mt-2" title="{!! __('Other') !!}" />
                        </x-cards.card-dashboard-version1-withshadow>

                        {{-- Chart | BAR : Number of employees who started parental leave --}}
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('Number of employees who started parental leave') !!}"
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">

                            <x-charts.bar id="employye_parental_leave"
                                class="m-auto relative !h-full !w-full"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Man'), __('Woman'), __('Other')]) }},
                                        {{ json_encode([53, 80, 63])}},
                                        'employye_parental_leave',
                                        ['#006CB7'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });" />
                        </x-cards.card>

                        {{-- Chart | BAR : Number of employees returning to work after parental leave --}}
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('Number of employees returning to work after parental leave') !!}"
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">

                            <x-charts.bar id="employye_returning_parental_leave"
                                class="m-auto relative !h-full !w-full"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Man'), __('Woman'), __('Other')]) }},
                                        {{ json_encode([53, 80, 63])}},
                                        'employye_returning_parental_leave',
                                        ['#006CB7'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });" />
                        </x-cards.card>

                        {{-- Chart | BAR : Number of employees returning to work after parental leave and remaining with the company after 12 months --}}
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('Number of employees returning to work after parental leave and remaining with the company after 12 months') !!}"
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">

                            <x-charts.bar id="employye_returning_parental_leave_12month"
                                class="m-auto relative !h-full !w-full"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Man'), __('Woman'), __('Other')]) }},
                                        {{ json_encode([53, 80, 63])}},
                                        'employye_returning_parental_leave_12month',
                                        ['#006CB7'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });" />
                        </x-cards.card>

                        <div class="grid grid-cols-1 gap-6">
                            {{-- Icon: Return to work and retention rates after parental leave --}}
                            <x-cards.card-icon-number text="{!! __('Return to work and retention rates after parental leave') !!}" class="items-center">
                                <x-slot:data>
                                    <div class="text-4xl text-esg8">00.000<span class="text-base">%</span></div>
                                </x-slot:data>
                                <x-slot:icon>
                                    @include('icons.dashboard.freelance', ['fill' => color(1)])
                                </x-slot:icon>
                            </x-cards.card-icon-number>

                            {{-- Icon: Return to work rate (after leave) --}}
                            <x-cards.card-icon-number text="{!! __('Return to work rate (after leave)') !!}" class="items-center">
                                <x-slot:data>
                                    <div class="text-4xl text-esg8">00.000<span class="text-base">%</span></div>
                                </x-slot:data>
                                <x-slot:icon>
                                    @include('icons.dashboard.employment', ['fill' => color(1)])
                                </x-slot:icon>
                            </x-cards.card-icon-number>

                            {{-- Icon: Retention rate (12 months after returning to work from leave) --}}
                            <x-cards.card-icon-number text="{!! __('Retention rate (12 months after returning to work from leave)') !!}" class="items-center">
                                <x-slot:data>
                                    <div class="text-4xl text-esg8">00.000<span class="text-base">%</span></div>
                                </x-slot:data>
                                <x-slot:icon>
                                    @include('icons.dashboard.absenteeism', ['fill' => color(1)])
                                </x-slot:icon>
                            </x-cards.card-icon-number>
                        </div>
                    </div>
                </div>

                {{-- SECTION Social : Local communities --}}
                <div class="p-8" x-show="communities">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Checkbox: Local_development --}}
                        <x-cards.card-dashboard-version1-withshadow
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">
                            @foreach($charts['local_development'] as $row)
                                <x-list.checkbox label="{{ $row['label'] }}" status="{{ $row['status'] }}" color="1" />
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>

                        {{-- Icon: Proportion of local purchases --}}
                        <x-cards.card-icon-number text="{!! __('Proportion of local purchases') !!}" class="items-center">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000<span class="text-base">%</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.cart', ['fill' => color(1)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        {{-- Icon: Amount spent on purchases of local products --}}
                        <x-cards.card-icon-number text="{!! __('Amount spent on purchases of local products') !!}" class="items-center">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000<span class="text-base">€</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include( tenant()->views . 'icons.supplier', ['color' => color(1), 'width' => 48])
                            </x-slot:icon>
                        </x-cards.card-icon-number>
                    </div>
                </div>

                {{-- SECTION Social : Social policies --}}
                <div class="p-8" x-show="policies">
                    <div class="grid grid-cols-1 gap-6">
                        {{-- Checkbox: policies --}}
                        <x-cards.card-dashboard-version1-withshadow
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">
                            @foreach($charts['social_policies'] as $row)
                                <x-list.checkbox label="{{ $row['label'] }}" status="{{ $row['status'] }}" color="1" />
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION: governance --}}
        <div class="" x-show="governance" x-data="{ compliance: true, ethics:false, transparency:false, management: false, chain: false, risk: false, policies: false }">
            <div class="my-8 grid grid-cols-1 md:grid-cols-4 gap-5">
                <div class="grid place-content-center border  rounded-md shadow cursor-pointer transition duration-300"
                    x-on:click="compliance=true, ethics=false, transparency=false, management=false, chain=false, risk=false, policies=false"
                    :class="compliance ? 'bg-esg3/10 border-esg3 text-esg3 font-bold' : 'text-esg16 hover:bg-esg3/10 font-medium bg-white'">
                    <div class="flex items-center cursor-pointer">
                        <label for="Governance" class="w-full py-4 ml-2 text-base cursor-pointer">{{ __('Legal compliance') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border  rounded-md shadow cursor-pointer transition duration-300"
                    x-on:click="compliance=false, ethics=true, transparency=false, management=false, chain=false, risk=false, policies=false"
                    :class="ethics ? 'bg-esg3/10 border-esg3 text-esg3 font-bold' : 'text-esg16 hover:bg-esg3/10 font-medium bg-white'">
                    <div class="flex items-center cursor-pointer">
                        <label for="Governance" class="w-full py-4 ml-2 text-base cursor-pointer">{{ __('Ethics') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border  rounded-md shadow cursor-pointer transition duration-300"
                    x-on:click="compliance=false, ethics=false, transparency=true, management=false, chain=false, risk=false, policies=false"
                    :class="transparency ? 'bg-esg3/10 border-esg3 text-esg3 font-bold' : 'text-esg16 hover:bg-esg3/10 font-medium bg-white'">
                    <div class="flex items-center cursor-pointer">
                        <label for="Governance" class="w-full py-4 ml-2 text-base cursor-pointer">{{ __('Transparency') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border  rounded-md shadow cursor-pointer transition duration-300"
                    x-on:click="compliance=false, ethics=false, transparency=false, management=true, chain=false, risk=false, policies=false"
                    :class="management ? 'bg-esg3/10 border-esg3 text-esg3 font-bold' : 'text-esg16 hover:bg-esg3/10 font-medium bg-white'">
                    <div class="flex items-center cursor-pointer">
                        <label for="Governance" class="w-full py-4 ml-2 text-base cursor-pointer">{{ __('Diversity on the board') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border  rounded-md shadow cursor-pointer transition duration-300"
                    x-on:click="compliance=false, ethics=false, transparency=false, management=false, chain=true, risk=false, policies=false"
                    :class="chain ? 'bg-esg3/10 border-esg3 text-esg3 font-bold' : 'text-esg16 hover:bg-esg3/10 font-medium bg-white'">
                    <div class="flex items-center cursor-pointer">
                        <label for="Governance" class="w-full py-4 ml-2 text-base cursor-pointer">{{ __('Due diligence in the supply chain') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border  rounded-md shadow cursor-pointer transition duration-300"
                    x-on:click="compliance=false, ethics=false, transparency=false, management=false, chain=false, risk=true, policies=false"
                    :class="risk ? 'bg-esg3/10 border-esg3 text-esg3 font-bold' : 'text-esg16 hover:bg-esg3/10 font-medium bg-white'">
                    <div class="flex items-center cursor-pointer">
                        <label for="Governance" class="w-full py-4 ml-2 text-base cursor-pointer">{{ __('Risk management') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border  rounded-md shadow cursor-pointer transition duration-300"
                    x-on:click="compliance=false, ethics=false, transparency=false, management=false, chain=false, risk=false, policies=true"
                    :class="policies ? 'bg-esg3/10 border-esg3 text-esg3 font-bold' : 'text-esg16 hover:bg-esg3/10 font-medium bg-white'">
                    <div class="flex items-center cursor-pointer">
                        <label for="Governance" class="w-full py-4 ml-2 text-base cursor-pointer">{{ __('Governance policies') }}</label>
                    </div>
                </div>
            </div>

            <div class="mt-8 bg-esg3/5 rounded">
                {{-- SECTION governance : Legal compliance  --}}
                <div class="p-8" x-show="compliance">
                    <div class="grid grid-cols-1 gap-6">
                        {{-- Checkbox: legal_compliance --}}
                        <x-cards.card-dashboard-version1-withshadow
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">
                            @foreach($charts['legal_compliance'] as $row)
                                <x-list.checkbox label="{{ $row['label'] }}" status="{{ $row['status'] }}" color="3" />
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>

                {{-- SECTION governance : Ethics --}}
                <div class="p-8" x-show="ethics">
                    <div class="grid grid-cols-1 gap-6">
                        {{-- Checkbox: Ethics --}}
                        <x-cards.card-dashboard-version1-withshadow
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">
                            @foreach($charts['ethics'] as $row)
                                <x-list.checkbox label="{{ $row['label'] }}" status="{{ $row['status'] }}" color="3" />
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>

                {{-- SECTION governance : Transparency --}}
                <div class="p-8" x-show="transparency">
                    {{-- List: Means by which sustainability performance is communicated --}}
                    <x-cards.card-dashboard-version1-withshadow text="{!! __('Means by which sustainability performance is communicated') !!}"
                        titleclass="!text-sm"
                        class="!h-auto"
                        contentplacement="none">

                        <x-list.bullet bgcolor="bg-esg3" title="{!! __('Report disseminated internally') !!}" />
                        <x-list.bullet bgcolor="bg-esg3" class="mt-2" title="{!! __('Report disseminated internally') !!}" />
                        <x-list.bullet bgcolor="bg-esg3" class="mt-2" title="{!! __('E-mail disseminated internally') !!}" />
                        <x-list.bullet bgcolor="bg-esg3" class="mt-2" title="{!! __('E-mail disseminated externally') !!}" />
                        <x-list.bullet bgcolor="bg-esg3" class="mt-2" title="{!! __('Internal publication/message') !!}" />
                        <x-list.bullet bgcolor="bg-esg3" class="mt-2" title="{!! __('External publication/message') !!}" />
                    </x-cards.card-dashboard-version1-withshadow>
                </div>

                {{-- SECTION governance : Diversity in the management body --}}
                <div class="p-8" x-show="management">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Chart | BAR : Composition of the governance body, by gender --}}
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('Composition of the governance body, by gender') !!}"
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">

                            <x-charts.bar id="composition_the_governance_body"
                                class="m-auto relative !h-full !w-full"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Man'), __('Woman'), __('Other')]) }},
                                        {{ json_encode([53, 80, 63])}},
                                        'composition_the_governance_body',
                                        ['#E19B00'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });" />
                        </x-cards.card>

                        {{-- Chart | BAR : Composition of the governance body, by age group --}}
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('Composition of the governance body, by age group') !!}"
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">

                            <x-charts.bar id="composition_the_governance_body_age"
                                class="m-auto relative !h-full !w-full"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('<30'), __('30-50'), __('>50')]) }},
                                        {{ json_encode([53, 80, 63])}},
                                        'composition_the_governance_body_age',
                                        ['#E19B00'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });" />
                        </x-cards.card>

                        {{-- Chart | BAR : Number of non-executive members of the governance body, by gender --}}
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('Number of non-executive members of the governance body, by gender') !!}"
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">

                            <x-charts.bar id="non-executive_the_governance_body_age"
                                class="m-auto relative !h-full !w-full"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Man'), __('Woman'), __('Other')]) }},
                                        {{ json_encode([53, 80, 63])}},
                                        'non-executive_the_governance_body_age',
                                        ['#E19B00'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });" />
                        </x-cards.card>
                    </div>
                </div>

                {{-- SECTION governance : Due diligence in the supply chain --}}
                <div class="p-8" x-show="chain">
                    {{-- List: Risks arising from the supply chain --}}
                    <x-cards.card-dashboard-version1-withshadow text="{!! __('Risks arising from the supply chain') !!}"
                        titleclass="!text-sm"
                        class="!h-auto"
                        contentplacement="none">

                        <x-list.bullet bgcolor="bg-esg3" title="{!! __('FUse of child labor and other situations of human rights abuse') !!}" />
                        <x-list.bullet bgcolor="bg-esg3" class="mt-2" title="{!! __('Unsafe working conditions') !!}" />
                        <x-list.bullet bgcolor="bg-esg3" class="mt-2" title="{!! __('Disregard for labor legislation') !!}" />
                        <x-list.bullet bgcolor="bg-esg3" class="mt-2" title="{!! __('Non-compliance with environmental legislation') !!}" />
                        <x-list.bullet bgcolor="bg-esg3" class="mt-2" title="{!! __('Use of dangerous substances') !!}" />
                        <x-list.bullet bgcolor="bg-esg3" class="mt-2" title="{!! __('Bribery and corruption situations') !!}" />
                        <x-list.bullet bgcolor="bg-esg3" class="mt-2" title="{!! __('Failure to comply with competition laws') !!}" />
                        <x-list.bullet bgcolor="bg-esg3" class="mt-2" title="{!! __('Other') !!}" />
                    </x-cards.card-dashboard-version1-withshadow>
                </div>

                {{-- SECTION governance : Risk management --}}
                <div class="p-8" x-show="risk">
                    <div class="grid grid-cols-1 gap-6">
                        {{-- Chart | BAR : Average probability of occurrence of risk categories --}}
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('Average probability of occurrence of risk categories') !!}"
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">

                            <x-charts.bar id="average_probability"
                                class="m-auto relative !h-full !w-full"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Ethical'), __('Economic sustainability'), __('Business continuity'), __('Financing'), __('Legal'), __('Physical risks'), __('Political environment'), __('Environmental impact'), __('Human risks'), __('Health impact'), __('Market risk'), __('Supply chain security')]) }},
                                        {{ json_encode([53, 80, 63,53, 80, 63,53, 80, 63,53, 80, 63])}},
                                        'average_probability',
                                        ['#E19B00'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        },
                                        'singlebar',
                                        'y'
                                    );
                                });" />
                        </x-cards.card-dashboard-version1-withshadow>

                        {{-- Chart | BAR : Average impact severity of risk categories --}}
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('Average impact severity of risk categories') !!}"
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">

                            <x-charts.bar id="average_impact_severity_risk_categories"
                                class="m-auto relative !h-full !w-full"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Ethical'), __('Economic sustainability'), __('Business continuity'), __('Financing'), __('Legal'), __('Physical risks'), __('Political environment'), __('Environmental impact'), __('Human risks'), __('Health impact'), __('Market risk'), __('Supply chain security')]) }},
                                        {{ json_encode([53, 80, 63,53, 80, 63,53, 80, 63,53, 80, 63])}},
                                        'average_impact_severity_risk_categories',
                                        ['#E19B00'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        },
                                        'singlebar',
                                        'y'
                                    );
                                });" />
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>

                {{-- SECTION governance : Governance policies --}}
                <div class="p-8" x-show="policies">
                    <div class="grid grid-cols-1 gap-6">
                        {{-- Checkbox: Governance policies --}}
                        <x-cards.card-dashboard-version1-withshadow
                            titleclass="!text-sm"
                            class="!h-auto"
                            contentplacement="none">
                            @foreach($charts['governance_policies'] as $row)
                                <x-list.checkbox label="{{ $row['label'] }}" status="{{ $row['status'] }}" color="3" />
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
