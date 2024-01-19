<div>
    <div wire:loading.delay.short wire:target="save" class="absolute">
        <x-loading />
    </div>
    @php
        $countriesList = getCountriesForSelect();
    @endphp
    <x-inputs.tomselect
        wire:model="value"
        wire:change="save()"
        :options="$countriesList"
        plugins="['no_backspace_delete', 'remove_button']"
        :items="$value"
        multiple
        placeholder="{{ __('Select the countries') }}"
    />
</div>

<style nonce="{{ csp_nonce() }}">
    .ts-control > input {
        width: 7rem;
    }
</style>