@extends(customInclude('layouts.tenant'), ['mainBgColor' => 'bg-esg4'])

@section('content')
<div class="px-4 md:px-0">
    <div class="bg-esg27 h-screen min-h-[1800px] md:min-h-0 md:max-h-[750px] flex left-[50%] -ml-[50vw] w-screen relative items-center justify-center mb-40 md:mb-0">
        <div class="w-full mx-auto max-w-7xl pt-8 px-4 sm:px-6 lg:px-8 absolute z-90">
            <div class="grid grid-cols-1 md:grid-cols-2 font-encodesans text-esg8 gap-14">
                <div class="mt-24">
                    <p class="uppercase font-medium text-lg text-esg2">{{ __('Software') }}</p>
                    <p class="text-4xl font-bold">{{ __('ESG Score') }}</p>

                    <p class="text-base font-normal mt-8 text-justify text-esg8">{{ __('Plataforma') }} <span class="font-bold">{{ __('Nossa Praia by C-MORE') }}</span> {{ __('com painel indicativo de dados para facilitar a análise de dados.') }}</p>
                    <p class="text-base font-normal mt-8 text-justify text-esg8">{{ __('Descubra novas marés de inovação com a Nossa Praia e navegue com coragem em direção a um futuro mais ESG.') }}</p>

                    <button class="bg-esg2 w-60 h-11 rounded-lg font-bold text-base mt-9 uppercase text-esg27" @click="window.location.href = '{!! $questionnaireUrl !!}'"> {{ __('crie um questionário') }} </button>
                </div>

                <div class="w-full">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="mt-10">
                            <img src="/images/customizations/tenant1379696d-fd48-4a45-9c3a-175bb5d6a736/img1.png" class="w-full md:w-auto"/>
                        </div>

                        <div class="">
                            <img src="/images/customizations/tenant1379696d-fd48-4a45-9c3a-175bb5d6a736/img2.png" class="w-full md:w-auto"/>
                        </div>
                    </div>
                    <div class="flex flex-col md:flex-row gap-8 mt-10">
                        <div class="mt-5">
                            <img src="/images/customizations/tenant1379696d-fd48-4a45-9c3a-175bb5d6a736/img3.png" class="w-full md:w-auto"/>
                        </div>

                        <div class="">
                            <img src="/images/customizations/tenant1379696d-fd48-4a45-9c3a-175bb5d6a736/img4.png" class="w-full md:w-auto"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-esg5 h-screen min-h-[800px] md:min-h-0 md:max-h-[400px] flex left-[50%] -ml-[50vw] w-screen relative items-center justify-center">
        <div class="w-full mx-auto max-w-7xl  px-4 sm:px-6 lg:px-8 absolute z-90">
            <div class="grid items-center text-center font-encodesans text-esg27 gap-14">
                <div>
                    <p class="text-lg font-medium uppercase mb-3">{{ __('sobre') }}</p>
                    <p class="text-4xl font-bold">{{ __('A nova praia da Outra Praia') }}</p>

                    <p class="text-xl font-normal mt-8  text-esg27">{{ __('Com mais de 10 anos de experiência, somos agora a Nossa Praia, mais regenerativa, inclusiva, participativa e muito mais ESG. Fazemos parte do grupo B&Partners.co e nossa identidade visual e nome refletem nossos valores e ideais.
                        Desde 2013, conectamos marcas e pessoas com propósito, transformando histórias e explorando novos territórios.') }}</p>
                    <p class="text-xl font-normal text-esg27"> <span class="font-bold">{{ __('DIVERSIDADE, RESPEITO, REGENERAÇÃO e CRIATIVIDADE') }}</span> {{ __('estão no centro de tudo que fazemos.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="font-encodesans mb-20">
        <div class="max-w-5xl mx-auto">
            <p class="text-lg font-medium text-esg3 text-center uppercase mt-12"> {{ __('Recursos') }} </p>

            <p class="text-4xl font-bold text-esg8 text-center mt-4"> {{ __('Coleta e análise de dados') }} </p>

            <p class="text-base font-normal text-esg8 text-center mt-5"> {{ __('Conheça e explore um mundo de recursos projetados para facilitar a coleta e análise de dados.') }} </p>

            <div class="grid grid-cols-1 md:grid-cols-2 md:gap-9 mt-10">
                <div  x-data="{ open: false }" @mouseleave="open = false" @mouseover="open = true" x-on:click="window.location='{{ route('tenant.questionnaires.panel') }}'" class="bg-esg4 border border-esg4 duration-500 hover:bg-esg3/[0.07] rounded-2xl p-8 cursor-pointer flex gap-5">
                    <div class="grid justify-items-center">
                        @include(tenant()->views .'icons.1')
                    </div>

                    <div class=" font-normal text-esg8 text-sm">
                        <p class="text-esg3 text-2xl font-semibold "> {{ __('Questionários') }} </p>
                        <p class="mt-3 font-normal text-base"> {{ __('Questionário fácil de navegar feito com base nas características da empresa.') }} </p>

                        <div x-show="open" class="float-right">
                            @include(tenant()->views .'icons.arrow')
                        </div>
                    </div>
                </div>

                <div x-data="{ open: false }" @mouseleave="open = false" @mouseover="open = true" x-on:click="window.location='{{ route('tenant.dashboard') }}'" class="bg-esg4 border border-esg4 duration-500 hover:bg-esg3/[0.07] rounded-2xl p-8 cursor-pointer flex gap-5">
                    <div class="grid justify-items-center">
                        @include(tenant()->views .'icons.2')
                    </div>

                    <div class=" font-normal text-esg8 text-sm">
                        <p class="text-esg3 text-2xl font-semibold "> {{ __('Dashboard') }} </p>
                        <p class="mt-3 font-normal text-base"> {{ __('Uma apresentação visual dos dados analisados, ​​coletados no questionário.') }} </p>

                        <div x-show="open" class="float-right">
                            @include(tenant()->views .'icons.arrow')
                        </div>
                    </div>
                </div>

                <div x-data="{ open: false }" @mouseleave="open = false" @mouseover="open = true" x-on:click="window.location='{{ route('tenant.targets.index') }}'" class="bg-esg4 border border-esg4 duration-500 hover:bg-esg3/[0.07] rounded-2xl p-8 cursor-pointer flex gap-5">
                    <div class="grid justify-items-center">
                        @include(tenant()->views .'icons.5')
                    </div>

                    <div class=" font-normal text-esg8 text-sm">
                        <p class="text-esg3 text-2xl font-semibold "> {{ __('Objetivos') }} </p>
                        <p class="mt-3 font-normal text-base"> {{ __('Crie metas e acompanhe o progresso de um indicador.') }} </p>

                        <div x-show="open" class="float-right">
                            @include(tenant()->views .'icons.arrow')
                        </div>
                    </div>
                </div>

                <div x-data="{ open: false }" @mouseleave="open = false" @mouseover="open = true" x-on:click="window.location='{{ route('tenant.library.index') }}'" class="bg-esg4 border border-esg4 duration-500 hover:bg-esg3/[0.07] rounded-2xl p-8 cursor-pointer flex gap-5">
                    <div class="grid justify-items-center">
                        @include(tenant()->views .'icons.6')
                    </div>

                    <div class=" font-normal text-esg8 text-sm">
                        <p class="text-esg3 text-2xl font-semibold "> {{ __('Biblioteca') }} </p>
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
