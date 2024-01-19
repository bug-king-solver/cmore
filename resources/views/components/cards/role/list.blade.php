<div class="grid grid-cols-1 md:grid-cols-{{ $grid ?? '3' }} gap-9">
    @foreach ($roles as $role)
        <x-cards.list>
            <x-slot:header>
                <div class="flex flex-row gap-2 items-center min-h-[50px] pb-3">
                    <a href="{{ $url ?? '#' }}" class="cursor-pointer">
                        <span class="text-esg29 font-encodesans text-sm font-bold line-clamp-2">
                            {{ $role->name }}
                        </span>
                    </a>
                </div>
            </x-slot>

            <x-slot:content>
                <div class="flex flex-row gap-2 mt-3 items-center">
                    <div class="">
                        <span data-tooltip-target="tooltip-role-user" data-tooltip-target="hover">
                            @include('icons.user', ['width' => 16, 'height' => 12, 'color' => color(6)])
                        </span>
                        <div id="tooltip-role-user" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            {{ __('Users') }}
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </div>
                    <div class="text-esg16 text-sm font-encodesans font-medium">
                        {{ count($role->users()->OnlyAppUsers()->get()) . " " . __('users') }}
                    </div>
                </div>

                <div class="flex flex-row gap-2 mt-3 items-center ">
                    <div class="">
                        <span data-tooltip-target="tooltip-role-permission" data-tooltip-target="hover">
                            @include('icons.lock', ['color' => color(6)])
                        </span>
                        <div id="tooltip-role-permission" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            {{ __('Permission') }}
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </div>
                    <div class="">
                        <p class="line-clamp-2 text-sm font-encodesans text-esg16 font-medium">
                            {{ count($role->permissions) . " " . __('permissions') }}
                        </p>
                    </div>
                </div>
            </x-slot>

            <x-slot:footer>
                <div class="flex flex-row gap-2 items-between">
                    <div class="absolute left-3">
                        @if ($role->default)
                                <span class="font-normal text-esg16 text-xs bg-esg16/20 py-1 px-2 rounded-md">{{ __('Default') }}</span>
                            @endif
                    </div>

                    <x-cards.cards-buttons
                        modalprefix="roles"
                        routeShow="tenant.roles.show"
                        :routeParams="['role' => $role->id]"
                        :data="json_encode(['role' => $role->id])"
                        href="{{ route('tenant.roles.form', ['role' => $role->id]) }}"
                        type="page"
                        view="view"
                        viewhref="{{ route('tenant.roles.show', ['role' => $role->id]) }}" />
                </div>
            </x-slot>
        </x-cards.list>
    @endforeach
</div>

<div class="">
    {{ $roles->links() }}
</div>
