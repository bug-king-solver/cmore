<div class="px-4 md:px-0 user">
    <x-slot name="header">
        <x-header title="{{ __('User') }}" data-test="user-header" click="{{ route('tenant.users.index') }}">
            <x-slot name="left"></x-slot>
        </x-header>
    </x-slot>
    <div class="w-full">
        <x-form.form title="{{ $user->exists ? __('Edit :name', ['name' => $user->name]) : __('Create a new User') }}"
            class="text-esg5 mb-5" cancel="{{ route('tenant.users.index') }}">

            @if (tenant()->users_type_self_managed && auth()->user()->is_internal_user)
                @php $options =  \App\Models\Enums\Users\Type::casesArray(); @endphp
                <x-form.form-col input="select" id="type" label="{{ __('Type') }}"
                    class="after:content-['*'] after:text-red-500" :extra="['options' => $options]"
                    dataDescription="{{ __('Internal users are the ones that belongs to the organization that owns the tenant. External users are the opposite, for example: clients and suppliers.') }}"
                    dataTest="user-type" form_div_size="w-full" fieldClass="!border-0 !bg-esg7/10 h-12 !text-esg8" />
            @endif

            <x-form.form-col input="text" id="name" label="{{ __('Name') }}"
                class="after:content-['*'] after:text-red-500" dataDescription="{{ __('The name of the user.') }}"
                dataTest="user-name" form_div_size="w-full" fieldClass="!border-0 !bg-esg7/10 h-12 !text-esg8" />

            <div>
                @if (!$this->isEmailForLogin)
                    <x-form.form-col input="text" id="username" label="{{ getUsernameLabel() }}"
                        class="after:content-['*'] after:text-red-500" dataTest="user-username" form_div_size="w-full"
                        fieldClass="!border-0 !bg-esg7/10 h-12 !text-esg8" />
                @endif
            </div>

            <x-form.form-col input="email" id="email" label="{{ __('E-mail') }}"
                class="after:content-['*'] after:text-red-500" dataDescription="{{ __('The email of the user.') }}"
                dataTest="user-email" form_div_size="w-full" fieldClass="!border-0 !bg-esg7/10 h-12 !text-esg8" />

            <x-form.form-col input="password" id="password" label="{{ __('Password') }}"
                dataDescription="{!! $passwordStrengthMessage !!}" dataTest="user-pw"
                form_div_size="w-full" fieldClass="!border-0 !bg-esg7/10 h-12 !text-esg8" />

            <div class="grid grid-cols-1 md:grid-cols-2 md:gap-5">
                <div>
                    @php $options =  array_combine(config('app.locales'), config('app.locales')); @endphp
                    <x-form.form-col input="select" id="locale" label="{{ __('Locale') }}"
                        class="after:content-['*'] after:text-red-500" :extra="['options' => $options]"
                        dataDescription="{{ __('The locale in which the user will use the software.') }}"
                        dataTest="user-locale" form_div_size="w-full"
                        fieldClass="!border-0 !bg-esg7/10 h-12 !text-esg8" />
                </div>

                <div>
                    @php
                        $preview = null;
                        if (!$errors->has('photo') && ($photo || $photoPreview)) {
                            $preview = $photo ? $photo->temporaryUrl() : asset($photoPreview);
                        }
                    @endphp
                    <x-form.form-col input="file" id="photo" label="{{ __('Photo') }}" :extra="['preview' => $preview]"
                        dataDescription="{{ __('The avatar of the user. It will be shown in the menu.') }}"
                        dataTest="user-photo" form_div_size="w-full"
                        fieldClass="!border-0 !bg-esg7/10 h-12 !text-esg8 !px-4" />
                </div>
            </div>

            @if (
                !tenant()->see_only_own_data ||
                    auth()->user()->isOwner())
                <x-form.form-col input="tomselect" id="roles" plugins="['checkbox_options','remove_button']"
                    label="{{ __('Roles') }}" :options="$rolesList" :items="$roles"
                    placeholder="{{ __('Select the roles') }}" multiple
                    dataDescription="{{ __('The roles for the user. Only internal users can see this option.') }}"
                    dataTest="user-roles" form_div_size="w-full" class="!border-0 !p-0"
                    modelmodifier=".lazy"
                    />

                <x-form.form-col input="tomselect" id="permissions" plugins="['checkbox_options','remove_button']"
                    label="{{ __('Permissions') }}" :optgroups="$permissionsList['optgroups']" :options="$permissionsList['options']" :items="$permissions"
                    placeholder="{{ __('Select the permissions') }}" multiple
                    dataDescription="{{ __('The permissions for the user - they will override the permissions in roles. Only internal users can see this option.') }}"
                    dataTest="user-permissions" form_div_size="w-full" class="!border-0 !p-0" />

                @if (tenant()->hasTagsFeature)
                    <x-form.form-col input="tomselect" id="taggableIds" label="{{ __('Tags') }}" :options="$taggableList"
                        :items="$taggableIds ?: []" plugins="['no_backspace_delete', 'remove_button']"
                        placeholder="{{ __('Select tags') }}"
                        dataDescription="{{ __('The tags for the user. They will help you segment the users. Only internal users can see this option.') }}"
                        dataTest="user-tags" form_div_size="w-full" />
                @endif
            @endif

            @foreach ($customColumnsFields as $field)
                <x-form.form-col input="{{ $field['type'] }}" id="customColumnsData.{{ $field['id'] }}"
                    label="{{ $field['label'] }}" :extra="['options' => $field['options'] ?? []]" form_div_size="w-full" />
            @endforeach

            <x-form.form-row input="checkbox" id="enabled" label="{{ __('Enabled?') }}"
                dataDescription="{{ __('Is the user enabled? If yes, the use can log in and use the software. Otherwise, the user can not log in.') }}"
                dataTest="user-enabled" />

            <x-form.form-row input="checkbox" id="password_force_change" label="{{ __('Force password change') }}"
                dataDescription="{{ __('Force the user to change his password.') }}" dataTest="user-force-change" />

            <div wire:key='{{ time() }}'>
                @include('livewire.tenant.wallet.payable', ['exists' => $user->exists])
            </div>

        </x-form.form>
    </div>
</div>
