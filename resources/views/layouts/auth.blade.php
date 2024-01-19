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
    <link href="https://fonts.googleapis.com/css2?family=Encode+Sans:wght@100;200;300;400;500;600;700;800;900&family=Lato:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @stack('head')
</head>
<body class="bg-esg4 h-screen antialiased">
    <!-- add class whitelabel to the div below to change the desired elements' color -->
    <div class="grid grid-cols-1 md:grid-cols-2">
        <div id="login" class="w-full h-screen bg-fixed grid grid-rows-1 self-stretch place-content-center order-0 left-4 items-center bg-[url('/images/pages/bg-login-2.png')] bg-fixed bg-no-repeat bg-cover before:bg-esg6/[0.75] before:absolute before:w-full before:md:w-1/2 before:h-full">
            <div class="flex flex-col items-center z-10">
                @include('logos/logo3', ['width' => '287.69px', 'height' => '150px'])
            </div>
        </div>
        <div class="relative">

            @include('partials/locale')

            <main class="flex flex-col justify-around items-center pb-0 pl-[32px] pr-[32px] h-full w-full">
                <div class="mx-auto w-[200px]">
                    @include('logos/cmore-new-logo', ['width' => '207.74px', 'height' => '50px'])
                </div>
                <div class="mx-auto flex flex-col items-center">
                    @yield('content')
                </div>
                <div>
                    <div class="absolute bottom-0 left-6 pb-[24px]">
                        <span class="font-encodesans leading-5 text-sm font-normal text-esg11">© ESG Maturity 2022</span>
                    </div>
                    <div class="absolute bottom-0 right-6 pb-[24px] flex flex-row gap-[2px]" >
                        @include('icons.mail', ['width' => '20px', 'height' => '20px', 'color' => color(11)])
                        <span class="font-encodesans leading-5 pl-1 text-sm font-normal text-esg11"></span>
                    </div>
                </div>
            </main>
        </div>
    </div>

    @include('partials/support/chat')
</body>
</html>
