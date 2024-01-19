<div class="w-full">
    <div class="px-2 md:px-0">
        <div class="border border-esg16/20 rounded-md py-4 px-6">
            <p class="text-esg6 text-lg font-bold">{!! __('Summary') !!}</p>
                <div class="mt-4">
                    <x-tables.table class="!w-full !min-w-full">
                        <x-slot name="thead">
                            <x-tables.th
                                class="!border-y-esg7/40 text-esg6 text-sm pb-2 font-normal text-center ">&nbsp;</x-tables.th>
                            <x-tables.th
                                class="!border-y-esg7/40 text-esg6 text-sm pb-2 font-normal text-center ">{!! __('Activity') !!}</x-tables.th>
                            <x-tables.th
                                class="!border-y-esg7/40 text-esg6 text-sm pb-2 font-normal text-center ">{!! __('Risk summary') !!}</x-tables.th>
                            <x-tables.th
                                class="!border-y-esg7/40 text-esg6 text-sm pb-2 font-normal text-center">{!! __('Relevance') !!}</x-tables.th>
                        </x-slot>

                        @foreach ($physicalRisks as $physicalrisk)
                            <x-tables.tr>
                                <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                    <p class="text-base font-bold text-esg8">{{ $physicalrisk->name }}</p>
                                    <div class="flex items-center gap-2">
                                        @include('icons.location-v2')
                                        <label class="text-sm text-esg8">
                                            {{ $physicalrisk->fullAddress }}
                                        </label>
                                    </div>
                                </x-tables.td>
                                <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5 text-esg8 items-center ">
                                    {{ $physicalrisk->businessActivities->name }}
                                </x-tables.td>
                                <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                    <div class="flex items-center gap-2 print:grid print:grid-cols-6 text-esg8">
                                        @foreach ($physicalrisk->hazards as $hazard)
                                            <div class="w-8">
                                                @include(
                                                    'icons.physical_risks.' . strtolower($hazard['name_slug']),
                                                    [
                                                        'fill' => $hazard['enabled']
                                                            ? getRiskLevelColor($hazard['risk_slug'], 'none')
                                                            : '#B1B1B1',
                                                    ]
                                                )
                                            </div>
                                        @endforeach
                                    </div>
                                </x-tables.td>
                                <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                    <p class="text-base font-extrabold text-black text-center">
                                        {{ str_replace('_', ' ', $physicalrisk->relevant) }}</p>
                                </x-tables.td>
                            </x-tables.tr>
                        @endforeach
                    </x-tables.table>
                </div>
            </div>
    </div>
</div>