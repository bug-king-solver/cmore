@push('body')
<script nonce="{{ csp_nonce() }}">
    document.addEventListener("chartUpdated", event => {
        dynamicCharts('{{ $slug }}-{{$index}}');
    });
</script>
@endpush

<div id="data_block" class="pt-15 xl:pr-20 lg:pt-5 xl:pt-0 w-full">
    <div class="text-lg text-esg8 font-bold font-encodesans">
        <span class="inline-block pr-3">
            @include('icons.user')
        </span>
        {{ $name }}
    </div>
    @if ($structure)
        <div class="flex items-center w-full">
            <x-charts.chartjs id="{{ $slug }}-{{$index}}" class="max-h-[650px]" x-init="$nextTick(() => { dynamicCharts('{{ $slug }}-{{$index}}') });"
                data-json="{{ json_encode($structure, JSON_UNESCAPED_UNICODE) }}" />
        </div>
    @else
        <div id="data">
            {{ __('No data available') }}
        </div>
    @endif
</div>
