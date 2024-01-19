<div class="grid grid-cols-1 gap-9 pagebreak-unset">
    <x-cards.physical_risks.card>
        <x-slot:header>
            <label class="text-sm font-medium"> {!! __('Potential risks') !!} </label>
        </x-slot:header>

        <x-slot:content>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 print:grid-cols-2">
                @foreach ($hazards as $hazard)
                    <div class="flex items-center gap-4">
                        <div class="w-8" wire:poll.visible>
                            @include('icons.physical_risks.' . strtolower($hazard['name_slug']), [
                                'fill' => $hazard['enabled'] ? color(5) : '#B1B1B1',
                            ])
                        </div>

                        <div class="text-sm w-48 {{ $hazard['enabled'] ? 'text-esg8' : 'text-[#B1B1B1]' }}">
                            {!! __($hazard['name']) !!}
                        </div>
                        <div>

                            <span
                                class="w-24 h-8 grid place-content-center block text-sm rounded-md {{ $hazard['enabled'] ? getRiskLevelColor($hazard['risk_slug']) : 'bg-[#B1B1B1] print:border-[#B1B1B1]' }} text-esg4 ">
                                <span class="{{ !$hazard['enabled'] ? 'print:text-[#B1B1B1]' : '' }}">
                                    {!! __(getRiskLevelLabel($hazard['risk_slug'])) !!}
                                </span>
                            </span>

                        </div>
                        @if (!$isReport && $questionnaire->completed_at == null)
                            <div>
                                @php $data = json_encode(["physicalRisks" => $physicalrisk->id, 'risk' =>  $hazard['name_slug']]); @endphp
                                <x-buttons.edit modal="questionnaires.physicalrisks.modals.change-risk"
                                    class="cursor-pointer" :param="json_encode(['color' => color(5), 'width' => 14, 'height' => 14])" :data="$data" />
                                <x-buttons.trash modal="questionnaires.physicalrisks.modals.disable-hazard"
                                    title="{!! __('Disable') !!}" class="cursor-pointer px-2 py-1" :param="json_encode(['color' => color(16), 'width' => 14, 'height' => 14])"
                                    :data="$data" />
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </x-slot:content>

        <x-slot:log>
            @if (isset($audits[$physicalrisk->id]))
                <div class="print:mt-5">
                    <div class="flex items-center gap-3">
                        <span>
                            @include('icons.info', ['color' => color(5)])
                        </span>
                        <span class="text-base text-esg8">
                            {!! __('Notes') !!}
                        </span>
                    </div>
                </div>
                @foreach ($audits[$physicalrisk->id] as $audit)
                    <div class="flex flex-row items-center gap-4 mt-6">
                        <div x-data="{ show: false }" x-init="setTimeout(() => { show = true }, 1000)">
                            <div x-show="show" class="w-8">
                                @include('icons.physical_risks.' . strtolower($audit['risk_slug']))
                            </div>
                        </div>
                        <div class="text-sm text-esg8 flex items-center gap-2">
                            <span>{!! __($audit['risk']) !!} :</span>

                            <span class="font-bold flex justify-center items-center">
                                @if ($audit['action'] === 'change_level')
                                    {{ __(getRiskLevelLabel($audit['old_slug'])) }} <span
                                        class="p-2">@include('icons.full-right-arrow')</span>{{ __(getRiskLevelLabel($audit['new_slug'])) }}
                                @else
                                    @if ($audit['action'] === 'enabled')
                                        <span class="font-bold text-[#3E9A00]">{!! __('Activated') !!}</span>
                                    @else
                                        <span class="font-bold text-[#9B2B2B]">{!! __('Disabled') !!}</span>
                                    @endif
                                @endif
                            </span>
                        </div>

                        <div class="text-sm text-esg8 ml-4">
                            {!! __('by:') !!} <span class="font-bold">{{ $audit['user'] }}</span>,
                            {!! __('at:') !!} <span
                                class="font-bold">{{ carbon()->parse($audit['created_at'])->format('Y-m-d H:i:s') }}</span>
                        </div>
                    </div>
                    <div class="flex mt-2">
                        <span class="text-sm text-esg8">
                            <span class="text-sm pr-2">{!! __('Justification: ') !!}</span>
                            <span class="text-sm font-bold">{{ $audit['description'] }}</span>
                        </span>
                    </div>

                    @if ($audit['action'] === 'change_level')
                        <div class="mt-2">
                            @if (isset($audit['old_has_contingency_plan']) &&
                                    isset($audit['has_contingency_plan']) &&
                                    $audit['old_has_contingency_plan'] != $audit['has_contingency_plan']
                            )
                                <div class="flex items-center gap-3 mt-2 text-esg8">
                                    <span class="text-sm">{!! __('Do you have a contingency plan?  ') !!}</span>
                                    @if (isset($audit['old_has_contingency_plan']))
                                        <div class="text-sm text-esg8">
                                            <span
                                                class="font-bold">{{ $audit['old_has_contingency_plan'] == true ? __('Yes') : __('No') }}</span></span>
                                        </div>
                                    @endif

                                    <span>
                                        @include('icons.full-right-arrow')
                                    </span>

                                    @if (isset($audit['has_contingency_plan']))
                                        <div class="text-sm text-esg8">
                                            <span
                                                class="font-bold text-esg8">{{ $audit['has_contingency_plan'] == true ? __('Yes') : __('No') }}</span></span>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @if (isset($audit['contingency_plan_description']))
                                <div class="text-sm text-esg8 mt-2 flex gap-2">
                                    <span class="text-sm">{!! __('Describe your contingency plan: ') !!}</span>
                                    <p class="font-bold">{{ $audit['contingency_plan_description'] ?? '' }}</p>
                                </div>
                            @endif

                            @if (isset($audit['old_has_continuity_plan']) &&
                                    isset($audit['has_continuity_plan']) &&
                                    $audit['old_has_continuity_plan'] != $audit['has_continuity_plan']
                            )
                                <div class="flex items-center gap-3 mt-2 text-esg8">
                                    <span class="text-sm">{!! __('Do you have a continuity plan? ') !!}</span>
                                    @if (isset($audit['old_has_continuity_plan']))
                                        <div class="text-sm text-esg8">
                                            <span
                                                class="font-bold">{{ $audit['old_has_continuity_plan'] == true ? __('Yes') : __('No') }}</span></span>
                                        </div>
                                    @endif

                                    <span>
                                        @include('icons.full-right-arrow')
                                    </span>

                                    @if (isset($audit['has_continuity_plan']))
                                        <div class="text-sm text-esg8">
                                            <span
                                                class="font-bold">{{ $audit['has_continuity_plan'] == true ? __('Yes') : __('No') }}</span></span>
                                        </div>
                                    @endif
                                </div>
                            @endif


                            @if (isset($audit['continuity_plan_description']))
                                <div class="text-sm text-esg8 flex gap-2">
                                    <span class="text-sm">{!! __('Describe your continuity plan: ') !!}</span>
                                    <p class="font-bold">{{ $audit['continuity_plan_description'] ?? '' }}</p>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if (!$loop->last)
                        <div class="flex items-center">
                            <div class="border-t border-gray-300 h-1 w-1/2"></div>
                            <div class="mx-2 text-gray-200">|</div>
                            <div class="mx-2 text-gray-200">|</div>
                            <div class="border-t border-gray-300 h-1 w-1/2"></div>
                        </div>
                    @endif
                @endforeach
            @endif
        </x-slot:log>
    </x-cards.physical_risks.card>
</div>
