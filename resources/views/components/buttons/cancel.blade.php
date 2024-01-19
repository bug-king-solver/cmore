<button type="button" {!! $action ?? 'x-on:click="Livewire.emit(\'closeModal\')"' !!} {{ $attributes->merge(['class' => 'cursor-pointer inline px-2 py-1 rounded bg-esg4 border-[1px] border-esg5 text-esg28 uppercase font-inter font-bold text-xs']) }}>
    {!! __('Cancel') !!}
</button>
