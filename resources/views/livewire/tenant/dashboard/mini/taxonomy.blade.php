<div class="w-full">

    <x-dashboards.dashboard-mini-header title="{{ __('Taxonomy') }}" :questionnaire="$this->questionnaire" :questionnaireList="$this->questionnaireList" />

    <div class="self-stretch h-44 flex-col justify-center items-center flex">
        <table class="table-fixed w-full">
            <thead class="h-[38px]">
                <th class="border-b border-esg5 w-[180px]"></th>
                <th class="border-b border-esg5 w-[130px] leading-9 text-esg5 text-sm text-normal text-left">
                    {{ __('Absolute value') }}</th>
                <th class="border-b border-esg5 w-[130px] leading-9 text-esg5 text-sm text-normal text-left">
                    {{ __('Not eligible') }}</th>
                <th class="border-b border-esg5 w-[160px] leading-9 text-esg5 text-sm text-normal text-left">
                    {{ __('Eligible and not aligned') }}</th>
                <th class="border-b border-esg5 w-[130px] leading-9 text-esg5 text-sm text-normal text-left">
                    {{ __('Aligned') }}</th>
            </thead>
            <tbody>
                @foreach ($this->data as $index => $row)
                    <tr class="px-4 border-b border-esg10 text-left last:border-b-0">
                        <td class="leading-9 text-sm text-esg5 items-center font-bold inline-flex gap-2.5">
                            @include('icons.' . $row['icon'], [
                                'width' => '14px',
                                'height' => '14px',
                                'color' => color(5),
                            ])
                            {{ $row['label'] }}
                        </td>
                        <td class="leading-9 text-sm text-esg8 items-center font-normal">
                            {{ number_format($row['total']['value'] ?? 0 , 2) }} €
                        </td>
                        <td class="leading-9 text-sm text-esg8 items-center font-normal">
                            {{ number_format($row['notEligible']['percentage'] ?? 0 , 2) }} %
                        </td>
                        <td class="leading-9 text-sm text-esg8 items-center font-normal">
                            {{ number_format($row['eligibleAndNotAligned']['percentage'] ?? 0 , 2) }} %
                        </td>
                        <td class="leading-9 items-center">
                            <div
                                class="px-1 py-0.5 bg-esg5/20 rounded flex-col justify-center items-center gap-2.5 inline-flex text-sm text-esg5 font-extrabold">
                                {{ number_format($row['aligned']['percentage'] ?? 0 , 2) }} %
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-8"></div>

    <x-dashboards.dashboard-mini-header title="{{ __('Activities') }}" :questionnaire="null" :questionnaireList="[]" />

    <div class="">
        <table class="w-full">
            <thead class="h-[38px]">
                <th class="border-b border-esg5 w-[5%] leading-9 text-esg5 text-sm text-normal text-left">
                    {{ __('ID') }}
                </th>
                <th class="border-b border-esg5 w-[33%] leading-9 text-esg5 text-sm text-normal text-left">
                    {{ __('Activity') }}
                </th>
                <th class="border-b border-esg5 w-[34%] leading-9 text-esg5 text-sm text-normal text-left">
                    {{ __('Name') }}
                </th>
                <th class="border-b border-esg5 w-[15%] leading-9 text-esg5 text-sm text-normal text-left">
                    {{ __('Type') }}
                </th>
                <th class="border-b border-esg5 w-[10%] leading-9 text-esg5 text-sm text-normal text-left">
                    {{ __('Alignment') }}
                </th>
                <th class="border-b border-esg5 w-[3%] leading-9 text-esg5 text-sm text-normal text-left"></th>
            </thead>
            <tbody>
                @foreach ($this->activities as $activity)
                    <tr class="px-4 border-b border-esg10 text-left last:border-b-0">
                        <td class="leading-9 text-sm text-esg5 items-center font-bold inline-flex gap-2.5">
                            {{ $activity['id'] }}
                        </td>
                        <td class="leading-9 text-sm text-esg8 items-center font-normal">
                            {{ $activity['sector'] }}
                        </td>
                        <td class="leading-9 text-sm text-esg8 items-center font-normal">
                            {{ $activity['name'] }}
                        </td>
                        <td class="leading-9 text-sm text-esg8 items-center font-normal">
                            {{ $activity['type'] }}
                        </td>
                        <td class="leading-9 text-sm text-esg8 items-center font-normal">
                            @if($activity['aligned'])
                            {{ __('Aligned') }}
                            @else
                            {{ __('Not Aligned') }}
                            @endif
                        </td>
                        <td class="leading-9 text-sm text-esg8 items-center font-normal">
                            <div class="p-1 border border-esg7/40 rounded text-center bg-esg4 grid justify-items-center cursor-pointer"
                                wire:click="showInformationRow({{ $loop->index }})">
                                @if ($loop->index === $rowActive)
                                    @include('icons.double_arrow_up', ['color' => color(8)])
                                @else
                                    @include('icons.double_arrow_up', [
                                        'color' => color(8),
                                        'class' => 'rotate-180',
                                    ])
                                @endif
                            </div>
                        </td>
                    </tr>
                    <tr class="{{ $loop->index !== $rowActive ? 'hidden' : '' }}">
                        <td colspan="6" class="bg-[#FBFBFD]">
                            <div class="flex flex-col gap-2 py-2 px-4">
                                <div class="grid grid-cols-2 gap-8">
                                    <div class="flex flex-col">
                                        <span class="uppercase text-[#757575] self-center">{{ __('Business Volume') }}</span>
                                        <span class="mt-0.5 self-center text-sm rounded px-2 py-1 text-[#444444] bg-[#444444]/10">{{ $activity['vn'] }} %</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="uppercase text-[#757575] self-center">{{ __('Business Volume aligned by the taxonomy') }}</span>
                                        <span class="mt-0.5 self-center text-sm rounded px-2 py-1 text-[#00AE4E] bg-[#00AE4E]/10">{{ $activity['taxonomy']}} %</span>
                                    </div>
                                </div>
                                <x-tables.table>
                                    @foreach($activity['items'] as $item)
                                    <x-tables.tr class="!border-esg5 !border-y">
                                        <x-tables.td class="!border-esg5 !border-y !py-2 !px-1 text-esg6">
                                            {{ $item['index'] }}
                                        </x-tables.td>
                                        <x-tables.td class="!border-esg5 !border-y !py-2 !px-1 text-esg6">
                                            {{ $item['text'] }}
                                        </x-tables.td>
                                        <x-tables.td class="!border-esg5 !border-y !py-2 !px-1 text-esg6 text-right">
                                            {{ formatToCurrency($item['value']) }}
                                            <span class="mt-0.5 self-center text-sm rounded px-2 py-1 text-[#59AB6B]">({{ $item['percentage'] }} %)</span>
                                        </x-tables.td>
                                    </x-tables.tr>
                                    @endforeach
                                </x-tables.table>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
