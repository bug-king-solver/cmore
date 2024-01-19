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
    <div id="app" class="w-full min-h-[750px] bg-no-repeat bg-cover">
        <nav class="z-50 h-20 w-full">
            <div class="mx-auto max-w-7xl px-4 py-2 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex-none">
                        {{-- <a href="{{ url('/') }}">@include('logos/esg1')</a> --}}
                    </div>
                    <div class="flex-auto">
                        <div class="ml-4 w-4 md:w-auto text-center md:ml-6">

                        </div>
                    </div>
                    <div class="flex-initial w-12 md:w-28">
                        <div class="">
                            <div x-data="{ expanded: false }" @click.away="expanded = false" class="relative z-10">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <main>
            <div x-data="" class="mx-auto max-w-7xl sm:px-6 px-4 lg:px-0">
                @yield('content')
            </div>
        </main>

        <div id="footer" class="flex flex-col md:flex-row justify-between items-center px-4 py-10 sm:px-16 ">

        </div>
    </div>

    @livewire('livewire-ui-modal')
    @livewireScripts(['nonce' => csp_nonce()])

</body>

</html>
