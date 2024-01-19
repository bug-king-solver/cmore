@extends(customInclude('layouts.tenant'), ['mainBgColor' => 'bg-esg15'])

@section('content')
<div class="px-4 md:px-0">
    <div class="bg-esg6 h-screen max-h-[1000px] flex left-[50%] -ml-[50vw] w-screen relative items-center justify-center bg-[url('/images/customizations/tenantfa385a88-d089-434e-917f-9888c24cd400/home-bg.jpg')]">

        <div class="w-full mx-auto max-w-5xl pt-8 px-4 sm:px-6 lg:px-8 absolute z-90">
            <div class="grid justify-items-center font-encodesans text-esg4">
                <div class="text-center mt-24">
                    <p class="text-4xl font-bold">{{ __('Jornada 2030 – Empresas pela sustentabilidade') }}</p>

                    <p class="text-[22px] font-semibold mt-8 text-justify">{{ __('Ferramenta de análise e monitorização do grau de maturidade em sustentabilidade e de gestão de indicadores ambientais, sociais e de governança (ESG) que apoia as empresas na sua jornada para a sustentabilidade.') }}</p>
                </div>

                <div class="border-t-2 border-esg5 w-full mt-8 text-center mb-20">
                    <p class="text-[22px] font-bold mt-9">{{ __('Comece aqui a sua jornada') }}</p>

                    <button class="bg-esg5 w-60 h-11 rounded-lg font-bold text-base mt-9 uppercase" @click="window.location.href = '{!! $questionnaireUrl !!}'"> {{ __('Iniciar questionário') }} </button>
                </div>
            </div>
        </div>

    </div>

    <div class="font-encodesans">
        <div class="max-w-5xl mx-auto">
            <p class="text-2xl font-bold text-esg5 text-center mt-12"> {{ __('O que poderá encontrar nesta plataforma?') }} </p>

            <div class="grid grid-cols-1 md:grid-cols-3 md:gap-9 mt-10">
                <div x-on:click="window.location='{{ route('tenant.questionnaires.panel') }}'" class="bg-esg4 border border-esg4 duration-500 hover:bg-[#1F9C8A]/[0.07] hover:border hover:border-esg6 rounded-2xl p-4 drop-shadow-md mb-8 md:mb-0 cursor-pointer">
                    <div class="grid justify-items-center">
                        @include('icons.dashboard.question', ['color' => color(6)])
                        <p class="text-center text-esg8 text-lg font-semibold mt-4"> {{ __('Questionário') }} </p>
                    </div>

                    <div class="mt-4 font-normal text-esg8 text-sm">
                        <p> {{ __('Com o preenchimento do questionário poderá:') }} </p>

                        <div class="flex mt-4">
                            <div class="text-esg5 text-2xl">
                                <span class="w-2 h-2 relative -top-2.5 rounded-full inline-block bg-esg5 text-esg5"></span>
                            </div>
                            <div class="pl-3 inline-block">{{ __('Analisar as práticas de gestão da sustentabilidade') }}</div>
                        </div>

                        <div class="flex mt-4">
                            <div class="text-esg5 text-2xl">
                                <span class="w-2 h-2 relative -top-2.5 rounded-full inline-block bg-esg5 text-esg5"></span>
                            </div>
                            <div class="pl-3 inline-block">{{ __('Analisar o desempenho em indicadores ESG') }}</div>
                        </div>

                        <p class="mt-4"> {{ __('O questionário poderá ser preenchido regularmente, sendo que o  histórico permanecerá disponível.') }} </p>
                    </div>
                </div>

                <div x-on:click="window.location='{{ route('tenant.dashboard') }}'" class="bg-esg4 border border-esg4 duration-500 hover:bg-[#1F9C8A]/[0.07] hover:border hover:border-esg6 rounded-2xl p-4 drop-shadow-md mb-8 md:mb-0 cursor-pointer">
                    <div class="grid justify-items-center">
                        @include('icons.dashboard.panel', ['color' => color(6)])
                        <p class="text-center text-esg8 text-lg font-semibold mt-4"> {{ __('Painel geral') }} </p>
                    </div>

                    <div class="mt-4 font-normal text-esg8 text-sm">
                        <p> {{ __('Após preenchimento do questionário, o painel geral permitirá:') }} </p>

                        <div class="flex mt-4">
                            <div class="text-esg5 text-2xl">
                                <span class="w-2 h-2 relative -top-2.5 rounded-full inline-block bg-esg5 text-esg5"></span>
                            </div>
                            <div class="pl-3 inline-block">{{ __('Consultar o grau de maturidade em sustentabilidade') }}</div>
                        </div>

                        <div class="flex mt-4">
                            <div class="text-esg5 text-2xl">
                                <span class="w-2 h-2 relative -top-2.5 rounded-full inline-block bg-esg5 text-esg5"></span>
                            </div>
                            <div class="pl-3 inline-block">{{ __('Monitorizar indicadores ESG') }}</div>
                        </div>

                        <div class="flex mt-4">
                            <div class="text-esg5 text-2xl">
                                <span class="w-2 h-2 relative -top-2.5 rounded-full inline-block bg-esg5 text-esg5"></span>
                            </div>
                            <div class="pl-3 inline-block">{{ __('Consultar uma lista de ações prioritárias, mapeada especificamente para a empresa (plano de ação)') }}</div>
                        </div>

                    </div>
                </div>

                <div x-on:click="window.location='{{ route('tenant.library.index') }}'" class="bg-esg4 border border-esg4 duration-500 hover:bg-[#1F9C8A]/[0.07] hover:border hover:border-esg6 rounded-2xl p-4 drop-shadow-md mb-8 md:mb-0 cursor-pointer">
                    <div class="grid justify-items-center">
                        @include('icons.dashboard.book', ['color' => color(6)])
                        <p class="text-center text-esg8 text-lg font-semibold mt-4"> {{ __('Biblioteca') }} </p>
                    </div>

                    <div class="mt-4 font-normal text-esg8 text-sm">
                        <p> {{ __('Consulte conteúdos variados:') }} </p>

                        <div class="flex mt-4">
                            <div class="text-esg5 text-2xl">
                                <span class="w-2 h-2 relative -top-2.5 rounded-full inline-block bg-esg5 text-esg5"></span>
                            </div>
                            <div class="pl-3 inline-block">{{ __('Informações sobre a Jornada 2030 e a Carta de Princípios do BCSD Portugal') }}</div>
                        </div>

                        <div class="flex mt-4">
                            <div class="text-esg5 text-2xl">
                                <span class="w-2 h-2 relative -top-2.5 rounded-full inline-block bg-esg5 text-esg5"></span>
                            </div>
                            <div class="pl-3 inline-block">{{ __('Referências e exemplos práticos de apoio à transição para a sustentabilidade e ao preenchimento do questionário') }}</div>
                        </div>

                        <div class="flex mt-4">
                            <div class="text-esg5 text-2xl">
                                <span class="w-2 h-2 relative -top-2.5 rounded-full inline-block bg-esg5 text-esg5"></span>
                            </div>
                            <div class="pl-3 inline-block">{{ __('Standards internacionais de referência (ex.: ODS, GRI, UN Global Compact)') }}</div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="font-encodesans mt-20 mb-24">
        <div class="max-w-5xl mx-auto bg-[#1F9C8A]/[0.13] rounded-2xl py-10">
            <p class="text-2xl font-bold text-esg6 text-center "> {{ __('Como surgiu esta ferramenta?') }} </p>

            <p class="text-xl font-normal text-esg8 mt-8 md:px-10 text-justify"> {{ __('Esta ferramenta surge para apoiar a implementação da Jornada 2030 – o instrumento que alinha e demonstra a contribuição das empresas para os Objetivos de Desenvolvimento Sustentável (ODS).') }} </p>

            <p class="text-xl font-normal text-esg8 mt-8 md:px-10 text-justify"> {{ __('A Jornada 2030 operacionaliza a Carta de Princípios do BCSD Portugal. Se pretende ter acesso a todas as funcionalidades desta ferramenta, torne-se signatário da Carta.') }} </p>

            <div class="text-center text-esg4 grid grid-cols-2 gap-9 mt-9">
                <div class="text-right">
                    <a
                        target="_blank"
                        rel="noopener"
                        href="https://bcsdportugal.org/carta-principios/"
                        class="bg-esg6 py-3 px-10 rounded-lg font-bold text-base mt-9 uppercase">
                            {{ __('Assinar a Carta') }}
                    </a>
                </div>
                <div class="text-left">
                    <a
                        target="_blank"
                        rel="noopener"
                        href="https://bcsdportugal.org/wp-content/uploads/2022/05/BCSD-Portugal_Jornada-2030.pdf"
                        class="bg-esg6 py-3 px-10 rounded-lg font-bold text-base mt-9 uppercase">
                            {{ __('jornada 2030') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
