<div class="w-full">
    <x-tables.white.table>
        <x-slot name="thead" class="!border-0 bg-esg4">
            <x-tables.white.th class="bg-esg4 text-esg6 !text-sm">{{ __('') }}</x-tables.white.th>
            <x-tables.white.th class="bg-esg4 text-esg6 !text-sm">{{ __('Name') }}</x-tables.white.th>
            <x-tables.white.th class="bg-esg4 text-esg6 !text-sm">{{ __('Email') }}</x-tables.white.th>
            <x-tables.white.th class="bg-esg4 text-esg6 !text-sm">{{ __('Permission') }}</x-tables.white.th>
            {{-- <x-tables.white.th class="bg-esg4 text-esg6 !text-sm"></x-tables.white.th> --}}
        </x-slot>

        @forelse ($users as $user)
            @php $rolesArr = json_encode($user->roles->pluck('name')); @endphp
            <x-tables.white.tr class="border-b border-b-esg8/20">
                <x-tables.white.td class="!px-0 text-sm"><img class="rounded-full" src="{{ $user->avatar }}"
                        width="34" height="34"></x-tables.white.td>
                <x-tables.white.td class="!px-0 text-sm"> {{ $user->name }} </x-tables.white.td>
                <x-tables.white.td class="!px-0 text-sm flex items-center gap-2">
                    {{ $user->email }}
                    @if (tenant()->hasUserEmailVerificationEnabled())
                        <span data-tooltip-target="tooltip-user-email-status-{{ $user->id }}"
                            data-tooltip-target="hover">
                            @if ($user->email_verified_at)
                                @include('icons.tables.checked', [
                                    'width' => 15,
                                    'height' => 15,
                                    'color' => color(5),
                                ])
                            @else
                                @include('icons.tables.unchecked', [
                                    'width' => 15,
                                    'height' => 15,
                                    'color' => color(67),
                                ])
                            @endif
                        </span>
                        <div id="tooltip-user-email-status-{{ $user->id }}" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            {{ $user->email_verified_at ? __('Verified') : __('Not Verified') }}
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    @endif
                </x-tables.white.td>

                <x-tables.white.td class="!px-0 text-sm ">
                    <div class="flex items-center gap-2">
                        <div class="">
                            <span data-tooltip-target="tooltip-user-role-{{ $user->id }}"
                                data-tooltip-target="hover">
                                @include('icons.single-user', [
                                    'width' => 13,
                                    'height' => 13,
                                    'color' => color(6),
                                ])
                            </span>
                            <div id="tooltip-user-role-{{ $user->id }}" role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                {{ __('Role') }}
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        </div>
                        <div class="">
                            <p class="line-clamp-2 text-sm font-encodesans text-esg16 font-medium">
                                <x-dropdown.toggle data="{!! $rolesArr !!}"
                                    roleid="{{ $user->id }}"></x-dropdown.toggle>
                            </p>
                        </div>
                    </div>
                </x-tables.white.td>

                {{-- <x-tables.white.td class="!px-0 text-sm">
                    <x-cards.cards-buttons modalprefix="users" routeShow="tenant.users.show" :routeParams="['user' => $user->id]"
                        :data="json_encode(['user' => $user->id])" href="{{ route('tenant.users.form', ['user' => $user->id]) }}" type="page"
                        view="view" viewhref="{{ route('tenant.users.show', ['user' => $user->id]) }}" />
                </x-tables.white.td> --}}
            </x-tables.white.tr>
        @empty
            <p>{!! __('Please, assign user to show the list!') !!}</p>
        @endforelse
    </x-tables.white.table>
</div>
