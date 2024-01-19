<div x-cloak x-show="notifications" @click.away="notifications = false" class="bg-esg4 text-esg29 absolute right-2 xs:-right-5 md:right-0 mt-2 w-screen md:w-96 rounded-md text-sm shadow-lg">
    <ul class="">
        @if (! $notifications->count())
            <li class="px-4 py-2 text-center">{{ __('There are no new notifications.')}}</li>
        @else
                @foreach ($notifications as $notification)
                    <li class="">
                        <div class="flex justify-between hover:bg-esg7/50 px-4 py-2">
                            <div class="">
                                <p class="">
                                    <span class="{{ $notification->unread() ? 'cursor-pointer': '' }}" wire:click="markAsRead('{{ $notification->id }}')">{{ __($notification->data['message'], $notification->data) }}</span>
                                    @if (isset($notification->data['action']))
                                        <x-buttons.a-alt class="text-sm p-0 border-none font-bold text-esg29 hover:bg-esg7/50" href="{{ $notification->data['action'] }}">{{ __('View') }}</x-buttons.a-alt>
                                    @endif
                                </p>
                                <span class="text-esg11 text-xs font-medium">{{ $notification->created_at->format('m-d H:i') }}</span>
                            </div>
                        </div>
                    </li>
                @endforeach
                <li class="px-4 py-2 text-center">
                    <x-buttons.btn-text wire:click="markAsRead()" class="text-xs">{{ __('Mark all as read') }}</x-buttons.btn-text>
                </li>
        @endif

        <li class="border-t-esg5 border-t-[1px] border-dashed px-4 py-2 text-center"><a href="{{ route('tenant.notifications.index') }}" class="text-esg28 text-xs">{{ __('see all') }}</a></li>
    </ul>
</div>
