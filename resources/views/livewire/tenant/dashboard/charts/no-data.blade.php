<div>
    <x-cards.card-dashboard-version1-withshadow class="!h-auto" contentplacement="none">
        <x-slot:filtersWithTitle>
            <div class="grid grid-cols-2">
                <div class="text-esg16 font-encodesans flex flex-col text-base  uppercase{{ $titleclass ?? '' }}">
                    <p> {{ $this->title }} </p>
                </div>

            </div>
        </x-slot:filtersWithTitle>

        <div class="w-full flex flex-col items-center text-center">
            <p class="font-encodesans leading-5 pl-1 text-sm font-normal text-esg11">
                {{ __('No data available') }}
            </p>
            <p class="font-encodesans leading-5 pl-1 text-sm font-normal text-esg11">
                {{ __('Please, review and submit your questionnaire to see the results') }}
            </p>
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
