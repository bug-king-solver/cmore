@extends(customInclude('layouts.tenant'), ['mainBgColor' => 'bg-esg4'])

@section('content')
    <div class="px-4 md:px-0">
        <div
            class="enter-transition bg-esg27 h-screen min-h-[1800px] md:min-h-0 md:max-h-[750px] flex left-[50%] -ml-[50vw] w-screen relative items-center justify-center mb-40 md:mb-0">
            <div class="w-full mx-auto max-w-7xl pt-8 px-4 sm:px-6 lg:px-8 absolute z-90">
                <div class="grid grid-cols-1 md:grid-cols-2 font-encodesans text-esg8 gap-14">
                    <div class="mt-24">
                        <p class="uppercase font-medium text-lg text-esg5">{{ __('Software') }}</p>
                        @if (tenant()->get_product_type == \App\Models\Enums\ProductType::PRO->value)
                            <p class="text-4xl font-bold text-esg8 my-4">{{ __('Welcome to ESG PRO') }}</p>
                        @elseif(tenant()->get_product_type == \App\Models\Enums\ProductType::MATURITY->value)
                            <p class="text-4xl font-bold text-esg8 my-4">{{ __('Welcome to ESG MATURITY') }}</p>
                        @endif

                        <p class="text-base font-normal text-justify text-esg8">
                            {{ __('Here you will find everything you need to be successful on this journey to help companies turn sustainability into business.') }}
                        </p>

                        <p class="text-base font-bold text-justify text-esg8 mt-8">
                            {{ __('Let\'s work together for a sustainable future!') }}</p>

                        @php
                            switch (true) {
                                case request()->routeIs('tenant.questionnaires.panel'):
                                    $btnText = __('Go to questionnaires');
                                    break;
                                case request()->routeIs('tenant.companies.index'):
                                    $btnText = __('Create a company');
                                    break;
                                default:
                                    $btnText = __('Create a questionnaire');
                                    break;
                            }
                        @endphp
                        <a href="{{ $questionnaireUrl }}">
                            <button
                                class="bg-esg5 w-60 h-11 rounded-lg font-bold text-base mt-11 uppercase text-esg27 hover:bg-esg67">
                                {{ $btnText }}
                            </button>
                        </a>
                    </div>

                    <div class="w-full">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="mt-10">
                                <img src="/images/home/img1.png" class="w-full md:w-auto" />
                            </div>

                            <div class="">
                                <img src="/images/home/img2.png" class="w-full md:w-auto" />
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row gap-8 mt-10">
                            <div class="mt-5">
                                <img src="/images/home/img3.png" class="w-full md:w-auto" />
                            </div>

                            <div class="">
                                <img src="/images/home/img4.png" class="w-full md:w-auto" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (tenant()->get_homepage_content['title'] ||
                tenant()->get_homepage_content['heading'] ||
                tenant()->get_homepage_content['content']
        )
            <div class="enter-transition bg-esg6 h-screen min-h-[800px] md:min-h-0 md:max-h-[400px] flex left-[50%] -ml-[50vw] w-screen relative items-center justify-center">
                <div class="w-full mx-auto max-w-7xl  px-4 sm:px-6 lg:px-8 absolute z-90">
                    <div class="grid items-center text-center font-encodesans text-esg27 gap-14">
                        <div>
                            <p class="text-lg font-medium uppercase mb-3">{!! tenant()->get_homepage_content['title'] !!}</p>
                            <p class="text-4xl font-bold">{!! tenant()->get_homepage_content['heading'] !!}</p>
                            <p class="text-xl font-normal mt-8  text-esg27 leading-8">{!! tenant()->get_homepage_content['content'] !!}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="enter-transition font-encodesans mt-20 mb-20">
            <div class="max-w-5xl mx-auto">
                <p class="text-lg font-medium text-esg5 text-center uppercases"> {{ __('Features') }} </p>

                <p class="text-4xl font-bold text-esg8 text-center mt-4"> {{ __('Collect and analyse data') }} </p>

                <p class="text-base font-normal text-esg8 text-center mt-5">
                    {{ __('Meet and explore a world of features designed to make it easier to collect and analyse data.') }}
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 md:gap-9 mt-10">

                    @can('questionnaires.view')
                        <div x-data="{ open: false }" @mouseleave="open = false" @mouseover="open = true"
                            class="bg-esg4 border border-esg4 duration-500 hover:bg-esg6/[0.07] rounded-2xl p-8 cursor-pointer flex gap-5">
                            <a href="{{ route('tenant.questionnaires.panel') }}" class="flex gap-5">
                                <div class="grid justify-items-center">
                                    @include('icons.home.1', ['color' => color(5)])
                                </div>

                                <div class="font-normal text-esg8 text-sm relative">
                                    <p class="text-esg6 text-2xl font-semibold "> {{ __('Questionnaires') }} </p>
                                    <p class="mt-3 font-normal text-base">
                                        {{ __('An easy to navigate questionnaire based on the company\'s traits.') }} </p>

                                    <div x-show="open" class="absolute right-0">
                                        @include('icons.home.arrow')
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endcan

                    @can('dashboard.view')
                        <div x-data="{ open: false }" @mouseleave="open = false" @mouseover="open = true"
                            class="bg-esg4 border border-esg4 duration-500 hover:bg-esg6/[0.07] rounded-2xl p-8 cursor-pointer flex gap-5">
                            <a href="{{ route('tenant.dashboard') }}" class="flex gap-5">
                                <div class="grid justify-items-center">
                                    @include('icons.home.2', ['color' => color(5)])
                                </div>

                                <div class="font-normal text-esg8 text-sm relative">
                                    <p class="text-esg6 text-2xl font-semibold "> {{ __('Dashboard') }} </p>
                                    <p class="mt-3 font-normal text-base">
                                        {{ __('A visual presentation of the analyzed data collected on the questionnaire.') }}
                                    </p>

                                    <div x-show="open" class="absolute right-0">
                                        @include('icons.home.arrow')
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endcan

                    <div x-data="{ open: false }" @mouseleave="open = false" @mouseover="open = true"
                        class="hidden bg-esg4 border border-esg4 duration-500 hover:bg-esg6/[0.07] rounded-2xl p-8 cursor-pointer flex gap-5">
                        <a href="{{ route('tenant.questionnaires.panel') }}" class="flex gap-5">
                            <div class="grid justify-items-center">
                                @include('icons.home.3', ['color' => color(5)])
                            </div>

                            <div class="font-normal text-esg8 text-sm relative">
                                <p class="text-esg6 text-2xl font-semibold "> {{ __('Document Analysis') }} </p>
                                <p class="mt-3 font-normal text-base">
                                    {{ __('A tool to validate company’s compliance with ESG rules and criteria.') }} </p>

                                <div x-show="open" class="absolute right-0">
                                    @include('icons.home.arrow')
                                </div>
                            </div>
                        </a>
                    </div>

                    <div x-data="{ open: false }" @mouseleave="open = false" @mouseover="open = true"
                        class="hidden bg-esg4 border border-esg4 duration-500 hover:bg-esg6/[0.07] rounded-2xl p-8 cursor-pointer flex gap-5">
                        <a href="{{ route('tenant.questionnaires.panel') }}" class="flex gap-5">
                            <div class="grid justify-items-center">
                                @include('icons.home.4', ['color' => color(5)])
                            </div>

                            <div class="font-normal text-esg8 text-sm relative">
                                <p class="text-esg6 text-2xl font-semibold "> {{ __('Reputational Analysis') }} </p>
                                <p class="mt-3 font-normal text-base">
                                    {{ __('An overview of a company’s perception and reputation.') }} </p>

                                <div x-show="open" class="absolute right-0">
                                    @include('icons.home.arrow')
                                </div>
                            </div>
                        </a>
                    </div>

                    @can('targets.view')
                        <div x-data="{ open: false }" @mouseleave="open = false" @mouseover="open = true"
                            class="bg-esg4 border border-esg4 duration-500 hover:bg-esg6/[0.07] rounded-2xl p-8 cursor-pointer flex gap-5">
                            <a href="{{ route('tenant.targets.index') }}" class="flex gap-5">
                                <div class="grid justify-items-center">
                                    @include('icons.home.5', ['color' => color(5)])
                                </div>

                                <div class="font-normal text-esg8 text-sm relative">
                                    <p class="text-esg6 text-2xl font-semibold "> {{ __('Targets') }} </p>
                                    <p class="mt-3 font-normal text-base">
                                        {{ __('Create goals and track the progress of an indicator.') }} </p>

                                    <div x-show="open" class="absolute right-0">
                                        @include('icons.home.arrow')
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endcan

                    @can('library.view')
                        <div x-data="{ open: false }" @mouseleave="open = false" @mouseover="open = true"
                            class="bg-esg4 border border-esg4 duration-500 hover:bg-esg6/[0.07] rounded-2xl p-8 cursor-pointer flex gap-5">
                            <a href="{{ route('tenant.library.index') }}" class="flex gap-5">
                                <div class="grid justify-items-center">
                                    @include('icons.home.6', ['color' => color(5)])
                                </div>

                                <div class="font-normal text-esg8 text-sm relative">
                                    <p class="text-esg6 text-2xl font-semibold "> {{ __('Library') }} </p>
                                    <p class="mt-3 font-normal text-base">
                                        {{ __('Information about the software, ESG frameworks, and much more!') }} </p>

                                    <div x-show="open" class="absolute right-0">
                                        @include('icons.home.arrow')
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection
