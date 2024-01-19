<div>
    <x-modals.form
        title="{{ $token->exists ? __('Edit: :name', ['name' => $token->name]) : __('Create a new token:') }}">

        <x-form.form-row input="text" id="name" label="{{ __('Token Name') }}" class="after:content-['*'] after:text-red-500"/>

        <x-form.form-row input="tomselect" id="userId" label="{{ __('User') }}" :options="$usersList" :items="$userId ?? null"
            limit="1" placeholder="{{ __('Select the user to associate the token') }}" class="after:content-['*'] after:text-red-500"/>

        <x-form.form-row input="tomselect" id="abilities" label="{{ __('Abilities') }}" :options="$abilitiesList"
            :items="$abilities ?? []" plugins="['remove_button', 'checkbox_options']"
            placeholder="{{ __('Select the abilities of the token') }}" class="after:content-['*'] after:text-red-500"/>

    </x-modals.form>
</div>
