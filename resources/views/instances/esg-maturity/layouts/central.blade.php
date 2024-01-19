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
    <script nonce="{{ csp_nonce() }}" src="{{ mix('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css2?family=Lato&subset=latin,latin-ext" rel="stylesheet" type="text/css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Encode+Sans:wght@100;200;300;400;500;600;700;800;900&family=Lato:wght@100;300;400;700;900&display=swap"
        rel="stylesheet">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    @stack('head')
</head>

<body class="bg-esg4 h-screen antialiased overflow-x-hidden">
    <div id="app" @if(isset($background_image) && $background_image) class="w-full min-h-[750px] bg-[url('/images/pages/bg-login.png')] bg-fixed bg-no-repeat bg-cover" @endif>
        <nav class="@if(isset($nav_background) && $nav_background) bg-esg4 @endif fixed z-50 h-20 w-full">
            <div class="mx-auto max-w-7xl px-4 py-2 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex-none">
                        <a href="{{ url('/') }}">@include('logos/esg1')</a>
                    </div>
                    <div class="flex-auto">
                        <div class="ml-4 w-4 md:w-auto text-center md:ml-6">
                            <div class="flex hidden space-x-10 lg:block">
                                <a href="{{ route('central.landing') }}"  class="font-encodesans cursor-pointer text-lg font-semibold uppercase {{ request()->routeIs('central.landing') ? 'text-esg28' : (isset($nav_text_white) && $nav_text_white == true ? 'text-esg27' : 'text-esg29') }}">{{ __('SOLUTION') }}</a>
                                <a href="{{ route('central.packages') }}" class="font-encodesans text-lg font-semibold uppercase {{ request()->routeIs('central.packages') ? 'text-esg28' : (isset($nav_text_white) && $nav_text_white == true ? 'text-esg27' : 'text-esg29') }}">{{ __('PACKAGES') }}</a>
                                <div x-data="{expanded: false}" class="relative z-10 inline cursor-pointer text-sm font-medium uppercase">
                                    <a @click="expanded = !expanded" @click.away="expanded = false" class="font-encodesans text-lg font-semibold uppercase {{ request()->routeIs('central.our-partners') || request()->routeIs('central.become-partner') ? 'text-esg28' : 'text-esg29' }}">
                                        <span> {{ __('PARTNERS') }} <svg class="right w-6 h-6 mb-1 inline-block" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></span>
                                        <div x-cloak x-show="expanded" class="absolute right-0 mt-2 w-56 rounded-md shadow-lg">
                                            <div class="bg-esg4 shadow-xs rounded-md py-4 px-4 text-left">
                                                <a href="{{ route('central.our-partners') }}" class="mb-4 block font-encodesans text-lg font-semibold uppercase {{ request()->routeIs('central.our-partners') ? 'text-esg28' : (isset($nav_text_white) && $nav_text_white == true ? 'text-esg27' : 'text-esg29') }}">{{ __('OUR PARTNERS') }}</a>
                                                <a href="{{ route('central.become-partner') }}" class="font-encodesans block mt-5 text-lg font-semibold uppercase {{ request()->routeIs('central.become-partner') ? 'text-esg28' : (isset($nav_text_white) && $nav_text_white == true ? 'text-esg27' : 'text-esg29') }}">{{ __('BECOME A PARTNER') }}</a>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <a href="{{ route('central.about-us') }}" class="font-encodesans cursor-pointer text-lg font-semibold uppercase {{ request()->routeIs('central.about-us') ? 'text-esg28' : (isset($nav_text_white) && $nav_text_white == true ? 'text-esg27' : 'text-esg29') }}">{{ __('ABOUT US') }}</a>
                                <div x-data="{expanded: false}" class="relative z-10 inline cursor-pointer text-sm font-medium uppercase">
                                    <a @click="expanded = !expanded" @click.away="expanded = false" class="font-encodesans text-lg font-semibold uppercase {{ request()->routeIs('central.support-request') ? 'text-esg28' : 'text-esg29' }}">
                                        <span> {{ __('SUPPORT') }} <svg class="right w-6 h-6 mb-1 inline-block" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></span>
                                        <div x-cloak x-show="expanded" class="absolute right-0 mt-2 w-56 rounded-md shadow-lg">
                                            <div class="bg-esg4 shadow-xs rounded-md py-4 px-4 text-left">
                                                <a href="{{ route('central.support-request') }}" class="font-encodesans cursor-pointer text-lg font-semibold uppercase {{ request()->routeIs('central.support-request') ? 'text-esg28' : (isset($nav_text_white) && $nav_text_white == true ? 'text-esg27' : 'text-esg29') }}">{{ __('SUPPORT REQUEST') }}</a>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <div x-data="{ expanded: false }" @click.away="expanded = false"
                                class="relative z-10 ml-4 lg:hidden">
                                <div>
                                    <button @click="expanded = !expanded"
                                        class="text-esg29 flex max-w-xs items-center rounded-full uppercase focus:outline-none  text-lg font-bold">
                                        {{ __('Menu') }}
                                    </button>
                                </div>
                                <div x-cloak x-show="expanded" class="absolute left-0 py-5 mt-2 w-48 rounded-md shadow-lg bg-white">
                                    <a href="{{ route('central.landing') }}" class="font-encodesans cursor-pointer text-lg mt-5 font-semibold block uppercase {{ request()->routeIs('central.landing') ? 'text-esg28' : 'text-esg29' }}">{{ __('SOLUTION') }}</a>
                                    <a href="{{ route('central.packages') }}" class="font-encodesans text-lg font-semibold block mt-5 uppercase {{ request()->routeIs('central.packages') ? 'text-esg28' : 'text-esg29' }}">{{ __('PACKAGES') }}</a>
                                    <div x-data="{expanded: false}" class="relative z-10 cursor-pointer block mt-5 text-sm font-medium uppercase">
                                        <a @click="expanded = !expanded" @click.away="expanded = false" class="font-encodesans text-lg font-semibold uppercase {{ request()->routeIs('central.our-partners') || request()->routeIs('central.become-partner') ? 'text-esg28' : 'text-esg29' }}">
                                            <span> {{ __('PARTNERS') }} <svg class="right w-6 h-6 mb-1 inline-block" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></span>
                                            <div x-cloak x-show="expanded" class="absolute right-0 mt-2 w-52 rounded-md shadow-lg">
                                                <div class="bg-esg4 shadow-xs rounded-md py-4 px-4 text-left">
                                                    <a href="{{ route('central.our-partners') }}" class="mb-4 block font-encodesans text-lg font-semibold uppercase {{ request()->routeIs('central.our-partners') ? 'text-esg28' : (isset($nav_text_white) && $nav_text_white == true ? 'text-esg27' : 'text-esg29') }}">{{ __('OUR PARTNERS') }}</a>
                                                    <a href="{{ route('central.become-partner') }}" class="font-encodesans block text-lg font-semibold uppercase {{ request()->routeIs('central.become-partner') ? 'text-esg28' : (isset($nav_text_white) && $nav_text_white == true ? 'text-esg27' : 'text-esg29') }}">{{ __('BECOME A PARTNER') }}</a>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <a href="{{ route('central.faqs') }}" class="font-encodesans cursor-pointer block mt-5 text-lg font-semibold uppercase {{ request()->routeIs('central.about-us') ? 'text-esg28' : 'text-esg29' }}">{{ __('ABOUT US') }}</a>
                                    <a href="{{ route('central.support-request') }}" class="font-encodesans cursor-pointer block mt-5 text-lg font-semibold uppercase {{ request()->routeIs('central.support-request') ? 'text-esg28' : 'text-esg29' }}">{{ __('SUPPORT') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex-initial w-12 md:w-28">
                        <div class="">
                            <div x-data="{ expanded: false }" @click.away="expanded = false" class="relative z-10">
                                <div>
                                    <button @click="expanded = !expanded" class="font-encodesans flex max-w-xs items-center rounded-full text-lg text-esg29 font-semibold focus:outline-none">
                                        {{ str_replace('_', '-', session()->get('locale')) ?? config('app.locale') }}
                                        <svg xmlns="https://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </div>
                                <div x-cloak x-show="expanded" class="absolute right-0 mt-6 w-32 shadow-lg">
                                    <div class="bg-esg6 shadow-xs px-3 text-center">
                                        @foreach (config('app.locales') as $locale)
                                            <a href="{{ route('tenant.locale', ['locale' => $locale]) }}" class="block py-3 px-6 text-esg28 hover:bg-esg8 @if (!$loop->last) border-b-[1px] border-b-esg7 @endif">{{ str_replace('_', '-', $locale) }}</a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </nav>

        <main class="{{ $mainBgColor ?? '' }} min-h-[calc(100vh-262px)]">
            <div x-data="" class="mx-auto max-w-7xl sm:px-6 px-4 lg:px-0">
                @yield('content')
            </div>
        </main>

        <div id="footer" class="flex flex-col md:flex-row justify-between items-center px-4 py-10 sm:px-16 bg-[#F8F8F8]">
            <div class="">
                <a href="https://cmore-sustainability.com/">@include('logos/cmore4', ['class'=>'inline-block'])</a>
                @include('logos/ibm', ['class'=>'inline-block'])
            </div>

            <div class="flex flex-col md:flex-row space-x-10 lg:block pt-10 md:pt-0">
                <a href="{{ route('central.contact-us') }}" class="font-encodesans text-lg font-semibold uppercase {{ request()->routeIs('central.contact-us') ? 'text-esg28' : 'text-esg29' }} ml-10 sm:ml-0">{{ __('Contact us') }}</a>
                <a href="{{ global_asset('storage/documentation/privacy-policy_' . app()->getLocale() . '.pdf') }}" class="font-encodesans text-lg font-semibold uppercase text-esg29">{{ __('Privacy Policy') }}</a>
                <a href="{{ global_asset('storage/documentation/terms-and-conditions_' . app()->getLocale() . '.pdf') }}" class="font-encodesans text-lg font-semibold uppercase text-esg29">{{ __('Terms and Conditions') }}
                </a>
            </div>

            <div class="grid grid-cols-2 text-right items-center pt-10 md:pt-0">
            </div>
        </div>
    </div>

    @include('partials/support/chat')
    @livewire('livewire-ui-modal')

    @livewireScripts(['nonce' => csp_nonce()])
</body>

</html>
