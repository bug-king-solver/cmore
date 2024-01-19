@extends(customInclude('layouts.tenant'))

@section('content')
    <div
        class="h-[300px] max-h-[1000px] flex left-[50%] -ml-[50vw] w-screen -mt-6 relative items-center justify-center bg-esg7/20">
        <div class="w-full mx-auto max-w-7xl pt-8 px-4 sm:px-6 lg:px-8 absolute z-90">
            <div class="text-center mt-10">
                <p class="text-5xl text-black"> {{ __('Welcome to') }} <span class="font-extrabold text-esg5">
                        {{ __('ST Consultants ESG Ecosystem') }} </span> </p>
                <div class="grid justify-center py-4"> @include('icons.st_consultants.elk') </div>
                <p class="text-2xl text-esg8 w-6/12 m-auto"> {{ __('An environment where companies can submit or update
                    your sustainability data with participating banks') }} </p>
            </div>
        </div>
    </div>
    <div
        class="h-[300px] max-h-[1000px] flex left-[50%] -ml-[50vw] w-screen relative items-center justify-center bg-esg7/20 rounded-b-full rounded-t-none">
        <div class="w-full mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 absolute z-90 -mt-10">
            <div class="flex items-center justify-between gap-5">
                @include('icons.st_consultants.elk')
                @include('icons.st_consultants.abanca')
                @include('icons.st_consultants.bankinter')
                @include('icons.st_consultants.bbva')
                @include('icons.st_consultants.caixabank')
            </div>

            <div class="flex items-center justify-between gap-5 mt-8">
                @include('icons.st_consultants.caja-rural-de-navarra')
                @include('icons.st_consultants.fiare_banca_etica')
                @include('icons.st_consultants.kutxabank')
                @include('icons.st_consultants.laboral-kutxa')
                @include('icons.st_consultants.bsabadell')
            </div>

            <div class="flex items-center justify-center gap-5 mt-8">
                @include('icons.st_consultants.tipo_del_gobierno_vasco')
            </div>
        </div>
    </div>

    <div class="my-32 md:ml-12">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-9">
            <div class="">
                <p class="uppercase text-lg font-medium text-esg5"> {{ __('Ecosystem') }} </p>

                <p class="text-4xl font-bold text-black mt-4 leading-10">
                    {!! __('We make sustainability :styleeasier:closestyle', ['style' => '<span class="bg-esg5 text-esg4 mr-2 px-2">', 'closestyle' => '</span>']) !!}
                </p>

                <p class="text-base font-medium text-esg8 mt-4 leading-7">
                    {!! __('In view of the growing demand for ESG ratings from Credit Institutions, Public Administrations and Investor Groups, :styleELKARGI:closestyle is interested in promoting the achievement of sustainability maturity in small and medium-sized companies,
                        as well as institutions, associations, foundations and other entities of any size in the Autonomous Communities of the Basque Country and Navarre.
                        To this end, ELKARGI has signed an agreement with :styleST Consultores:closestyle (Grupo Sociedad de Tasación) to produce its ESG maturity report.
                        Through this platform you will be able to complete the questionnaire and obtain a report that will enable you to analyse your ESG performance and support your transition process towards greater sustainability".'
                        , ['style' => '<span class="text-esg5 font-black">', 'closestyle' => '</span>']) !!}
                </p>
            </div>

            <div class="">
                <img src="/images/home/stconsultants.png" />
            </div>
        </div>
    </div>

    <div>
        <div class="grid justify-center">
            <p class="uppercase text-lg font-medium text-esg5 text-center"> {{ __('Steps') }} </p>

            <p class="mt-4 text-4xl font-bold text-center"> {{ __('Simple and direct') }} </p>

            <p class="text-base font-medium text-esg5 text-center mt-4 text-esg8">
                {{ __('Understand what the journey is like on this portal') }} </p>
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
