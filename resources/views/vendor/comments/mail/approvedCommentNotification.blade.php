@component('mail::message')
# {{ __('A new comment on ":commentable_name"', [
    'commentable_name' => $topLevelComment->commentable->commentableName(),
    'commentable_url' => $topLevelComment->commentable->commentUrl(),
    'commentator_name' => $comment->commentatorProperties()->name ?? 'anonymous',
]) }}

{{ __('Posted by :commentator_name', [
    'commentable_name' => $topLevelComment->commentable->commentableName(),
    'commentable_url' => $topLevelComment->commentable->commentUrl(),
    'commentator_name' => $comment->commentatorProperties()->name ?? 'anonymous',
]) }}

{!! $comment->text !!}

@component('mail::button', ['url' => $comment->commentUrl()])
{{ __('View comment') }}
@endcomponent

@if($unsubscribeUrl = $commentator->unsubscribeFromCommentNotificationsUrl($comment))
<a href="{{ $unsubscribeUrl }}">Unsubscribe from receive notification about {{ $topLevelComment->commentable->commentableName() }}</a>
@endif

@endcomponent
