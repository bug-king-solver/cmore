<div>
    <div class="mt-10">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
            @php
                $param = null;
                if ($ratio == null) {
                    $param = 'ratio';
                } elseif ($ratio != null && $ratio != 'ratio') {
                    $param = 'ratio';
                } else {
                    $param = null;
                }
            @endphp
            <x-garbtar.card title="{{ $kpi == 'gar' ? __('GAR') : __('BTAR') }}"
                percentage="{{ $data[$stockflow][$business][$kpi][$kpi] }}"
                class="{{ $ratio == 'ratio' ? '!bg-esg5/10 border border-[#39B54A]' : '' }}"
                x-on:click="gar = !gar, coverage = false, eligibility = false, alignment = false"
                wire:click="updateRation('{{ $param }}')">
                <x-slot:icon>
                    @if ($kpi == 'gar')
                        @include('icons.garbtar.gar')
                    @else
                        @include('icons.garbtar.btar')
                    @endif
                </x-slot:icon>

                <x-slot:button>
                    <span x-show="gar"> @include('icons.double_arrow_up') </span>
                    <span x-show="!gar"> @include('icons.double_arrow_up', ['class' => 'rotate-180']) </span>
                </x-slot:button>
            </x-garbtar.card>

            @php
                $param = null;
                if ($ratio == null) {
                    $param = 'coverage';
                } elseif ($ratio != null && $ratio != 'coverage') {
                    $param = 'coverage';
                } else {
                    $param = null;
                }
            @endphp
            <x-garbtar.card title="{{ __('Coverage') }}"
                percentage="{{ $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::COVERAGE] }}"
                class="{{ $ratio == 'coverage' ? '!bg-esg5/10 border border-[#39B54A]' : '' }}"
                x-on:click="gar = false, coverage = !coverage, eligibility = false, alignment = false"
                wire:click="updateRation('{{ $param }}')">
                <x-slot:icon>
                    @include('icons.garbtar.coverage')
                </x-slot:icon>

                <x-slot:button>
                    <span x-show="coverage"> @include('icons.double_arrow_up') </span>
                    <span x-show="!coverage"> @include('icons.double_arrow_up', ['class' => 'rotate-180']) </span>
                </x-slot:button>
            </x-garbtar.card>

            @php
                $param = null;
                if ($ratio == null) {
                    $param = 'eligibility';
                } elseif ($ratio != null && $ratio != 'eligibility') {
                    $param = 'eligibility';
                } else {
                    $param = null;
                }
            @endphp
            <x-garbtar.card title="{{ __('Eligibility') }}"
                percentage="{{ $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::ELIGIBLE] }}"
                class="{{ $ratio == 'eligibility' ? '!bg-esg5/10 border border-[#39B54A]' : '' }}"
                x-on:click="gar = false, coverage = false, eligibility = !eligibility, alignment = false"
                wire:click="updateRation('{{ $param }}')">
                <x-slot:icon>
                    @include('icons.garbtar.eligibility')
                </x-slot:icon>

                <x-slot:button>
                    <span x-show="eligibility"> @include('icons.double_arrow_up') </span>
                    <span x-show="!eligibility"> @include('icons.double_arrow_up', ['class' => 'rotate-180']) </span>
                </x-slot:button>
            </x-garbtar.card>

            @php
                $param = null;
                if ($ratio == null) {
                    $param = 'alignment';
                } elseif ($ratio != null && $ratio != 'alignment') {
                    $param = 'alignment';
                } else {
                    $param = null;
                }
            @endphp
            <x-garbtar.card title="{{ __('Alignment') }}"
                percentage="{{ $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::ALIGNED] }}"
                class="{{ $ratio == 'alignment' ? '!bg-esg5/10 border border-[#39B54A]' : '' }}"
                x-on:click="gar = false, coverage = false, eligibility = false, alignment = !alignment"
                wire:click="updateRation('{{ $param }}')">
                <x-slot:icon>
                    @include('icons.garbtar.alignment')
                </x-slot:icon>

                <x-slot:button>
                    <span x-show="alignment"> @include('icons.double_arrow_up') </span>
                    <span x-show="!alignment"> @include('icons.double_arrow_up', ['class' => 'rotate-180']) </span>
                </x-slot:button>
            </x-garbtar.card>
        </div>
    </div>

    @php
        $excludedValue = $kpi == 'gar'
            ? $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::ASSETS_EXCLUDED_FROM_NUMERATOR_GAR]
            : $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::ASSETS_EXCLUDED_FROM_NUMERATOR_GAR_BTAR];

        $percentExcludedValue = $kpi == 'gar'
                ? $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::ASSETS_EXCLUDED_FROM_NUMERATOR_GAR_PERCENT]
                : $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::ASSETS_EXCLUDED_FROM_NUMERATOR_GAR_BTAR_PERCENT];

        $coverBy = $kpi == 'gar'
            ? $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::ASSETS_COVERED_GAR]
            : $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::ASSETS_COVERED_BTAR];

        $percentCoverBy = $kpi == 'gar'
            ? $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::ASSETS_COVERED_GAR_PERCENT]
            : $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::ASSETS_COVERED_BTAR_PERCENT];

        $tooltips = [
            [
                'id' => 'aligned',
                'text' => __('Aligned'),
                'value' => $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::ELIGIBLE_AND_ALIGNED_ASSETS],
                'percentagem' => $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::ELIGIBLE_AND_ALIGNED_ASSETS_PERCENT],
            ],
            [
                'id' => 'notaligned',
                'text' => __('Not aligned'),
                'value' => $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::ELIGIBLE_AND_NOT_ALIGNED_ASSETS],
                'percentagem' => $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::ELIGIBLE_AND_NOT_ALIGNED_ASSETS_PERCENT],
            ],
            [
                'id' => 'nodata',
                'text' => __('Not data'),
                'value' => $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::ASSETS_WITHOUT_DATA] ?? 0,
                'percentagem' => $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::ASSETS_WITHOUT_DATA_PERCENT] ?? 0,
            ],
            [
                'id' => 'notaligned2',
                'text' => __('Not eligible'),
                'value' => $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::NOT_ELIGIBLE_ASSETS],
                'percentagem' => $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::NOT_ELIGIBLE_ASSETS_PERCENT],
            ],
            [
                'id' => 'notaligned3',
                'text' => __('Excluded from the ') . ($kpi == 'gar' ? __('GAR') : __('BTAR')),
                'value' => $excludedValue,
                'percentagem' => $percentExcludedValue,
            ],
            [
                'id' => 'notaligned4',
                'text' => __('Excluded from ratios'),
                'value' => $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::ASSETS_EXCLUDED_NUMERATOR_DENOMINATOR],
                'percentagem' => $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::ASSETS_EXCLUDED_NUMERATOR_DENOMINATOR_PERCENT],
            ],
            [
                'id' => 'eligible',
                'text' => __('Eligible'),
                'value' => $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::ELIGIBLE_ASSETS],
                'percentagem' => $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::ELIGIBLE_ASSETS_PERCENT],
            ],
            [
                'id' => 'covered',
                'text' => __('Covered by the ') . ($kpi == 'gar' ? __('GAR') : __('BTAR')),
                'value' => $coverBy,
                'percentagem' => $percentCoverBy,
            ],
            [
                'id' => 'relevant',
                'text' => __('Relevant for ratio calculations') . ($stockflow == 'flow' ? __('(Flow)') : ''),
                'value' => $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::DENOMINATOR],
                'percentagem' => $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::DENOMINATOR_PERCENT],
            ],
            [
                'id' => 'balance',
                'text' => __('Balance sheet total'),
                'value' => $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::BANK_ASSETS],
                'percentagem' => '100',
            ],
        ];

    @endphp
    <div class="grid mt-4 bg-white p-8 shadow rounded">
        <div class="flex relative">
            <div class="w-[40%] h-10 flex items-center cursor-pointer justify-between px-3 {{ $ratio == 'ratio' || $ratio == 'alignment' || ($ratio == null && $graphOption == null) || $graphOption == 'aligned' ? 'bg-[#9CDAA5] text-white' : 'bg-[#E1E6EF] text-esg16 border border-[#B1B1B1] border-b-0' }}"
                wire:click="updateGraphselection('aligned')" @if(!isset($ratio)) data-tooltip-target="tooltip-aligned" @elseif($ratio == 'ratio' || $ratio == 'alignment') data-tooltip-target="tooltip-numerator-{{$ratio}}" @endif>
                <span class="text-sm ">{{ __('Aligned') }}</span>
                <div class="flex items-center gap-2">
                    <span class="text-xl font-extrabold">{{ $tooltips[0]['percentagem'] }}%</span>
                    <div
                        class="w-5 h-5 grid place-content-center bg-white rounded border border-slate-200 cursor-pointer">
                        @include('icons.double_arrow_up', ['class' => 'rotate-90'])
                    </div>
                </div>
            </div>
            <div class="absolute w-[60%] ml-[40%]">
                <div class="ml-[0%] w-[33.3%] h-10 cursor-pointer border border-dotted  grid place-content-center text-esg16 {{ ($ratio == null && $graphOption == null) || $graphOption == 'notaligned' ? 'border-[#39B54A] bg-[#E2F4E4]' : 'bg-[#E1E6EF] border-[#B1B1B1] border-b-0' }}"
                    wire:click="updateGraphselection('notaligned')" @if(!isset($ratio)) data-tooltip-target="tooltip-notaligned" @endif>
                    <p class="text-center text-xs">{{ __('Not aligned') }} </p>
                    <div class="flex items-center gap-1">
                        <span class="text-base font-extrabold">{{ $tooltips[1]['percentagem'] }}%</span>
                        <div
                            class="w-5 h-5 grid place-content-center bg-white rounded border border-slate-200 cursor-pointer">
                            @include('icons.double_arrow_up', ['class' => 'rotate-90'])</div>
                    </div>
                </div>

                <div class="ml-[33.3%] w-[16.8%] h-20 -mt-10 cursor-pointer border border-dotted grid place-content-center text-esg16 {{ ($ratio == null && $graphOption == null) || $graphOption == 'nodata' ? 'bg-[#c8cbd1] border-[#B1B1B1] border-b-0' : 'bg-[#E1E6EF] border-[#B1B1B1] border-b-0' }}"
                    wire:click="updateGraphselection('nodata')" @if(!isset($ratio)) data-tooltip-target="tooltip-nodata" @endif>
                    <p class="text-center text-xs">{{ __('No data ') }} </p>
                    <div class="flex items-center gap-1">
                        <span class="text-base font-extrabold">{{ $tooltips[2]['percentagem'] }}%</span>
                        <div
                            class="w-5 h-5 grid place-content-center bg-white rounded border border-slate-200 cursor-pointer">
                            @include('icons.double_arrow_up', ['class' => 'rotate-90'])</div>
                    </div>
                </div>

                <div class="ml-[50.1%] w-[16.5%] h-20 -m-[80px] cursor-pointer border border-dotted grid place-content-center text-esg16 {{ ($ratio == null && $graphOption == null) || $graphOption == 'notaligned2' ? 'border-[#39B54A] bg-[#E2F4E4]' : 'bg-[#E1E6EF] border-[#B1B1B1] border-b-0' }}"
                    wire:click="updateGraphselection('notaligned2')" @if(!isset($ratio)) data-tooltip-target="tooltip-notaligned2" @endif>
                    <p class="text-center text-xs">{{ __('Not eligible') }} </p>
                    <div class="flex items-center gap-1">
                        <span class="text-base font-extrabold">{{ $tooltips[3]['percentagem'] }}%</span>
                        <div
                            class="w-5 h-5 grid place-content-center bg-white rounded border border-slate-200 cursor-pointer">
                            @include('icons.double_arrow_up', ['class' => 'rotate-90'])</div>
                    </div>
                </div>

                <div class="ml-[66.6%] w-[16.7%] h-[120px] -m-[80px] cursor-pointer border border-dotted grid place-content-center text-esg16 justify-center {{ ($ratio == null && $graphOption == null) || $graphOption == 'notaligned3' ? 'border-[#39B54A] bg-[#E2F4E4]' : 'bg-[#E1E6EF] border-[#B1B1B1] border-b-0' }}"
                    wire:click="updateGraphselection('notaligned3')" @if(!isset($ratio)) data-tooltip-target="tooltip-notaligned3" @endif>
                    <p class="text-center text-xs">{{ __('Excluded from the ') }}
                        {{ $kpi == 'gar' ? __('GAR') : __('BTAR') }}</p>
                    <div class="items-center text-center justify-center gap-1">
                        <span class="text-base font-extrabold">{{ $tooltips[4]['percentagem'] }}%</span>
                    </div>
                    <div class="flex justify-center">
                        <div
                            class="w-5 h-5 grid place-content-center bg-white rounded border border-slate-200 cursor-pointer">
                            @include('icons.double_arrow_up', ['class' => 'rotate-90'])</div>
                    </div>
                </div>

                <div class="ml-[83.3%] w-[16.6%] h-[160px] -m-[120px] cursor-pointer border border-dotted grid place-content-center justify-center text-esg16 {{ ($ratio == null && $graphOption == null) || $graphOption == 'notaligned4' ? 'border-[#39B54A] bg-[#E2F4E4]' : 'bg-[#E1E6EF] border-[#B1B1B1] border-b-0' }}"
                    wire:click="updateGraphselection('notaligned4')" @if(!isset($ratio)) data-tooltip-target="tooltip-notaligned4" @endif>
                    <p class="text-center text-xs">{{ __('Excluded from ratios') }} </p>
                    <div class="items-center text-center justify-center gap-1">
                        <span class="text-base font-extrabold">{{ $tooltips[5]['percentagem'] }}%</span>
                    </div>
                    <div class="flex justify-center">
                        <div
                            class="w-5 h-5 grid place-content-center bg-white rounded border border-slate-200 cursor-pointer">
                            @include('icons.double_arrow_up', ['class' => 'rotate-90'])</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-[60%] h-10 flex items-center cursor-pointer justify-between px-3 {{ $ratio == 'eligibility' || ($ratio == null && $graphOption == null) || $graphOption == 'eligible' ? 'bg-[#89C694] text-white' : 'bg-[#E1E6EF] text-esg16 border border-[#B1B1B1] border-b-0' }}"
            wire:click="updateGraphselection('eligible')" @if(!isset($ratio)) data-tooltip-target="tooltip-eligible" @elseif($ratio == 'eligibility') data-tooltip-target="tooltip-numerator-{{$ratio}}" @endif>
            <span class="text-sm ">{{ __('Eligible') }}</span>
            <div class="flex items-center gap-2">
                <span class="text-xl font-extrabold">{{ $tooltips[6]['percentagem'] }}%</span>
                <div
                    class="w-5 h-5 ml-2 grid place-content-center bg-white rounded border border-slate-200 cursor-pointer">
                    @include('icons.double_arrow_up', ['class' => 'rotate-90'])</div>
            </div>
        </div>

        <div class="w-[80%] h-10 flex items-center cursor-pointer justify-between px-3 {{ $ratio == 'coverage' || $ratio == 'eligibility' || $ratio == 'alignment' || ($ratio == null && $graphOption == null) || $graphOption == 'covered' ? 'bg-[#7FBC8B] text-white' : 'bg-[#E1E6EF] text-esg16 border border-[#B1B1B1] border-b-0' }}"
            wire:click="updateGraphselection('covered')" @if(!isset($ratio)) data-tooltip-target="tooltip-covered" @elseif($ratio == 'coverage') data-tooltip-target="tooltip-numerator-{{$ratio}}" @elseif($ratio == 'eligibility' || $ratio == 'alignment') data-tooltip-target="tooltip-denominator-{{$ratio}}" @endif>
            <span class="text-sm ">{{ __('Covered by the ') }} {{ $kpi == 'gar' ? __('GAR') : __('BTAR') }}</span>
            <div class="flex items-center gap-2">
                <span class="text-xl font-extrabold">{{ $tooltips[7]['percentagem'] }}%</span>
                <div
                    class="w-5 h-5 ml-2 grid place-content-center bg-white rounded border border-slate-200 cursor-pointer">
                    @include('icons.double_arrow_up', ['class' => 'rotate-90'])</div>
            </div>
        </div>

        <div class="w-[90%] h-10 flex items-center cursor-pointer justify-between px-3 {{ $ratio == 'ratio' || ($ratio == null && $graphOption == null) || $graphOption == 'relevant' ? 'bg-[#629E71] text-white' : 'bg-[#E1E6EF] text-esg16 border border-[#B1B1B1] border-b-0' }}"
            wire:click="updateGraphselection('relevant')" @if(!isset($ratio)) data-tooltip-target="tooltip-relevant" @elseif($ratio == 'ratio') data-tooltip-target="tooltip-denominator-{{$ratio}}" @endif>
            <span class="text-sm ">{{ __('Relevant for ratio calculations') }}
                {{ $stockflow == 'flow' ? __('(Flow)') : '' }}</span>
            <div class="flex items-center gap-2">
                <span class="text-xl font-extrabold">{{ $tooltips[8]['percentagem'] }}%</span>
                <div
                    class="w-5 h-5 ml-2 grid place-content-center bg-white rounded border border-slate-200 cursor-pointer">
                    @include('icons.double_arrow_up', ['class' => 'rotate-90'])</div>
            </div>
        </div>

        <div class="w-[100%] h-10 flex items-center cursor-pointer justify-between px-3 {{ $ratio == 'coverage' || ($ratio == null && $graphOption == null) || $graphOption == 'balance' ? 'bg-[#4F895F] text-white' : 'bg-[#E1E6EF] text-esg16 border border-[#B1B1B1]' }}"
            wire:click="updateGraphselection('balance')" @if(!isset($ratio)) data-tooltip-target="tooltip-balance" @elseif($ratio == 'coverage') data-tooltip-target="tooltip-denominator-{{$ratio}}" @endif>
            <span class="text-sm ">{{ __('Balance sheet total') }}</span>
            <div class="flex items-center gap-2">
                <span class="text-xl font-extrabold">{{ $tooltips[9]['percentagem'] }}%</span>
                <div
                    class="w-5 h-5 ml-2 grid place-content-center bg-white rounded border border-slate-200 cursor-pointer">
                    @include('icons.double_arrow_up', ['class' => 'rotate-90'])</div>
            </div>
        </div>
    </div>
    @if(!isset($ratio))
    @foreach ($tooltips as $tooltip)
        <div id="tooltip-{{ $tooltip['id'] }}" role="tooltip"
            class="shadow-lg absolute z-10 max-w-md invisible inline-block px-3 py-2 text-sm font-medium text-black transition-opacity duration-300 bg-white rounded-lg opacity-0 tooltip dark:bg-gray-700">
            <div class="grid grid-rows-3 gap content-center">
                <div class="text-center">
                    <div class="w-3 h-3 rounded-full bg-[#3C814F] inline-block"></div>
                    <span class="ml-2 text-center text-sm text-[#444444]">{{ $tooltip['text'] }}</span>
                </div>
                <span class="text-center text-xl text-[#3C814F] font-black">{{ $tooltip['percentagem'] }} %</span>
                <span class="text-center text-base text-[#444444]">
                    <x-currency :value="$tooltip['value']" />
                </span>
            </div>
            <div class="tooltip-arrow" data-popper-arrow></div>
        </div>
    @endforeach
    @else
    <div id="tooltip-numerator-{{$ratio}}"
        class="shadow-lg absolute z-10 max-w-md invisible inline-block p-2 text-lg font-medium text-black transition-opacity duration-300 bg-white rounded-lg opacity-0 tooltip dark:bg-gray-700">
        <div class="grid grid-rows-1 gap content-center">
            <div class="text-center">
                <span class="text-center text-lg font-bold text-[#9CDAA5] uppercase">{{ __('Numerator') }}</span>
            </div>
        </div>
        <div class="tooltip-arrow" data-popper-arrow></div>
    </div>
    <div id="tooltip-denominator-{{$ratio}}"
        class="shadow-lg absolute z-10 max-w-md invisible inline-block p-2 text-lg font-medium text-black transition-opacity duration-300 bg-white rounded-lg opacity-0 tooltip dark:bg-gray-700">
        <div class="grid grid-rows-1 gap content-center">
            <div class="text-center">
                <span class="text-center text-lg font-bold text-[#629E71] uppercase">{{ __('Denominator') }}</span>
            </div>
        </div>
        <div class="tooltip-arrow" data-popper-arrow></div>
    </div>
    @endif

</div>
@push('body')
    <script nonce="{{ csp_nonce() }}">
        document.addEventListener("DOMContentLoaded", () => {
            Livewire.hook('message.processed', (message, component) => {
                document.querySelectorAll('[data-tooltip-target]').forEach(function(triggerEl) {
                    const targetEl = document.getElementById(triggerEl.getAttribute(
                        'data-tooltip-target'));
                    const triggerType = triggerEl.getAttribute('data-tooltip-trigger');
                    const placement = triggerEl.getAttribute('data-tooltip-placement');
                    new Tooltip(targetEl, triggerEl, {
                        placement: placement ? placement : 'top',
                        triggerType: triggerType ? triggerType : 'hover'
                    });
                });

            })
        });
    </script>
@endpush
