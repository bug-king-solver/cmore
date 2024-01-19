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
    @include('partials/colors')
    @stack('head')
    <!-- add class whitelabel to the div below to change the desired elements' color -->
    @php
        $bgImage = instanceImages('background_login.jpg');
    @endphp
    <style nonce="{{ csp_nonce() }}">
        .custom-background {
            background-image: url('{{ $bgImage }}');
            background-position: center;
        }
    </style>
</head>

<body class="bg-esg4 h-screen antialiased">

    <div class="grid grid-cols-1 md:grid-cols-2">
        <div id="login"
            class="w-full h-screen grid grid-rows-1 self-stretch place-content-center order-0 left-4 items-center bg-no-repeat bg-cover before:bg-esg6/[0.75] before:absolute before:w-full before:md:w-1/2 before:h-full custom-background">
            <div class="flex flex-col items-center z-10">
                @includeFirst(
                    [tenant()->views . 'icons.logo-auth-secondary', 'icons.auth.logo-secondary'],
                    ['width' => '287.69px', 'height' => '150px']
                )
            </div>
        </div>
        <div class="relative">

            @include('partials/locale')

            <main class="flex flex-col justify-around items-center pb-0 pl-[32px] pr-[32px] h-full w-full">
                <div class="mx-auto w-[200px] flex place-content-center">
                    @includeFirst(
                        [tenant()->views . 'icons.logo-header', 'icons.auth.logo-main'],
                        ['width' => '207.74px', 'height' => '50px']
                    )
                </div>
                <div class="mx-auto flex flex-col items-center">
                    @yield('content')
                </div>

                {!! tenantView('tenant.auth.footer') !!}
            </main>
        </div>
    </div>

    @include('partials/support/chat')
</body>

</html>
