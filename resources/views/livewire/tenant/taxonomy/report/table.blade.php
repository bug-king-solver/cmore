@push('head')
    <style nonce="{{ csp_nonce() }}">
        @media print {
            * {
                background-color: transparent !important;
                color: black !important;
                font-size: 98% !important;
            }

            p {
                font-size: 98% !important;
            }

            #launcher,
            #footer {
                visibility: hidden;
            }

            .page {
                margin: 1cm;
            }

            .no-print {
                display: none !important;
            }

            div {
                page-break-after: avoid;
            }

            .break-after-page {
                page-break-after: always !important;
            }
        }

        @page {
            size: A4;
            margin: 2 0.5 5 0.5;
        }
    </style>
@endpush

<div class="">

    <x-slot name="header">
        <x-header title="{{ __('Taxonomy table - ' . $questionnaire->company->name) }}" data-test="taxonomy-header"
            click="{{ route('tenant.questionnaires.panel') }}" class="!bg-esg4 no-print" textcolor="text-esg5">
            <x-slot name="left"></x-slot>
            <x-slot name="right"></x-slot>
        </x-header>
    </x-slot>


    <div>
        <x-form.form-col input="tomselect" id="valueToShow" label="{{ __('Select the value to show in the Table') }}"
            class="after:content-['*'] after:text-red-500" :options="$volumeOptionsToShow" items="{{ $valueToShow }}" limit=1 modelmodifier=".lazy" />
    </div>

    @foreach ($this->activities as $key => $activities)
        <div class="mt-10" style="page-break-after: always;">

            <div class="w-full relative">
                <table>
                    <thead>
                        <tr>
                            <th class="p-2" colspan="4"></th>
                            <th class="p-2 border" colspan="6">
                                {{ __('Substantial contribution criteria') }}
                            </th>
                            <th class="p-2 border" colspan="6">
                                {{ __('No significant harm criteria') }}
                            </th>
                            <th class="px-4 py-2" colspan="5"></th>
                        </tr>

                        <tr class="h-[340px]">
                            <td class="border max-w-[250px] text-left pl-4">
                                <div>
                                    {{ __('Economic activities') }} (1)
                                </div>
                            </td>
                            <td class="border max-w-[40px] whitespace-nowrap">
                                <div class="-rotate-90 mt-[224px] ">
                                    {{ __('Code') }} (2)
                                </div>
                            </td>
                            <td class="border max-w-[90px] whitespace-nowrap">
                                <div class="-rotate-90 mt-[210px] ">
                                    {{ $textToShow }} (3)
                                </div>
                            </td>
                            <td class="border max-w-[30px] whitespace-nowrap">
                                <div class="-rotate-90 mt-[240px] ">
                                    {{ __('Proportion of :text', ['text' => $textToShow]) }} (4)
                                </div>
                            </td>

                            @php $count = 5; @endphp
                            @foreach ($this->objectives as $objectives)
                                @foreach ($objectives as $objective)
                                    <td class="border max-w-[30px] whitespace-nowrap">
                                        <div class="-rotate-90 mt-[265px] ">{{ $objective }} ({{ $count }})
                                        </div>
                                    </td>
                                    @php $count++; @endphp
                                @endforeach
                            @endforeach

                            <td class="border max-w-[30px] whitespace-nowrap">
                                <div class="-rotate-90 mt-[265px] ">
                                    {{ __('Minimum safeguards') }}
                                </div>
                            </td>

                            <td class="border max-w-[70px] w-[70px] min-w-[70px] whitespace-nowrap">
                                <div class="-rotate-90 mt-[240px] ">
                                    <p>{{ __('Proportion of :text aligned', ['text' => $textToShow]) }}</p>
                                    <p>{{ __('by the taxonomy, year N (18)') }}</p>
                                </div>
                            </td>

                            <td class="border max-w-[70px] w-[70px] min-w-[70px] whitespace-nowrap">
                                <div class="-rotate-90 mt-[230px] ">
                                    <p>{{ __('Proportion of :text aligned', ['text' => $textToShow]) }}</p>
                                    <p>{{ __('by the taxonomy, year N-1 (19)') }}</p>
                                </div>
                            </td>

                            <td class="border max-w-[30px] whitespace-nowrap">
                                <div class="-rotate-90 mt-[270px] ">
                                    {{ __('Category (enabling activity) (20)') }}
                                </div>
                            </td>
                            <td class="border max-w-[30px] whitespace-nowrap">
                                <div class="-rotate-90 mt-[270px] ">
                                    {{ __('Category (transition activity) (21)') }}
                                </div>
                            </td>
                        </tr>

                        @if ($activities['table'] == 1)
                            <tr class="border">
                                <th class="p-2 text-left text-base font-bold text-[#1F5734]" colspan="21">
                                    A. {{ __('Activities eligible for taxonomy') }}
                                </th>
                            </tr>
                            <tr class="border">
                                <th class="p-2 text-left" colspan="21">
                                    A.1
                                    {{ __('Activities sustainable from an environmental point of view (aligned by taxonomy)') }}
                                </th>
                            </tr>
                        @elseif($activities['table'] == 2)
                            <tr class="border">
                                <td class="p-2 text-left">
                                    {{ __(':text of the activities sustainable from an environmental point of view ( aligned by taxonomy ( A.1 ))', ['text' => $textToShow]) }}
                                </td>
                                <td class="p-2 text-left">-</td>
                                <td class="p-2 text-left">
                                    <x-currency :value="$businessVolume['eligibleAligned'][$columnToIndex]['value']" currency="€" />
                                </td>
                                <td class="p-2 text-left">{{ $proportionEligibleAndAligned }}</td>
                                @for ($i = 0; $i < 17; $i++)
                                    <td class="border p-2">-</td>
                                @endfor
                            </tr>
                            <tr class="border">
                                <th class="p-2 text-left" colspan="21">
                                    A.2
                                    {{ __('Activities eligible for taxonomy but not sustainable from an environmental point of view (activities not aligned by taxonomy)') }}
                                </th>
                            </tr>
                        @endif
                    </thead>
                    <tbody class="border">
                        @foreach ($activities['data'] as $activity)
                            <tr>
                                @php $count = 0; @endphp
                                @foreach ($activity as $i => $item)
                                    @if ($activities['table'] !== 1)
                                        @if ($count > 3)
                                            <td class="border p-2">-</td>
                                        @else
                                            <td class="border p-2">{{ $item }}</td>
                                        @endif
                                    @else
                                        <td class="border p-2">{{ $item }}</td>
                                        {{-- <td colspan="13" class="bg-esg6/20"></td>  For empt columns witht background color --}}
                                    @endif
                                    @php $count++; @endphp
                                @endforeach

                                @if ($activities['table'] === 3)
                                    @for ($i = 0; $i < 17; $i++)
                                        <td class="border p-2">-</td>
                                    @endfor
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach

    <div class="flex gap-5 items-center place-content-center mt-8 no-print">
        <x-buttons.a-icon class="bg-esg6 !text-esg4 !border w-80 !rounded-md !p-2 !text-center !pl-5"
            @click="window.print()">
            <div class="flex gap-3 items-center place-content-center">
                @include('icons.download', ['color' => color(4)])
                <label class="cursor-pointer">{{ __('Download full report') }}</label>
            </div>
        </x-buttons.a-icon>
    </div>

</div>
