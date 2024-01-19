<div class="flex items-center justify-center mb-4">
    <div class="justify-start grow min-w-fit">
        <p class="text-base font-bold leading-5">{{ __('Average Sentiment')}}</p>
    </div>
    <div class="flex justify-end ">
        <x-inputs.daterange
            class="rounded-md text-esg8 text-xs"
            wire:change="filters()"
            wire:model.lazy="selectedRange"
            id="sentimentAverageRange"
        />
    </div>
</div>
<div class="absolute left-[111px] bottom-[62px]">
    <img src="/images/icons/emoji/sad.svg">
</div>
<div class="absolute right-[111px] bottom-[62px]">
    <img src="/images/icons/emoji/positive.svg">
</div>

<div class="absolute left-[47%] top-[126px]">
    <img src="/images/icons/emoji/neutral.svg">
</div>
<div class="relative m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]">

    <canvas id="avg_sentiment_1" class="absolute m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]"></canvas>
    <canvas id="avg_sentiment_2" class="absolute m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]"></canvas>
    <div id="avg_sentiment_value" class=" absolute bottom-[50px] w-full text-center text-4xl font-bold"></div>
</div>

<script nonce="{{ csp_nonce() }}">
document.addEventListener('DOMContentLoaded', () => {
    function showPercenrageLabel(perecentage) {
        perecentage = Math.round(perecentage);
        if(perecentage < 1) {
            return "{{ __('Negative')}}";
        } else if(perecentage >1 ) {
            return "{{ __('Positive')}}";
        } else {
            return "{{ __('Neutral')}}";
        }
    }

    function getColors(perecentage) {
        perecentage = Math.round(perecentage);
        var firstColor = '#19A0FD';
        if(perecentage < 1) {
            firstColor = '#F44336';
        } else if(perecentage > 1) {
            firstColor = '#99CA3C';
        }
        return [firstColor, twConfig.theme.colors.esg7];
    }
    var options =  {
            plugins: {
                title: {
                    display: true,
                    text: '',
                    font: {
                        family: twConfig.theme.fontFamily.encodesans,
                        size: 18,
                        weight: twConfig.theme.fontWeight.bold,
                    }
                },
                tooltip: {
                    enabled: false,
                },
            },
            rotation: 270, // start angle in degrees
            circumference: 180, // sweep angle in degrees
            cutout: '80%'
        };

        var data = {
            datasets: [{
                data: @json($datasets),
                backgroundColor: getColors('{{$datasets[0]}}'),
                borderRadius: 8,
                borderWidth: 0,
                spacing: 0,
            }],
        };

        var config = {
            type: 'doughnut',
            data: data,
            options
        };

        var ctxone = document.getElementById('avg_sentiment_2');
        var firstSAChart = new Chart(ctxone, config);


        var options =  {
            plugins: {
                title: {
                    display: true,
                    text: '',
                    font: {
                        family: twConfig.theme.fontFamily.encodesans,
                        size: 18,
                        weight: twConfig.theme.fontWeight.bold,
                    },
                    color: '#000'
                },
                tooltip: {
                    enabled: false,
                },
            },
            rotation: 270, // start angle in degrees
            circumference: 180, // sweep angle in degrees
            cutout: '80%',
            elements: {
				arc: {
					roundedCornersFor: 0
				}
			}
        };

        var data = {
            datasets: [{
                data: [1],
                backgroundColor: [ twConfig.theme.colors.esg7],
                hoverBackgroundColor: [ twConfig.theme.colors.esg7],
                borderRadius: 8,
                borderWidth: 0,
                spacing: 0,
            }],
        };

        var config = {
            type: 'doughnut',
            data: data,
            options
        };

        var ctx = document.getElementById('avg_sentiment_1');
        var secondSAChart = new Chart(ctx, config);
        document.getElementById('avg_sentiment_value').innerHTML = showPercenrageLabel('{{$datasets[0]}}');
        Livewire.on('updateSAChart', data => {
            firstSAChart.data.datasets[0].data = data.datasets;
            firstSAChart.update();
            secondSAChart.update();
            document.getElementById('avg_sentiment_value').innerHTML = showPercenrageLabel(data.datasets[0]);
        });

});
</script>
