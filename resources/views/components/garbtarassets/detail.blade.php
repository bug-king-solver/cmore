<div class="flex flex-col gap-2">
    <div class="flex flex-row justify-around items-center !px-10">
        <div class="flex flex-col">
            <span
                class="uppercase text-[#757575]">{{ __('Change in the financial year') }}</span>
            <span
                class="mt-0.5 self-center text-sm rounded text-[#00AE4E] bg-[#00AE4E]/10 px-2 py-1">
                {{ formatToCurrency(floatval($row[App\Models\Tenant\GarBtar\BankAssets::CHANGE_IN_THE_FINANCIAL_YEAR])) }}
            </span>
        </div>
        <div class="flex flex-col">
            <span class="uppercase text-[#757575]">{{ __('Subject to NFDR') }}</span>
            <span class="mt-0.5 self-center">
                @if ($row[App\Models\Tenant\GarBtar\BankAssets::SUBJECT_NFDR] === App\Models\Tenant\GarBtar\BankAssets::YES)
                    @include('icons/yes_right', ['width' => 22, 'height' => 22])
                @else
                    @include('icons/no', ['color' => '#F03B20'])
                @endif
            </span>
        </div>
        <div class="flex flex-col">
            <span class="uppercase text-[#757575]">{{ __('European Company') }}</span>
            <span class="mt-0.5 self-center">
                @if ($row[App\Models\Tenant\GarBtar\BankAssets::EUROPEAN_COMPANY] === App\Models\Tenant\GarBtar\BankAssets::YES)
                    @include('icons/yes_right', ['width' => 22, 'height' => 22])
                @else
                    @include('icons/no', ['color' => '#F03B20'])
                @endif
            </span>
        </div>
        <div class="flex flex-col">
            <span class="uppercase text-[#757575]">{{ __('Specialised lending') }}</span>
            <span class="mt-0.5 self-center">
                @if ($row[App\Models\Tenant\GarBtar\BankAssets::SPECIFIC_PURPOSE] === App\Models\Tenant\GarBtar\BankAssets::YES)
                    @include('icons/yes_right', ['width' => 22, 'height' => 22])
                @else
                    @include('icons/no', ['color' => '#F03B20'])
                @endif
            </span>
        </div>
        @if($row[App\Models\Tenant\GarBtar\BankAssets::SIMULATION])
        <div class="flex flex-col">
            <span class="uppercase text-[#757575]">{{ __('Information') }}</span>
            <span class="mt-0.5 self-center">
                <span class="mt-0.5 self-center text-sm rounded text-esg8 bg-esg5/{{ ($row[App\Models\Tenant\GarBtar\BankAssets::SIMULATION][App\Models\Tenant\GarBtar\BankAssets::REAL]) ? 25 : 50 }} px-2 py-1">
                @if ($row[App\Models\Tenant\GarBtar\BankAssets::SIMULATION][App\Models\Tenant\GarBtar\BankAssets::REAL])
                    {{ __('Bank') }}
                @else
                    {{ __('Manual') }}
                @endif
            </span>
            </span>
        </div>
        @endif
    </div>
    <x-tables.table>
        <x-tables.tr class="!border-esg5 !border-y">
            <x-tables.td
                class="!border-esg5 !border-y bg-[#E1E6EF] !px-10 !py-2 text-center text-esg6 uppercase font-bold"
                rowspan="5">
                {{ __('Turnover') }}
            </x-tables.td>
        </x-tables.tr>

        <x-tables.tr class="bg-[#E1E6EF]/50">
            <x-tables.td class="!px-8 !py-2 text-[#757575] font-semibold uppercase text-xs">
                {{ __('CCM Eligible (€)') }}
            </x-tables.td>
            <x-tables.td class="!px-8 !py-2 text-[#757575] font-semibold uppercase text-xs">
                {{ __('CCM Aligned (€)') }}
            </x-tables.td>
            <x-tables.td class="!px-8 !py-2 text-[#757575] font-semibold uppercase text-xs">
                {{ __('CCM Transitional (€)') }}
            </x-tables.td>
            <x-tables.td class="!px-8 !py-2 text-[#757575] font-semibold uppercase text-xs">
                {{ __('CCM Enabling (€)') }}
            </x-tables.td>
        </x-tables.tr>

        <x-tables.tr>
            <x-tables.td class="!px-8 !py-2 text-[#757575]">
                {{ formatToCurrency($row->elegibilidadeCCM[App\Models\Tenant\GarBtar\BankAssets::STOCK][App\Models\Tenant\GarBtar\BankAssets::VN]) }}
            </x-tables.td>
            <x-tables.td class="!px-8 !py-2 text-[#757575]">
                {{ formatToCurrency($row->alinhamentoCCM[App\Models\Tenant\GarBtar\BankAssets::STOCK][App\Models\Tenant\GarBtar\BankAssets::VN]) }}
            </x-tables.td>
            <x-tables.td class="!px-8 !py-2 text-[#757575]">
                {{ formatToCurrency($row->transicaoCCM[App\Models\Tenant\GarBtar\BankAssets::STOCK][App\Models\Tenant\GarBtar\BankAssets::VN]) }}
            </x-tables.td>
            <x-tables.td class="!px-8 !py-2 text-[#757575]">
                {{ formatToCurrency($row->capacitanteCCM[App\Models\Tenant\GarBtar\BankAssets::STOCK][App\Models\Tenant\GarBtar\BankAssets::VN]) }}
            </x-tables.td>
        </x-tables.tr>

        <x-tables.tr class="bg-[#E1E6EF]/50">
            <x-tables.td class="!px-8 !py-2 text-[#757575] font-semibold uppercase text-xs">
                {{ __('CCA Eligible (€)') }}
            </x-tables.td>
            <x-tables.td class="!px-8 !py-2 text-[#757575] font-semibold uppercase text-xs">
                {{ __('CCA Aligned (€)') }}
            </x-tables.td>
            <x-tables.td class="!px-8 !py-2 text-[#757575] font-semibold uppercase text-xs">
                {{ __('CCA Transitional (€)') }}
            </x-tables.td>
            <x-tables.td class="!px-8 !py-2 text-[#757575] font-semibold uppercase text-xs">
                {{ __('CCA Enabling (€)') }}
            </x-tables.td>
        </x-tables.tr>

        <x-tables.tr>
            <x-tables.td class="!px-8 !py-2 text-[#757575]">
                {{ formatToCurrency($row->elegibilidadeCCA[App\Models\Tenant\GarBtar\BankAssets::STOCK][App\Models\Tenant\GarBtar\BankAssets::VN]) }}
            </x-tables.td>
            <x-tables.td class="!px-8 !py-2 text-[#757575]">
                {{ formatToCurrency($row->alinhamentoCCA[App\Models\Tenant\GarBtar\BankAssets::STOCK][App\Models\Tenant\GarBtar\BankAssets::VN]) }}
            </x-tables.td>
            <x-tables.td class="!px-8 !py-2 text-[#757575]">
                {{ formatToCurrency($row->adaptacaoCCA[App\Models\Tenant\GarBtar\BankAssets::STOCK][App\Models\Tenant\GarBtar\BankAssets::VN]) }}
            </x-tables.td>
            <x-tables.td class="!px-8 !py-2 text-[#757575]">
                {{ formatToCurrency($row->capacitanteCCA[App\Models\Tenant\GarBtar\BankAssets::STOCK][App\Models\Tenant\GarBtar\BankAssets::VN]) }}
            </x-tables.td>
        </x-tables.tr>

        <x-tables.tr class="!border-esg5 !border-y">
            <x-tables.td
                class="!border-esg5 !border-y bg-[#E1E6EF] !px-10 !py-2 text-center text-esg6 uppercase font-bold"
                rowspan="5">
                {{ __('CAPEX') }}
            </x-tables.td>
        </x-tables.tr>

        <x-tables.tr class="bg-[#E1E6EF]/50">
            <x-tables.td class="!px-8 !py-2 text-[#757575] font-semibold uppercase text-xs">
                {{ __('CCM Eligible (€)') }}
            </x-tables.td>
            <x-tables.td class="!px-8 !py-2 text-[#757575] font-semibold uppercase text-xs">
                {{ __('CCM Aligned (€)') }}
            </x-tables.td>
            <x-tables.td class="!px-8 !py-2 text-[#757575] font-semibold uppercase text-xs">
                {{ __('CCM Transitional (€)') }}
            </x-tables.td>
            <x-tables.td class="!px-8 !py-2 text-[#757575] font-semibold uppercase text-xs">
                {{ __('CCM Enabling (€)') }}
            </x-tables.td>
        </x-tables.tr>
        <x-tables.tr>
            <x-tables.td class="!px-8 !py-2 text-[#757575]">
                {{ formatToCurrency($row->elegibilidadeCCM[App\Models\Tenant\GarBtar\BankAssets::FLOW][App\Models\Tenant\GarBtar\BankAssets::CAPEX]) }}
            </x-tables.td>
            <x-tables.td class="!px-8 !py-2 text-[#757575]">
                {{ formatToCurrency($row->alinhamentoCCM[App\Models\Tenant\GarBtar\BankAssets::FLOW][App\Models\Tenant\GarBtar\BankAssets::CAPEX]) }}
            </x-tables.td>
            <x-tables.td class="!px-8 !py-2 text-[#757575]">
                {{ formatToCurrency($row->transicaoCCM[App\Models\Tenant\GarBtar\BankAssets::FLOW][App\Models\Tenant\GarBtar\BankAssets::CAPEX]) }}
            </x-tables.td>
            <x-tables.td class="!px-8 !py-2 text-[#757575]">
                {{ formatToCurrency($row->capacitanteCCM[App\Models\Tenant\GarBtar\BankAssets::FLOW][App\Models\Tenant\GarBtar\BankAssets::CAPEX]) }}
            </x-tables.td>
        </x-tables.tr>

        <x-tables.tr class="bg-[#E1E6EF]/50">
            <x-tables.td class="!px-8 !py-2 text-[#757575] font-semibold uppercase text-xs">
                {{ __('CCA Eligible (€)') }}
            </x-tables.td>
            <x-tables.td class="!px-8 !py-2 text-[#757575] font-semibold uppercase text-xs">
                {{ __('CCA Aligned (€)') }}
            </x-tables.td>
            <x-tables.td class="!px-8 !py-2 text-[#757575] font-semibold uppercase text-xs">
                {{ __('CCA Transitional (€)') }}
            </x-tables.td>
            <x-tables.td class="!px-8 !py-2 text-[#757575] font-semibold uppercase text-xs">
                {{ __('CCA Enabling (€)') }}
            </x-tables.td>
        </x-tables.tr>
        <x-tables.tr class="!border-esg5 !border-b">
            <x-tables.td class="!px-8 !py-2 text-[#757575] !border-esg5 !border-b">
                {{ formatToCurrency($row->elegibilidadeCCA[App\Models\Tenant\GarBtar\BankAssets::FLOW][App\Models\Tenant\GarBtar\BankAssets::CAPEX]) }}
            </x-tables.td>
            <x-tables.td class="!px-8 !py-2 text-[#757575] !border-esg5 !border-b">
                {{ formatToCurrency($row->alinhamentoCCA[App\Models\Tenant\GarBtar\BankAssets::FLOW][App\Models\Tenant\GarBtar\BankAssets::CAPEX]) }}
            </x-tables.td>
            <x-tables.td class="!px-8 !py-2 text-[#757575] !border-esg5 !border-b">
                {{ formatToCurrency($row->adaptacaoCCA[App\Models\Tenant\GarBtar\BankAssets::FLOW][App\Models\Tenant\GarBtar\BankAssets::CAPEX]) }}
            </x-tables.td>
            <x-tables.td class="!px-8 !py-2 text-[#757575] !border-esg5 !border-b">
                {{ formatToCurrency($row->capacitanteCCA[App\Models\Tenant\GarBtar\BankAssets::FLOW][App\Models\Tenant\GarBtar\BankAssets::CAPEX]) }}
            </x-tables.td>
        </x-tables.tr>

    </x-tables.table>
</div>
