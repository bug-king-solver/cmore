<div>
    <x-slot name="header">
        <x-header title="{{ __('Roles') }}" data-test="users-header" click="{{ route('tenant.roles.index') }}">
            <x-slot name="left"></x-slot>
            <x-slot name="right">
                <x-buttons.a-icon href="{{ route('tenant.roles.form', ['role' => $role->id]) }}">
                    <div class="flex gap-3 items-center border border-esg16 py-1 px-2 rounded-md">
                        @include('icons.tables.edit', ['color' => color(16)])
                        {{ __('Edit') }}
                    </div>
                </x-buttons.a-icon>
            </x-slot>
        </x-header>
    </x-slot>

    <div class="flex justify-between items-center mt-2">
        <div class="text-lg font-bold text-esg5">
            {{ $role->name }}
        </div>
        <div class="">

        </div>
    </div>

    <div class="mt-6 mb-3">
        <label class="text-base text-esg6 font-bold"> {{ __('Users') }} </label>
    </div>

    @if ($users->isEmpty())
        <div class="flex justify-center items-center p-6">
            <h3 class="w-fit text-md">{{ __('No users found yet.') }}</h3>
        </div>
    @else
        <x-cards.user.list :users="$users" grid="4" />
    @endif


    <div class="mt-6 mb-3">
        <label class="text-base text-esg6 font-bold"> {{ __('Permissions') }} </label>
    </div>

    @php
        $permissionGroups = getRolePermissionsGroup($permissions);
    @endphp

    @if (!empty($permissionGroups))
        <x-tables.white.table>
            @foreach($permissionGroups as $key => $permissions)
                <x-tables.white.tr class="border-b border-b-esg8/20">
                    <x-tables.white.td class="!px-0 text-sm">{{ $key }}</x-tables.white.td>

                    @foreach($permissions as $key => $permission)
                        <x-tables.white.td class="!px-0 text-sm">
                            <x-inputs.switch id="{{ $key }}" checked="checked" disabled="disabled" label="{{ $permission }}"/>
                        </x-tables.white.td>
                    @endforeach
                </x-tables.white.tr>
            @endforeach
        </x-tables.white.table>
    @else
        <div class="flex justify-center items-center p-6">
            <h3 class="w-fit text-md">{{ __('Permission not found yet.') }}</h3>
        </div>
    @endif
</div>
