<div class="px-4 md:px-0 role">
    <x-slot name="header">
        <x-header title="{{ __('Role') }}" data-test="user-header" click="{{ route('tenant.roles.index') }}">
            <x-slot name="left" ></x-slot>
        </x-header>
    </x-slot>
    <div class="w-full">
        <x-form.form title="{{ $role->exists ? __('Edit :name', ['name' => $role->name]) : __('Create a new Role') }}" class="text-esg5 mb-5" cancel="{{ route('tenant.roles.index') }}">
            
            <x-form.form-col input="text" id="name" label="{{ __('Name') }}" 
                class="after:content-['*'] after:text-red-500"
                form_div_size="w-full" 
                fieldClass="!border-0 !bg-esg7/10 h-12 !text-esg8"
            />

            <x-form.form-col input="tomselect"
                id="permissions"
                plugins="['checkbox_options','remove_button']"
                label="{{ __('Permissions') }}"
                :optgroups="$permissionsList['optgroups']"
                :options="$permissionsList['options']"
                :items="$permissions"
                placeholder="{{ __('Select the permissions') }}"
                form_div_size="w-full" 
            />

            <x-form.form-col input="tomselect"
                id="users"
                plugins="['checkbox_options','remove_button']"
                label="{{ __('Users') }}"
                :options="$usersList"
                :items="$users"
                placeholder="{{ __('Select the users') }}"
                form_div_size="w-full" 
            />

            <x-form.form-row input="checkbox" id="default" label="{{ __('Default?') }}" />

        </x-form.form>
    </div>
</div>
