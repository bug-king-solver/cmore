@push('body')
    <script nonce="{{ csp_nonce() }}">
        document.addEventListener("chartUpdated", event => {
            pieChartNew('ratio_calculations');
            pieChartNew('aligned_transition');
            pieChartNew('aligned_capacitating');
            pieChartNew('aligned_specialised');
            pieChartNew('excluded_ratio');
            pieChartNew('exculded_asset_type_new');

            barChartNew('balance_total');
            barChartNew('aligned_asset');
            barChartNew('notaligned_asset');
            barChartNew('not_eligible_asset');
            barChartNew('eligible_asset');
            barChartNew('entity_type');
            barChartNew('asset_type');
            barChartNew('main_type');
            barChartNew('eligible_entity_type');
            barChartNew('eligible_main_type');
            barChartNew('aligned_entity_type');
            barChartNew('aligned_main_type');
            barChartNew('aligned_enviroment_type');
            barChartNew('notaligned_entity_type');
            barChartNew('notaligned_main_type');
            barChartNew('not_eligible2_entity_type');
            barChartNew('not_eligible2_main_type');
            barChartNew('exculded_main_type');
            barChartNew('exculded_asset_type');
            barChartNew('no_data_asset');
            barChartNew('no_data_entity_type');
            barChartNew('no_data_main_type');
        });
    </script>
@endpush

<div id="garbtarblock" class="px-4 md:px-0" x-data="{ gar: false, coverage: false, eligibility: false, alignment: false }">
    <x-slot name="header">
        <x-header title="{{ __('Painel GAR/BTAR') }}" dataTest="data-header" class="!bg-transparent" textcolor="text-esg5"
            iconcolor="{{ color(5) }}" click="#">
            <x-slot name="left"></x-slot>
            <x-slot name="right">
                <x-buttons.a-icon modal="gar-btar.import" :data="''" data-test="add-data-btn"
                    class="flex place-content-end uppercase">
                    <div class="flex gap-1 items-center bg-esg5 py-2 px-4 cursor-pointer rounded-md text-esg4">
                        @include('icons/library/upload', ['color' => color(4)])
                        <label>{{ __('Upload') }}</label>
                    </div>
                </x-buttons.a-icon>
            </x-slot>
        </x-header>
    </x-slot>

    <div class="flex items-center gap-5">
        <div class="">
            <x-inputs.select id="period" :extra="['options' => $periodList]"
                class="!h-10 !p-0 !pt-1.5 !pb-2 !pl-4 !pr-10 !border-esg7/40" />
        </div>

        <div class="">
            <div class="inline-flex rounded-md shadow-sm bg-esg6/10 p-1" role="group">
                <button type="button"
                    class="px-2 py-1 text-base !font-medium {{ $kpi == 'gar' ? 'text-esg6 bg-white shadow' : 'text-esg8' }} rounded-md "
                    wire:click="changeKpi('gar')">
                    {{ __('GAR') }}
                </button>
                <button type="button"
                    class="px-2 py-1 text-base !font-medium {{ $kpi == 'btar' ? 'text-esg6 bg-white shadow' : 'text-esg8 ' }} rounded-md "
                    wire:click="changeKpi('btar')">
                    {{ __('BTAR') }}
                </button>
            </div>
        </div>

        <div class="">
            <div class="inline-flex rounded-md shadow-sm bg-esg6/10 p-1" role="group">
                <button type="button"
                    class="px-2 py-1 text-base !font-medium {{ $stockflow == 'stock' ? 'text-esg6 bg-white shadow' : 'text-esg8' }} rounded-md"
                    wire:click="changeStockFlow('stock')">
                    {{ __('Stock') }}
                </button>
                <button type="button"
                    class="px-2 py-1 text-base !font-medium {{ $stockflow == 'flow' ? 'text-esg6 bg-white shadow' : 'text-esg8' }} rounded-md"
                    wire:click="changeStockFlow('flow')">
                    {{ __('Flow') }}
                </button>
            </div>
        </div>

        <div class="">
            <div class="inline-flex rounded-md shadow-sm bg-esg6/10 p-1" role="group">
                <button type="button"
                    class="px-2 py-1 text-base !font-medium {{ $business == 'volum' ? 'text-esg6 bg-white shadow' : 'text-esg8' }} rounded-md"
                    wire:click="changeBusiness('volum')">
                    {{ __('Business volume') }}
                </button>
                <button type="button"
                    class="px-2 py-1 text-base !font-medium {{ $business == 'capex' ? 'text-esg6 bg-white shadow' : 'text-esg8' }} rounded-md"
                    wire:click="changeBusiness('capex')">
                    {{ __('CAPEX') }}
                </button>
            </div>
        </div>




    </div>

    <x-garbtar.maingraph :data="$data" :kpi="$kpi" :graphOption="$graphOption" :ratio="$ratio" :stockflow="$stockflow"
        :business="$business" />

    <x-garbtar.info :kpi="$kpi" :stockflow="$stockflow" :ratio="$ratio" />

    {{-- Balance sheet total --}}
    <x-garbtar.balance_sheet :data="$data" :graphOption="$graphOption" :kpi="$kpi" :stockflow="$stockflow"
        :business="$business" />

    {{-- Relevant for ratio calculations --}}
    <x-garbtar.relevant :data="$data" :graphOption="$graphOption" :kpi="$kpi" :stockflow="$stockflow"
        :business="$business" />

    {{-- Excluded from ratios --}}
    <x-garbtar.excluded_ratio :data="$data" :graphOption="$graphOption" :kpi="$kpi" :stockflow="$stockflow"
        :business="$business" />

    {{-- Covered by the GAR/BTAR --}}
    <x-garbtar.coverby :data="$data" :graphOption="$graphOption" :kpi="$kpi" :stockflow="$stockflow" :business="$business"
        :itemsInMainSectorsGraph="$itemsInMainSectorsGraph" :itensInMainSectorsTable="$itensInMainSectorsTable" />

    {{-- Excluded from  GAR/BTAR --}}
    <x-garbtar.excluded_garbtar :data="$data" :graphOption="$graphOption" :kpi="$kpi" :stockflow="$stockflow"
        :business="$business" :itemsInMainSectorsGraph="$itemsInMainSectorsGraph" :itensInMainSectorsTable="$itensInMainSectorsTable" />

    {{-- Eligible --}}
    <x-garbtar.eligible :data="$data" :graphOption="$graphOption" :kpi="$kpi" :stockflow="$stockflow" :business="$business"
        :itemsInMainSectorsGraph="$itemsInMainSectorsGraph" :itensInMainSectorsTable="$itensInMainSectorsTable" />

    {{-- Not Eligible --}}
    <x-garbtar.not_eligible :data="$data" :graphOption="$graphOption" :kpi="$kpi" :stockflow="$stockflow"
        :business="$business" :itemsInMainSectorsGraph="$itemsInMainSectorsGraph" :itensInMainSectorsTable="$itensInMainSectorsTable" />

    {{-- Not Aligned --}}
    <x-garbtar.not_aligned :data="$data" :graphOption="$graphOption" :kpi="$kpi" :stockflow="$stockflow"
        :business="$business" :itemsInMainSectorsGraph="$itemsInMainSectorsGraph" :itensInMainSectorsTable="$itensInMainSectorsTable" />

    {{-- Aligned --}}
    <x-garbtar.aligned :data="$data" :graphOption="$graphOption" :kpi="$kpi" :stockflow="$stockflow" :business="$business"
        :itemsInMainSectorsGraph="$itemsInMainSectorsGraph" :itensInMainSectorsTable="$itensInMainSectorsTable" />

    {{-- Not Data --}}
    <x-garbtar.no_data :data="$data" :graphOption="$graphOption" :kpi="$kpi" :stockflow="$stockflow" :business="$business"
        :itemsInMainSectorsGraph="$itemsInMainSectorsGraph" :itensInMainSectorsTable="$itensInMainSectorsTable" />
</div>
