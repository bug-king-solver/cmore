<div class="grid grid-cols-3 gap-4 mb-4">
    <div class="">
        <p class="text-base font-bold leading-5">{{ __('Term Relevance')}}</p>
        <p class="text-esg35 text-xs">{{ __('Term importance over time') }}</p>
    </div>
    <div class="col-span-2 flex justify-end">
        <x-inputs.select-media
            class="text-esg8 text-xs !w-[120px] mr-2 !m-w-[120px] !flex-none"
            wire:model="filters.term"
            wire:change="filters()"
            :extra="['options' => parseKeyValueForSelect($terms)]"
            placeholder="{{__('Select word')}}"
        />
        <x-inputs.daterange
            class="rounded-md text-esg8 text-xs"
            wire:change="filters()"
            wire:model.lazy="selectedRange"
            id="termFrequencyRange"
        />
    </div>
</div>
<div class="">
    <canvas id="{{ $elemId }}"></canvas>
</div>

<script nonce="{{ csp_nonce() }}">
    document.addEventListener('DOMContentLoaded', () => {
        const termChart = new Chart(
            document.getElementById('{{ $elemId }}'), {
                type: 'line',
                data: {
                    labels: @json($labels),
                    datasets: @json($datasets)
                },
                options: {
                    scales: {
                        x: {
                            display: true,
                            offset: true,
                            grid: {
                                display:false,

                                borderWidth: 1,
                                tickLength: 0,

                            }
                        },
                        y: {
                            display: true,
                            grid: {
                                display:false,

                                borderWidth: 1,
                                tickLength: 0,
                                color: 'rgba(255, 255, 255, 0.1)',
                            },
                            ticks: {
                                display: true,
                                autoSkip : false,
                            },
                            min : 0
                        }
                    },
                    plugins: {
                        legend: {
                            display: false,
                            position: "top",
                            labels: {
                                color: twConfig.theme.colors.esg4
                            }
                        }
                    }
                }
            }
        );

        Livewire.on('updateTFChart', data => {
            termChart.data = data;
            termChart.update();
        });
    });
</script>

