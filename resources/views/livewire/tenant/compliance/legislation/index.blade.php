<div>
    <x-slot name="header">
        <x-header title="{{ __('Compliance') }}">
            <x-slot name="left"></x-slot>
        </x-header>
    </x-slot>
</div>

<div class="mx-auto max-w-7xl sm:px-6 px-4 lg:px-0 leading-normal">
    <div class="mt-4" x-data="{ tab: 'legislation' }">
        <div class="flex w-full border-b border-[#8A8A8A] text-[#8A8A8A]">
            <a :class="{ 'active text-esg8 border-b-2 border-esg5': tab === 'analysis' }"
                class="p-3 text-sm focus:outline-none focus:border-b-2 focus:border-esg5 focus:text-esg8 hover:text-esg8 rounded-t-sm mr-6"
                href="{{ route('tenant.compliance.document_analysis.index') }}">{{ __('Document Analysis') }}</a>
            <a :class="{ 'active text-esg8 border-b-2 border-esg5': tab === 'legislation' }"
                class="p-3 text-sm focus:outline-none focus:border-b-2 focus:border-esg5 focus:text-esg8 hover:text-esg8 rounded-t-sm">{{ __('Legislation') }}</a>
        </div>

        @if (is_null($result))
            <div class="flex justify-center items-center p-6">
                <h3 class="w-fit text-md">
                    {{ __('No domains available yet.') }}</h3>
            </div>
        @else
            <div x-show="tab === 'legislation'">
                <div class="my-12">
                    <div id="accordion-flush" data-accordion="collapse" data-active-classes="bg-esg37"
                        data-inactive-classes="" class="mt-6 mb-36">
                        @foreach ($result->domains as $domain)
                            @php
                                $loopEncoded = json_encode($loop);
                            @endphp

                            <x-accordian.legislation.heading loop="{!! $loopEncoded !!}">
                                <x-slot name="heading">
                                    <div class="w-full md:w-4/5 flex items-center">
                                        <div class="mr-3">
                                            @include('icons/legislation/governance')
                                        </div>
                                        Proíbe e pune a discriminação em razão da deficiência e da existência de risco
                                        agravado de saúde Proíbe e pune a discriminação em razão da deficiência e da
                                        existência de risco agravado de saúde Proíbe e pune a discriminação em razão da
                                        deficiência e da existência de risco agravado de saúde
                                        <div class="ml-3">
                                            @include('icons/legislation/pin')
                                        </div>
                                    </div>
                                </x-slot>
                            </x-accordian.legislation.heading>

                            <x-accordian.legislation.body loop="{!! $loopEncoded !!}">
                                <div
                                    class="py-3 px-4 md:pl-11 text-xs text-esg8 bg-esg37 border-b border-esg38 dark:border-esg38">
                                    <p class="text-esg34 mb-2">
                                        Decreto-Lei n.º 109-E/2021, de 9 de dezembro de 2021
                                    </p>
                                    <p class="mb-2">
                                        <strong>Data que entra em vigor:</strong> 09/06/2022
                                    </p>
                                    <p class="mb-2">
                                        <strong>Coima:</strong> 1000,00€ - 44891,81€
                                    </p>
                                    <p class="mb-2"><strong>Contraordenações:</strong></p>

                                    <ul class="list-disc pl-4 mb-2">
                                        <li>Não adoção ou implementação do PPR</li>
                                        <li>Não adoção de um código de conduta</li>
                                        <li>Não implementação de um sistema de controlo interno</li>
                                        <li>Não elaboração dos relatórios de controlo do PPR</li>
                                        <li>Não revisão do PPR</li>
                                        <li>Não publicitação do PPR</li>
                                        <li>Não comunicação do PPR ou dos respetivos relatórios de controlo</li>
                                        <li>Não elaboração dos relatórios</li>
                                        <li>Não revisão do código de conduta</li>
                                        <li>Não publicitação do código de conduta aos trabalhadores</li>
                                        <li>Não comunicação do código de ética e dos pertinentes relatórios</li>
                                    </ul>

                                    <p class="mb-2"><strong>Sanções acessórias:</strong></p>
                                    <ul class="list-disc pl-4 mb-2">
                                        <li>
                                            Publicidade da condenação
                                        </li>
                                    </ul>

                                </div>
                            </x-accordian.legislation.body>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

</div>
