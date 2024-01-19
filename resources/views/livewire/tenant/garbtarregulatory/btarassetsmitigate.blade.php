<div class="px-4 md:px-0">

    <x-slot name="header">
        <x-header title="{{ __('Mitigating actions: BTAR') }}" dataTest="data-header" class="!bg-transparent" textcolor="text-[#39B54A]" iconcolor="#39B54A" click="{{ route('tenant.garbtar.regulatory') }}">
            <x-slot name="left"></x-slot>
        </x-header>
    </x-slot>

    <div>
        <h2 class="text-[#44724D] uppercase text-xl font-bold">{{ __('Mitigating actions: Assets for the calculation of BTAR') }}</h2>

        <div class="mt-6">
            <x-inputs.search-bar isLeft='true' model="search" class="mt-4 border border-[#E1E6EF] h-full flex outline-none px-4 rounded !w-1/2" placeholder="{{ __('Search for assets by text or enter cell coordinates (ex: A01)') }}" />
        </div>
    </div>

    <x-garbtarassets.table
        :letters="$letters"
        :frameworkList="$frameworkList"
        :framework="$framework"
        :optionsKpi="$optionsKpi"
        :kpi="$kpi"
        numberVisibleLettersColumns="{{ sizeof($letters) }}"
        totalAtFirstColumn="{{ __('Total gross carrying amount') }}"
        unit="{{ __('Million EUR') }}"
        firstTitle="{{ __('Taxonomy relevant sectors (Taxonomy-eligible)') }}"
        secondTitle="{{ __('Environmentally sustainable (Taxonomy-aligned)') }}"
        :data="$data" />


    <div class="mt-6 pt-6">
        <h2 class="text-[#44724D] uppercase text-xl font-bold">{{ __('BTAR') }} %</h2>

        <div class="mt-6">
            <x-inputs.search-bar isLeft='true' model="searchPercentageTable" class="mt-4 border border-[#E1E6EF] h-full flex outline-none px-4 rounded !w-1/2" placeholder="{{ __('Search for assets by text or enter cell coordinates (ex: A01)') }}" />
        </div>
    </div>

    <x-garbtarassets.table
        :letters="$lettersPercentageTable"
        :frameworkList="$frameworkList"
        :framework="$frameworkPercentageTable"
        :optionsKpi="$optionsKpi"
        :kpi="$kpiPercentageTable"
        :formatToCurrency="$formatToCurrencyPercentageTable"
        numberVisibleLettersColumns="{{ sizeof($lettersPercentageTable) }}"
        totalAtLastColumn="{{ $frameworkPercentageTable === 'total' ? __('Proportion of total assets covered') : false }}"
        unit="{{ __('% (Compared to total covered assets in the denominator)') }}"
        firstTitle="{{ __('Proportion of eligible assets funding taxonomy relevant sectors') }}"
        secondTitle="{{ __('Environmentally sustainable') }}"
        :data="$dataPercentageTable"
        modelKpi='kpiPercentageTable'
        modelFramework='frameworkPercentageTable' />


    <div class="mt-6 pt-6">
        <h2 class="text-[#44724D] uppercase text-xl font-bold">{{ __('Summary table - BTAR') }}%</h2>

        @foreach($dataSummary as $table)
        <x-tables.table class="mt-6 table-fixed">
            <x-slot name="thead">
                <x-tables.tr>
                    <x-tables.td class="!py-1 !px-2 !border-b-0"></x-tables.td>
                    <x-tables.td class="!py-1 !px-2 text-center bg-[#E1E6EF]" colspan="4">{{ $table['title'] }}</x-tables.td>
                </x-tables.tr>
                <x-tables.tr>
                    <x-tables.td class="text-center !py-1 !px-2 !border-t-0"></x-tables.td>
                    <x-tables.td class="text-center !py-1 !px-2 border">{{ __('Climate change mitigation (CCM)') }}</x-tables.td>
                    <x-tables.td class="text-center !py-1 !px-2 border">{{ __('Climate change adaptation (CCA)') }}</x-tables.td>
                    <x-tables.td class="text-center !py-1 !px-2 border">{{ __('Total (CCM + CCA)') }}</x-tables.td>
                    <x-tables.td class="text-center !py-1 !px-2 border">
                        <span class="font-bold">{{ __('% coverage (over total assets)*') }}</span>
                    </x-tables.td>
                </x-tables.tr>
            </x-slot>

            @foreach($table['data'] as $item)
            <x-tables.tr wire:key="{{ $item['id'] }}">
                <x-tables.td class="text-[#444444] !py-1 !px-2 border border-[#E1E6EF]">
                    {{ $item['rowTitle'] }}
                </x-tables.td>
                @foreach($item['values'] as $key => $value)
                <x-tables.td class="text-[#444444] !py-1 !px-2 border border-[#E1E6EF] text-center font-bold">{{ $value }} %</x-tables.td>
                @endforeach
            </x-tables.tr>
            @endforeach

        </x-tables.table>
        @endforeach

    </div>

</div>