@extends(customInclude('layouts.tenant'))

@section('content')
    <div
        class="h-[300px] max-h-[1000px] flex left-[50%] -ml-[50vw] w-screen -mt-6 relative items-center justify-center bg-esg7/20">
        <div class="w-full mx-auto max-w-7xl pt-8 px-4 sm:px-6 lg:px-8 absolute z-90">
            <div class="text-center">
                <p class="text-5xl text-black"> {{ __('Welcome to') }} <span class="font-extrabold text-esg5">
                        {{ __('SIBS ESG Ecosystem') }} </span> </p>

                <p class="text-2xl text-esg8 mt-4"> {{ __('An environment where companies can submit or update their sustainability data with acceding banks') }} </p>
            </div>
        </div>
    </div>
    <div
        class="h-[300px] max-h-[1000px] flex left-[50%] -ml-[50vw] w-screen relative items-center justify-center bg-esg7/20 rounded-b-full rounded-t-none">
        <div class="w-full mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 absolute z-90 -mt-40">
            <div class="flex items-center justify-between gap-5">
                @include(tenant()->views . 'icons.logo.millennium')

                @include(tenant()->views . 'icons.logo.santander')

                @include(tenant()->views . 'icons.logo.caixa')

                @include(tenant()->views . 'icons.logo.novobanco')

                @include(tenant()->views . 'icons.logo.bpi')

                @include(tenant()->views . 'icons.logo.caixaagro')
            </div>


            <div class="flex items-center justify-between gap-5 mt-8">
                @include(tenant()->views . 'icons.logo.banco')

                @include(tenant()->views . 'icons.logo.eurobic')

                @include(tenant()->views . 'icons.logo.bankinter')

                @include(tenant()->views . 'icons.logo.caixaagro1')

                @include(tenant()->views . 'icons.logo.cca2')

                @include(tenant()->views . 'icons.logo.caixa2')

                @include(tenant()->views . 'icons.logo.cca1')
            </div>
        </div>
    </div>

    <div class="my-32 md:ml-12">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-9">
            <div class="mt-6">
                <p class="uppercase text-lg font-medium text-esg5"> {{ __('Ecosystem') }} </p>

                <p class="text-4xl font-bold text-black mt-4 leading-10">
                    {!! __('We :stylesimplify:closestyle the sustainability', ['style' => '<span class="bg-esg5 text-esg4 mr-2 px-2">', 'closestyle' => '</span>']) !!}
                </p>

                <p class="text-base font-medium text-esg8 mt-4 leading-7">
                    {{ __('The SIBS ESG Ecosystem is designed to facilitate companies compliance with regulatory obligations. Fill in the mandatory questionnaires once and have the information automatically made available to all your banks. Get reports and analyses that allow you to assess your ESG performance and support your transition process towards greater sustainability. Manage all your companies and activities in a single portal.') }}
                </p>
            </div>

            <div class="">
                <img src="/images/customizations/tenantd7f4ecf1-71a4-4dcf-8381-834ea8fd3e20/bg1.png" />
            </div>
        </div>
    </div>

    <div>
        <div class="grid justify-center">
            <p class="uppercase text-lg font-medium text-esg5 text-center"> {{ __('Steps') }} </p>

            <p class="mt-4 text-4xl font-bold text-center"> {{ __('Simple and straightforward') }} </p>

            <p class="text-base font-medium text-esg5 text-center mt-4 text-esg8">
                {{ __('Understand how the journey is on this portal') }} </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mt-20">

            {!! tenantView('tenant.companies.introduction') !!}

            {!! tenantView('tenant.questionnaires.introduction') !!}

            {!! tenantView('tenant.questionnaires.summary') !!}
        </div>

        <div class="grid justify-items-center mt-16">
            <x-buttons.btn text="{{ __('Start your journey now') }}" @click="window.location.href = '{{ route('tenant.companies.form') }}'"
                class="!p-3 !uppercase" />
        </div>
    </div>
@endsection
