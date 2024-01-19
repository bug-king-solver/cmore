@extends(customInclude('layouts.tenant'), ['mainBgColor' => 'bg-esg4'])

@section('content')
<div class="px-4 md:px-0">
    <div class="h-screen min-h-[1800px] md:min-h-0 md:max-h-[750px] flex left-[50%] -ml-[50vw] w-screen relative items-center justify-center mb-40 md:mb-0">
        <div class="w-full mx-auto max-w-7xl pt-8 px-4 sm:px-6 lg:px-8 absolute z-90">
            <div class="grid grid-cols-1 md:grid-cols-2 font-encodesans text-esg8 gap-14">
                <div class="mt-24">
                    <p class="uppercase font-medium text-lg text-esg8/70">{{ __('Software') }}</p>
                    <p class="text-4xl font-bold mt-4">{{ __('ESG Score') }}</p>
                    <p class="text-base font-normal mt-4 text-justify text-esg8">{{ __('A plataforma ESG Maturity permite a recolha e controlo da informação ESG do Santander Portugal, de modo a cumprir as exigências de reporting e o acompanhamento periódico do desempenho dos nossos compromissos públicos.') }}</p>

                    <button class="bg-esg8/70 w-60 h-11 rounded-lg font-bold text-base mt-9 uppercase text-esg4" @click="window.location.href = '{!! $questionnaireUrl !!}'"> {{ __('Create a questionnaire') }} </button>
                </div>

                <div class="w-full">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="mt-10">
                            <img src="/images/customizations/tenant010c6960-110e-42a6-9440-182773891185/Img1.png" class="w-full md:w-auto"/>
                        </div>

                        <div class="">
                            <img src="/images/customizations/tenant010c6960-110e-42a6-9440-182773891185/Img2.png" class="w-full md:w-auto"/>
                        </div>
                    </div>
                    <div class="flex flex-col md:flex-row gap-8 mt-10">
                        <div class="mt-5">
                            <img src="/images/customizations/tenant010c6960-110e-42a6-9440-182773891185/Img3.png" class="w-full md:w-auto"/>
                        </div>

                        <div class="">
                            <img src="/images/customizations/tenant010c6960-110e-42a6-9440-182773891185/Img4.png" class="w-full md:w-auto"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="font-encodesans mb-20">
        <div class="max-w-7xl mx-auto">
            <p class="text-lg font-medium text-[#EC0000] text-center uppercase mt-12"> {{ __('RECURSOS') }} </p>

            <p class="text-4xl font-bold text-esg8 text-center mt-4"> {{ __('Coleta e análise de dados') }} </p>

            <p class="text-base font-normal text-esg8 text-center mt-5"> {{ __('Conheça e explore um mundo de recursos projetados para facilitar a coleta e análise de dados.') }} </p>

            <div class="grid grid-cols-1 md:grid-cols-2 md:gap-9 mt-10">
                <div  x-data="{ open: false }" @mouseleave="open = false" @mouseover="open = true" x-on:click="window.location='{{ route('tenant.questionnaires.panel') }}'" class="bg-esg4 border border-esg4 duration-500 hover:bg-[#EC0000]/[0.07] rounded-2xl p-8 cursor-pointer flex gap-5">
                    <div class="grid justify-items-center">
                        @include(tenant()->views .'icons.1', ['color' => '#EC0000'])
                    </div>

                    <div class=" font-normal text-esg8 text-sm">
                        <p class="text-[#EC0000] text-2xl font-semibold "> {{ __('Questionários') }} </p>
                        <p class="mt-3 font-normal text-base"> {{ __('Questionário fácil de navegar feito com base nas características da empresa.') }} </p>

                        <div x-show="open" class="float-right">
                            @include(tenant()->views .'icons.arrow')
                        </div>
                    </div>
                </div>

                <div x-data="{ open: false }" @mouseleave="open = false" @mouseover="open = true" x-on:click="window.location='{{ route('tenant.dashboard') }}'" class="bg-esg4 border border-esg4 duration-500 hover:bg-[#EC0000]/[0.07] rounded-2xl p-8 cursor-pointer flex gap-5">
                    <div class="grid justify-items-center">
                        @include(tenant()->views .'icons.2', ['color' => '#EC0000'])
                    </div>

                    <div class=" font-normal text-esg8 text-sm">
                        <p class="text-[#EC0000] text-2xl font-semibold "> {{ __('Dashboard') }} </p>
                        <p class="mt-3 font-normal text-base"> {{ __('Uma apresentação visual dos dados analisados, ​​coletados no questionário.') }} </p>

                        <div x-show="open" class="float-right">
                            @include(tenant()->views .'icons.arrow')
                        </div>
                    </div>
                </div>

                <div x-data="{ open: false }" @mouseleave="open = false" @mouseover="open = true" x-on:click="window.location='{{ route('tenant.targets.index') }}'" class="bg-esg4 border border-esg4 duration-500 hover:bg-[#EC0000]/[0.07] rounded-2xl p-8 cursor-pointer flex gap-5">
                    <div class="grid justify-items-center">
                        @include(tenant()->views .'icons.5', ['color' => '#EC0000'])
                    </div>

                    <div class=" font-normal text-esg8 text-sm">
                        <p class="text-[#EC0000] text-2xl font-semibold "> {{ __('Objetivos') }} </p>
                        <p class="mt-3 font-normal text-base"> {{ __('Crie metas e acompanhe o progresso de um indicador.') }} </p>

                        <div x-show="open" class="float-right">
                            @include(tenant()->views .'icons.arrow')
                        </div>
                    </div>
                </div>

                <div x-data="{ open: false }" @mouseleave="open = false" @mouseover="open = true" x-on:click="window.location='{{ route('tenant.library.index') }}'" class="bg-esg4 border border-esg4 duration-500 hover:bg-[#EC0000]/[0.07] rounded-2xl p-8 cursor-pointer flex gap-5">
                    <div class="grid justify-items-center">
                        @include(tenant()->views .'icons.6', ['color' => '#EC0000'])
                    </div>

                    <div class=" font-normal text-esg8 text-sm">
                        <p class="text-[#EC0000] text-2xl font-semibold "> {{ __('Biblioteca') }} </p>
                        <p class="mt-3 font-normal text-base"> {{ __('Informações sobre o software, frameworks ESG e muito mais!') }} </p>

                        <div x-show="open" class="float-right">
                            @include(tenant()->views .'icons.arrow')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
