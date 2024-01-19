@extends(customInclude('layouts.tenant'), ['mainBgColor' => 'bg-esg4'])

@section('content')
<div class="px-4 md:px-0">
    <div class="bg-esg27 h-screen min-h-[1800px] md:min-h-0 md:max-h-[750px] flex left-[50%] -ml-[50vw] w-screen relative items-center justify-center mb-40 md:mb-0">
        <div class="w-full mx-auto max-w-7xl pt-8 px-4 sm:px-6 lg:px-8 absolute z-90">
            <div class="grid grid-cols-1 md:grid-cols-2 font-encodesans text-esg8 gap-14">
                <div class="mt-24">
                    <p class="uppercase font-medium text-lg text-esg5">{{ __('Software') }}</p>
                    <p class="text-4xl font-bold mt-2">{{ __('Welcome to ESG PRO') }}</p>

                    <p class="text-base font-normal mt-8 text-justify text-esg8/60"> {{ __('Aquí encontrará todo lo que necesita para tener éxito en este viaje para ayudar a las empresas a convertir la sostenibilidad en un negocio.') }}</p>
                    <p class="text-base font-bold mt-8 text-justify text-esg8/60"> {{ __('¡Trabajemos juntos por un futuro sostenible!') }}</p>

                    <button class="bg-esg5 w-60 h-11 rounded-lg font-bold text-base mt-9 uppercase text-esg27" @click="window.location.href = '{!! $questionnaireUrl !!}'"> {{ __('Crear un cuestionario') }} </button>
                </div>

                <div class="w-full">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="mt-10">
                            <img src="/images/customizations/tenantf75572aa-a2d9-4a54-95cd-fdcad67179e2/Img1.png" class="w-full md:w-auto"/>
                        </div>

                        <div class="">
                            <img src="/images/customizations/tenantf75572aa-a2d9-4a54-95cd-fdcad67179e2/Img2.png" class="w-full md:w-auto"/>
                        </div>
                    </div>
                    <div class="flex flex-col md:flex-row gap-8 mt-10">
                        <div class="mt-5">
                            <img src="/images/customizations/tenantf75572aa-a2d9-4a54-95cd-fdcad67179e2/Img3.png" class="w-full md:w-auto"/>
                        </div>

                        <div class="">
                            <img src="/images/customizations/tenantf75572aa-a2d9-4a54-95cd-fdcad67179e2/Img4.png" class="w-full md:w-auto"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-esg6 h-screen min-h-[800px] md:min-h-0 md:max-h-[400px] flex left-[50%] -ml-[50vw] w-screen relative items-center justify-center">
        <div class="w-full mx-auto max-w-7xl  px-4 sm:px-6 lg:px-8 absolute z-90">
            <div class="grid items-center text-center font-encodesans text-esg27 gap-14">
                <div>
                    <p class="text-lg font-medium uppercase mb-3">{{ __('who we are') }}</p>
                    <p class="text-4xl font-bold">{{ __('The INSURE.hub Iniciative') }}</p>

                    <p class="text-xl font-normal mt-8  text-esg27">{!! __('INSURE.hub is an initiative nurtured by :strong Universidade Católica Portuguesa, in Porto, :cstrong through its Schools - :strong Católica Porto Business School :cstrong and :strong Faculty of Biotechnology :cstrong– with :strong Planetiers New Generation :cstrong that aims to promote circular, sustainable and regenerative business solutions, powered by disruptive innovation and technologies. We bring forefront knowledge and research labs, along with the capacity to mobilize practitioners to address the challenges ahead regarding innovation in sustainability and regeneration.', ['strong' => '<strong>', 'cstrong' => '</strong>']) !!}</p>

                </div>
            </div>
        </div>
    </div>

    <div class="font-encodesans mb-20">
        <div class="max-w-5xl mx-auto">
            <p class="text-lg font-medium text-esg5 text-center uppercase mt-12"> {{ __('Features') }} </p>

            <p class="text-4xl font-bold text-esg8 text-center mt-4"> {{ __('Recopilar y analizar datos') }} </p>

            <p class="text-base font-normal text-esg8 text-center mt-5"> {{ __('Conozca y explore un mundo de funciones diseñadas para facilitar la recopilación y el análisis de datos.') }} </p>

            <div class="grid grid-cols-1 md:grid-cols-2 md:gap-9 mt-10">
                <div x-data="{ open: false }" @mouseleave="open = false" @mouseover="open = true" x-on:click="window.location='{{ route('tenant.questionnaires.panel') }}'" class="bg-esg4 border border-esg4 duration-500 hover:bg-[#E1E6EF] rounded-2xl p-8 cursor-pointer flex gap-5">
                    <div class="grid justify-items-center">
                        @include(tenant()->views .'icons.1', ['color' => color(6)])
                    </div>

                    <div class=" font-normal text-esg8 text-sm">
                        <p class="text-esg6 text-2xl font-semibold "> {{ __('Cuestionarios') }} </p>
                        <p class="mt-3 font-normal text-base"> {{ __('Un cuestionario fácil de navegar basado en las características de la empresa.') }} </p>

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
                        <p class="mt-3 font-normal text-base"> {{ __('Una presentación visual de los datos analizados recogidos en el cuestionario.') }} </p>

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
                        <p class="text-esg6 text-2xl font-semibold "> {{ __('Objetivos') }} </p>
                        <p class="mt-3 font-normal text-base"> {{ __('Cree metas y siga el progreso de un indicador.') }} </p>

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
                        <p class="text-esg6 text-2xl font-semibold "> {{ __('Biblioteca') }} </p>
                        <p class="mt-3 font-normal text-base"> {{ __('¡Información sobre el software, marcos ESG y mucho más!') }} </p>

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
