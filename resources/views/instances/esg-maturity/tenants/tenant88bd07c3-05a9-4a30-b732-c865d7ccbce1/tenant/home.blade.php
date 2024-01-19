@extends(customInclude('layouts.tenant'), ['mainBgColor' => 'bg-esg15'])

@section('content')
<div class="px-4 md:px-0">
    <div class="bg-esg6 h-screen max-h-[1000px] flex left-[50%] -ml-[50vw] w-screen -mt-6 relative items-center justify-center bg-no-repeat bg-cover bg-[url('/images/customizations/tenant88bd07c3-05a9-4a30-b732-c865d7ccbce1/image.png')]">
        <div class="w-full mx-auto max-w-7xl pt-8 px-4 sm:px-6 lg:px-8 absolute z-90">
            <div class="grid font-encodesans text-esg4">
                <div class="mt-24">
                    @include(tenant()->views .'icons.forest', ['class' => 'w-full md:w-auto'])
                    <p class="text-4xl font-normal mt-8 text-justify">{{ __('Medir o desempenho') }} <span class="text-esg6 font-bold"> {{ __('ESG') }} </span></p>
                </div>

                <div class="w-full mt-6">
                    <p class="text-2xl font-normal w-1/3">{{ __('Ferramenta Organizacional de Reporte da Sustentabilidade no Turismo') }}</p>
                    <button class="bg-esg1 w-72 p-6 rounded-lg font-semibold text-2xl mt-20 uppercase" @click="window.location.href = '{!! $questionnaireUrl !!}'"> {{ $hasQuestionnaires ? 'CONTINUAR' : 'COMEÇAR' }} @include(tenant()->views .'icons.right-arrow', ['class' => 'inline-block ml-1 -mt-1'])</button>
                </div>
            </div>
        </div>
    </div>

    <div class="font-encodesans mt-10 md:-mt-24 z-10 relative">
        <div class="grid justify-items-center font-encodesans text-esg4 bg-esg6 rounded-3xl h-72">
            <div class="p-4 md:p-14 md:px-32 text-center">
                <p class="text-2xl md:text-[40px] font-bold text-justify leading-nor">{{ __('Preparar o futuro do Turismo e ') }} </p>
                <p class="text-2xl md:text-[40px] font-bold mt-2 text-justify leading-tight">{{ __('destacá-lo como um setor líder na') }} </p>
                <p class="text-2xl md:text-[40px] font-bold mt-2 text-justify leading-tight">{{ __('promoção do desenvolvimento sustentável.') }}</p>
            </div>
        </div>
    </div>

    <div class="w-full mx-auto max-w-5xl">
        <div class="grid justify-end text-right mt-20 mb-5">
            @include(tenant()->views .'icons.quotes')
        </div>

        <div class="">
            <p class="text-2xl font-medium">{{ __('A atividade empresarial privada, o investimento e a inovação são os principais elementos impulsionadores da produtividade, do crescimento económico inclusivo e da criação do emprego. Reconhecemos a diversidade do setor privado, que vai desde as microempresas e cooperativas às multinacionais. Convocamos todas as empresas a aplicar a sua criatividade e inovação na resolução dos desafios do desenvolvimento sustentável.') }}</p>
        </div>

        <div class="text-right mt-5">
            @include(tenant()->views .'icons.quotes')
        </div>

        <div class="grid justify-center text-center mt-10">
            <p class="text-esg6 text-2xl font-extrabold"> {{ __('Agenda 2030 para o Desenvolvimento Sustentável') }} </p>
            <p class="text-esg8 text-xl font-normal"> {{ __('Artigo 67 | Acordado pelos 193 Estados-Membros das Nações Unidas') }} </p>
        </div>
    </div>

    <div class="mt-24">
        <div class="text-4xl font-normal text-esg8 text-center"> {{ __('Desempenho') }} <span class="font-bold">{{ __('ESG') }}</span> </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 text-esg4 mt-14">
            <div class="rounded-3xl p-10 h-80 bg-no-repeat bg-cover bg-[url('/images/customizations/tenant88bd07c3-05a9-4a30-b732-c865d7ccbce1/BG1.jpg')]">
                @include('icons.categories.1', ['color' => color(4), 'width' => 39, 'height' => 60])
                <p class="text-2xl font-extrabold italic mt-7"> {{ __('Environmental') }} </p>
                <p class="font-medium text-xl"> {{ __('Ambiental') }} </p>
                <p class="mt-5 text-2xl font-normal"> {{ __('Contribuição para os desafios ambientais') }} </p>
            </div>

            <div class="rounded-3xl p-10 h-80 bg-no-repeat bg-cover bg-[url('/images/customizations/tenant88bd07c3-05a9-4a30-b732-c865d7ccbce1/BG2.jpg')]">
                @include('icons.categories.2', ['color' => color(4), 'width' => 54, 'height' => 60])
                <p class="text-2xl font-extrabold italic mt-7"> {{ __('Social') }} </p>
                <p class="font-medium text-xl"> {{ __('Social') }} </p>
                <p class="mt-5 text-2xl font-normal"> {{ __('Relação com o meio social') }} </p>
            </div>

            <div class="rounded-3xl p-10 h-80 bg-no-repeat bg-cover bg-[url('/images/customizations/tenant88bd07c3-05a9-4a30-b732-c865d7ccbce1/BG3.jpg')]">
                @include('icons.categories.3', ['color' => color(4), 'width' => 59, 'height' => 60])
                <p class="text-2xl font-extrabold italic mt-7">Governance</p>
                <p class="font-medium text-xl"> {{ __('Governação') }} </p>
                <p class="mt-5 text-2xl font-normal"> {{ __('Elementos organizacionais e políticas empresariais') }} </p>
            </div>
        </div>
    </div>

    <div class="mt-24">
        <div class="text-4xl font-normal text-esg8 text-center"> {{ __('Meça a sustentabilidade do seu') }}</div>
        <div class="text-4xl font-normal text-esg8 text-center">negócio através da gestão <span class="font-bold">ESG</span> </div>


        <div class="grid grid-cols-3 mt-14">
            <div class="">
                <div class="grid grid-cols-1">
                    <div class="bg-esg6 rounded-xl w-14 h-14 grid mx-auto place-content-center">
                        @include(tenant()->views .'icons.eye', ['width' => 32, 'height' => 22, 'color' => color(4)])
                    </div>

                    <div class="text-center pt-4">
                        <p class="text-2xl font-normal text-esg8"> <span class="font-bold"> {{ __('Monitorize') }} </span> {{ __('as práticas') }} </p>
                        <p class="text-2xl font-normal text-esg8"> {{ __('ambientais, sociais e de governação') }} </p>
                    </div>
                </div>
            </div>

            <div class="">
                <div class="grid grid-cols-1">
                    <div class="bg-esg6 p-2 rounded-xl w-14 h-14 grid mx-auto place-content-center">
                        @include(tenant()->views .'icons.union')
                    </div>

                    <div class="text-center pt-4">
                        <p class="text-2xl font-normal text-esg8"> <span class="font-bold"> {{ __('Reporte') }} </span> </p>
                        <p class="text-2xl font-normal text-esg8"> {{ __('o desempenho') }} </p>
                    </div>
                </div>
            </div>

            <div class="">
                <div class="grid grid-cols-1">
                    <div class="bg-esg6 p-2 rounded-xl w-14 h-14 grid mx-auto place-content-center">
                        @include(tenant()->views .'icons.gestao')
                    </div>

                    <div class="text-center pt-4">
                        <p class="text-2xl font-normal text-esg8"> <span class="font-bold"> {{ __('Crie valor sustentável') }} </span> </p>
                        <p class="text-2xl font-normal text-esg8"> {{ __('para todos os stakeholders') }} </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-24">
        <div class="text-4xl font-normal text-esg8 text-center"> {{ __('Gestão de dados') }} <span class="font-bold">{{ __('ESG') }}</span> <span class="italic">{{ __('end-to-end') }}</span> </div>

        <div class="w-full mx-auto max-w-6xl md:border-t-2 border-t-esg6 absolute mt-[120px]"></div>
        <div class="flex flex-col md:flex-row gap-10 mt-14 justify-between relative">
            <div class="">
                <div class="flex flex-col item-center text-center">
                    <div class="grid justify-center">
                        <div class="bg-esg6 p-4 rounded-full w-32 h-32 grid place-content-center">
                            @include(tenant()->views .'icons.network')
                        </div>
                    </div>
                    <div class="pt-8 text-2xl font-bold text-esg8">
                        {{ __('Agregue') }}
                    </div>
                </div>
            </div>

            <div class="">
                <div class="flex flex-col text-center">
                    <div class="grid justify-center">
                        <div class="bg-esg6 p-4 rounded-full w-32 h-32 grid place-content-center">
                            @include(tenant()->views .'icons.folder')
                        </div>
                    </div>
                    <div class="pt-8 text-2xl font-bold text-esg8">
                        {{ __('Organize') }}
                    </div>
                </div>
            </div>

            <div class="">
                <div class="flex flex-col text-center">
                    <div class="grid justify-center">
                        <div class="bg-esg6 p-4 rounded-full w-32 h-32 grid place-content-center">
                            @include(tenant()->views .'icons.search')
                        </div>
                    </div>
                    <div class="pt-8 text-2xl font-bold text-esg8">
                        {{ __('Analise') }}
                    </div>
                </div>
            </div>

            <div class="">
                <div class="flex flex-col text-center">
                    <div class="grid justify-center">
                        <div class="bg-esg6 p-4 rounded-full w-32 h-32 grid place-content-center">
                            @include(tenant()->views .'icons.union', ['width' => 45, 'height' => 60])
                        </div>
                    </div>
                    <div class="pt-8 text-2xl font-bold text-esg8">
                        {{ __('Relate') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="grid justify-center mt-24">
            <button class="bg-esg1 text-esg4 w-72 p-6 rounded-lg font-semibold text-2xl uppercase" @click="window.location.href = '{!! $questionnaireUrl !!}'"> {{ $hasQuestionnaires ? 'CONTINUAR' : 'COMEÇAR' }} @include(tenant()->views .'icons.right-arrow', ['class' => 'inline-block ml-1 -mt-1'])</button>
        </div>
    </div>

    <div class="mt-24 mb-20 bg-esg6 p-10 rounded-xl">
        <div class="text-4xl font-bold text-esg4 text-center"> {{ __('Contacte-nos') }} </div>
        <div class="text-2xl font-normal text-esg4 text-center mt-6"> {{ __('Ajudamos a acelerar a sua jornada') }} <span class="font-bold">{{ __('ESG') }}</span> </div>

        <div class="w-full mt-8">
            <div class="text-center text-esg4">
                <span class="inline-block"> @include(tenant()->views .'icons.email', ['color' => color(4), 'height' => 16]) </span>
                <span class="mr-5">empresasturismo360@turismodeportugal.pt</span>
                <span class="ml-5 inline-block"> @include(tenant()->views .'icons.phone', ['color' => color(4), 'height' => 16]) </span>
                +351 808 209 209
            </div>
        </div>
    </div>
</div>
@endsection
