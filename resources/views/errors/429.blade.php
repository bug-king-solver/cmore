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
    @livewireStyles(['nonce' => csp_nonce()])
    @stack('head')
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link href="{{ mix('css/tenant/custom.css') }}" rel="stylesheet">

    @include('partials/colors')
</head>

<body class="bg-esg4 h-screen antialiased overflow-x-hidden">

    <div class="flex justify-center items-center h-screen">
        <div class="info w-auto">
            <div class="m-auto content-center w-[514px]">
                @include('icons.errors.429')
            </div>
            <div class="text-center content mt-8 max-w-2xl">
                <h3 class="font-bold text-3xl text-esg6">{!! __('Too many requests') !!}</h3>
                <p class="text-2xl text-esg8 mb-8">
                    {!! __('The user has exceeded the rate limit by sending too many requests within a specific time frame. To maintain system performance and stability, please wait for a brief moment before trying again.') !!}
                </p>
                <x-buttons.a class="!bg-esg6 py-4 px-8" href="{{ url()->previous() }}" text="{{ __('Go Back') }}" />
            </div>
        </div>
    </div>

</body>

</html>
