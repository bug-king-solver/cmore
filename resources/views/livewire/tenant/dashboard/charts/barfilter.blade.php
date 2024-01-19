<div>
<x-cards.card-dashboard-version1-withshadow text="{{ json_encode([$this->title]) }}" subpoint="{{ json_encode($this->subPoint) }}" class="!h-full">
    <x-charts.bar id="{{ $elemId }}" class="m-auto relative !h-full !w-full" unit="{{$this->chartData['unit']}}" subinfo="{{json_encode($this->subInfo)}}">
        <x-slot:filters>
            <x-inputs.select-media
                class="w-auto text-esg8 text-xs w-auto max-w-[120px] mr-2"
                wire:model="filters.filterby"
                wire:change="filters()"
                :extra="['options' => parseKeyValueForSelect($this->optionsList)]"
            />
        </x-slot:filters>
    </x-charts.bar>
</x-cards.card-dashboard-version1-withshadow>
</div>

<script nonce="{{ csp_nonce() }}">
    document.addEventListener('DOMContentLoaded', () => {
        var enviroment_color1 = "#008131",
            enviroment_color2 = "#99CA3C",
            enviroment_color3 = "#6AD794",
            enviroment_color4 = "#98BDA6";

        window['chart_' + '{{ $elemId }}'] = barCharts(
            {!! json_encode($this->labels) !!},
            {!! json_encode($this->datasets) !!},
            '{{ $elemId }}',
            [enviroment_color1, enviroment_color2],
            'x'
        );
        
        Livewire.on('updatePollutionChart{{ $elemId }}', data => {
            console.log({!! json_encode($this->subPoint) !!});
            var chartName = window['chart_' + '{{ $elemId }}'];
            chartName.data.datasets[0].data = data.datasets;
            chartName.data.labels = data.labels;
            chartName.update();
        });
    });
</script>

