<div class="px-4 md:px-0">
    <x-slot name="header">
        <x-header title="{{ __('Data') }}" data-test="compliance-header" click="{{ route('tenant.data.index') }}">
            <x-slot name="left"></x-slot>
            <x-slot name="right">
            </x-slot>
        </x-header>
    </x-slot>

    <div class="w-full text-esg5 w-fit text-lg font-bold flex flex-row gap-2 items-center">
        {{ $indicator->name }}
    </div>

    <div class="mt-6 bg-esg6/5 rounded-md p-4" x-data="{ open: true }">
        <div class="flex justify-between items-center cursor-pointer" x-on:click="open = ! open">
            <span class="text-base font-bold text-esg6">{{ __('General information') }}</span>
            <div class="">
                <span x-show="open"> @include('icons.arrow', [
                    'width' => 15,
                    'height' => 15,
                    'class' => '-rotate-90',
                    'fill' => color(8),
                ]) </span>
                <span x-show="!open"> @include('icons.arrow', [
                    'width' => 15,
                    'height' => 15,
                    'class' => 'rotate-90',
                    'fill' => color(8),
                ]) </span>
            </div>
        </div>
        <div class="mt-6" x-show="open">
            <div class="flex gap-2 items-center">
                <div class="text-sm font-bold text-esg8"> {{ __('ID') }} : </div>
                <div class="text-sm text-esg8"> {{ $indicator->id }} </div>
            </div>

            <div class="flex gap-2 items-center mt-2">
                <div class="text-sm font-bold text-esg8"> {{ __('Type') }} : </div>
                <div class="text-sm text-esg8"> {{ $indicator->calc == null ? __('Simple') : __('Compound') }} </div>
            </div>

            <div class="flex gap-2 items-center mt-2">
                <div class="text-sm font-bold text-esg8"> {{ __('Category') }} : </div>
                <div class="flex flex-row gap-1 items-center">
                    <div class="">
                        @php
                        $icon = null;

                        if ($indicator->category) {
                            $categoryIcon = $indicator->category->parent ?? $indicator->category;
                            $icon = match (strtolower($categoryIcon->getTranslation('name', 'en'))) {
                                'environment' => '1',
                                'social' => '2',
                                'governance' => '1',
                                default => null
                            };
                        }
                        @endphp
                        @if ($icon)
                            @include('icons.categories.' . $icon, ['width' => 16, 'height' => 14])
                        @endif
                    </div>

                    <div class="text-sm text-esg8">{{ $indicator->category ? $indicator->category->name : '-' }}</div>
                </div>
            </div>

            <div class="flex gap-2 items-center mt-2">
                <div class="text-sm font-bold text-esg8"> {{ __('Full name') }} : </div>
                <div class="text-sm text-esg8"> {{ $indicator->name }} </div>
            </div>

            <div class="flex gap-2 items-center mt-2">
                <div class="text-sm font-bold text-esg8"> {{ __('Description') }} : </div>
                <div class="text-sm text-esg8"> {{ $indicator->description }} </div>
            </div>

            <div class="flex gap-2 items-center mt-2">
                <div class="text-sm font-bold text-esg8"> {{ __('Unit') }} : </div>
                <div class="text-sm text-esg8"> {{ $indicator->unit_default ?? '-' }} </div>
            </div>
        </div>
    </div>

    <div class="flex justify-between items-center mt-6">
        <div class="">
            <x-inputs.tomselect id="company" :wire_ignore="false" wire:model="company" :options="$companiesList"
                :items="$company" plugins="['dropdown_input', 'checkbox_options']"
                placeholder="{{ __('Select a company') }}" dataTest="company-list" class="w-44" limit="1" wire:change="$emit('updateCompanyId', $event.target.value)" />
        </div>
        <div class="flex gap-2 items-center">

            <x-buttons.a-icon
                href="{{ $company != null ? route('tenant.data.validator', ['company' => $company, 'indicator' => $indicator->id]) : '#' }}"
                data-test="add-data-btn" class="flex place-content-end !p-0 ">
                <div
                    class="flex gap-1 items-center bg-esg4 py-2 px-4 cursor-pointer rounded-md  border  {{ $company != null ? 'text-esg16 border-esg16' : 'text-esg7 border-esg7' }}">
                    @include('icons.edit', ['color' => $company != null ? color(16) : color(7)])
                    <label>{{ __('Edit') }}</label>
                </div>
            </x-buttons.a-icon>

            <x-buttons.a-icon
                href="{{ $company != null ? route('tenant.data.form', ['company' => $company, 'indicator' => $indicator->id]) : '#' }}"
                data-test="add-data-btn" class="flex place-content-end !p-0">
                <div
                    class="flex gap-1 items-center {{ $company != null ? 'bg-esg5' : 'bg-esg7' }} py-2 px-4 cursor-pointer rounded-md text-esg4">
                    <label>{{ __('ADD') }}</label>
                </div>
            </x-buttons.a-icon>
        </div>
    </div>


    @if ($company)
        <div class="flex flex-row justify-between items-center mt-6 gap-2">
            <div
                class="w-[50%] h-full  p-4 bg-white rounded border border-slate-200 flex-col justify-start items-center inline-flex">
                <div class="self-stretch pb-0.5 justify-start items-start inline-flex">
                    <div class="grow shrink basis-0 text-neutral-700 text-base font-medium font-['Lato']">
                        Current value
                    </div>
                </div>
                <div class="self-stretch grow shrink basis-0 flex-col justify-center items-center flex mb-5">
                    <div class="text-center text-neutral-700 text-5xl font-bold font-['Lato']">
                        {{ $totalData }}
                    </div>
                    @if ($indicator->unit_default)
                        <div class="text-neutral-500 text-2xl font-normal font-['Lato']">
                            {{ $indicator->unit_default }} {{ $indicator->unit_qty }}
                        </div>
                    @endif
                </div>
                @foreach ($data->sortByDesc('id')->take(2) as $row)
                    <div class="self-stretch px-0.5 rounded-3xl justify-center items-center gap-2 inline-flex">
                        <div class="w-32 text-center text-neutral-700 text-xs font-normal font-['Lato'] tracking-tight">
                            {{ carbon()->parse($row->reported_at)->diffForHumans() }}
                        </div>
                        <div class="pr-1 py-0.5 rounded-lg justify-center items-center gap-1 flex">
                            <img class="w-5 h-5 rounded-full" src="{{ $row->user->avatar ?? '' }}" />
                            <div
                                class="w-28 text-center text-neutral-700 text-xs font-bold font-['Lato'] tracking-tight">
                                {{ $row->user->name ?? '--' }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="w-[50%] h-full  p-4 bg-white rounded border border-slate-200 flex">
                <x-charts.chartjs id="data_bar_chart" class="" x-init="$nextTick(() => {
                    tenantBarChart(
                        {{ json_encode(array_column($graphsData, 'label'), true) }},
                        {{ json_encode(array_column($graphsData, 'value'), true) }},
                        'data_bar_chart',
                        ['#008131']
                    );
                });" />
            </div>
        </div>
    @endif

    <div class="mt-6">
        <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="myTab"
                data-tabs-toggle="#myTabContent" role="tablist">
                <li class="mr-2" role="presentation">
                    <button
                        class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 {{ $tab == 'information' ? 'border-b-esg5' : 'border-0' }}"
                        id="information-tab" wire:click="tabChnage('information')" data-tabs-target="#information"
                        type="button" role="tab" aria-controls="information" aria-selected="false">
                        {{ __('Information') }} </button>
                </li>
                <li class="mr-2" role="presentation">
                    <button
                        class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 {{ $tab == 'log' ? 'border-b-esg5' : 'border-0' }}"
                        id="log-tab" wire:click="tabChnage('log')" data-tabs-target="#log" type="button"
                        role="tab" aria-controls="log" aria-selected="false"> {{ __('Update log') }} </button>
                </li>
                <li class="mr-2" role="presentation">
                    <button
                        class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 {{ $tab == 'evidences' ? 'border-b-esg5' : 'border-0' }}"
                        id="evidences-tab" wire:click="tabChnage('evidences')" data-tabs-target="#evidences"
                        type="button" role="tab" aria-controls="evidences" aria-selected="false">
                        {{ __('Evidences') }} </button>
                </li>
            </ul>
        </div>
        <div id="myTabContent">
            <div class="{{ $tab != 'information' ? 'hidden' : '' }}" id="information" role="tabpanel"
                aria-labelledby="information-tab" x>
                @if (!$company)
                    {{ __('Select a company to view the Information') }}
                @endif

                @if ($companyTasks != null)
                    <x-tasks.table :tasks="$companyTasks" entity='companies' entityId="{{ $company }}" />
                @endif
            </div>

            <div class="{{ $tab != 'log' ? 'hidden' : '' }}" id="log" role="tabpanel"
                aria-labelledby="log-tab">
                <x-cards.data.log.list :log="$data" :indicator="$indicator" />
            </div>

            <div class="{{ $tab != 'evidences' ? 'hidden' : '' }}" id="evidences" role="tabpanel"
                aria-labelledby="evidences-tab">
                @if (!$company)
                    {{ __('Select a company to view the Evidenaces') }}
                @endif
            </div>
        </div>
    </div>

</div>
