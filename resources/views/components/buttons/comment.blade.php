<button
    {{ $attributes->merge(['class' => 'relative cursor-pointer py-1' . (($required) ? '': ' text-esg29')]) }}
    @if (! tenant()->onActiveSubscription) @click="trial_modal = true" @else wire:click="$toggle('showComments')" @endif >

    <span class="flex items-center gap-2 whitespace-nowrap text-xs text-esg6">
        @include('icons.comment-chat', ['class' =>'shrink-0 w-4 h-4', 'counter'=>$counter,'stroke' => config('theme.default.colors.' . ($required ? 'esg6' : 'esg6'))])
         {{ $counter==0?'':$counter }} {{ __('Comments') }}
    </span>

</button>
