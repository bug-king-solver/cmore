<div>
    <div>
        <x-slot name="header">
            <x-header title="{{ __('Reputational Analysis') }}">
                <x-slot name="left"></x-slot>
                <x-slot name="right">
                    @can('reputation.create')
                        <x-buttons.btn-icon-text modal="compliance.reputational.modals.form" class="text-sm">
                            <x-slot name="buttonicon">
                                @include('icons/tables/plus')
                            </x-slot>
                            {{__('ADD') }}
                        </x-buttons.btn-blue>
                    @endcan
                </x-slot>
            </x-header>
        </x-slot>
    </div>

    <div class="mx-auto max-w-7xl sm:px-6 px-4 lg:px-0 leading-normal mb-20">

        <div class="flex items-center mb-0">
            @if(count($this->analysisInfoList) > 0)
                <div class="flex items-center justify-start">
                    <x-inputs.select-media
                        class="text-esg8 text-lg font-semibold leading-6 w-60"
                        :extra="['options' => parseDataForSelect($this->analysisInfoList, 'id', 'name')]"
                        :items="$this->analysisInfo->id ?? null"
                        wire:change="redirecrtToSelectedAnalysis()"
                        wire:model="selectedAnalysis"
                    />
                    @php $editAnalysis = json_encode(["analysisInfo" => $this->analysisInfo->id]); @endphp
                    @can('reputation.update')
                    <x-buttons.btn-icon modal="compliance.reputational.modals.form" :data="$editAnalysis" class="text-esg8">
                        <x-slot name="buttonicon">
                            @include('icons/tables/edit')
                        </x-slot>
                    </x-buttons.btn-icon>
                    @endcan
                    @can('reputation.create')
                    <x-buttons.btn-icon modal="compliance.reputational.modals.delete" class="text-esg8"
                        :data="$editAnalysis">
                        <x-slot name="buttonicon">
                            @include('icons.trash', ['stroke' => color(12)])
                        </x-slot>
                    </x-buttons.btn-icon>
                    @endcan
                </div>
            @endif
        </div>
        @if ($this->analysisInfo)
        <div class="mb-8  font-medium text-esg8 mt-2">
            @if(count($this->analysisInfo->search_terms)>0)
                {{__('Analyze terms')}} :
                @foreach($this->analysisInfo->search_terms as $serachTeam)
                <span class="bg-esg58 text-esg62 text-sm font-medium mr-2 px-2.5 py-1 rounded">{{$serachTeam}}</span>

                @endforeach
            @endif
        </div>
        @endif
        @if(count($this->analysisInfoList) == 0)
            <div class="mb-5 text-center text-lg mt-10">
                {{ __('No data available. Click the “Add” button to configure a new analysis.') }}
            </div>
        @else
            @if($showSuccess)
                <div class="mb-5 text-center text-lg my-24">
                    @include('icons/document_analysis/success')
                    <p class="mt-6 text-lg">{{ __("All set! We are collecting and analysing the data.") }}</p>
                </div>
            @else
                @if ($this->analysisInfo)

                    <div class="grid grid-cols-2 gap-x-4 gap-y-6">
                        <div class="p-4 border border-esg61 rounded">
                            @livewire('compliance.reputational.charts.word-cloud', ['analysisInfo' => $this->analysisInfo->id, 'selectedRange' => $selectedRange])
                        </div>

                        <div class="p-4 border border-esg61 rounded">
                            @livewire('compliance.reputational.charts.term-frequency', ['analysisInfo' => $this->analysisInfo->id, 'selectedRange' => $selectedRange])
                        </div>

                        <div class="p-4 border border-esg61 rounded relative">
                            @livewire('compliance.reputational.charts.sentiment-average', ['analysisInfo' => $this->analysisInfo->id, 'selectedRange' => $selectedRange])
                        </div>

                        <div class="p-4 border border-esg61 rounded">
                            @livewire('compliance.reputational.charts.overtime-sentiment', ['analysisInfo' => $this->analysisInfo->id, 'selectedRange' => $selectedRange])
                        </div>

                        <div class="p-4 border border-esg61 rounded">
                            @livewire('compliance.reputational.charts.emotion-average', ['analysisInfo' => $this->analysisInfo->id, 'selectedRange' => $selectedRange])
                        </div>

                        <div class="p-4 border border-esg61 rounded">
                            @livewire('compliance.reputational.charts.overtime-emotions', ['analysisInfo' => $this->analysisInfo->id, 'selectedRange' => $selectedRange])
                        </div>
                    </div>
                @endif
            @endif
        @endif
    </div>
</div>
