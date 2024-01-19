<div class="w-full">

    <x-dashboards.dashboard-mini-header title="{{ __('Greenhouse gas emissions') }}" :questionnaire="$this->questionnaire"
        :questionnaireList="$this->questionnaireList" />

    <div class="h-full py-4 items-center flex flex-row gap-5">
        <div class="w-full h-full">
            <x-cards.card-dashboard-version1 text="" subpoint="" class="!border-0 !p-0"
                titleclass="!uppercase !font-normal !text-esg16 !text-base ">
                <x-charts.bar id="ghg_emissin" class="m-auto relative !h-full !w-full" x-init="$nextTick(() => {
                    tenantBarChart(
                        ['Scope 1', 'Scope 2', 'Scope 3'],
                        {!! json_encode($chartTotals) !!},
                        'ghg_emissin',
                        ['#008131', '#6AD794', '#98BDA6']
                    );
                })" />
                <div class="flex items-end gap-2 text-sm text-esg16 font-normal place-content-center mt-5">
                    <span>{{ __('Total') }}:</span>
                    <span class="text-2xl text-esg8">
                        <x-number :value="$chartTotals->sum()" />
                    </span>
                    <span>{{ __('t CO2 eq') }}</span>
                </div>
            </x-cards.card-dashboard-version1>
        </div>
        <div class="w-[400px] h-full flex items-center">
            <table class="table-fixed w-full">
                <thead class="h-9 py-2 border-b border-esg5">
                    <th class="w-5 text-esg8 text-sm text-normal text-left"></th>
                    <th class="text-esg8 text-sm font-normal text-left uppercase">{{ __('Active') }}</th>
                    <th class="text-esg8 text-sm font-normal text-left uppercase">{{ __('Value') }}</th>
                </thead>
                <tbody>
                    @foreach ($data as $index => $row)
                        <tr class="h-10 py-2 border-b border-esg10 text-left last:border-b-0">
                            <td class="w-5">
                                <div class="w-2.5 h-2.5 rounded-[100px]" style="background-color: {{ $row['color'] }}">
                                </div>
                            </td>
                            <td class="grow shrink basis-0 text-esg8 text-sm font-normal">{{ $row['name'] }}</td>
                            <td class="">
                                <span class="text-xl font-bold" style="color: {{ $row['color'] }}">
                                    <x-number :value="$row['total']" />
                                </span>
                                <span class="text-xs font-bold" style="color: {{ $row['color'] }}">
                                    {{-- {{ $data['unit'] }} --}}
                                </span>
                                <div
                                    class="px-1 py-0.5 bg-esg5/20 rounded flex-col justify-center items-center gap-2.5 inline-flex text-xs text-esg5 font-bold">
                                    {{ calculatePercentage($row['total'], $chartTotals->sum()) }} %
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
