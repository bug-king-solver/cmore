<div
    x-data="compose({ text: @entangle($model).defer, autofocus: @json($autofocus ?? false) })"
    x-init="
        $wire.on('comment', clear);
        @isset($comment)
            $wire.on('reply-{{ $comment->id }}', clear);
        @endisset
    "
>
    <div wire:ignore>
        <textarea placeholder="{{ $placeholder ?? '' }}"></textarea>
    </div>

    <div class="comments-form-editor-tip">

    </div>
</div>
