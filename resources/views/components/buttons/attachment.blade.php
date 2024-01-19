<button
    {{ $attributes->merge(['class' => 'relative cursor-pointer py-1 px-4 rounded-md ' . ($required ?? false ? 'bg-esg5 ' : 'text-esg29')]) }}
    @if (!tenant()->onActiveSubscription) @click="trial_modal = true" @else @click="Livewire.emit('openModal', 'modals.attachments', {{ $data }})" @endif>

    <span class="flex items-center gap-2 whitespace-nowrap text-xs {{ $required ?? false ? 'text-esg4' : 'text-esg6' }}">
        @if (isset($text))
            {{ $text }}
        @else
            @include('icons.attachment', [
                'class' => 'shrink-0 w-4 h-4',
                'stroke' => config('theme.default.colors.' . ($required ?? false ? 'esg4' : 'esg6')),
                'color' => $required ?? false ? color(4) : color(6),
            ])
        @endif

        @if ($counter)
            {{ $counter }} {{ __('Attached files') }}
        @else
            {{ __('Attach file') }}
        @endif

    </span>
</button>
