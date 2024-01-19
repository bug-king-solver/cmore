@extends(customInclude('layouts.tenant'), ['mainBgColor' => 'bg-esg4'])

@section('content')
<div class="px-4 md:px-0">
    <div class="bg-esg27 h-screen min-h-[1800px] md:min-h-0 md:max-h-[750px] flex left-[50%] -ml-[50vw] w-screen relative items-center justify-center mb-40 md:mb-0">
        <div class="w-full mx-auto max-w-7xl pt-8 px-4 sm:px-6 lg:px-8 absolute z-90">
            <div class="grid grid-cols-1 md:grid-cols-2 font-encodesans text-esg8 gap-14">
                <div class="mt-24">
                    <p class="uppercase font-medium text-lg text-esg5">{{ __('Software') }}</p>
                    <p class="text-4xl font-bold mt-2">Bem-vindo ao ESG PRO</p>

                    <p class="text-base font-normal mt-8 text-justify text-esg8/60"> Aqui encontra tudo o que precisa para ter sucesso nesta jornada de ajudar empresas a transformar sustentabilidade em negócio.</p>
                    <p class="text-base font-bold mt-8 text-justify text-esg8/60">Vamos trabalhar juntos para um futuro sustentável!</p>

                    <button class="bg-esg5 w-60 h-11 rounded-lg font-bold text-base mt-9 uppercase text-esg27" @click="window.location.href = '{!! $questionnaireUrl !!}'">Criar questionário</button>
                </div>

                <div class="w-full">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="mt-10">
                            <img src="{{ global_asset('images/customizations/tenant0a360125-5d29-4049-856f-4edcdbc90904/Img1.png')}}" class="w-full md:w-auto"/>
                        </div>

                        <div class="">
                            <img src="/images/customizations/tenant0a360125-5d29-4049-856f-4edcdbc90904/Img2.png" class="w-full md:w-auto"/>
                        </div>
                    </div>
                    <div class="flex flex-col md:flex-row gap-8 mt-10">
                        <div class="mt-5">
                            <img src="/images/customizations/tenant0a360125-5d29-4049-856f-4edcdbc90904/Img3.png" class="w-full md:w-auto"/>
                        </div>

                        <div class="">
                            <img src="/images/customizations/tenant0a360125-5d29-4049-856f-4edcdbc90904/Img4.png" class="w-full md:w-auto"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="font-encodesans mb-20">
        <div class="max-w-5xl mx-auto">
            <p class="text-lg font-medium text-esg5 text-center uppercase mt-12">Funcionalidades</p>

            <p class="text-4xl font-bold text-esg8 text-center mt-4">Recolher e analisar dados</p>

            <p class="text-base font-normal text-esg8 text-center mt-5">Conheça e explore um mundo de recursos projetados para facilitar a recolha e análise de dados.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 md:gap-9 mt-10">
                <div  x-data="{ open: false }" @mouseleave="open = false" @mouseover="open = true" x-on:click="window.location='{{ route('tenant.questionnaires.panel') }}'" class="bg-esg4 border border-esg4 duration-500 hover:bg-[#E1E6EF] rounded-2xl p-8 cursor-pointer flex gap-5">
                    <div class="grid justify-items-center">
                        @include(tenant()->views .'icons.1', ['color' => color(6)])
                    </div>

                    <div class=" font-normal text-esg8 text-sm">
                        <p class="text-esg6 text-2xl font-semibold "> {{ __('Questionnaires') }} </p>
                        <p class="mt-3 font-normal text-base">Um questionário fácil de navegar com base nas características da empresa.</p>

                        <div x-show="open" class="float-right">
                            @include(tenant()->views .'icons.arrow')
                        </div>
                    </div>
                </div>

                <div x-data="{ open: false }" @mouseleave="open = false" @mouseover="open = true" x-on:click="window.location='{{ route('tenant.dashboard') }}'" class="bg-esg4 border border-esg4 duration-500 hover:bg-[#E1E6EF] rounded-2xl p-8 cursor-pointer flex gap-5">
                    <div class="grid justify-items-center">
                        @include(tenant()->views .'icons.2', ['color' => color(6)])
                    </div>

                    <div class=" font-normal text-esg8 text-sm">
                        <p class="text-esg6 text-2xl font-semibold "> {{ __('Dashboard') }} </p>
                        <p class="mt-3 font-normal text-base">Uma apresentação visual dos dados analisados recolhidos no questionário.</p>

                        <div x-show="open" class="float-right">
                            @include(tenant()->views .'icons.arrow')
                        </div>
                    </div>
                </div>

                <div x-data="{ open: false }" @mouseleave="open = false" @mouseover="open = true" x-on:click="window.location='{{ route('tenant.targets.index') }}'" class="bg-esg4 border border-esg4 duration-500 hover:bg-[#E1E6EF] rounded-2xl p-8 cursor-pointer flex gap-5">
                    <div class="grid justify-items-center">
                        @include(tenant()->views .'icons.5', ['color' => color(6)])
                    </div>

                    <div class=" font-normal text-esg8 text-sm">
                        <p class="text-esg6 text-2xl font-semibold "> {{ __('Targets') }} </p>
                        <p class="mt-3 font-normal text-base">Crie metas e acompanhe o progresso de um indicador.</p>

                        <div x-show="open" class="float-right">
                            @include(tenant()->views .'icons.arrow')
                        </div>
                    </div>
                </div>

                <div x-data="{ open: false }" @mouseleave="open = false" @mouseover="open = true" x-on:click="window.location='{{ route('tenant.library.index') }}'" class="bg-esg4 border border-esg4 duration-500 hover:bg-[#E1E6EF] rounded-2xl p-8 cursor-pointer flex gap-5">
                    <div class="grid justify-items-center">
                        @include(tenant()->views .'icons.6', ['color' => color(6)])
                    </div>

                    <div class=" font-normal text-esg8 text-sm">
                        <p class="text-esg6 text-2xl font-semibold "> {{ __('Library') }} </p>
                        <p class="mt-3 font-normal text-base">Informações sobre o software, estruturas ESG e muito mais!</p>

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
