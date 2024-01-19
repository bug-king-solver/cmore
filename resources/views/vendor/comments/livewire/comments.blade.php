@php
    use Spatie\Comments\Enums\NotificationSubscriptionType;
@endphp

<section class="comments {{ $newestFirst ? 'comments-newest-first' : '' }} mt-20">
    <header class="comments-header">
        <div class="">
            <h2 class="{{ $class ?? '' }} mb-5 text-xl font-semibold">{{ __('Comments') }}</h2>
        </div>

        @if($writable)
            @auth
                @if($showNotificationOptions)
                    <div x-data="{ subscriptionsOpen: false}" class="comments-subscription">
                        <button @click.prevent="subscriptionsOpen = true" class="comments-subscription-trigger text-esg12">
                            {{ NotificationSubscriptionType::from($selectedNotificationSubscriptionType)->longDescription() }}
                        </button>
                        <x-comments::modal
                                bottom
                                compact
                                x-show="subscriptionsOpen"
                                @click.outside="subscriptionsOpen = false"
                            >
                            @foreach(NotificationSubscriptionType::cases() as $case)
                                <button class="comments-subscription-item text-esg12" @click="subscriptionsOpen = false" wire:click="updateSelectedNotificationSubscriptionType('{{ $case->value }}')">
                                    {{ $case->description() }}
                                </button>
                            @endforeach
                        </x-comments::modal>
                    </div>
                @endif
            @endif
        @endauth
    </header>

    @if ($newestFirst)
        @include('comments::livewire.partials.newComment')
    @endif

    @if($comments->count())
        @foreach($comments as $comment)
            @can('see', $comment)
                <livewire:comments-comment
                    :key="$comment->id"
                    :comment="$comment"
                    :show-avatar="$showAvatars"
                    :newest-first="$newestFirst"
                    :writable="$writable"
                    :show-replies="$showReplies"
                />
            @endcan
        @endforeach

        @if ($comments->hasPages())
            {{ $comments->links() }}
        @endif

    @else
        <p class="comments-no-comment-yet">{{ $noCommentsText ?? __('No comments yet…') }}</p>
    @endif

    @if (! $newestFirst)
        @include('comments::livewire.partials.newComment')
    @endif
</section>
