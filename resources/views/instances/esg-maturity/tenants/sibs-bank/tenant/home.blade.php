@extends('tenant.home')

@section('content')
    <div class="mt-10">
        <div class="">
            <div class="flex items-center gap-4 bg-esg27 px-4 py-1 shadow rounded-md">
                @include('icons.search', ['color' => color(16), 'width' => 17, 'height' => 17])
                <x-inputs.text name="search" id="search" class="!border-0 !text-sm !font-normal"
                    placeholder="{!! __('Search a company by name or NIF') !!}" />
            </div>

            <div class="mt-6">
                <div class="">
                    <x-cards.card-dashboard-version1-withshadow class="!h-auto !shadow !p-6" contentplacement="none">
                        <div class="text-2xl font-medium text-esg5"> {!! __('Hello, :name!', ['name' => auth()->user()->name]) !!} </div>
                        <div class="text-base text-esg16 mt-4"> {!! __('Here`s an overview of your ecosystem.') !!} </div>

                        <div class="flex items-center justify-between mt-6">
                            <div class="flex items-center gap-4">

                                @include(tenant()->views . 'icons.up')

                                <div class="">
                                    <p class="text-base font-medium text-esg16">{!! __('Total of submitted questionnaires') !!}</p>
                                    <span class="text-2xl font-bold text-esg8">
                                        {{ $cards['questionnaires']['value'] }}
                                    </span>
                                    <span class="">/
                                        {{ $cards['questionnaires']['total'] }}
                                    </span>
                                    <span class="text-[#19A0FD] text-xs font-medium"></span>
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                @include(tenant()->views . 'icons.up')

                                <div class="">
                                    <p class="text-base font-medium text-esg16">{!! __('Total of clients with ESG Information') !!}</p>
                                    <span class="text-2xl font-bold text-esg8">
                                        {{ $cards['esg_information']['value'] }}
                                    </span>
                                    <span class="">/
                                        {{ $cards['esg_information']['total'] }}
                                    </span>

                                    <span class="text-[#EE2722] text-xs font-medium"></span>
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                @include(tenant()->views . 'icons.up')

                                <div class="">
                                    <p class="text-base font-medium text-esg16">{!! __('Total of clients obligated to Taxonomy') !!}</p>
                                    <span class="text-2xl font-bold text-esg8">
                                        {{ $cards['obligated_taxonomy']['value'] }}
                                    </span>
                                    <span class="">/
                                        {{ $cards['obligated_taxonomy']['total'] }}
                                    </span>
                                    <span class="text-[#19A0FD] text-xs font-medium"></span>
                                </div>
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>
                </div>
            </div>

            <div class="" x-data="{ costum: false, internal: false, regulation: false, asset: false }">
                <div class="mt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="!h-auto !shadow !p-6 border bg-esg27 rounded-md border-esg27 hover:border hover:border-esg5 hover:bg-esg5/10 transition duration-200 cursor-pointer"
                            :class="costum == false && internal == false && regulation == false && asset == false ?
                                '' :
                                (costum ? '!bg-esg5/10 !border-esg5' : '')"
                            x-on:click="costum=true, internal=false, regulation=false, asset=false">
                            <div class="flex flex-col items-center"
                                :class="costum == false && internal == false && regulation == false && asset == false ?
                                    '' :
                                    (costum ? '' : 'grayscale')">
                                @include(tenant()->views . 'icons.banchmark')
                                <p class="text-lg font-medium text-esg5 mt-6 uppercase">{!! __('Customer’s ESG') !!}</p>
                                <p class="text-sm text-esg16 mt-6">{!! __('Customer’s information with general and individual view of data visualization.') !!}</p>
                            </div>
                        </div>

                        <div class="!h-auto !shadow !p-6 border bg-esg27 rounded-md border-esg27 hover:border hover:border-esg5 hover:bg-esg5/10 transition duration-200 cursor-pointer"
                            :class="costum == false && internal == false && regulation == false && asset == false ?
                                '' :
                                (internal ? '!bg-esg5/5 !border-esg5' : '')"
                            x-on:click="costum=false, internal=true, regulation=false, asset=false">
                            <div class="flex flex-col items-center"
                                :class="costum == false && internal == false && regulation == false && asset == false ?
                                    '' :
                                    (internal ? '' : 'grayscale')">
                                @include(tenant()->views . 'icons.bank')
                                <p class="text-lg font-medium text-esg5 mt-6 uppercase">{!! __('internal ESG') !!}</p>
                                <p class="text-sm text-esg16 mt-6">{!! __('Internal self-evaluation space with tools to assist this journey.') !!}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 transition duration-300" x-show="costum"
                        x-transition:enter="transition ease-[cubic-bezier(0.95,0.05,0.795,0.035)] duration-200"
                        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
                        <x-cards.card-dashboard-version1-withshadow class="!h-auto !shadow !p-6 border border-esg5"
                            contentplacement="none">
                            <div class="flex justify-end -mt-5">
                                <span
                                    class="bg-[#E5E5E5] rounded-full w-6 h-6 flex items-center justify-center cursor-pointer"
                                    x-on:click="costum = ! costum"> @include(tenant()->views . 'icons.close') </span>
                            </div>
                            <p class="text-center text-base text-esg16">{!! __('Choose how you wish to visualize the information:') !!}</p>
                            <div class="flex items-center justify-center gap-5 mt-6">
                                <a href="{{ route('tenant.companies.index') }}"
                                    class="text-lg font-medium text-esg16 px-10 py-1.5 shadow rounded border border-esg27 hover:border-esg5">
                                    {!! __('Companies') !!}
                                </a>
                                <a href="{{ route('tenant.garbtar.assets') }}"
                                    class="text-lg font-medium text-esg16 px-10 py-1.5 shadow rounded border border-esg27 hover:border-esg5">
                                    {!! __('Assets') !!}
                                </a>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>

                    <div class="mt-6 transition duration-300" x-show="internal"
                        x-transition:enter="transition ease-[cubic-bezier(0.95,0.05,0.795,0.035)] duration-200"
                        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
                        <x-cards.card-dashboard-version1-withshadow class="!h-auto !shadow !p-6 border border-esg5"
                            contentplacement="none">
                            <div class="flex justify-end -mt-5">
                                <span
                                    class="bg-[#E5E5E5] rounded-full w-6 h-6 flex items-center justify-center cursor-pointer"
                                    x-on:click="internal = ! internal"> @include(tenant()->views . 'icons.close') </span>
                            </div>
                            <p class="text-center text-base text-esg16">{!! __('Choose which tool you want to access:') !!}</p>
                            <div class="flex items-center justify-center gap-5 mt-6">
                                <a href="{{ route('tenant.data.panel') }}"
                                    class="text-lg font-medium text-esg16 px-10 py-1.5 shadow rounded border border-esg27 hover:border-esg5">{!! __('Monitoring') !!}
                                    </a>
                                <a href="{{ route('tenant.targets.index') }}"
                                    class="text-lg font-medium text-esg16 px-10 py-1.5 shadow rounded border border-esg27 hover:border-esg5">{!! __('Targets') !!}
                                    </a>
                                <a href="{{ route('tenant.exports.index') }}"
                                    class="text-lg font-medium text-esg16 px-10 py-1.5 shadow rounded border border-esg27 hover:border-esg5">{!! __('Reporting') !!}
                                    </a>
                                <a href="{{ route('tenant.companies.list' , [ 's[company_relation_filter][0]' => 'Client'] ) }}"
                                    class="text-lg font-medium text-esg16 px-10 py-1.5 shadow rounded border border-esg27 hover:border-esg5">{!! __('Clients') !!}
                                    </a>
                                <a href="{{ route('tenant.companies.list' , [ 's[company_relation_filter][0]' => 'Supplier'] ) }}"
                                    class="text-lg font-medium text-esg16 px-10 py-1.5 shadow rounded border border-esg27 hover:border-esg5">{!! __('Suppliers') !!}
                                </a>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>

                <div class="mt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="!h-auto !shadow !p-6 border bg-esg27 rounded-md border-esg27 hover:border hover:border-esg5 hover:bg-esg5/10 transition duration-200 cursor-pointer"
                            :class="costum == false && internal == false && regulation == false && asset == false ?
                                '' :
                                (regulation ? '!bg-esg5/5 !border-esg5' : '')"
                            x-on:click="costum=false, internal=false, regulation=true, asset=false">
                            <div class="flex flex-col items-center"
                                :class="costum == false && internal == false && regulation == false && asset == false ?
                                    '' :
                                    (regulation ? '' : 'grayscale')">
                                @include(tenant()->views . 'icons.regulation')
                                <p class="text-lg font-medium text-esg5 mt-6 uppercase">{!! __('regulatory ESG') !!}</p>
                                <p class="text-sm text-esg16 mt-6">{!! __('Government standards for ESG-related actions, reporting or disclosures.') !!}</p>
                            </div>
                        </div>

                        <div class="!h-auto !shadow !p-6 border bg-white rounded-md border-esg27 hover:bordertransition duration-200"
                            :class="costum == false && internal == false && regulation == false && asset == false ?
                                '' :
                                (asset ? '!bg-esg5/5 !border-esg5' : '')">
                            <div class="flex flex-col items-center"
                                :class="costum == false && internal == false && regulation == false && asset == false ?
                                    '' :
                                    (asset ? '' : 'grayscale')">
                                @include(tenant()->views . 'icons.asset', ['color' => color(16)])
                                <p class="text-lg text-esg16/80 font-medium text-1 mt-6 uppercase">{!! __('Asset Management') !!}
                                </p>
                                <p class="text-sm text-esg16/80 mt-6">{!! __('Asset management approach including SFDR and Taxonomy.') !!}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 transition duration-300" x-show="regulation"
                        x-transition:enter="transition ease-[cubic-bezier(0.95,0.05,0.795,0.035)] duration-200"
                        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
                        <x-cards.card-dashboard-version1-withshadow class="!h-auto !shadow !p-6 border border-esg5"
                            contentplacement="none">
                            <div class="flex justify-end -mt-5">
                                <span
                                    class="bg-[#E5E5E5] rounded-full w-6 h-6 flex items-center justify-center cursor-pointer"
                                    x-on:click="regulation = ! regulation"> @include(tenant()->views . 'icons.close') </span>
                            </div>
                            <p class="text-center text-base text-esg16">{!! __('Choose what you want to access:') !!}</p>
                            <div class="flex items-center justify-center gap-5 mt-6">
                                <a href="{{ route('tenant.garbtar.index') }}"
                                    class="text-lg font-medium text-esg16 px-10 py-1.5 shadow rounded border border-esg27 hover:border-esg5">
                                    {!! __('Ratios') !!}
                                </a>
                                <a href="{{ route('tenant.garbtar.crr.panel') }}"
                                    class="text-lg font-medium text-esg16 px-4 py-1.5 shadow rounded border border-esg27 hover:border-esg5">
                                    {!! __('ESG/CRR indicators​') !!}
                                </a>
                                <div
                                    class="text-lg font-medium text-esg16 px-4 py-1.5 shadow rounded border border-esg27 hover:border-esg5">
                                    {!! __('Regulatory Tables') !!}</div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>

                    <div class="mt-6 transition duration-300" x-show="asset"
                        x-transition:enter="transition ease-[cubic-bezier(0.95,0.05,0.795,0.035)] duration-200"
                        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
                        <x-cards.card-dashboard-version1-withshadow class="!h-auto !shadow !p-6 border border-esg5"
                            contentplacement="none">
                            <div class="flex justify-end -mt-5">
                                <span
                                    class="bg-[#E5E5E5] rounded-full w-6 h-6 flex items-center justify-center cursor-pointer"
                                    x-on:click="asset = ! asset"> @include(tenant()->views . 'icons.close') </span>
                            </div>

                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
