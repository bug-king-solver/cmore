@extends('tenant.home')

@section('content')
    @if (app()->isLocale('pt-PT') || app()->isLocale('pt-BR'))
        <div class="p-3 pt-[107px]">
            <h2 class="text-esg27 text-center text-3xl font-semibold">Bem Vind@ ao ESG Maturity!</h2>
            <hr class="border-esg5 mt-[26px]">
            <h2 class="text-esg27 pt-[26px] text-center text-3xl font-semibold">Qual o impacto que a adopção de medidas <br>ESG terá no meu negócio?</h2>
        </div>

        <div class="text-esg7 text-lg text-justify font-light mt-4 w-1/2 m-auto">

            <p class="text-esg27 mt-5">
                No contexto actual, esta questão está no topo da preocupação dos Gestores. E é essa questão que a Flexdeal, em colaboração com a C-More, querem ver respondida.
            </p>

            <p class="text-esg27 mt-5 font-bold">
                Mas não o podemos fazer sozinhos!
            </p>

            <p class="text-esg27 mt-5">
                Agradecemos a Sua ajuda através do preenchimento de um questionário, que tem como objetivo analisar a relação existente entre as áreas financeira e de sustentabilidade social e ambiental de uma organização.
            </p>

            <p class="text-esg27 mt-5">
                A sua colaboração para este estudo permitir-lhe-á obter o posicionamento da sua empresa face às restantes empresas da amostra, em variáveis chave nas áreas financeira e ESG.
            </p>
        </div>


        <div class="py-10"><hr class="border-esg5 mt-[26px]"></div>
        <div class="text-esg27 px-3 pb-16 pt-4 text-2xl text-center font-semibold">O seu percurso pela Sustentabilidade começa connosco!</div>
        <div class="mb-16 text-center"><a href="{{ $questionnaireUrl }}" class="text-esg27 font-inter bg-esg5 rounded-lg px-6 py-3 text-lg font-bold uppercase">Iniciar Avaliação ESG</a></div>

        <div class="mb-[100px] text-esg27 px-3 pb-16 pt-4 text-2xl text-center font-semibold">Traduzimos sustentabilidade em negócio</div>
    @elseif (app()->isLocale('en'))
        <div class="p-3 pt-[107px]">
            <h2 class="text-esg27 text-center text-3xl font-semibold">Welcome to ESG Maturity!</h2>
            <hr class="border-esg5 mt-[26px]">
            <h2 class="text-esg27 pt-[26px] text-center text-3xl font-semibold">How will the adoption of ESG measures impact my business?</h2>
        </div>

        <div class="text-esg7 text-lg text-justify font-light mt-4 w-1/2 m-auto">

            <p class="text-esg27 mt-5">
                In the current environment, this question is at the top of Managers' concerns. And it's this question that Flexdeal, together with C-More, wants to see answered.
            </p>

            <p class="text-esg27 mt-5 font-bold">
                But we cannot do it alone!
            </p>

            <p class="text-esg27 mt-5">
                We would appreciate your help by filling in a survey, aiming to assess the relationship between the financial areas and the social and environmental sustainability of an organization.
            </p>

            <p class="text-esg27 mt-5">
                Your collaboration in this study will allow you to obtain the positioning of your company against the other companies in the sample, in key variables in the financial and ESG areas.
            </p>
        </div>


        <div class="py-10"><hr class="border-esg5 mt-[26px]"></div>
        <div class="text-esg27 px-3 pb-16 pt-4 text-2xl text-center font-semibold">Your journey towards Sustainability begins with us!</div>
        <div class="mb-16 text-center"><a href="{{ $questionnaireUrl }}" class="text-esg27 font-inter bg-esg5 rounded-lg px-6 py-3 text-lg font-bold uppercase">BEGIN ESG ASSESSMENT</a></div>

        <div class="mb-[100px] text-esg27 px-3 pb-16 pt-4 text-2xl text-center font-semibold">We translate sustainability into business</div>
    @elseif (app()->isLocale('es'))
        <div class="p-3 pt-[107px]">
            <h2 class="text-esg27 text-center text-3xl font-semibold">¡Bienvenido a ESG Maturity!</h2>
            <hr class="border-esg5 mt-[26px]">
            <h2 class="text-esg27 pt-[26px] text-center text-3xl font-semibold">¿Qué impacto tendrá la adopción de medidas ESG en mi empresa?</h2>
        </div>

        <div class="text-esg7 text-lg text-justify font-light mt-4 w-1/2 m-auto">

            <p class="text-esg27 mt-5">
                En el contexto actual, esta cuestión es la que más preocupa a los gestores. Y es esta pregunta la que Flexdeal, en colaboración con C-More, quiere ver respondida.
            </p>

            <p class="text-esg27 mt-5 font-bold">
                Pero no podemos hacerlo solos.
            </p>

            <p class="text-esg27 mt-5">
                Le agradeceríamos que nos ayudara rellenando un cuestionario, cuyo objetivo es analizar la relación existente entre las áreas financieras y la sostenibilidad social y medioambiental de una organización.
            </p>

            <p class="text-esg27 mt-5">
                Su colaboración en este estudio le permitirá obtener el posicionamiento de su empresa en relación con el resto de empresas de la muestra, en variables clave en el ámbito financiero y ESG.
            </p>
        </div>


        <div class="py-10"><hr class="border-esg5 mt-[26px]"></div>
        <div class="text-esg27 px-3 pb-16 pt-4 text-2xl text-center font-semibold">Su viaje hacia la sostenibilidad comienza con nosotros.</div>
        <div class="mb-16 text-center"><a href="{{ $questionnaireUrl }}" class="text-esg27 font-inter bg-esg5 rounded-lg px-6 py-3 text-lg font-bold uppercase">INICIAR LA EVALUACIÓN DE LA ESG</a></div>

        <div class="mb-[100px] text-esg27 px-3 pb-16 pt-4 text-2xl text-center font-semibold">Traducimos la sostenibilidad en negocio</div>
    @elseif (app()->isLocale('fr'))
        <div class="p-3 pt-[107px]">
            <h2 class="text-esg27 text-center text-3xl font-semibold">Bienvenue à ESG Maturity!</h2>
            <hr class="border-esg5 mt-[26px]">
            <h2 class="text-esg27 pt-[26px] text-center text-3xl font-semibold">Quel impact l'adoption de mesures ESG aura-t-elle sur mon entreprise?</h2>
        </div>

        <div class="text-esg7 text-lg text-justify font-light mt-4 w-1/2 m-auto">

            <p class="text-esg27 mt-5">
                Dans le contexte actuel, cette question est en tête des préoccupations des managers. Et c'est à cette question que Flexdeal, en collaboration avec C-More, veut voir répondu.
            </p>

            <p class="text-esg27 mt-5 font-bold">
                Mais nous ne pouvons pas le faire seuls!
            </p>

            <p class="text-esg27 mt-5">
                Nous vous serions reconnaissants de nous aider en remplissant un questionnaire, qui vise à analyser la relation existante entre les domaines financiers et la durabilité sociale et environnementale d'une organisation.
            </p>

            <p class="text-esg27 mt-5">
                Votre collaboration à cette étude vous permettra d'obtenir le positionnement de votre entreprise par rapport aux autres entreprises de l'échantillon, sur des variables clés dans les domaines financier et ESG.
            </p>
        </div>


        <div class="py-10"><hr class="border-esg5 mt-[26px]"></div>
        <div class="text-esg27 px-3 pb-16 pt-4 text-2xl text-center font-semibold">Votre voyage vers la durabilité commence avec nous!</div>
        <div class="mb-16 text-center"><a href="{{ $questionnaireUrl }}" class="text-esg27 font-inter bg-esg5 rounded-lg px-6 py-3 text-lg font-bold uppercase">COMMENCER L'ÉVALUATION DE L'ESG</a></div>

        <div class="mb-[100px] text-esg27 px-3 pb-16 pt-4 text-2xl text-center font-semibold">Nous traduisons la durabilité en affaires</div>
    @endif
@endsection
