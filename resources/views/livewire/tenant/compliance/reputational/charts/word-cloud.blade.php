<div class="flex items-center justify-center mb-4">
    <div class="justify-start grow min-w-fit">
        <p class="text-base font-bold leading-5">{{ __('Word Cloud') }}</p>
        <p class="text-esg35 text-xs">{{ __('Most important terms') }}</p>
    </div>
    <div class="flex justify-end">
        <x-inputs.daterange
            class="rounded-md text-esg8 text-xs"
            wire:change="filters()"
            wire:model.lazy="selectedRange"
            id="wordCloudRange"
        />
    </div>
</div>
<div id="{{ $elemId }}" class="w-full h-72"></div>

<style nonce="{{ csp_nonce() }}">
    #{{ $elemId }} span {
        margin-right: 5px;
        opacity: 0.7;
    }

    #{{ $elemId }} span:first-child {
        opacity: 1;
    }
</style>

<script nonce="{{ csp_nonce() }}">
    document.addEventListener('DOMContentLoaded', () => {
        WordCloud(document.getElementById('{{ $elemId }}'), {
            list: @json($datasets),
            color: '#E86321',
            fontFamily: twConfig.theme.fontFamily.encodesans,
            rotateRatio: 0,
            rotationSteps: 2,
        });

        Livewire.on('updateWc', data => {
            WordCloud(document.getElementById('{{ $elemId }}'), {
                list: data.datasets,
                color: '#E86321',
                fontFamily: twConfig.theme.fontFamily.encodesans,
                rotateRatio: 0,
                rotationSteps: 2,
            });
        });
    });
</script>

