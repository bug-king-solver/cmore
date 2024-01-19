<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ESG Maturity') }}</title>

    <link rel="icon" type="image/png" href="{{ global_asset('images/logos/favicon.png') }}" />

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css2?family=Lato&subset=latin,latin-ext" rel="stylesheet" type="text/css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Encode+Sans:wght@100;200;300;400;500;600;700;800;900&family=Lato:wght@100;300;400;700;900&display=swap"
        rel="stylesheet">

    <!-- Google tag (gtag.js) -->
    @if (config('app.google.analytics.api_key'))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('app.google.analytics.api_key') }}"></script>
        <script nonce="{{ csp_nonce() }}">
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());

            gtag('config', "{{ config('app.google.analytics.api_key') }}");
        </script>
    @endif

    @livewireStyles(['nonce' => csp_nonce()])
    @stack('head')

    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link href="{{ mix('css/tenant/custom.css') }}" rel="stylesheet">

    @include('partials/colors')
</head>

@php
    $tenant = tenant();
    $user = auth()->user();
    $userIsSuperAdmin = $user->isOwner();
@endphp

<body class="bg-esg4 h-screen antialiased overflow-x-hidden" x-data="{ trial_modal: false, {!! $xData ?? '' !!} }" {!! $bodyTag ?? '' !!}>

    @if (str_contains($tenant->views ?? '', 'sibs'))
        <div
            class="loader-overlay w-[250px] h-[250px] fixed left-[45%] top-[40%] z-99999 flex items-center justify-center duration-500 !b-0">
            <img alt="Loader" src="{{ '/images/gif/loader.gif' }}" class="w-full">
        </div>
    @endif

    <div x-cloak x-show="trial_modal">
        <div class="fixed inset-x-0 bottom-0 z-50 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center">
            <div class="fixed inset-0">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div @click.away="trial_modal = false"
                class="bg-esg4 border-esg6 w-[45rem] transform overflow-hidden rounded-lg border-2 shadow-xl">
                <div class="bg-esg4 px-4 pt-5 pb-5 sm:p-6 sm:pb-4">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left">
                        <h3 class="text-esg8 relative text-2xl font-extrabold" id="modal-headline">
                            {{ __('Trial') }}
                            <span @click="trial_modal = false"
                                class="border-esg6 absolute right-0 top-0 cursor-pointer rounded-full border-2 p-1 text-sm font-bold leading-3">X</span>
                        </h3>
                        <div class="mt-3">
                            {{ __('This feature is not available in the trial version.') }}
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex justify-center pb-5">
                    <button @click="trial_modal = false"
                        class="text-esg27 bg-esg6 rounded-md py-2.5 px-6 text-base font-bold uppercase">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>


    <div id="app">
        @if ($tenant->show_topbar)
            <nav aria-label="secondary" class="fixed z-50 w-full left-0 top-0">
                <div class="w-full h-10 bg-esg5 text-esg4 justify-center flex flex-row items-center">
                    {{ __('Want to know more about ESG Maturity and its features?') }}
                    <button x-on:click="Livewire.emit('openModal', 'modals.discover-message')"
                        class="ml-2 bg-esg4 text-esg5 inline py-1.5 px-2 rounded-lg uppercase font-inter text-xs">
                        {{ __('click to discover') }}
                    </button>
                </div>
            </nav>
        @endif

        <nav class="bg-white fixed top-0 z-50 h-16 w-full print:hidden drop-shadow @if ($tenant->show_topbar) left-0 top-10 @endif"
            x-data="{ expanded: false }" @click.away="expanded = false">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between" x-data="{ menu: false }">
                    <div class="space-x-10 md:block hidden">
                        <a href="{{ url('/') }}">
                            @if ($tenant->logo)
                                <img alt="{{ __('Company logo') }}" src="{{ asset($tenant->logo) }}"
                                    class="max-w-[200px] max-h-11" />
                            @else
                                @include('logos/cmore7')
                            @endif
                        </a>
                    </div>
                    <div @click="menu = !menu"
                        class="print:hidden md:hidden block relative flex max-w-xs items-center rounded-full text-sm uppercase focus:outline-none">
                        <div class="py-3 sm:max-w-xl mx-auto">
                            <nav x-data="{ open: false }">
                                <button class="text-gray-500 w-10 h-10 relative focus:outline-none bg-white"
                                    @click="open = !open" @click.away="open = false">
                                    <span class="sr-only">Open main menu</span>
                                    <div
                                        class="block w-5 absolute left-1/2 top-1/2   transform  -translate-x-1/2 -translate-y-1/2">
                                        <span aria-hidden="true"
                                            class="block absolute h-0.5 w-5 bg-current transform transition duration-500 ease-in-out"
                                            :class="{ 'rotate-45': open, ' -translate-y-1.5': !open }"></span>
                                        <span aria-hidden="true"
                                            class="block absolute  h-0.5 w-5 bg-current   transform transition duration-500 ease-in-out"
                                            :class="{ 'opacity-0': open }"></span>
                                        <span aria-hidden="true"
                                            class="block absolute  h-0.5 w-5 bg-current transform  transition duration-500 ease-in-out"
                                            :class="{ '-rotate-45': open, ' translate-y-1.5': !open }"></span>
                                    </div>
                                </button>
                            </nav>
                        </div>
                    </div>

                    <div class="md:hidden relative flex items-center ml-14">
                        <a href="{{ url('/') }}">
                            @if ($tenant->logo)
                                <img alt="{{ __('Company logo') }}" src="{{ asset($tenant->logo) }}"
                                    class="max-w-[200px] max-h-11" />
                            @else
                                @include('logos/cmore7')
                            @endif
                        </a>
                    </div>

                    <div class="print:hidden">
                        <div class="flex items-center md:ml-6">
                            @guest
                                <a href="{{ route('tenant.login') }}"
                                    class="hover:text-esg27 focus:text-esg27 mt-1 block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 focus:bg-gray-700 focus:outline-none">{{ __('Login') }}
                                </a>
                                @if (Route::has('tenant.register'))
                                    <a href="{{ route('tenant.register') }}"
                                        class="hover:text-esg27 focus:text-esg27 mt-1 block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 focus:bg-gray-700 focus:outline-none">{{ __('Register') }}
                                    </a>
                                @endif
                            @else
                                <div class="flex hidden gap-x-10 md:block md:flex">
                                    @if ($tenant->has_catalog_feature && auth()->user()->can('catalog.view'))
                                        <div class="relative z-10 inline cursor-pointer text-sm font-medium uppercase">
                                            <a href="{{ route('tenant.catalog') }}"
                                                class="{{ request()->routeIs('tenant.catalog') ? 'text-esg28' : 'text-esg8' }}"
                                                data-test="catalog-menu">
                                                {{ __('Catalog') }}
                                            </a>
                                        </div>
                                    @endif

                                    <div x-data="{ expanded: false }"
                                        class="relative z-10 inline cursor-pointer text-sm font-medium uppercase ">
                                        <a @click="expanded = !expanded" @click.away="expanded = false"
                                            class=" {{ request()->routeIs('tenant.companies.index') || request()->routeIs('tenant.dashboards') || request()->routeIs('tenant.tags.index') || request()->routeIs('tenant.targets.index') || request()->routeIs('tenant.users.tasks.index') || request()->routeIs('tenant.users.index') || request()->routeIs('tenant.roles.index') ? 'text-esg28' : 'text-esg8' }}"
                                            data-test="manage-menu">
                                            {{ __('Manage') }}
                                            <div x-show="!expanded" class="inline-flex pl-1">@include('icons.arrow-menu')
                                            </div>
                                            <div x-cloak x-show="expanded" class="pl-1 inline-flex">
                                                @include('icons.arrow-up', [
                                                    'class' =>
                                                        request()->routeIs('tenant.companies.index') ||
                                                        request()->routeIs('tenant.dashboards') ||
                                                        request()->routeIs('tenant.tags.index') ||
                                                        request()->routeIs('tenant.targets.index') ||
                                                        request()->routeIs('tenant.users.tasks.index') ||
                                                        request()->routeIs('tenant.users.index') ||
                                                        request()->routeIs('tenant.roles.index')
                                                            ? 'text-esg28'
                                                            : 'text-esg8',
                                                ])</div>
                                            <div x-cloak x-show="expanded"
                                                class="absolute center mt-2  mr-2 w-max rounded-md shadow-lg -left-4">
                                                <div class="bg-esg4 shadow-xs rounded py-1 w-max text-left shadow-bxesg1">

                                                    @can('companies.view')
                                                        <a href="{{ route('tenant.companies.index') }}"
                                                            class="cursor-pointer block px-4 py-1 text-sm font-medium leading-7 {{ request()->routeIs('tenant.companies.index') ? 'text-esg28' : 'text-esg8' }}"
                                                            data-test="companies-menu">{{ __('Companies') }}</a>
                                                    @endcan

                                                    @if ($tenant->hasGarBtarFeature)
                                                        <a href="{{ route('tenant.garbtar.assets') }}"
                                                            class="cursor-pointer block px-4 py-1 text-sm font-medium  leading-7 {{ request()->routeIs('tenant.garbtar.assets') ? 'text-esg28' : 'text-esg8' }}"
                                                            data-test="Assets-menu">{{ __('Assets') }}</a>
                                                    @endif

                                                    @if ($tenant->hasTargetsFeature)
                                                        @can('targets.view')
                                                            <a href="{{ route('tenant.targets.index') }}"
                                                                class="relative cursor-pointer block px-4 py-1 text-sm font-medium leading-7 {{ request()->routeIs('tenant.targets.*') ? 'text-esg28' : 'text-esg8' }}"
                                                                data-test="targets-menu">
                                                                {{ __('Targets') }}
                                                            </a>
                                                        @endcan
                                                    @endif

                                                    @if ($tenant->hasTasksFeature)
                                                        <a href="{{ route('tenant.users.tasks.index') }}"
                                                            class="cursor-pointer block px-4 py-1 text-sm font-medium  leading-7 {{ request()->routeIs('tenant.users.tasks.index') ? 'text-esg28' : 'text-esg8' }}"
                                                            data-test="tasks-menu">
                                                            {{ __('Tasks') }}
                                                        </a>
                                                    @endif

                                                    @if ($tenant->hasTagsFeature)
                                                        @can('tags.view')
                                                            <a href="{{ route('tenant.tags.index') }}"
                                                                class="cursor-pointer block px-4 py-1 text-sm font-medium leading-7 {{ request()->routeIs('tenant.tags.index') ? 'text-esg28' : 'text-esg8' }}"
                                                                data-test="tags-menu">{{ __('Tags') }}</a>
                                                        @endcan
                                                    @endif

                                                    @can('users.view')
                                                        <a href="{{ route('tenant.users.index') }}"
                                                            class="cursor-pointer block px-4 py-1 text-sm font-medium leading-7 {{ request()->routeIs('tenant.users.index') || request()->routeIs('tenant.roles.index') ? 'text-esg28' : 'text-esg8' }}"
                                                            data-test="users-menu">{{ __('Users & Roles') }}</a>
                                                    @endcan

                                                    @if ($tenant->hasApiTokenFeature &&
                                                            $userIsSuperAdmin)
                                                        @can('tags.view')
                                                            <a href="{{ route('tenant.api.tokens.index') }}"
                                                                class="cursor-pointer block px-4 py-1 text-sm font-medium leading-7 {{ request()->routeIs('tenant.api.tokens.index') ? 'text-esg28' : 'text-esg8' }}"
                                                                data-test="tags-menu">{{ __('Api Tokens') }}</a>
                                                        @endcan
                                                    @endif

                                                    @if ($userIsSuperAdmin && $tenant->hasReportingPeriodFeature)
                                                        <a href="{{ route('tenant.reporting-periods.index') }}"
                                                            class="cursor-pointer block px-4 py-1 text-sm font-medium leading-7 {{ request()->routeIs('tenant.reporting-periods.index') ? 'text-esg28' : 'text-esg8' }}"
                                                            data-test="reporting-periods-menu">
                                                            {{ __('Reporting periods') }}
                                                        </a>
                                                    @endif

                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    @if ($tenant->hasBenchmarkingFeature ||
                                            $tenant->hasComplianceFeature ||
                                            $tenant->hasReputationFeature ||
                                            $tenant->hasDynamicDashboardFeature)
                                        <div x-data="{ expanded: false }"
                                            class="relative z-10 inline cursor-pointer text-sm font-medium uppercase">
                                            <a @click="expanded = !expanded" @click.away="expanded = false"
                                                class="{{ request()->routeIs('tenant.benchmarking.index') ? 'text-esg28' : 'text-esg8' }}"
                                                data-test="analyse-menu">
                                                {{ __('Analyse') }}
                                                <div x-show="!expanded" class="inline-flex pl-1">
                                                    @include('icons.arrow-menu')</div>
                                                <div x-cloak x-show="expanded" class="pl-1 inline-flex">
                                                    @include('icons.arrow-up', [
                                                        'class' => request()->routeIs('tenant.benchmarking.index')
                                                            ? 'text-esg28'
                                                            : 'text-esg8',
                                                    ])
                                                </div>
                                                <div x-cloak x-show="expanded"
                                                    class="absolute mt-2 w-max rounded-md text-left -left-4">
                                                    <div
                                                        class="bg-esg4 shadow-xs rounded py-1 w-max text-left shadow-bxesg1">

                                                        @if ($tenant->hasDynamicDashboardFeature)
                                                            @can('dashboard.view')
                                                                <a href="{{ route('tenant.dynamic-dashboard.index') }}"
                                                                    class="cursor-pointer block px-4 py-1 text-sm font-medium  leading-7 {{ request()->routeIs('tenant.dynamic-dashboard.*') ? 'text-esg28' : 'text-esg8' }}"
                                                                    data-test="dashboards-menu">{{ __('Dashboards') }}</a>
                                                            @endcan
                                                        @endif

                                                        @if ($tenant->hasBenchmarkingFeature)
                                                            @can('benchmarking.view')
                                                                <a x-data="{ premium: false }" @mouseover="premium = true"
                                                                    @mouseover.away="premium = false"
                                                                    href="{{ $tenant->see_only_own_data ? route('tenant.home') : route('tenant.benchmarking.index') }}"
                                                                    class="relative cursor-pointer block px-4 py-1 text-sm font-medium leading-7 {{ $tenant->see_only_own_data ? 'text-esg9' : (request()->routeIs('tenant.benchmarking.*') ? 'text-esg28' : 'text-esg8') }}"
                                                                    data-test="benchmark-menu">
                                                                    {{ __('Benchmark') }}
                                                                    @if ($tenant->see_only_own_data)
                                                                        <div x-cloak x-show="premium"
                                                                            class="absolute -top-2 ml-6 text-xs font-bold text-esg5 p-1">
                                                                            PREMIUM
                                                                        </div>
                                                                    @endif
                                                                </a>
                                                            @endcan
                                                        @endif


                                                        @if ($tenant->hasComplianceFeature)
                                                            @can('compliance.view')
                                                                <a href="{{ route('tenant.compliance.document_analysis.index') }}"
                                                                    class="cursor-pointer block px-4 py-1 text-sm font-medium  leading-7 {{ request()->routeIs('tenant.compliance.*') ? 'text-esg28' : 'text-esg8' }}"
                                                                    data-test="compliance-menu">{{ __('Compliance') }}</a>
                                                            @endcan
                                                        @endif

                                                        @if ($tenant->hasReputationFeature)
                                                            @can('reputation.view')
                                                                <a href="{{ route('tenant.reputation.index') }}"
                                                                    class="cursor-pointer block px-4 py-1 text-sm font-medium  leading-7 {{ request()->routeIs('tenant.reputation.*') ? 'text-esg28' : 'text-esg8' }}"
                                                                    data-test="reputation-menu">{{ __('Reputation') }}</a>
                                                            @endcan
                                                        @endif
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endif

                                    @if ($tenant->hasGarBtarFeature)
                                        <div x-data="{ expanded: false }"
                                            class="relative z-10 inline cursor-pointer text-sm font-medium uppercase">
                                            <a @click="expanded = !expanded" @click.away="expanded = false"
                                                class="{{ request()->routeIs('tenant.garbtar.*') ? 'text-esg28' : 'text-esg8' }}"
                                                data-test="analyse-menu">
                                                {{ __('Regulation') }}

                                                <div x-show="!expanded" class="inline-flex pl-1">
                                                    @include('icons.arrow-menu')
                                                </div>

                                                <div x-cloak x-show="expanded" class="pl-1 inline-flex">
                                                    @include('icons.arrow-up', [
                                                        'class' => request()->routeIs('tenant.garbtar.*')
                                                            ? 'text-esg28'
                                                            : 'text-esg8',
                                                    ])
                                                </div>

                                                <div x-cloak x-show="expanded"
                                                    class="absolute mt-2 w-max rounded-md text-left -left-4">
                                                    <div
                                                        class="bg-esg4 shadow-xs rounded py-1 w-max text-left shadow-bxesg1">

                                                        <a href="{{ route('tenant.garbtar.index') }}"
                                                            class="cursor-pointer block px-4 py-1 text-sm font-medium  leading-7 {{ request()->routeIs('tenant.garbtar.index') ? 'text-esg28' : 'text-esg8' }}"
                                                            data-test="panel-menu">{{ __('Ratios') }}</a>

                                                        <a href="{{ route('tenant.garbtar.crr.panel') }}"
                                                            class="cursor-pointer block px-4 py-1 text-sm font-medium  leading-7 {{ request()->routeIs('tenant.garbtar.crr.panel') ? 'text-esg28' : 'text-esg8' }}"
                                                            data-test="panel-menu">{{ __('CRR Indicators') }}</a>

                                                        <a href="{{ route('tenant.garbtar.regulatory') }}"
                                                            class="cursor-pointer block px-4 py-1 text-sm font-medium  leading-7 {{ request()->routeIs('tenant.garbtar.regulatory') ? 'text-esg28' : 'text-esg8' }}"
                                                            data-test="regulatory-menu">{{ __('Regulatory Tables') }}</a>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endif

                                    <div x-data="{ expanded: false }"
                                        class="relative z-10 inline cursor-pointer text-sm font-medium uppercase">
                                        <a @click="expanded = !expanded" @click.away="expanded = false"
                                            class="{{ request()->routeIs('tenant.data.index') || request()->routeIs('tenant.questionnaires.panel') ? 'text-esg28' : 'text-esg8' }}"
                                            data-test="report-menu">
                                            {{ __('Report') }}
                                            <div x-show="!expanded" class="inline-flex pl-1">@include('icons.arrow-menu')
                                            </div>
                                            <div x-cloak x-show="expanded" class="pl-1 inline-flex">
                                                @include('icons.arrow-up', [
                                                    'class' =>
                                                        request()->routeIs('tenant.data.index') ||
                                                        request()->routeIs('tenant.questionnaires.panel')
                                                            ? 'text-esg28'
                                                            : 'text-esg8',
                                                ])
                                            </div>
                                            <div x-cloak x-show="expanded"
                                                class="absolute mt-2 rounded-md text-left -left-4">
                                                <div class="bg-esg4 shadow-xs rounded py-1 w-max shadow-bxesg1">

                                                    @if ($tenant->hasMonitoringFeature)
                                                        @can('data.view')
                                                            <a href="{{ route('tenant.data.panel') }}"
                                                                class="relative cursor-pointer block px-4 py-1 text-sm font-medium leading-7 {{ request()->routeIs('tenant.data.*') ? 'text-esg28' : 'text-esg8' }}"
                                                                data-test="data-menu">
                                                                {{ __('Monitoring') }}
                                                            </a>
                                                        @endcan
                                                    @endif

                                                    @can('questionnaires.view')
                                                        <a href="{{ route('tenant.questionnaires.panel') }}"
                                                            class="cursor-pointer block px-4 py-1 text-sm font-medium leading-7 {{ request()->routeIs('tenant.questionnaires.*') ? 'text-esg28' : 'text-esg8' }}"
                                                            data-test="questionnaires-menu">{{ __('Questionnaires') }}</a>
                                                    @endcan

                                                    @if ($tenant->hasReportingFeature)
                                                        @can('export.view')
                                                            <a href="{{ route('tenant.exports.index') }}"
                                                                class="cursor-pointer block px-4 py-1 text-sm font-medium leading-7 {{ request()->routeIs('tenant.reports.*') ? 'text-esg28' : 'text-esg8' }}"
                                                                data-test="export-menu">{{ __('Reports') }}</a>
                                                        @endcan
                                                    @endif
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div x-data="{ expanded: false }"
                                        class="relative z-10 inline cursor-pointer text-sm font-medium uppercase">
                                        <a @click="expanded = !expanded" @click.away="expanded = false"
                                            class="{{ request()->routeIs('tenant.library.*') ? 'text-esg28' : 'text-esg8' }}"
                                            data-test="learn-menu">
                                            {{ __('Learn') }}
                                            <div x-show="!expanded" class="inline-flex pl-1">@include('icons.arrow-menu')
                                            </div>
                                            <div x-cloak x-show="expanded" class="pl-1 inline-flex">
                                                @include('icons.arrow-up', [
                                                    'class' => request()->routeIs('tenant.library.*')
                                                        ? 'text-esg28'
                                                        : 'text-esg8',
                                                ])
                                            </div>
                                            <div x-cloak x-show="expanded"
                                                class="absolute mt-2 w-max rounded-md shadow-lg text-left -left-4">
                                                <div class="bg-esg4 shadow-xs rounded py-1 w-max shadow-bxesg1">
                                                    @can('library.view')
                                                        <a href="{{ route('tenant.library.index') }}"
                                                            class="cursor-pointer block px-4 py-1 text-sm font-medium leading-7 {{ request()->routeIs('tenant.library.*') ? 'text-esg28' : 'text-esg8' }}"
                                                            data-test="library-menu">{{ __('Library') }}</a>
                                                    @endcan
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                                <div x-data="{ expanded: false }" @click.away="expanded = false"
                                    class="relative z-10 ml-14 lg:hidden flex flex-row justify-between">
                                    <div x-cloak x-show="menu" x-data="{ expanded: false }" @click.away="menu = false"
                                        class="bg-esg4 absolute p-3 right-2 xs:-right-[6.7rem] md:right-[-10.6rem] w-[101vw] md:w-[101vw] shadow-lg flex flex-col mt-8 leading-8">

                                        <div @click="expanded = !expanded"
                                            class="relative z-10 inline cursor-pointer font-medium uppercase min-h-[3rem] px-3 w-full p-1">
                                            <div @click.away="expanded = false"
                                                class="{{ request()->routeIs('tenant.companies.index') || request()->routeIs('tenant.dashboards') || request()->routeIs('tenant.tags.index') || request()->routeIs('tenant.targets.index') || request()->routeIs('tenant.users.tasks.index') || request()->routeIs('tenant.users.index') || request()->routeIs('tenant.roles.index') ? 'text-esg28' : 'text-esg8' }} "
                                                data-test="manage-menu">
                                                {{ __('Manage') }}
                                                <div x-show="!expanded" class="float-right my-3">
                                                    @include('icons.arrow-menu')</div>
                                                <div x-cloak x-show="expanded" class="float-right inline my-3">
                                                    @include('icons.arrow-up', [
                                                        'class' =>
                                                            request()->routeIs('tenant.companies.index') ||
                                                            request()->routeIs('tenant.dashboards') ||
                                                            request()->routeIs('tenant.tags.index') ||
                                                            request()->routeIs('tenant.targets.index') ||
                                                            request()->routeIs('tenant.users.tasks.index') ||
                                                            request()->routeIs('tenant.users.index') ||
                                                            request()->routeIs('tenant.roles.index')
                                                                ? 'text-esg28'
                                                                : 'text-esg8',
                                                    ])
                                                </div>
                                                <div x-cloak x-show="expanded" class="mt-2  mr-2 w-max rounded-md">
                                                    <div class="bg-esg4 shadow-xs rounded-md py-1 -left-7">
                                                        @if ($tenant->hasTasksFeature)
                                                            <a href="{{ route('tenant.users.tasks.index') }}"
                                                                class="cursor-pointer block px-4 py-1 font-medium text-[0.9rem] {{ request()->routeIs('tenant.users.tasks.index') ? 'text-esg28' : 'text-esg8' }}"
                                                                data-test="tasks-menu">
                                                                {{ __('Tasks') }}
                                                            </a>
                                                        @endif

                                                        @if ($tenant->hasTargetsFeature)
                                                            @can('targets.view')
                                                                <a href="{{ route('tenant.targets.index') }}"
                                                                    class="relative cursor-pointer block px-4 py-1 font-medium text-[0.9rem] {{ request()->routeIs('tenant.targets.*') ? 'text-esg28' : 'text-esg8' }}"
                                                                    data-test="targets-menu">
                                                                    {{ __('Targets') }}
                                                                </a>
                                                            @endcan
                                                        @endif

                                                        @can('companies.view')
                                                            <a href="{{ route('tenant.companies.index') }}"
                                                                class="cursor-pointer block px-4 py-1 font-medium text-[0.9rem] {{ request()->routeIs('tenant.companies.index') ? 'text-esg28' : 'text-esg8' }}"
                                                                data-test="companies-menu">{{ __('Companies') }}</a>
                                                        @endcan

                                                        @if ($tenant->hasTagsFeature)
                                                            @can('tags.view')
                                                                <a href="{{ route('tenant.tags.index') }}"
                                                                    class="cursor-pointer block px-4 py-1 font-medium text-[0.9rem] {{ request()->routeIs('tenant.tags.index') ? 'text-esg28' : 'text-esg8' }}"
                                                                    data-test="tags-menu">{{ __('Tags') }}</a>
                                                            @endcan
                                                        @endif

                                                        @can('users.view')
                                                            <a href="{{ route('tenant.users.index') }}"
                                                                class="cursor-pointer block px-4 py-1 font-medium text-[0.9rem] {{ request()->routeIs('tenant.users.index') || request()->routeIs('tenant.roles.index') ? 'text-esg28' : 'text-esg8' }}"
                                                                data-test="users-menu">{{ __('Users & Roles') }}</a>
                                                        @endcan

                                                        @if ($tenant->hasApiTokenFeature &&
                                                                $userIsSuperAdmin)
                                                            @can('tags.view')
                                                                <a href="{{ route('tenant.api.tokens.index') }}"
                                                                    class="cursor-pointer block px-4 py-1 font-medium text-[0.9rem] {{ request()->routeIs('tenant.api.tokens.index') ? 'text-esg28' : 'text-esg8' }}"
                                                                    data-test="tags-menu">{{ __('Api Tokens') }}</a>
                                                            @endcan
                                                        @endif

                                                        @if ($userIsSuperAdmin && $tenant->hasReportingPeriodFeature)
                                                            <a href="{{ route('tenant.reporting-periods.index') }}"
                                                                class="cursor-pointer block px-4 py-1 font-medium text-[0.9rem] {{ request()->routeIs('tenant.reporting-periods.index') ? 'text-esg28' : 'text-esg8' }}"
                                                                data-test="reporting-periods-menu">
                                                                {{ __('Reporting periods') }}
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($tenant->hasBenchmarkingFeature ||
                                                $tenant->hasComplianceFeature ||
                                                $tenant->hasReputationFeature ||
                                                $tenant->hasDynamicDashboardFeature)
                                            <div x-data="{ expanded: false }"
                                                class="relative z-10 inline cursor-pointer font-medium uppercase min-h-[3rem] px-3 w-full p-1">
                                                <div @click="expanded = !expanded" @click.away="expanded = false"
                                                    class="{{ request()->routeIs('tenant.benchmarking.index') ? 'text-esg28' : 'text-esg8' }}"
                                                    data-test="analyse-menu">
                                                    {{ __('Analyse') }}
                                                    <div x-show="!expanded" class="float-right my-3">
                                                        @include('icons.arrow-menu')</div>
                                                    <div x-cloak x-show="expanded" class="float-right inline my-3">
                                                        @include('icons.arrow-up', [
                                                            'class' => request()->routeIs(
                                                                'tenant.benchmarking.index')
                                                                ? 'text-esg28'
                                                                : 'text-esg8',
                                                        ])
                                                    </div>
                                                    <div x-cloak x-show="expanded"
                                                        class="right-0 mt-2 w-48 rounded-md text-left">
                                                        <div class="bg-esg4 shadow-xs rounded-md py-1 w-min left-20">

                                                            @if ($tenant->hasDynamicDashboardFeature)
                                                                @can('dashboard.view')
                                                                    <a href="{{ route('tenant.dynamic-dashboard.index') }}"
                                                                        class="cursor-pointer block px-4 py-1 font-medium text-[0.9rem] {{ request()->routeIs('tenant.dynamic-dashboard.*') ? 'text-esg28' : 'text-esg8' }}"
                                                                        data-test="dashboards-menu">{{ __('Dashboards') }}</a>
                                                                @endcan
                                                            @endif

                                                            @if ($tenant->hasBenchmarkingFeature)
                                                                )
                                                                @can('benchmarking.view')
                                                                    <a x-data="{ premium: false }" @mouseover="premium = true"
                                                                        @mouseover.away="premium = false"
                                                                        href="{{ $tenant->see_only_own_data ? route('tenant.home') : route('tenant.benchmarking.index') }}"
                                                                        class="cursor-pointer block px-4 py-1 font-medium text-[0.9rem] {{ $tenant->see_only_own_data ? 'text-esg9' : (request()->routeIs('tenant.benchmarking.*') ? 'text-esg28' : 'text-esg8') }}"
                                                                        data-test="benchmark-menu">
                                                                        {{ __('Benchmark') }}
                                                                        @if ($tenant->see_only_own_data)
                                                                            <div x-cloak x-show="premium"
                                                                                class="absolute top-12 ml-8 text-xs font-bold text-esg5 p-1">
                                                                                PREMIUM
                                                                            </div>
                                                                        @endif
                                                                    </a>
                                                                @endcan
                                                            @endif


                                                            @if ($tenant->hasComplianceFeature)
                                                                @can('compliance.view')
                                                                    <a href="{{ route('tenant.compliance.document_analysis.index') }}"
                                                                        class="cursor-pointer block px-4 py-1 font-medium text-[0.9rem] {{ request()->routeIs('tenant.compliance.*') ? 'text-esg28' : 'text-esg8' }}"
                                                                        data-test="compliance-menu">{{ __('Compliance') }}</a>
                                                                @endcan
                                                            @endif

                                                            @if ($tenant->hasReputationFeature)
                                                                @can('reputation.view')
                                                                    <a href="{{ route('tenant.reputation.index') }}"
                                                                        class="cursor-pointer block px-4 py-1 font-medium text-[0.9rem] {{ request()->routeIs('tenant.reputation.*') ? 'text-esg28' : 'text-esg8' }}"
                                                                        data-test="reputation-menu">{{ __('Reputation') }}</a>
                                                                @endcan
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif


                                        <div x-data="{ expanded: false }"
                                            class="relative z-10 inline cursor-pointer font-medium uppercase min-h-[3rem] px-3 w-full p-1">
                                            <div @click="expanded = !expanded" @click.away="expanded = false"
                                                class="{{ request()->routeIs('tenant.data.index') || request()->routeIs('tenant.questionnaires.panel') ? 'text-esg28' : 'text-esg8' }}"
                                                data-test="report-menu">
                                                {{ __('Report') }}
                                                <div x-show="!expanded" class="float-right my-3">
                                                    @include('icons.arrow-menu')</div>
                                                <div x-cloak x-show="expanded" class="float-right inline my-3">
                                                    @include('icons.arrow-up', [
                                                        'class' =>
                                                            request()->routeIs('tenant.data.index') ||
                                                            request()->routeIs('tenant.questionnaires.panel')
                                                                ? 'text-esg28'
                                                                : 'text-esg8',
                                                    ])
                                                </div>
                                                <div x-cloak x-show="expanded"
                                                    class="right-0 mt-2 w-48 rounded-md text-left">
                                                    <div class="bg-esg4 shadow-xs rounded-md py-1 w-min left-20">

                                                        @if ($tenant->hasMonitoringFeature)
                                                            @can('data.view')
                                                                <a x-data="{ premium: false }" @mouseover="premium = true"
                                                                    @mouseover.away="premium = false"
                                                                    href="{{ $tenant->see_only_own_data ? route('tenant.home') : route('tenant.data.index') }}"
                                                                    class="cursor-pointer block px-4 py-1 font-medium text-[0.9rem] {{ $tenant->see_only_own_data ? 'text-esg9' : (request()->routeIs('tenant.data.*') ? 'text-esg28' : 'text-esg8') }}"
                                                                    data-test="data-menu">
                                                                    {{ __('Monitoring') }}
                                                                    @if ($tenant->see_only_own_data)
                                                                        <div x-cloak x-show="premium"
                                                                            class="absolute top-12 ml-6 text-xs font-bold text-esg5 p-1">
                                                                            PREMIUM
                                                                        </div>
                                                                    @endif
                                                                </a>
                                                            @endcan
                                                        @endif

                                                        @can('questionnaires.view')
                                                            <a href="{{ route('tenant.questionnaires.panel') }}"
                                                                class="cursor-pointer block px-4 py-1 font-medium text-[0.9rem] {{ request()->routeIs('tenant.questionnaires.*') ? 'text-esg28' : 'text-esg8' }}"
                                                                data-test="questionnaires-menu">{{ __('Questionnaires') }}</a>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div x-data="{ expanded: false }"
                                            class="relative z-10 inline cursor-pointer font-medium uppercase min-h-[3rem] px-3 w-full p-1">
                                            <div @click="expanded = !expanded" @click.away="expanded = false"
                                                class="{{ request()->routeIs('tenant.library.*') ? 'text-esg28' : 'text-esg8' }}"
                                                data-test="learn-menu">
                                                {{ __('Learn') }}
                                                <div x-show="!expanded" class="float-right my-3">
                                                    @include('icons.arrow-menu')</div>
                                                <div x-cloak x-show="expanded" class="float-right inline my-3">
                                                    @include('icons.arrow-up', [
                                                        'class' => request()->routeIs('tenant.library.*')
                                                            ? 'text-esg28'
                                                            : 'text-esg8',
                                                    ])
                                                </div>
                                                <div x-cloak x-show="expanded"
                                                    class="right-0 mt-2 w-48 rounded-md text-left">
                                                    <div class="bg-esg4 shadow-xs rounded-md py-1 w-min left-20">
                                                        @can('library.view')
                                                            <a href="{{ route('tenant.library.index') }}"
                                                                class="cursor-pointer block px-4 py-1 font-medium text-[0.9rem] {{ request()->routeIs('tenant.library.*') ? 'text-esg28' : 'text-esg8' }}"
                                                                data-test="library-menu">{{ __('Library') }}</a>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div x-data="{ expanded: false, notifications: false }" class="relative z-10 md:ml-14">
                                    <div class="flex items-center">
                                        <a @if ($tenant->onActiveSubscription) @click="notifications = !notifications" @else @click="trial_modal = true" @endif
                                            class="relative mr-7 cursor-pointer">@include(
                                                'icons.bell' .
                                                    (auth()->user()->unreadNotifications->count()
                                                        ? '-alert'
                                                        : ''))</a>

                                        <button @click="expanded = !expanded"
                                            class="text-esg27 flex max-w-xs items-center rounded-full text-sm focus:outline-none">
                                            <img class="h-10 w-10 rounded-full" src="{{ auth()->user()->avatar }}"
                                                alt="{{ auth()->user()->name }}">
                                        </button>
                                    </div>

                                    @livewire('notifications.bell')

                                    <div x-cloak x-show="expanded" @click.away="expanded = false"
                                        class="absolute -right-4 mt-2 rounded-md shadow-lg text-right w-[100vw] md:w-max box-border">
                                        <div class="bg-esg4 shadow-xs rounded md:p-0 shadow-bxesg1 !pb-1">
                                            <a @if ($tenant->onActiveSubscription) href="{{ route('tenant.settings.user') }}" @else @click="trial_modal = true" @endif
                                                class="relative cursor-pointer block px-4 md:py-1 md:text-sm font-medium md:leading-8 leading-[3rem] uppercase md:normal-case {{ request()->routeIs('tenant.settings.user') ? 'text-esg28' : 'text-esg8' }}"
                                                data-test="my-acc-menu">{{ __('My account') }}</a>

                                            @if ($userIsSuperAdmin)
                                                <a @if ($tenant->onActiveSubscription) href="{{ route('tenant.settings.application') }}" @else @click="trial_modal = true" @endif
                                                    class="cursor-pointer block px-4 md:py-1 md:text-sm font-medium md:leading-8 leading-[3rem] uppercase md:normal-case {{ request()->routeIs('tenant.settings.application') ? 'text-esg28' : 'text-esg8' }}"
                                                    data-test="application-settings-menu">{{ __('Application settings') }}</a>
                                            @endif

                                            @if (auth()->user()->hasRole('Super Admin') && $tenant->hasWalletFeature)
                                                <a href="{{ route('tenant.wallet') }}"
                                                    class="cursor-pointer block px-4 md:py-1 md:text-sm font-medium md:leading-8 leading-[3rem] uppercase md:normal-case {{ request()->routeIs('tenant.wallet') ? 'text-esg28' : 'text-esg8' }}"
                                                    data-test="wallet-balance">{{ __('Wallet') }}</a>
                                            @endif

                                            <a href="{{ route('tenant.logout') }}"
                                                class="cursor-pointer block px-4 md:py-1 md:text-sm font-medium md:leading-8 leading-[3rem] uppercase md:normal-case {{ request()->routeIs('tenant.home') ? 'text-esg28' : 'text-esg8' }}"
                                                data-test=“logout-menu”
                                                x-on:click="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                                {{ __('Logout') }}
                                            </a>
                                            <form id="logout-form" action="{{ route('tenant.logout') }}" method="POST"
                                                class="hidden">
                                                {{ csrf_field() }}
                                            </form>

                                            <div x-data="{ expanded: false }"
                                                class="border-t-esg7 mt-4 border-t-[1px] border-dashed p-0.5 px-3">
                                                <a href="#" @click="expanded = ! expanded"
                                                    class="text-esg11 text-sm">
                                                    {{ __('Languages') }}
                                                    <div x-show="!expanded" class="float-right my-3 ml-1">
                                                        @include('icons.arrow-menu')
                                                    </div>
                                                    <div x-show="expanded" class="float-right my-3 ml-1">
                                                        @include('icons.arrow-up')</div>
                                                </a>

                                                <div x-show="expanded" class="">
                                                    @foreach (config('app.locales') as $locale)
                                                        <a href="{{ route('tenant.locale', ['locale' => $locale]) }}"
                                                            class="text-esg11 block px-4 py-1 text-sm font-medium">{{ str_replace('_', '-', $locale) }}</a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        {{ $header ?? '' }}

        @if (isset($isheader))
            <div class="bg-esg4 mt-10 pt-16 text-esg5 text-3xl font-medium print:hidden">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-esg5 text-2xl font-bold">
                    {{ $title }}
                </div>
            </div>
        @endif

        <main
            class="{{ $mainBgColor ?? '' }} min-h-[calc(100vh-262px)] @if (!isset($header)) @if ($tenant->show_topbar) pt-[6.5rem] @else pt-16 @endif @endif ">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

                <x-alerts.info
                    class="md:hidden">{{ __('We highly recommend using a larger screen for a better user experience.') }}</x-alerts>

                    @if (session('success'))
                        <div x-data="{ show: true }" x-show="show" class="mb-6 rounded-md bg-green-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium leading-5 text-green-800">
                                        {{ session('success') }}
                                    </p>
                                </div>
                                <div class="ml-auto pl-3">
                                    <div class="-mx-1.5 -my-1.5">
                                        <button @click="show = false"
                                            class="inline-flex rounded-md p-1.5 text-green-500 transition duration-150 ease-in-out hover:bg-green-100 focus:bg-green-100 focus:outline-none">
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @yield('content')

                    {{ $slot ?? '' }}

            </div>
        </main>
    </div>

    {!! tenantView('partials/footer') !!}

    @livewire('livewire-ui-modal')

    @livewireScripts(['nonce' => csp_nonce()])


    @stack('body')

    <x-comments::scripts />


    @include('partials/support/chat')


    @stack('child-scripts')

    @if (session()->has('messageText'))
        <script nonce="{{ csp_nonce() }}">
            window.addEventListener('load', function() {
                window.livewire.emit('openModal', 'modals.notification', {
                    'data': {
                        type: '{{ session('messageType') ?? 'error' }}',
                        title: '{{ session('messageTitle') ?? __('Error') }}',
                        message: '{{ session('messageText') }}'
                    }
                });
            });
        </script>
    @endif
</body>

</html>
