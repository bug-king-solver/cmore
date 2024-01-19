<div>
    <x-cards.card-dashboard-version1-withshadow class="!h-auto" contentplacement="none">
        <x-slot:filtersWithTitle>
            <div class="grid grid-cols-2">
                <div class="text-esg16 font-encodesans flex flex-col text-base  uppercase{{ $titleclass ?? '' }}">
                    <p> {{ $this->title }} </p>
                </div>
                @if (!empty($this->filterLists))
                    <div class="flex justify-end">
                        <x-inputs.select-media class="w-auto text-esg8 text-xs w-auto max-w-[200px] mr-2"
                            wire:model="radarFilterOpt1" :extra="['options' => parseKeyValueForSelect($this->optionsList1)]" />
                        <x-inputs.select-media class="w-auto text-esg8 text-xs w-auto max-w-[200px] mr-2"
                            wire:model="radarFilterOpt2" :extra="['options' => parseKeyValueForSelect($this->optionsList2)]" />
                    </div>
                @endif
            </div>
        </x-slot:filtersWithTitle>

        <div class="w-full flex items-center">
            <x-charts.radar id="{{ $elemId }}" width="100%" height="600" x-init="$nextTick(() => {
                window['{{ $elemId }}'] = tenantRadarChart(
                    {{ json_encode($this->data['labels'], true) }},
                    {{ json_encode($this->data['data']) }},
                    '{{ $elemId }}',
                    ['{{ color(5) }}', '{{ color(6) }}']
                );
            });" />
        </div>
    </x-cards.card-dashboard-version1-withshadow>

</div>

<script nonce="{{ csp_nonce() }}">
    document.addEventListener('DOMContentLoaded', () => {
        Livewire.on('updateRadarChart', data => {
            const {
                elemId,
                labels,
                datasets
            } = data;

            console.log(elemId, labels, datasets);

            var chartName = window[elemId];
            chartName.data.datasets = datasets;
            chartName.data.labels = labels;
            chartName.update();
            chartName.resize();
        });
    });
</script>
