@component('mail::message')

{{ __('A pending comment :commentable_name by :commentator_name awaits your approval', [
    'commentable_name' => $topLevelComment->commentable->commentableName(),
    'commentator_name' => $comment->commentatorProperties()->name ?? 'anonymous',
]) }}

[{{ __('View comment') }}]({{ $comment->commentUrl() }})

{!! $comment->text !!}

@component('mail::button', ['url' => $comment->approveUrl()])
    {{ __('Approve comment') }}
@endcomponent

@component('mail::button', ['url' => $comment->rejectUrl()])
    {{ __('Reject comment') }}
@endcomponent

@endcomponent
