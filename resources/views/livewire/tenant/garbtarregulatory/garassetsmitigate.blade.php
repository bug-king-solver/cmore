<div class="px-4 md:px-0">

    <x-slot name="header">
        <x-header title="{{ __('Mitigating Actions - GAR Assets') }}" dataTest="data-header" class="!bg-transparent" textcolor="text-[#39B54A]" iconcolor="#39B54A" click="{{ route('tenant.garbtar.regulatory') }}">
            <x-slot name="left"></x-slot>
        </x-header>
    </x-slot>

    <div>
        <h2 class="text-[#44724D] uppercase text-xl font-bold">{{ __('Mitigating actions: Assets for the calculation of GAR') }}</h2>

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

</div>
