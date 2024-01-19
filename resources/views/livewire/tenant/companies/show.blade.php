<div class="px-4 md:px-0">
    @push('body')
        <script nonce="{{ csp_nonce() }}">
            document.addEventListener("chartUpdated", event => {
                //pieChartNew('ratio_calculations');
                barChartNew('ratio');
            });
        </script>
    @endpush

    <x-slot name="header">
        <x-header title="{{ $company->name }}" data-test="companies-header" click="{{ route('tenant.companies.list') }}"
            chip="{{ $company->id }}">
            <x-slot name="left">
            </x-slot>
            <x-slot name="right">
                <x-buttons.a-icon href="{{ route('tenant.companies.form', ['company' => $company->id]) }}"
                    data-test="add-data-btn" class="flex place-content-end">
                    <div
                        class="flex gap-1 items-center bg-esg4 cursor-pointer rounded-md text-esg16 py-1 px-2 border border-esg16/70 rounded-md">
                        @include('icons.edit', ['color' => color(16)])
                        <label>{{ __('Edit') }}</label>
                    </div>
                </x-buttons.a-icon>
            </x-slot>
        </x-header>
    </x-slot>

    <div class="justify-start items-start gap-10 flex">

        <div class="w-9/12">

            <x-tabs.panel :tablist="$tabList" :activetab="$activeTab" />

            {{-- General tab --}}
            <div class="{{ $activeTab == 'general_esg_information' ? '' : 'hidden' }}">
                <div class="mt-10">
                    @forelse ($questionnaires as $id => $questionnaireList)
                        <div class="self-stretch h-auto mb-4 p-4 rounded-md shadow-md flex flex-col justify-start items-center gap-6"
                            wire:key='type-{{ $id }}-{{ time() }}'>
                            @php $data = array_column($questionnaireList, 'id'); @endphp
                            @livewire("dashboard.mini.dashboard{$id}", ['questionnaires' => $data], key('dashboard' . $id))
                        </div>
                    @empty
                        <div class="flex w-full text-center justify-center items-center gap-4">
                            <span class="text-gray-500 text-center">{{ __('No data available yet.') }}</span>
                        </div>
                    @endforelse
                </div>
            </div>

            @if (tenant()->hasGarBtarFeature)
                {{-- Ratios & Impact tab --}}
                <div class="{{ $activeTab == 'ratios_and_impacts' ? '' : 'hidden' }}">
                    <div class="flex items-center gap-5 mt-10">
                        <div class="">
                            <div class="inline-flex rounded-md shadow-sm bg-esg6/10 p-1" role="group">
                                <button type="button"
                                    class="px-2 py-1 text-base !font-medium {{ $kpi == 'real' ? 'text-esg6 bg-white shadow' : 'text-esg8' }} rounded-md "
                                    wire:click="changeKpi('real')">
                                    {{ __('Real') }}
                                </button>
                                @if ($listSimulations->count() > 0)
                                    <button type="button"
                                        class="px-2 py-1 text-base !font-medium {{ $kpi == 'simulation' ? 'text-esg6 bg-white shadow' : 'text-esg8 ' }} rounded-md "
                                        wire:click="changeKpi('simulation')">
                                        {{ __('Simulation') }}
                                    </button>
                                @endif
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

                        <div class="ml-auto">
                            <div class="inline-flex gap-5">
                                @php $simulationdata = json_encode(["company" => $company->id]); @endphp
                                @if ($kpi != 'real')
                                    <select class="rounded border border-[#E1E6EF]" wire:model="simulationIndex">
                                        @foreach ($listSimulations as $simulationItem)
                                            <option value="{{ $loop->index }}">{{ $simulationItem->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                                <x-buttons.btn-alt modal="companies.modals.ratios.simulation" :data="$simulationdata"
                                    class="px-4 py-2 !bg-esg6/70 !border-esg6">
                                    <x-slot name="buttonicon">
                                        <span
                                            class="text-esg4 flex justify-center items-center normal-case font-normal text-lg"
                                            title="{{ __('Add new simulation') }}">
                                            @if ($kpi != 'real')
                                                +
                                            @else
                                                + {{ __('Simulation') }}
                                            @endif
                                        </span>
                                    </x-slot>
                                </x-buttons.btn-alt>
                            </div>
                        </div>
                    </div>

                    <x-garbtar.company.ratio :kpi="$kpi" :stockflow="$stockflow" :company="$company" :business="$business"
                        :simulation="$simulation" :denominatorGAR="$denominatorGAR" :denominatorBTAR="$denominatorBTAR" :rowActive="$rowActive" />
                </div>
            @endif

            @if (tenant()->hasSharingEnabled)
                {{-- Sharing Consent tab --}}
                <div class="{{ $activeTab == 'sharing_consent' ? '' : 'hidden' }}">
                    <div class="flex w-full gap-5 mt-10">
                        <x-tables.table class="w-full">
                            <x-slot name="thead">
                                <x-tables.th
                                    class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left uppercase">{{ __('Name') }}</x-tables.th>
                                <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left">&nbsp;
                                </x-tables.th>
                            </x-slot>

                            @foreach ($listBankEcoSystems as $index => $item)
                                @php
                                    $hasSharing = findInCollection($company->sharingOptions, 'id', $item->id);
                                    $title = $hasSharing ? 'Revoke Access' : 'Grant Access';
                                    $params = json_encode(['company' => $company->id, 'sharing' => $item->id]);
                                @endphp

                                <x-tables.tr>
                                    <x-tables.td class="text-sm !border-b-esg7/40">
                                        <div class="text-esg8 text-sm">{!! $item['name'] !!}</div>
                                    </x-tables.td>
                                    <x-tables.td class="text-sm !border-b-esg7/40 flex justify-center">
                                        <x-buttons.a-icon modal="companies.modals.sharing-consent-modal"
                                            :data="$params" class="flex place-content-end uppercase cursor-pointer">
                                            <div
                                                class="flex gap-1 items-center py-2 px-4 rounded-md border {{ !$hasSharing ? 'bg-esg5 hover:bg-esg5/80 border-esg5/70' : 'bg-esg4 hover:bg-esg4/80 border-esg16/70' }} w-fit">
                                                <label class="cursor-pointer {{ !$hasSharing ? 'text-esg4' : 'text-esg16' }}">
                                                    {{ $title }}
                                                </label>
                                            </div>
                                        </x-buttons.a-icon>
                                    </x-tables.td>
                                </x-tables.tr>
                            @endforeach

                        </x-tables.table>

                    </div>
                </div>
            @endif

            {{-- Info tab --}}
            <div class="{{ $activeTab == 'info' ? '' : 'hidden' }}">

                <div class="mt-10">
                    <label class="text-esg5 text-lg font-medium">{!! __('Locations') !!}</label>
                </div>

                <div class="mt-10">
                    <x-tables.dynamic.table :resource="$company->locations()->get()" :header="[__('Name'), __('Location')]" :body="['nameHeadquarter', 'fullAddress']" :buttons="[]"
                        defaultNoRecords="{{ __('No locations found') }}" />
                </div>

                <div class="mt-10">
                    <label class="text-esg5 text-lg font-medium">{!! __('Questionnaires') !!}</label>
                </div>

                <div class="mt-10">
                    <x-tables.questionnaire.list :questionnaires="$this->questionnaireList" />
                </div>

                <div class="mt-10">
                    <label class="text-esg5 text-lg font-medium">{!! __('Internal notes') !!}</label>
                    <x-buttons.btn-icon-text modal="companies.modals.notes.form" :data="json_encode(['company' => $company->id])"
                        class="cursor-pointer !bg-esg4 !normal-case !text-[#757575] !border-0 !font-normal"
                        :param="json_encode(['color' => color(16), 'width' => 14, 'height' => 14])">
                        <x-slot:buttonicon>+</x-slot:buttonicon>
                        <x-slot:slot>{!! __('Add') !!}</x-slot:slot>
                    </x-buttons.btn-icon-text>

                    <x-cards.company.notes :notes="$this->notes" />
                </div>

                <div class="mt-10">
                    <label class="text-esg5 text-lg font-medium">{!! __('Users') !!}</label>
                    <x-tables.user.list :users="$this->users" />
                </div>

            </div>
        </div>

        <div class="w-3/12 h-auto p-6 rounded shadow justify-center items-center gap-6 flex">
            <div class="grow shrink basis-0 flex-col justify-center items-start gap-6 inline-flex">
                <div class="self-stretch flex-col justify-center items-start gap-2 flex">
                    <div class="justify-start items-center gap-1 inline-flex">
                        <div class="text-esg5 text-base font-bold uppercase">{{ __('Basic Information') }}</div>
                    </div>
                </div>

                <div class="self-stretch flex-col justify-center items-start gap-2 flex">
                    <div class="justify-start items-center gap-1 inline-flex">
                        @include('icons.building-v2', [
                            'width' => '16px',
                            'height' => '16px',
                            'color' => color(5),
                        ]) <div class="text-esg5 text-base font-normal">
                            {{ __('VAT number') }}
                        </div>
                    </div>
                    <div class="justify-start items-center gap-2.5 inline-flex">
                        <div class="text-esg8 text-base font-normal">{{ $company->vat_number ?? '-' }}</div>
                    </div>
                </div>

                <div class="self-stretch flex-col justify-center items-start gap-2 flex">
                    <div class="justify-start items-center gap-1 inline-flex">
                        <div class="min-w-4 self-start pt-1">
                            @include('icons.building-v2', [
                                'width' => '16px',
                                'height' => '16px',
                                'color' => color(5),
                            ])
                        </div>
                        <div class="text-esg5 text-base font-normal">
                            {{ __('VAT of the subsidiaries included in the consolidation perimeter of the ESG exercise') }}
                        </div>
                    </div>
                    <div class="justify-start items-center gap-2.5 inline-flex">
                        @empty($company->vat_secundary)
                        <div class="text-esg8 text-base font-normal">-</div>
                        @else
                        <div class="text-esg8 text-base font-normal">
                        @foreach($company->vat_secundary as $vat)
                        {{ $vat['title'] }}<br>
                        @endforeach
                        </div>
                        @endempty
                    </div>
                </div>

                @if (tenant()->companiesTypeSelfManaged)
                    <div class="self-stretch flex-col justify-center items-start gap-2 flex">
                        <div class="justify-start items-center gap-1 inline-flex">
                            @include('icons.type', [
                                'width' => '16px',
                                'height' => '16px',
                                'color' => color(5),
                            ]) <div class="text-esg5 text-base font-normal">
                                {{ __('Type') }}
                            </div>
                            <x-information id="{{ time() }}" model="true">
                                <span>
                                    {{ __("Internal company: The company belongs to the organization that owns the tenant.
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        External company: The company doesn't belongs to the organization that owns the
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        tenant (eg. clients and suppliers).") }}
                                </span>
                            </x-information>
                        </div>
                        <div class="justify-start items-center gap-2.5 inline-flex">
                            <div class="text-esg8 text-base font-normal">{{ $company->type->label() ?? '-' }}</div>
                        </div>
                    </div>
                @endif

                @if ($company->is_external && $company->relations->count())
                    <div class="self-stretch flex-col justify-center items-start gap-2 flex">
                        <div class="justify-start items-center gap-1 inline-flex">
                            @include('icons.type', [
                                'width' => '16px',
                                'height' => '16px',
                                'color' => color(5),
                            ]) <div class="text-esg5 text-base font-normal">
                                {{ __('Relation') }}
                            </div>
                        </div>
                        <div class="justify-start items-center gap-2.5 inline-flex">
                            <div class="text-esg8 text-base font-normal">
                                {{ $company->relations->map(fn($relation) => $relation->label())->join(', ') }}
                            </div>
                        </div>
                    </div>
                @endif

                <div class="self-stretch flex-col justify-center items-start gap-2 flex">
                    <div class="justify-start items-center gap-1 inline-flex">
                        @include('icons.user', [
                            'width' => '16px',
                            'height' => '16px',
                            'color' => color(5),
                        ]) <div class="text-esg5 text-base font-normal">{{ __('Size') }}
                        </div>
                    </div>
                    <div class="justify-start items-center gap-2.5 inline-flex">
                        <div class="text-esg8 text-base font-normal">{{ $company->sizeLabel }}</div>
                    </div>
                </div>

                <div class="self-stretch flex-col justify-center items-start gap-2 flex">
                    <div class="justify-start items-center gap-1 inline-flex">
                        @include('icons.user', [
                            'width' => '16px',
                            'height' => '16px',
                            'color' => color(5),
                        ]) <div class="text-esg5 text-base font-normal">
                            {{ __('Employees') }}
                        </div>
                    </div>
                    <div class="justify-start items-center gap-2.5 inline-flex">
                        <div class="text-esg8 text-base font-normal">{{ $company->dynTotalNumberOfEmployees }}</div>
                    </div>
                </div>

                <div class="self-stretch flex-col justify-center items-start gap-2 flex">
                    <div class="justify-start items-center gap-1 inline-flex">
                        @include('icons.briefcase', [
                            'width' => '16px',
                            'height' => '16px',
                            'color' => color(5),
                        ]) <div class="text-esg5 text-base font-normal">
                            {{ __('Business Sector') }}</div>
                    </div>
                    <div class="justify-start items-center gap-2.5 inline-flex">
                        <div class="text-esg8 text-base font-normal">{{ $company->business_sector->name ?? '-' }}
                        </div>
                    </div>
                </div>

                <div class="self-stretch flex-col justify-center items-start gap-2 flex">
                    <div class="justify-start items-center gap-1 inline-flex">
                        @include('icons.location-v2', [
                            'width' => '16px',
                            'height' => '16px',
                            'color' => color(5),
                        ]) <div class="text-esg5 text-base font-normal">{{ __('Location') }}
                        </div>
                    </div>
                    <div class="justify-start items-center gap-2.5 inline-flex">
                        <div class="text-esg8 text-base font-normal">City,
                            {{ isset($company->country) ? getCountriesWhereIn([$company->country])[$company->country]['name'] : '-' }}
                        </div>
                    </div>
                </div>

                <div class="self-stretch flex-col justify-center items-start gap-2 flex">
                    <div class="justify-start items-center gap-1 inline-flex">
                        @include('icons.calender', [
                            'width' => '16px',
                            'height' => '16px',
                            'color' => color(5),
                        ]) <div class="text-esg5 text-base font-normal">{{ __('Founded') }}
                        </div>
                    </div>
                    <div class="justify-start items-center gap-2.5 inline-flex">
                        <div class="text-esg8 text-base font-normal">
                            {{ $company->founded_at ? $company->founded_at->format('Y-m-d') : '-' }}</div>
                    </div>
                </div>

                <div class="self-stretch flex-col justify-center items-start gap-2 flex">
                    <div class="justify-start items-center gap-1 inline-flex">
                        @include('icons.user', [
                            'width' => '16px',
                            'height' => '16px',
                            'color' => color(5),
                        ])
                        <div class="text-esg5 text-base font-normal">
                            {{ __('Responsible Manager') }}
                        </div>
                    </div>
                    <div class="justify-center items-start gap-1 flex-col">
                        <div class="text-esg8 text-base font-normal">{{ $company->owner->name ?? '-' }}</div>
                        <div class="text-neutral-500 text-xs font-normal">
                            {{ $company->owner->roles[0]->name ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
