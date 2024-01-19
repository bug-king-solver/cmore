@extends(customInclude('layouts.central'), ['isheader' => false, 'nav_background' => true, 'background_image' => true])

@section('content')
    <div class="text-center">
        <h1 class="text-esg28 text-7xl font-encodesans font-bold pt-48 text-center">
            {{__('Global Partner\'s Network')}}
        </h1>

        <h2 class="mt-10 m-auto text-esg27 text-3xl w-2/3 font-encodesans font-semibold">{{ __('Our Partners can help you to select the best package for your organization and provide you with additional services such as setup, training, consultancy and support. Feel free to contact our Partners directly.') }}</h2>
    </div>

    <div class="grid justify-items-center">
        <div class="grid grid-cols-1 sm:grid-cols-2 font-encodesans pb-16 mt-20 gap-8">

            <div class="lg:pl-[10%] lg:w-4/5">
                <div class="text-2xl font-semibold text-esg28 pb-5 mb-5 border-b border-b-esg4">
                    @include('icons.flag.br', ['class' => 'mx-4 max-h-14 inline-block', 'height' => 40, 'width' => 61])
                    <img class="mx-4 max-h-14 inline-block" src="{{ global_asset('images/partners/alfacentauri.png')}}" alt="">
                </div>

                <div class="font-medium text-esg27 text-xl">Rua Cel. Joaquim Ferreira Lobo, 330 - Sala B, Vila Olímpia, São Paulo-SP 04544-150, Brazil</div>
                <div class="font-medium text-esg27 text-xl mt-3"> @include('icons.email-fill', ['class' => 'inline-block mr-2']) <a class="text-esg27 underline" href="mailto:contato@alfacentauri.com.br">contato@alfacentauri.com.br</a></div>
                <div class="font-medium text-esg27 text-xl mt-3"> @include('icons.earth', ['class' => 'inline-block mr-2']) <a class="text-esg27 underline" href="http://www.acentauri.com.br">acentauri.com.br/</a></div>
            </div>

            <div class="lg:pl-[10%] lg:w-4/5">
                <div class="text-2xl font-semibold text-esg28 pb-5 mb-5 border-b border-b-esg4">
                    @include('icons.flag.mx', ['class' => 'mx-4 max-h-14 inline-block', 'height' => 40, 'width' => 61])
                    <img class="mx-4 max-h-14 inline-block" src="{{ global_asset('images/partners/accse.png')}}" alt="">
                </div>

                <div class="font-medium text-esg27 text-xl">Berlín No.107, 1er piso, Col. Del Carmen Coyoacán, Delegación Coyoacán, CP 04100, México</div>
                <div class="font-medium text-esg27 text-xl mt-3"> @include('icons.phone', ['class' => 'inline-block mr-2']) +52 (55) 5407 3182</div>
                <div class="font-medium text-esg27 text-xl mt-3"> @include('icons.email-fill', ['class' => 'inline-block mr-2']) <a class="text-esg27 underline" href="mailto:raguilar@accse.net">raguilar@accse.net</a></div>
                <div class="font-medium text-esg27 text-xl mt-3"> @include('icons.earth', ['class' => 'inline-block mr-2']) <a class="text-esg27 underline" href="https://www.accionsocialempresarial.com/">accionsocialempresarial.com/</a></div>
            </div>

            <div class="lg:pl-[10%] lg:w-4/5 mt-14">
                <div class="text-2xl font-semibold text-esg28 pb-5 mb-5 border-b border-b-esg4">
                    @include('icons.flag.br', ['class' => 'mx-4 max-h-14 inline-block', 'height' => 40, 'width' => 61])
                    <img class="mx-4 max-h-14 inline-block" src="{{ global_asset('images/partners/bittencourt_logo.svg')}}" alt="">
                 </div>

                 <div class="font-medium text-esg27 text-xl">Av. Paulista, 171 - 9 andar - Paraíso, São Paulo - SP 01311-904</div>
                 <div class="font-medium text-esg27 text-xl mt-3"> @include('icons.phone', ['class' => 'inline-block mr-2']) +55 11 3660-2201 </div>
                 <div class="font-medium text-esg27 text-xl mt-3"> @include('icons.email-fill', ['class' => 'inline-block mr-2']) <a class="text-esg27 underline" href="mailto:help@theunconnected.org">contato@bcef.com.br</a></div>
                 <div class="font-medium text-esg27 text-xl mt-3"> @include('icons.earth', ['class' => 'inline-block mr-2']) <a class="text-esg27 underline underline-offset-1" href="https://www.theunconnected.org/">bittencourtconsultoria.com.br/</a></div>
            </div>

            <div class="lg:pl-[10%] lg:w-4/5 mt-14">
                <div class="text-2xl font-semibold text-esg28 pb-5 mb-5 border-b border-b-esg4">
                    @include('icons.flag.pt', ['class' => 'mx-4 max-h-14 inline-block', 'height' => 40, 'width' => 61])
                    <img class="mx-4 max-h-14 inline-block" src="{{ global_asset('images/partners/INFOSISTEMA.png')}}" alt="">
                 </div>

                 <div class="font-medium text-esg27 text-xl">Rua do Proletariado Nº7 (Lote 1), 2794-076 Carnaxide, Portugal</div>
                 <div class="font-medium text-esg27 text-xl mt-3"> @include('icons.phone', ['class' => 'inline-block mr-2']) +351 214 139 860 </div>
                 <div class="font-medium text-esg27 text-xl mt-3"> @include('icons.email-fill', ['class' => 'inline-block mr-2']) <a class="text-esg27 underline" href="mailto:help@theunconnected.org">worldwide.pt@infosistema.com</a></div>
                 <div class="font-medium text-esg27 text-xl mt-3"> @include('icons.earth', ['class' => 'inline-block mr-2']) <a class="text-esg27 underline underline-offset-1" href="https://www.infosistema.com/">https://www.infosistema.com/</a></div>
            </div>

            <div class="lg:pl-[10%] lg:w-4/5 mt-14">
                <div class="text-2xl font-semibold text-esg28 pb-5 mb-5 border-b border-b-esg4">
                    @include('icons.flag.ie', ['class' => 'mx-4 max-h-24 inline-block', 'height' => 40, 'width' => 61])
                     <img class="mx-4 max-h-14 inline-block" src="{{ global_asset('images/partners/KRA-Renewables.png')}}" alt="">
                 </div>

                 <div class="font-medium text-esg27 text-xl">E11a Network Enterprise Park Kilcoole Co Wicklow, Ireland</div>
                 <div class="font-medium text-esg27 text-xl mt-3"> @include('icons.phone', ['class' => 'inline-block mr-2']) +353 (0)1 524 0555 </div>
                 <div class="font-medium text-esg27 text-xl mt-3"> @include('icons.email-fill', ['class' => 'inline-block mr-2']) <a class="text-esg27 underline" href="mailto:help@theunconnected.org">CIARA@KRA.IE</a></div>
                 <div class="font-medium text-esg27 text-xl mt-3"> @include('icons.earth', ['class' => 'inline-block mr-2']) <a class="text-esg27 underline underline-offset-1" href="https://www.kra.ie/">https://www.kra.ie/</a></div>
            </div>

            <div class="lg:pl-[10%] lg:w-4/5 mt-14">
                <div class="text-2xl font-semibold text-esg28 pb-5 mb-5 border-b border-b-esg4">
                    @include('icons.flag.pt', ['class' => 'mx-4 max-h-14 inline-block', 'height' => 40, 'width' => 61])
                    <img class="mx-4 max-h-14 inline-block" src="{{ global_asset('images/partners/lxgest.png')}}" alt="">
                </div>

                <div class="font-medium text-esg27 text-xl">Avenida Casal Ribeiro, nº15 7º andar 1000-090 Lisboa, Portugal</div>
                <div class="font-medium text-esg27 text-xl mt-3"> @include('icons.phone', ['class' => 'inline-block mr-2']) +351 21 355 7799</div>
                <div class="font-medium text-esg27 text-xl mt-3"> @include('icons.email-fill', ['class' => 'inline-block mr-2']) <a class="text-esg27 underline" href="mailto:geral@lxgest.com">geral@lxgest.com</a></div>
                <div class="font-medium text-esg27 text-xl mt-3"> @include('icons.earth', ['class' => 'inline-block mr-2']) <a class="text-esg27 underline" href="https://lxgest.com/">lxgest.com/</a></div>
            </div>

            <div class="lg:pl-[10%] lg:w-4/5 mt-14">
                <div class="text-2xl font-semibold text-esg28 pb-6 mb-5 border-b border-b-esg4">
                    @include('icons.flag.be', ['class' => 'mx-4 max-h-14 inline-block', 'height' => 40, 'width' => 61])
                    <img class="mx-4 max-h-14 inline-block" src="{{ global_asset('images/partners/six-paths.png')}}" alt="">
                </div>

                <div class="font-medium text-esg27 text-xl">Avenue de la Couronne 256, 1050 Brussels, Belgium</div>
                <div class="font-medium text-esg27 text-xl mt-3"> @include('icons.phone', ['class' => 'inline-block mr-2']) +32 491 12 66 52</div>
                <div class="font-medium text-esg27 text-xl mt-3"> @include('icons.email-fill', ['class' => 'inline-block mr-2']) <a class="text-esg27 underline" href="mailto:office@sixpathsconsulting.com">office@sixpathsconsulting.com</a></div>
                <div class="font-medium text-esg27 text-xl mt-3"> @include('icons.earth', ['class' => 'inline-block mr-2']) <a class="text-esg27 underline" href="https://www.sixpathsconsulting.com/">sixpathsconsulting.com/</a></div>
            </div>

            <div class="lg:pl-[10%] lg:w-4/5 mt-14">
               <div class="text-2xl font-semibold text-esg28 pb-5 mb-5 border-b border-b-esg4">
                @include('icons.flag.uk', ['class' => 'mx-4 max-h-14 inline-block', 'height' => 40, 'width' => 61])
                    <img class="mx-4 max-h-14 inline-block" src="{{ global_asset('images/partners/unconnected.png')}}" alt="">
                </div>

                <div class="font-medium text-esg27 text-xl">2 Frederick Street, London, WC1X 0ND, United Kingdom</div>
                <div class="font-medium text-esg27 text-xl mt-3"> @include('icons.phone', ['class' => 'inline-block mr-2']) - </div>
                <div class="font-medium text-esg27 text-xl mt-3"> @include('icons.email-fill', ['class' => 'inline-block mr-2']) <a class="text-esg27 underline" href="mailto:help@theunconnected.org">help@theunconnected.org</a></div>
                <div class="font-medium text-esg27 text-xl mt-3"> @include('icons.earth', ['class' => 'inline-block mr-2']) <a class="text-esg27 underline underline-offset-1" href="https://www.theunconnected.org/">theunconnected.org/</a></div>
            </div>


        </div>
    </div>
@endsection
