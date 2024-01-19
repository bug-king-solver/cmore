<div>
    <div wire:loading.delay.short wire:target="save" class="absolute">
        <x-loading />
    </div>
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
