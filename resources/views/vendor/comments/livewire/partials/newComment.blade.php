@if($writable)
    @can('createComment', $model)
        <div class="comments-form">
            @if($showAvatars)
                <x-comments::avatar/>
            @endif
            <form class="comments-form-inner" wire:submit.prevent="comment">
                <x-dynamic-component
                    :component="\Spatie\LivewireComments\Support\Config::editor()"
                    model="text"
                    :placeholder="__('Write comment')"
                />
                @error('text')
                <p class="comments-error">
                    {{ $message }}
                </p>
                @enderror
                <x-buttons.btn text="{{ __('Comment') }}" />
            </form>
        </div>
    @endcan
@endif
