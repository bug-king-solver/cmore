<div class="grid grid-cols-1 md:grid-cols-{{ $grid ?? '3' }} gap-9">
    @foreach ($users as $user)
        @php $rolesArr = json_encode($user->roles->pluck('name')); @endphp
        <x-cards.list>
            <x-slot:header>
                <div class="flex flex-row gap-2 items-center min-h-[50px] pb-3">
                    <img class="rounded-full" src="{{ $user->avatar }}" width="34" height="34">
                    <a href="{{ $url ?? '#' }}" class="cursor-pointer">
                        <span class="text-esg29 font-encodesans text-sm font-bold line-clamp-2">
                            {{ $user->name }}
                        </span>
                    </a>
                </div>
            </x-slot>

            <x-slot:content>
                <div class="flex flex-row gap-2 mt-3 items-center">
                    <div class="">
                        <span data-tooltip-target="tooltip-user-email" data-tooltip-target="hover">
                            @include('icons.email', ['width' => 15, 'height' => 16, 'color' => color(6)])
                        </span>
                        <div id="tooltip-user-email" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            {{ __('Email') }}
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </div>
                    <div class="text-esg16 text-sm font-encodesans font-medium flex gap-2 items-center">
                        {{ $user->email }}
                        @if (tenant()->hasUserEmailVerificationEnabled())
                            <span data-tooltip-target="tooltip-user-email-status-{{ $user->id }}" data-tooltip-target="hover">
                                @if ($user->email_verified_at)
                                    @include('icons.tables.checked', ['width' => 15, 'height' => 15, 'color' => color(2)])
                                @else
                                    @include('icons.tables.unchecked', ['width' => 15, 'height' => 15, 'color' => 'red'])
                                @endif
                            </span>
                            <div id="tooltip-user-email-status-{{ $user->id }}" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                {{ ($user->email_verified_at) ? __('Verified') : __('Not Verified') }}
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex flex-row gap-2 mt-3 items-center ">
                    <div class="">
                        <span data-tooltip-target="tooltip-user-role-{{ $user->id }}" data-tooltip-target="hover">
                            @include('icons.single-user', ['width' => 13, 'height' => 13, 'color' => color(6)])
                        </span>
                        <div id="tooltip-user-role-{{ $user->id }}" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            {{ __('Role') }}
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </div>
                    <div class="">
                        <p class="line-clamp-2 text-sm font-encodesans text-esg16 font-medium">
                            <x-dropdown.toggle data="{!! $rolesArr !!}" roleid="{{ $user->id }}"></x-dropdown.toggle>
                        </p>
                    </div>
                </div>
                
                @if (tenant()->hasUserManualActivationEnabled())
                    <div class="flex flex-row gap-2 mt-3 items-center ">
                        <div class="text-center ml-0.5">
                            <span data-tooltip-target="tooltip-user-status" data-tooltip-target="hover">
                                @if ($user->enabled)
                                    <p class="w-3 h-3 rounded-full bg-esg2"></p>
                                @else
                                    <p class="w-3 h-3 rounded-full bg-red-600"></p>
                                @endif
                            </span>
                            <div id="tooltip-user-status" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                {{ __('Status') }}
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        </div>
                        <div class="">
                            <p class="line-clamp-2 text-sm font-encodesans text-esg16 font-medium pl-0.5">
                                @if ($user->enabled)
                                    {{ __('Enabled') }}
                                @else
                                    {{ __('Disabled') }}
                                @endif
                            </p>
                        </div>
                    </div>
                @endif
            </x-slot>

            <x-slot:footer>
                <div class="flex flex-row gap-2 items-end">
                    <x-cards.cards-buttons 
                        modalprefix="users" 
                        routeShow="tenant.users.show" 
                        :routeParams="['user' => $user->id]"
                        :data="json_encode(['user' => $user->id])" 
                        href="{{ route('tenant.users.form', ['user' => $user->id]) }}" 
                        type="page"
                        view="view"
                        viewhref="{{ route('tenant.users.show', ['user' => $user->id]) }}" />
                </div>
            </x-slot>
        </x-cards.list>
    @endforeach
</div>

<div class="mb-10">
    {{ $users->links() }}
</div>