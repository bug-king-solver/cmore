
<button @if (! tenant()->onActiveSubscription) @click="trial_modal = true" @else @click="Livewire.emit('openModal', 'modals.attachments', {{ $data }})" @endif
    {{ $attributes->merge(['class' => 'inline ml-5 py-1.5 px-2 rounded-lg bg-esg5 text-esg27 uppercase font-inter font-bold text-xs']) }}>
    @if(isset($text))
        {{$text}}
    @else
        @include('icons.attachment', ['stroke' => config('theme.default.colors.' . ($required ?? false ? 'esg4' : 'esg6'))])
    @endif
    @if ($counter)<span class="absolute inline-flex justify-center items-center p-3 -top-3 -right-3 w-3 h-3 text-sm font-medium text-esg27 bg-esg6 rounded-full ">{{ $counter }}</span>@endif
</button>

