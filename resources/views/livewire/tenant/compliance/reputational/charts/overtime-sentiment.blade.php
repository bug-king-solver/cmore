<div class="grid grid-cols-2 gap-4 mb-4">
    <div class="">
        <p class="text-base font-bold leading-5">{{ __('Sentiment over time') }}</p>
        <div id="legend-container" ></div>
    </div>
    <div class="flex justify-end">
        <x-inputs.daterange
            class="rounded-md text-esg8 text-xs"
            wire:change="filters()"
            wire:model.lazy="selectedRange"
            id="sentimentOvertimeRange"
        />
    </div>
</div>
<div class="">
    <canvas id="{{ $elemId }}"></canvas>
</div>

    <script nonce="{{ csp_nonce() }}">
        document.addEventListener('DOMContentLoaded', () => {
            const getOrCreateLegendList = (chart, id) => {
                const legendContainer = document.getElementById(id);
                let listContainer = legendContainer.querySelector('ul');

                if (!listContainer) {
                    listContainer = document.createElement('ul');
                    listContainer.style.display = 'flex';
                    listContainer.style.flexDirection = 'row';
                    listContainer.style.margin = 0;
                    listContainer.style.padding = 0;

                    legendContainer.appendChild(listContainer);
                }

                return listContainer;
                };

                const htmlLegendPlugin = {
                id: 'htmlLegend',
                afterUpdate(chart, args, options) {
                    const ul = getOrCreateLegendList(chart, options.containerID);

                    // Remove old legend items
                    while (ul.firstChild) {
                    ul.firstChild.remove();
                    }

                    // Reuse the built-in legendItems generator
                    const items = chart.options.plugins.legend.labels.generateLabels(chart);

                    items.forEach(function callback(item, index) {
                    const li = document.createElement('li');
                    li.style.alignItems = 'center';
                    li.style.cursor = 'pointer';
                    li.style.display = 'flex';
                    li.style.flexDirection = 'row';
                    if(index!=0) {
                    li.style.marginLeft = '10px';
                    }
                    li.onclick = () => {
                        const {type} = chart.config;
                        if (type === 'pie' || type === 'doughnut') {
                        // Pie and doughnut charts only have a single dataset and visibility is per item
                        chart.toggleDataVisibility(item.index);
                        } else {
                        chart.setDatasetVisibility(item.datasetIndex, !chart.isDatasetVisible(item.datasetIndex));
                        }
                        chart.update();
                    };

                    // Color box
                    const boxSpan = document.createElement('span');
                    boxSpan.style.background = item.fillStyle;
                    boxSpan.style.borderColor = item.strokeStyle;
                    boxSpan.style.borderWidth = item.lineWidth + 'px';
                    boxSpan.style.borderRadius = '50%';
                    boxSpan.style.display = 'inline-block';
                    boxSpan.style.height = '10px';
                    boxSpan.style.marginRight = '10px';
                    boxSpan.style.width = '10px';

                    // Text
                    const textContainer = document.createElement('p');
                    textContainer.style.color = item.fontColor;
                    textContainer.style.margin = 0;
                    textContainer.style.padding = 0;
                    textContainer.style.fontSize = '12px';
                    textContainer.style.textDecoration = item.hidden ? 'line-through' : '';

                    const text = document.createTextNode(item.text);
                    textContainer.appendChild(text);

                    li.appendChild(boxSpan);
                    li.appendChild(textContainer);
                    ul.appendChild(li);
                    });
                }
            };
            const customLegendImage = {
                id: 'customLegend',
                afterDraw: (chart, args, options) => {
                    const {
                        _metasets,
                        ctx
                    } = chart;
                    ctx.save();

                    var negative = new Image();
                    negative.src = "{{ global_asset('images/icons/emoji/sad.svg') }}";

                    var positive = new Image();
                    positive.src = "{{ global_asset('images/icons/emoji/positive.svg') }}";

                    var neutral = new Image();
                    neutral.src = "{{ global_asset('images/icons/emoji/neutral.svg') }}";

                    _metasets.forEach((meta) => {
                        if(meta.data[meta.data.length - 1]) {
                            var xPostition = meta.data[meta.data.length - 1].x;
                            var yPostition = meta.data[meta.data.length - 1].y;

                            var icon = '';
                            if (meta._dataset.label == 'Positive') {
                                icon = positive;
                            } else if (meta._dataset.label == 'Negative') {
                                icon = negative;
                            } else {
                                icon = neutral;
                            }

                            ctx.drawImage(icon, xPostition + 10, yPostition - 15, 25, 25);
                        }
                    });
                }
            };

            const chart = new Chart(
                document.getElementById('{{ $elemId }}').getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: @json($labels),
                        datasets: @json($datasets)
                    },
                    options: {
                        scales: {
                            x: {
                                grid: {
                                    display: false,

                                    tickLength: 0,
                                }
                            },
                            y: {
                                grid: {
                                    display: true,
                                    borderWidth: 1,
                                    tickLength: 0,
                                },
                                ticks: {
                                    display: true,
                                    autoSkip : false
                                },
                                min : 0
                            }
                        },
                        plugins: {
                            htmlLegend: {
                                // ID of the container to put the legend in
                                containerID: 'legend-container',
                            },
                            legend: {
                                display: false,
                            }
                        },
                        layout: {
                            padding: {
                                // Any unspecified dimensions are assumed to be 0
                                right: 35
                            }
                        }
                    },
                    plugins: [customLegendImage, htmlLegendPlugin]
                });
            Livewire.on('updateOSChart', data => {
                chart.data = data;
                chart.update();
            });
        });
    </script>
