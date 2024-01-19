<div class="w-full">

    <x-dashboards.dashboard-mini-header title="{{ __('Physical risks') }}" :questionnaire="$this->questionnaire" :questionnaireList="$this->questionnaireList" />

    <div class="self-stretch h-full w-full flex-col justify-center items-center flex">
        @foreach ($physicalRisks as $physicalrisk)
            <div class="h-auto w-full flex justify-center items-center gap-1 border-b border-color-esg67">
                <div class="flex flex-col h-auto w-full">
                    <div class="self-stretch text-esg1 text-base font-bold">{{ $physicalrisk->name }}</div>
                    <div
                        class="self-stretch justify-start items-center gap-2 inline-flex text-esg8 text-sm font-normal capitalize">
                        @include('icons.location-v2', [
                            'width' => '9.6px',
                            'height' => '12px',
                            'color' => color(5),
                        ]) {{ $physicalrisk->fullAddress }}
                    </div>
                    <div
                        class="self-stretch justify-start items-center gap-2 inline-flex text-esg8 text-sm font-normal capitalize">
                        @include('icons.alert', [
                            'width' => '9.6px',
                            'height' => '12px',
                            'color' => color(5),
                        ])
                        {{ $physicalrisk->relevantLabel }}
                    </div>
                    <div class="w-full h-[98px] py-4 justify-start items-center gap-8 inline-flex">
                        <div class="grow shrink basis-0 flex-row justify-between items-center gap-2 flex w-full">
                            @foreach ($physicalrisk->hazards as $hazard)
                                <div
                                    class="w-[42.45px] h-[66px] flex-col justify-center items-center gap-2 inline-flex">
                                    <div class="w-10 h-10">
                                        @include(
                                            'icons.physical_risks.' . trim(strtolower($hazard['name_slug'])),
                                            [
                                                'fill' => $hazard['enabled']
                                                    ? getRiskLevelColor($hazard['risk_slug'], 'none')
                                                    : '#B1B1B1',
                                                'width' => '40',
                                                'height' => '40',
                                            ]
                                        )
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>
