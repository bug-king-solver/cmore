<div class="grid grid-cols-2 gap-4 mb-4">
    <div class="">
        <p class="text-base font-bold leading-5">{{ __('Average Emotion') }}</p>
    </div>
    <div class="flex justify-end">
        <x-inputs.daterange
            class="rounded-md text-esg8 text-xs"
            wire:change="filters()"
            wire:model.lazy="selectedRange"
            id="emotionAverageRange"
        />
    </div>
</div>
<div class="h-[300px]">
    <canvas id="{{ $elemId}}" class="m-auto relative !h-full !w-full"></canvas>
</div>

<script nonce="{{ csp_nonce() }}">
    document.addEventListener('DOMContentLoaded', () => {
        const termChart = new Chart(
            document.getElementById('{{ $elemId }}'), {
                type: 'radar',
                data: {
                    labels: @json($labels),
                    datasets: @json($datasets)
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scale: {
                        min: 0,
                    },
                    scales: {
                        r: {
                            ticks: {
                                callback: function(value, index, ticks) {
                                    return value + '%';
                                }
                            },
                            min : 0
                        }
                    },
                },
            }
        );

        Livewire.on('updateEAChart', data => {
            termChart.data = data;
            termChart.update();
        });
    });
</script>
