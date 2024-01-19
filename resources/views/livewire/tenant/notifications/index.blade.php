<div>
    <x-slot name="header">
        <x-header title="{{ __('List of Notifications') }}">
            <x-slot name="left"></x-slot>
        </x-header>
    </x-slot>

    <x-tables.table>
        <x-slot name="thead">
            <x-tables.th>{{ __('Notification') }}</x-tables.th>
            <x-tables.th width="150" class="text-center">{{ __('Date/Time') }}</x-tables.th>
            <x-tables.th>
                <div x-data="{expanded: false}" class="relative block py-2 cursor-pointer text-sm font-medium">
                    <a @click="expanded = !expanded" @click.away="expanded = false">
                        @include('icons.option', ['height' => 20, 'color' => color('esg4')])
                        <div x-cloak x-show="expanded" class="absolute bg-esg4/[.10] w-44 border-0 rounded z-10">
                            <x-buttons.btn-text wire:click="markAsRead()" class="text-sm text-esg27 font-semibold w-full p-2 text-left hover:bg-esg6/[.80]">{{ __('Mark all as read') }}</x-buttons.btn-text>
                            <x-buttons.btn-text wire:click="delete()" class="text-sm text-esg27 font-semibold w-full p-2 text-left hover:bg-esg6/[.80]">{{ __('Delete all')}}</x-buttons.btn-text>
                        </div>
                    </a>
                </div>
            </x-tables.th>
        </x-slot>

        @foreach($notifications as $notification)
            @php
            $loopEncoded = json_encode($loop);
            $markAsRead = $notification->unread() ? "markAsRead('{$notification->id}')" : '';
            @endphp
            <x-tables.tr class="hover:bg-esg4/[.05]">
                <x-tables.td loop="{!! $loopEncoded !!}">
                    <span class="{{ $notification->unread() ? 'font-bold cursor-pointer' : '' }}" wire:click="{!! $markAsRead !!}">{{ __($notification->data['message'], $notification->data) }}</span>
                    @if (isset($notification->data['action']))
                        <x-buttons.a-alt class="text-sm p-0 border-none font-bold text-esg29 hover:bg-esg7/50" href="{{ $notification->data['action'] }}">{{ __('View') }}</x-buttons.a-alt>
                    @endif
                </x-tables.td>
                <x-tables.td loop="{!! $loopEncoded !!}" class="text-sm text-center">{{ $notification->created_at->format('Y-m-d H:i:s') }}</x-tables.td>
                <x-tables.td loop="{!! $loopEncoded !!}"><x-buttons.trash-immediately wire:click="delete('{{ $notification->id }}')" /></x-tables.td>
            </x-tables.tr>
        @endforeach
    </x-tables-table>

    <div class="">
        {{ $notifications->links() }}
    </div>
</div>
