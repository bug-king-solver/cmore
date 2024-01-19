@extends('tenant.home')

@section('content')
    @if (app()->isLocale('pt-PT') || app()->isLocale('pt-BR'))
        <div class="p-3 pt-[107px]">
            <h2 class="text-esg27 text-center text-3xl font-semibold">Bem Vind@ ao ESG Maturity!</h2>
            <hr class="border-esg5 mt-[26px]">
            <h2 class="text-esg27 pt-[26px] text-center text-3xl font-semibold">Parabéns, este poderá ser um passo importante na jornada para a sustentabilidade da sua empresa!</h2>
        </div>

        <div class="text-esg7 divide-esg5 grid grid-cols-2 text-lg font-light sm:divide-x mt-4">
            <div class="col-span-2 sm:col-span-1 text-right w-50 p-[17px] @if (app()->isLocale('en') || app()->isLocale('pt-PT') || app()->isLocale('pt-BR')) leading-8 @elseif(app()->isLocale('fr') || app()->isLocale('es')) leading-9 @endif">
                <p class="text-esg27">
                    Traduzir sustentabilidade em negócio é o mote do evento
                    <br>da <span class="font-bold text-esg28">Flexdeal - Exame</span>. Estamos cientes da crescente
                    <br>importância e impacto dos critérios ESG na vida das
                    <br>empresas, mas acreditamos que tal só fará sentido
                    <br> respeitando-se duas premissas chave: ser simples e fácil
                    <br>de entender; trazer benefícios tangíveis e fáceis de
                    <br>quantificar para as empresas.
                </p>

                <p class="text-esg27 mt-5">
                    A <span class="font-bold text-esg28">Flexdeal</span> pretende apoiar as PMEs na transição ESG,
                    <br>através da sensibilização, partilha de conhecimento e das
                    <br>melhores práticas e ferramentas de gestão.
                </p>
            </div>

            <div class="w-50 col-span-2 p-[17px] text-left sm:col-span-1">
                <p class="text-esg27">
                    Nesse sentido, a <span class="font-bold text-esg28">Flexdeal e as Associações signatárias do</span>
                        <br><span class="font-bold text-esg28">protocolo em parceria com a C-MORE</span> têm o prazer de lhe
                        <br>apresentar o software <span class="font-bold text-esg28">ESG Maturity</span>: uma ferramenta de
                        <br>gestão que simplifica a sustentabilidade para PMEs,
                        <br>tornando-a acessível a tod@s.
                </p>

                <p class="text-esg27 mt-5">
                    Terá desde já a oportunidade de realizar uma primeira
                    <br>avaliação ESG da sua empresa de forma gratuita, clicando no
                    <br>botão embaixo. Este poderá ser o seu ponto de partida e
                    <br>nós podemos apoiá-lo nessa jornada.
                </p>
            </div>
        </div>


        <div class="py-10"><hr class="border-esg5 mt-[26px]"></div>
        <div class="text-esg27 px-3 pb-16 pt-4 text-2xl text-center font-semibold">Descodificamos o ESG para PMEs</div>
        <div class="mb-[100px] text-center"><a href="{{ $questionnaireUrl }}" class="text-esg27 font-inter bg-esg5 rounded-lg px-6 py-3 text-lg font-bold uppercase">Iniciar Avaliação ESG</a></div>
    @elseif (app()->isLocale('en'))
        <div class="p-3 pt-[107px]">
            <h2 class="text-esg27 text-center text-3xl font-semibold">Welcome to ESG Maturity</h2>
            <hr class="border-esg5 mt-[26px]">
            <h2 class="text-esg27 pt-[26px] text-center text-3xl font-semibold">Congratulations, this can be an important step in your company's journey towards sustainability!</h2>
        </div>

        <div class="text-esg7 divide-esg5 grid grid-cols-2 text-lg font-light sm:divide-x mt-4">
            <div class="col-span-2 sm:col-span-1 text-right w-50 p-[17px] @if (app()->isLocale('en') || app()->isLocale('pt-PT') || app()->isLocale('pt-BR')) leading-8 @elseif(app()->isLocale('fr') || app()->isLocale('es')) leading-9 @endif">
                <p class="text-esg27">
                    Translating sustainability into business is the motto<br>
                    of <span class="font-bold text-esg28">Flexdeal - Exame</span> event. We are aware of the
                    <br>growing importance and impact of the ESG criteria
                    <br>in the life of companies, but we believe that this will
                    <br>only make sense if it respects two key premises: be
                    <br>simple and easy to understand; bring tangible and
                    <br>easy to quantify benefits to companies.
                </p>

                <p class="text-esg27 mt-5">
                    <span class="font-bold text-esg28">Flexdeal</span>  intends to support SMEs in the ESG
                    <br>transition, through awareness raising, knowledge
                    <br>sharing and best management practices and tools.
                </p>
            </div>

            <div class="w-50 col-span-2 p-[17px] text-left sm:col-span-1">
                <p class="text-esg27">
                    In this sense, <span class="font-bold text-esg28">Flexdeal and the Associations signatory</span>
                        <br><span class="font-bold text-esg28">of the protocol in partnership with C-MORE</span>,
                        <br>have the pleasure to present you the <span class="font-bold text-esg28">ESG Maturity</span>
                        <br><span class="font-bold text-esg28">software</span>: a management tool that simplifies
                        <br>sustainability for SMEs, turning it
                        <br>accessible to everyone.
                </p>

                <p class="text-esg27 mt-5">
                    You have now the opportunity to make a first ESG
                    <br>assessment of your company for free, by clicking on
                    <br>the button below. This can be your starting point and
                    <br>we can support you in this journey.
                </p>
            </div>
        </div>


        <div class="py-10"><hr class="border-esg5 mt-[26px]"></div>
        <div class="text-esg27 px-3 pb-16 pt-4 text-2xl text-center font-semibold">We decode ESG for SMEs.</div>
        <div class="mb-[100px] text-center"><a href="{{ $questionnaireUrl }}" class="text-esg27 font-inter bg-esg5 rounded-lg px-6 py-3 text-lg font-bold uppercase">Start ESG assessment</a></div>
    @elseif (app()->isLocale('es'))
        <div class="p-3 pt-[107px]">
            <h2 class="text-esg27 text-center text-3xl font-semibold">Bienvenido a la ESG Maturity</h2>
            <hr class="border-esg5 mt-[26px]">
            <h2 class="text-esg27 pt-[26px] text-center text-3xl font-semibold">Enhorabuena, ¡este puede ser un paso importante en el camino de su empresa hacia la sostenibilidad!</h2>
        </div>

        <div class="text-esg7 divide-esg5 grid grid-cols-2 text-lg font-light sm:divide-x mt-4">
            <div class="col-span-2 sm:col-span-1 text-right w-50 p-[17px] @if (app()->isLocale('en') || app()->isLocale('pt-PT') || app()->isLocale('pt-BR')) leading-8 @elseif(app()->isLocale('fr') || app()->isLocale('es')) leading-9 @endif">
                <p class="text-esg27">
                    Traducir la sostenibilidad en negocios es el lema del evento
                    <br>de <span class="font-bold text-esg28">Flexdeal - Exame</span>. Somos conscientes de la creciente
                    <br>importancia e impacto de los criterios ESG en la vida de
                    <br>las empresas, pero creemos que sólo tendrá sentido si
                    <br>respeta dos premisas clave: ser simple y fácil de
                    <br>entender; aportar beneficios tangibles y fáciles
                    <br>de cuantificar a las empresas.
                </p>

                <p class="text-esg27 mt-5">
                    <span class="font-bold text-esg28">Flexdeal</span> tiene la intención de apoyar a las PYME
                    <br>en la transición de la ESG, a través de la sensibilización,
                    <br> el intercambio de conocimientos y las mejores
                    <br>prácticas y herramientas de gestión.
                </p>
            </div>

            <div class="w-50 col-span-2 p-[17px] text-left sm:col-span-1">
                <p class="text-esg27">
                    En este sentido, <span class="font-bold text-esg28"> Flexdeal y las Asociaciones firmantes</span>
                    <br><span class="font-bold text-esg28">del protocolo en colaboración con C-MORE</span> tienen el
                    <br>placer de presentarles el <span class="font-bold text-esg28">software ESG Maturity</span>:
                    <br>una herramienta de gestión que simplifica la
                    <br>sostenibilidad para las PYMES,
                    <br>haciéndola accesible a todos.
                </p>

                <p class="text-esg27 mt-5">
                    Ahora tiene la oportunidad de realizar una primera
                    <br>evaluación ESG de su empresa de forma gratuita,
                    <br>haciendo clic en el botón de abajo. Este puede ser
                    <br>su punto de partida y podemos apoyarle en este viaje.
                </p>
            </div>
        </div>


        <div class="py-10"><hr class="border-esg5 mt-[26px]"></div>
        <div class="text-esg27 px-3 pb-16 pt-4 text-2xl text-center font-semibold">Desciframos la ESG para las PYMES.</div>
        <div class="mb-[100px] text-center"><a href="{{ $questionnaireUrl }}" class="text-esg27 font-inter bg-esg5 rounded-lg px-6 py-3 text-lg font-bold uppercase">Comenzar la evaluación ESG</a></div>
    @elseif (app()->isLocale('fr'))
        <div class="p-3 pt-[107px]">
            <h2 class="text-esg27 text-center text-3xl font-semibold">Bienvenue à la ESG Maturity</h2>
            <hr class="border-esg5 mt-[26px]">
            <h2 class="text-esg27 pt-[26px] text-center text-3xl font-semibold">Félicitations, cela peut être une étape importante dans le parcours de votre entreprise vers la durabilité!</h2>
        </div>

        <div class="text-esg7 divide-esg5 grid grid-cols-2 text-lg font-light sm:divide-x mt-4">
            <div class="col-span-2 sm:col-span-1 text-right w-50 p-[17px] @if (app()->isLocale('en') || app()->isLocale('pt-PT') || app()->isLocale('pt-BR')) leading-8 @elseif(app()->isLocale('fr') || app()->isLocale('es')) leading-9 @endif">
                <p class="text-esg27">
                    Traduire la durabilité en affaires est la devise de
                    <br>l'événement de <span class="font-bold text-esg28">Flexdeal - Exame</span>. Nous sommes
                    <br>conscients de l'importance et de l'impact croissants
                    <br>des critères ESG dans la vie des entreprises, mais
                    <br>nous pensons que cela n'aura pas de sens que si cela
                    <br>respecte deux prémisses clés : être simple et facile
                    <br>à comprendre ; apporter des avantages tangibles et faciles
                    <br>à quantifier aux entreprises.
                </p>

                <p class="text-esg27 mt-5">
                    <span class="font-bold text-esg28">Flexdeal</span> entend soutenir les PME dans la transition
                    <br>ESG, par la sensibilisation, le partage des et outils
                    <br>connaissances et les meilleures pratiques de gestion.
                </p>
            </div>

            <div class="w-50 col-span-2 p-[17px] text-left sm:col-span-1">
                <p class="text-esg27">
                    Dans ce sens, <span class="font-bold text-esg28">Flexdeal et les Associations signataires</span>
                        <br><span class="font-bold text-esg28">du protocole en partenariat avec C-MORE</span> ont le plaisir
                        <br>de vous présenter le <span class="font-bold text-esg28">logiciel ESG Maturity</span>: un outil
                        <br>de gestion qui simplifie la durabilité pour les PME,
                        <br>la rendant accessible à tous.

                <p class="text-esg27 mt-5">
                    Vous avez déjà la possibilité d'effectuer gratuitement
                    <br>une première évaluation ESG de votre entreprise, en
                    <br>cliquant sur le bouton ci-dessous. Cela peut être
                    <br>votre point de départ et nous pouvons vous soutenir
                    <br>dans cette démarche.
                </p>
            </div>
        </div>


        <div class="py-10"><hr class="border-esg5 mt-[26px]"></div>
        <div class="text-esg27 px-3 pb-16 pt-4 text-2xl text-center font-semibold">Nous décodons l'ESG pour les PME.</div>
        <div class="mb-[100px] text-center"><a href="{{ $questionnaireUrl }}" class="text-esg27 font-inter bg-esg5 rounded-lg px-6 py-3 text-lg font-bold uppercase">Commencer l'évaluation ESG</a></div>
    @endif
@endsection
