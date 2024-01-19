@extends('tenant.home')

@section('content')
    <div class="p-3 pt-[107px]">
        <h2 class="text-esg27 text-center text-3xl font-semibold">{{ __('Bem Vind@!') }}</h2>
        <h2 class="text-esg27 pt-[26px] text-center text-3xl font-semibold">{{ __('Sua jornada pela sustentabilidade começa conosco!') }}</h2>
        <hr class="border-esg5 mt-[26px]">
    </div>

    <div class="text-esg7 divide-esg5 grid grid-cols-2 text-lg font-light sm:divide-x">
        <div class="col-span-2 sm:col-span-1 text-right w-50 p-[17px] @if (app()->isLocale('en') || app()->isLocale('pt-PT') || app()->isLocale('pt-BR')) leading-8 @elseif(app()->isLocale('fr') || app()->isLocale('es')) leading-9 @endif">
            <p class="text-esg27">
                <span class="font-bold">Inovabilidade</span>, conceito criado a partir da junção da inovação <br>e sustentabilidade, é o tema de 2022 do BConnected.
            </p>
            <p class="text-esg27">
                O <span class="font-bold">Grupo BITTENCOURT</span> pretende sensibilizar e compartilhar <br>com os seus participantes o que se tem feito, <br>as melhores práticas e ferramentas que podem <br>ajudar as empresas na sua jornada de Inovabilidade.
            </p>
        </div>

        <div class="w-50 col-span-2 p-[17px] text-left sm:col-span-1">
            <p class="text-esg27">
                É nesse sentido, que o <span class="font-bold">Grupo BITTENCOURT</span> em parceria com <br>a <span class="font-bold">C-MORE</span> tem o prazer de lhe apresentar o software <br><span class="text-bold">ESG Maturity</span>.
            </p>
            <p class="mt-5 font-medium">
                Uma ferramenta de gestão que ajuda, simplifica e facilita <br>o percurso das empresas na sustentabilidade.
            </p>
        </div>
    </div>


    <div class="text-esg27 px-3 py-[31px] text-2xl text-center font-semibold">Traduzimos Sustentabilidade em Negócio</div>
    <div class=""><hr class="border-esg5 mt-[26px]"></div>
    <div class="text-esg27 py-10 text-center text-3xl">Comece aqui a sua jornada!</div>
    <div class="mb-[100px] text-center"><a href="{{ $questionnaireUrl }}" class="text-esg27 font-inter bg-esg5 rounded-lg px-6 py-3 text-lg font-bold uppercase">Inicie o questionário</a></div>
@endsection
