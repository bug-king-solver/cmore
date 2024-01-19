@extends(customInclude('layouts.tenant'), ['title' => __('Dashboard'), 'mainBgColor' => 'bg-esg15'])

@php
$categoryIconUrl = global_asset('images/icons/categories/{id}.svg');
$genderIconUrl = global_asset('images/icons/genders/{id}.svg');
@endphp
@push('body')
    <script nonce="{{ csp_nonce() }}">
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById("plus").onclick = function() {
                document.getElementById("minus").style.display = 'block';
                document.getElementById("plus").style.display = 'none';
                document.getElementById("saiba_mais").style.display = 'block';
            };

            document.getElementById("minus").onclick = function() {
                document.getElementById("plus").style.display = 'block';
                document.getElementById("minus").style.display = 'none';
                document.getElementById("saiba_mais").style.display = 'none';
            };

        });
    </script>
@endpush

@section('content')
    <div class="px-4 lg:px-0">
        <div class="max-w-7xl mx-auto">

            <div class="mb-4 border-b border-gray-200 mt-12">
                <ul class="flex flex-wrap -mb-px text-lg !text-esg8 font-medium text-center" id="myTab" data-tabs-toggle="#myTabContent" role="tablist">
                    <li class="mr-2" role="presentation">
                        <button class="inline-block p-4 rounded-t-lg border-b-2" id="action_plan-tab" data-tabs-target="#action_plan" type="button" role="tab" aria-controls="action_plan" aria-selected="false">{{ __('Posicionamento e Plano de Ação') }}</button>
                    </li>
                    <li class="mr-2" role="presentation">
                        <button class="inline-block p-4 rounded-t-lg border-b-2 border-transparent" id="indicators-tab" data-tabs-target="#indicators" type="button" role="tab" aria-controls="indicators" aria-selected="false">{{ __('Indicadores de Desempenho') }}</button>
                    </li>
                    <li class="mr-2" role="presentation">
                        <button class="inline-block p-4 rounded-t-lg border-b-2 border-transparent" id="goals-tab" data-tabs-target="#goals" type="button" role="tab" aria-controls="goals" aria-selected="false">{{ __('Objetivos Materiais e Metas') }}</button>
                    </li>
                </ul>
            </div>
            <div id="myTabContent">
                <div class="hidden" id="action_plan" role="tabpanel" aria-labelledby="action_plan-tab">

                    <div class="text-center">
                        <h1 class="font-encodesans text-4xl font-bold leading-10 text-esg5 mt-24">{{__('Etapas da Jornada 2030')}}</h1>
                        <p class="font-encodesans text-2xl font-bold leading-10 text-esg8 mt-4 mb-16">{{__('Posicionamento da sua empresa')}}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2">

                        <div class="grid justify-items-center mt-36">
                            <div class="absolute -mt-[80px] ml-[10px]">
                                @include('icons.dashboard.coliderar', ['color' => $position === 'coliderar' ? '#BC7527' : '#C4C4C4'])
                            </div>

                            <div class="absolute -mt-[60px] -ml-[200px]">
                                @include('icons.dashboard.consolidar', ['color' => in_array($position, ['coliderar', 'consolidar']) ? '#D37E48' : '#C4C4C4'])
                            </div>

                            <div class="absolute mt-[25px] -ml-[320px]">
                                @include('icons.dashboard.comunicar', ['color' => in_array($position, ['coliderar', 'consolidar', 'comunicar']) ? '#E4A53C' : '#C4C4C4'])
                            </div>

                            <div class="absolute mt-[130px] -ml-[320px]">
                                @include('icons.dashboard.construir', ['color' => in_array($position, ['coliderar', 'consolidar', 'comunicar', 'construir']) ? '#216470' : '#C4C4C4'])
                            </div>

                            <div class="absolute mt-[205px] -ml-[200px]">
                                @include('icons.dashboard.conhecer', ['color' => in_array($position, ['coliderar', 'consolidar', 'comunicar', 'construir', 'conhecer']) ? '#1F9C8A' : '#C4C4C4'])
                            </div>

                            <div class="absolute mt-[250px] ml-[0px]">
                                @include('icons.dashboard.despertar', ['color' => in_array($position, ['coliderar', 'consolidar', 'comunicar', 'construir', 'conhecer', 'despertar']) ? '#99C1C0' : '#C4C4C4'])
                            </div>
                            <div class="w-[242.67px] h-[242.67px] shadow-md bg-esg4 rounded-full text-center font-encodesans text-lg text-esg8 font-bold">
                                <p class="pt-24">{{ __('Posicionamento ') }}</p>
                                <p> {{ __('da sua Empresa') }} </p>
                            </div>
                        </div>

                        <div class="font-encodesans mt-32 md:mt-0">
                            <div class="flex">
                                <div class="pt-1">
                                    <span class="w-8 h-8 grid items-center rounded-full  bg-esg4 text-esg4 pl-1.5">
                                        <span class="w-5 h-5 rounded-full inline-block @if ($position === 'coliderar') bg-[#BC7527] text-[#BC7527] @else bg-esg11 text-esg11 @endif"></span>
                                    </span>
                                </div>

                                <div class="pl-5">
                                    <p class="font-bold @if ($position === 'coliderar') text-2xl text-[#BC7527] @else text-xl text-esg11 @endif">{{ __('COLIDERAR') }}</p>
                                    <p class="text-base text-esg11">Estabelecer compromissos de longo prazo: Alcançar os objetivos 2030 e definir a ambição 2050</p>
                                </div>
                            </div>

                            <div class="flex mt-5">
                                <div class="pt-1">
                                    <span class="w-8 h-8 grid items-center rounded-full  bg-esg4 text-esg4 pl-1.5">
                                        <span class="w-5 h-5 rounded-full inline-block @if (in_array($position, ['coliderar', 'consolidar'])) bg-[#D37E48] text-[#D37E48] @else bg-esg11 text-esg11 @endif"></span>
                                    </span>
                                </div>

                                <div class="pl-5">
                                    <p class="font-bold @if (in_array($position, ['coliderar', 'consolidar'])) text-2xl text-[#D37E48] @else text-xl text-esg11 @endif">{{ __('CONSOLIDAR') }}</p>
                                    <p class="text-base text-esg11">Monitorizar e atualizar para garantir o progresso e a ambição: Reavaliar a trajetória e reforçar medidas</p>
                                </div>
                            </div>

                            <div class="flex mt-5">
                                <div class="pt-1">
                                    <span class="w-8 h-8 grid items-center rounded-full  bg-esg4 text-esg4 pl-1.5">
                                        <span class="w-5 h-5 rounded-full inline-block @if (in_array($position, ['coliderar', 'consolidar', 'comunicar'])) bg-[#E4A53C] text-[#E4A53C] @else bg-esg11 text-esg11 @endif"></span>
                                    </span>
                                </div>

                                <div class="pl-5">
                                    <p class="font-bold @if (in_array($position, ['coliderar', 'consolidar', 'comunicar'])) text-2xl text-[#E4A53C] @else text-xl text-esg11 @endif">{{ __('COMUNICAR') }}</p>
                                    <p class="text-base text-esg11">Comunicar a jornada: Comunicar compromissos e desempenho e envolver as partes interessadas</p>
                                </div>
                            </div>

                            <div class="flex mt-5">
                                <div class="pt-1">
                                    <span class="w-8 h-8 grid items-center rounded-full  bg-esg4 text-esg4 pl-1.5">
                                        <span class="w-5 h-5 rounded-full inline-block @if (in_array($position, ['coliderar', 'consolidar', 'comunicar', 'construir'])) bg-[#216470] text-[#216470] @else bg-esg11 text-esg11 @endif"></span>
                                    </span>
                                </div>

                                <div class="pl-5">
                                    <p class="font-bold @if (in_array($position, ['coliderar', 'consolidar', 'comunicar', 'construir'])) text-2xl text-[#216470] @else text-xl text-esg11 @endif">{{ __('CONSTRUIR') }}</p>
                                    <p class="text-base text-esg11">Operacionalizar a jornada: Estabelecer objetivos e metas e definir planos de ação</p>
                                </div>
                            </div>

                            <div class="flex mt-5">
                                <div class="pt-1">
                                    <span class="w-8 h-8 grid items-center rounded-full  bg-esg4 text-esg4 pl-1.5">
                                        <span class="w-5 h-5 rounded-full inline-block @if (in_array($position, ['coliderar', 'consolidar', 'comunicar', 'construir', 'conhecer'])) bg-[#1F9C8A] text-[#1F9C8A] @else bg-esg11 text-esg11 @endif"></span>
                                    </span>
                                </div>

                                <div class="pl-5">
                                    <p class="font-bold @if (in_array($position, ['coliderar', 'consolidar', 'comunicar', 'construir', 'conhecer'])) text-2xl text-[#1F9C8A] @else text-xl text-esg11 @endif">{{ __('CONHECER') }}</p>
                                    <p class="text-base text-esg11">Estabelecer a base: Fazer diagnóstico e estabelecer prioridades estratégicas</p>
                                </div>
                            </div>

                            <div class="flex mt-5">
                                <div class="pt-1">
                                    <span class="w-8 h-8 grid items-center rounded-full  bg-esg4 text-esg4 pl-1.5">
                                        <span class="w-5 h-5 rounded-full inline-block @if (in_array($position, ['coliderar', 'consolidar', 'comunicar', 'construir', 'conhecer', 'despertar'])) bg-[#99C1C0] text-[#99C1C0] @else bg-esg11 text-esg11 @endif"></span>
                                    </span>
                                </div>

                                <div class="pl-5">
                                    <p class="font-bold @if (in_array($position, ['coliderar', 'consolidar', 'comunicar', 'construir', 'conhecer', 'despertar'])) text-2xl text-[#99C1C0] @else text-xl text-esg11 @endif">{{ __('DESPERTAR') }}</p>
                                    <p class="text-base text-esg11">Dar os primeiros passos: Compreender a necessidade e as oportunidades da sustentabilidade como estratégia corporativa</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-20 font-encodesans mb-20">
                        <p class="text-xl text-esg8 font-bold"> {{ __('Saiba mais') }} </p>
                        <div class="" id="plus">
                            @include('icons.dashboard.plus', ['class' => 'mt-2 inline-block', 'width' => 30, 'height' => 30])
                        </div>

                        <div class="hidden" id="minus">
                            @include('icons.dashboard.minus', ['class' => 'mt-2 inline-block', 'width' => 28, 'height' => 28])
                        </div>
                    </div>

                    <div id="saiba_mais" class="font-encodesans text-justify text-esg8 text-base font-normal my-14 hidden transition delay-150 duration-300">
                        @if ($position === 'despertar')
                            <p class="text-base font-bold text-[#99C1C0] text-center">Despertar: Os primeiros passos são importantes</p>
                            <p class="mt-5">
                                A sua empresa já começou a realizar algumas ações para iniciar a jornada para a sustentabilidade, no entanto, tem potencial para avançar mais.
                            </p>

                            <p class="mt-5">
                                Para conhecer os próximos passos, recomendamos explorar os recursos disponibilizados na <a href="{{ route('tenant.library.index') }}">Biblioteca</a> desta plataforma, nomeadamente os que integram as categorias “Referências” e “Aplicação prática” das pastas dos Objetivos da Jornada 2030. Estes recursos foram sistematizados de forma a apoiar as empresas no seu percurso para a sustentabilidade, em cada um dos 20 objetivos que compõem a Jornada 2030.
                            </p>

                            <p class="mt-5">
                                Se tiver interesse em realizar formação, que também pode apoiar este percurso, recomendamos o curso <a href="https://elearning.bcsdportugal.org/course/view.php?id=73" class="text-esg6">“Beginners”</a> do BCSD Portugal. Este programa é dirigido a empresas interessadas em começar a integrar os princípios da sustentabilidade nos seus negócios e operações, para gestão de risco e otimização da cadeia de valor. A oferta formativa do BCSD Portugal oferece ainda formações à medida.
                            </p>
                        @endif

                        @if ($position === 'conhecer')
                            <p class="text-base font-bold text-[#1F9C8A] text-center mt-10">Etapa “Conhecer”: Estabelecer a base é fundamental</p>
                            <p class="mt-5">
                                A sua empresa está a conhecer os aspetos chave para a integração da sustentabilidade na sua estratégia empresarial. Nesta etapa, são definidas as prioridades estratégicas no âmbito da sustentabilidade para a organização, sendo realizadas as seguintes ações principais: atribuição de responsabilidades pela gestão da sustentabilidade, mapeamento de stakeholders, diagnóstico interno, análise de materialidade e análise da cadeia de valor.
                            </p>
                            <p class="mt-5">
                                Tendo por base as prioridades estratégicas de sustentabilidade definidas, nesta etapa são ainda identificados os instrumentos e práticas de gestão da sustentabilidade que permitam a sua operacionalização.
                            </p>
                            <p class="mt-5">
                                Concluídos estes passos, estará estabelecida a base do conhecimento, visando a etapa “construir” da Jornada 2030. Nesta etapa, são desenvolvidos os planos de ação, estabelecidas metas SMART, utilizados referenciais/diretrizes e estabelecidas parcerias chave.
                            </p>
                            <p class="mt-5">
                                Para conhecer os próximos passos, recomendamos explorar os recursos disponibilizados na <a href="{{ route('tenant.library.index') }}">Biblioteca</a> desta plataforma, nomeadamente os que integram as categorias “Referências” e “Aplicação prática” das pastas dos Objetivos da Jornada 2030. Estes recursos foram sistematizados de forma a apoiar as empresas no seu percurso para a sustentabilidade, em cada um dos 20 objetivos que compõem a Jornada 2030
                            </p>
                            <p class="mt-5">
                                Se tiver interesse em realizar formação, que também pode apoiar este percurso, recomendamos o curso <a href="https://elearning.bcsdportugal.org/course/view.php?id=73" class="text-esg6">“Beginners”</a> do BCSD Portugal para a consolidação de conhecimentos base. A oferta formativa do BCSD Portugal oferece ainda formações à medida.
                            </p>
                        @endif

                        @if ($position === 'construir')
                            <p class="text-base font-bold text-[#216470] text-center mt-10">Etapa “Construir”: Operacionalizar a jornada</p>
                            <p class="mt-5">
                                A sua empresa encontra-se a desenvolver um plano de ação detalhado para a operacionalização das prioridades estratégicas de sustentabilidade estabelecidas na etapa “Conhecer”
                            </p>
                            <p class="mt-5">
                                Na etapa “Construir”, são estabelecidos os objetivos e metas para avançar na sua jornada para a sustentabilidade e é definido o plano de ação com indicadores de monitorização, a respetiva calendarização e alocando responsabilidades. São, também, implementados instrumentos e práticas de gestão da sustentabilidade com o apoio de referenciais e diretrizes reconhecidos, e são estabelecidas parcerias chave.
                            </p>
                            <p class="mt-5">
                                Concluídos estes passos, está em curso a operacionalização da jornada, visando a etapa “Comunicar” da Jornada 2030. Nesta etapa, são identificadas as estratégias de comunicação que permitam envolver stakeholders através de ações como auscultação de stakeholders e formação de colaboradores, e dar a conhecer o desempenho em sustentabilidade da empresa, nomeadamente através da publicação do desempenho da empresa relativamente aos seus temas materiais no relatório de sustentabilidade.
                            </p>
                            <p class="mt-5">
                                Para conhecer os próximos passos, recomendamos explorar os recursos disponibilizados na <a href="{{ route('tenant.library.index') }}">Biblioteca</a> desta plataforma, nomeadamente os que integram as categorias “Referências” e “Aplicação prática” das pastas dos Objetivos da Jornada 2030. Estes recursos foram sistematizados de forma a apoiar as empresas no seu percurso para a sustentabilidade, em cada um dos 20 objetivos que compõem a Jornada 2030.
                            </p>
                            <p class="mt-5">
                                Se tiver interesse em realizar formação, que também pode apoiar este percurso, recomendamos o curso <a href="https://elearning.bcsdportugal.org/course/view.php?id=73" class="text-esg6">“Beginners”</a> do BCSD Portugal para a consolidação de conhecimentos base. A oferta formativa do BCSD Portugal oferece ainda formações à medida.
                            </p>
                        @endif

                        @if ($position === 'comunicar')
                            <p class="text-base font-bold text-[#E4A53C] text-center mt-10">Etapa “Comunicar”: Comunicar a jornada para as partes interessadas</p>
                            <p class="mt-5">
                                A sua empresa realiza ações voltadas para a comunicação interna e externa alinhadas com as metas estabelecidas e o seu desempenho em sustentabilidade, nomeadamente através da publicação do relatório de sustentabilidade, capacitação de colaboradores e subscrição de iniciativas/princípios/compromissos específicos alinhados com os temas materiais identificados.
                            </p>
                            <p class="mt-5">
                                Na etapa “Comunicar”, a empresa desenvolve também uma estratégia de envolvimento com stakeholders e realiza ações de auscultação com os grupos identificados. Adicionalmente, a empresa exerce um papel de influência na sua cadeia de valor através de ações dirigidas a estes stakeholders.
                            </p>
                            <p class="mt-5">
                                Concluídos estes passos, está assegurada a comunicação interna e externa para garantir a transparência, envolvimento e capacitação dos seus stakeholders, visando a etapa “Consolidar” da Jornada 2030. Nesta etapa, são estabelecidos planos de reavaliação da trajetória, nomeadamente através da monitorização do seu progresso, de forma a garantir a consecução das metas estabelecidas e reforçar medidas que permitam aumentar a ambição rumo a 2030.
                            </p>
                            <p class="mt-5">
                                Para conhecer os próximos passos, recomendamos explorar os recursos disponibilizados na <a href="{{ route('tenant.library.index') }}">Biblioteca</a> desta plataforma, nomeadamente os que integram as categorias “Referências” e “Aplicação prática” das pastas dos Objetivos da Jornada 2030. Estes recursos foram sistematizados de forma a apoiar as empresas no seu percurso para a sustentabilidade, em cada um dos 20 objetivos que compõem a Jornada 2030.
                            </p>
                            <p class="mt-5">
                                Se tiver interesse em realizar formação, que também pode apoiar este percurso, recomendamos o curso <a href="https://elearning.bcsdportugal.org/course/view.php?id=74" class="text-esg6">“Achievers”</a> do BCSD Portugal, que foi concebido com o objetivo de apoiar a continuidade da jornada das empresas para a sustentabilidade e proporcionar acesso ao conhecimento para a sua permanente evolução. A oferta formativa do BCSD Portugal oferece ainda formações à medida.
                            </p>
                        @endif

                        @if ($position === 'consolidar')
                            <p class="text-base font-bold text-[#D37E48] text-center mt-10">Etapa “Consolidar”: Monitorizar e atualizar para garantir o progresso e a ambiçã</p>
                            <p class="mt-5">
                                A sua empresa encontra-se num estágio avançado de conhecimento e implementação dos planos de ação que endereçam os temas materiais identificados pela empresa. Também realiza ações de comunicação interna e externa para garantir a transparência, envolvimento e capacitação dos seus stakeholders
                            </p>
                            <p class="mt-5">
                                Na etapa “Consolidar”, a empresa verifica o nível de implementação dos planos de ação e identifica necessidades de melhoria e oportunidades de inovação, através do desenvolvimento de ações relacionadas à atualização do diagnóstico interno, envolvimento de stakeholders, estabelecimento de novas parcerias chave e atualização de compromissos.
                            </p>
                            <p class="mt-5">
                                Estando em curso ações de revisão e atualização regulares das prioridades estratégicas de sustentabilidade e respetivos planos de ação, estão estabelecidas as condições para a etapa “Coliderar” da Jornada 2030. Nesta etapa, a empresa continua a implementar instrumentos de gestão que garantam a prossecução dos seus objetivos 2030 e, com base nesta concretização, estabelece metas de longo prazo, com vista a 2050.
                            </p>
                            <p class="mt-5">
                                Para conhecer os próximos passos, recomendamos explorar os recursos disponibilizados na <a href="{{ route('tenant.library.index') }}">Biblioteca</a> desta plataforma, nomeadamente os que integram as categorias “Referências” e “Aplicação prática” das pastas dos Objetivos da Jornada 2030. Estes recursos foram sistematizados de forma a apoiar as empresas no seu percurso para a sustentabilidade, em cada um dos 20 objetivos que compõem a Jornada 2030.
                            </p>
                            <p class="mt-5">
                                Se tiver interesse em realizar formação, que também pode apoiar este percurso, sugerimos que explore o curso <a href="https://elearning.bcsdportugal.org/course/view.php?id=74" class="text-esg6">“Achievers”</a> do BCSD Portugal, que foi concebido com o objetivo de apoiar a continuidade da jornada das empresas para a sustentabilidade e proporcionar acesso ao conhecimento para a sua permanente evolução. A oferta formativa do BCSD Portugal oferece ainda formações à medida.
                            </p>
                        @endif

                        @if ($position === 'coliderar')
                            <p class="text-base font-bold text-[#BC7527] text-center mt-10">Etapa “Coliderar”: Estabelecer compromissos de longo prazo – ambição 205</p>
                            <p class="mt-5">
                                Com um conhecimento sólido sobre os temas materiais da empresa e um plano de ação alicerçado em revisões e atualizações constantes para garantir o cumprimento das metas estabelecidas, a sua empresa adota as ferramentas de gestão necessárias para garantir a consecução das suas metas de sustentabilidade rumo a 2030.
                            </p>
                            <p class="mt-5">
                                Adicionalmente, a sua empresa está em condições de traçar metas de longo-prazo, bem como adotar medidas de gestão rumo a 2050.
                            </p>
                            <p class="mt-5">
                                Na etapa “Coliderar”, espera-se que a empresa integre, cada vez mais profundamente, a sustentabilidade no seu modelo de negócio, nomeadamente ao utilizar recursos tecnológicos inovadores e/ou ao estabelecer ou transformar totalmente o seu modelo de negócio segundo critérios de sustentabilidade (ambientais, sociais e de governança).
                            </p>
                            <p class="mt-5">
                                Para conhecer os próximos passos, recomendamos explorar os recursos disponibilizados na <a href="{{ route('tenant.library.index') }}">Biblioteca</a> desta plataforma, nomeadamente os que integram as categorias “Referências” e “Aplicação prática” das pastas dos Objetivos da Jornada 2030. Estes recursos foram sistematizados de forma a apoiar as empresas no seu percurso para a sustentabilidade, em cada um dos 20 objetivos que compõem a Jornada 2030.
                            </p>
                        @endif

                    </div>
                </div>


                <div class="hidden" id="indicators" role="tabpanel" aria-labelledby="indicators-tab">
                    <p class="text-center text-esg5 font-bold text-xl">
                        <a href="https://bcsdportugal.org/carta-principios/" target="_blank">
                            Aderir à Carta de Princípios do BCSD Portugal para aceder a todas as funcionalidades desta ferramenta
                        </a>
                    </p>
                </div>

                <div class="hidden" id="goals" role="tabpanel" aria-labelledby="goals-tab">
                    <p class="text-center text-esg5 font-bold text-xl">
                        <a href="https://bcsdportugal.org/carta-principios/" target="_blank">
                            Aderir à Carta de Princípios do BCSD Portugal para aceder a todas as funcionalidades desta ferramenta
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

@endsection
