@php
    $avatar = global_asset('images/icons/user.png');
    $user = null;

    if (isset($comment) && $comment->commentatorProperties()) {
        $user = $comment->commentatorProperties();
        $user->add('avatar', $comment->commentator->avatar);
    } elseif (auth()->user()) {
        $user = auth()->user();
    }

    if ($user){
        $avatar = $user->avatar;
    }
@endphp

<img
    class="comments-avatar"
    src="{{ $avatar }}"
    alt="avatar"
>
