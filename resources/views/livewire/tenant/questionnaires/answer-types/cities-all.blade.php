<div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

        <x-form.form-col input="tomselect" id="regions" label="{{ __('Region') }}"
            class="after:content-['*'] after:text-red-500 !text-esg8 !font-normal !text-sm mt-0 !border-none"
            form_div_size="w-full" :options="$regionList" :items="$regions" placeholder="{{ __('Select regions') }}"
            :wire_ignore="false" wire:change='save()' multiple plugins="['remove_button']"/>

        <x-form.form-col input="tomselect" id="cities" label="{{ __('City') }}"
            class="after:content-['*'] after:text-red-500 !text-esg8 !font-normal !text-sm mt-0 !border-none"
            form_div_size="w-full" :options="$citiesList" :items="$cities" placeholder="{{ __('Select cities') }}"
            :wire_ignore="false" wire:change='save()' multiple plugins="['remove_button']"/>

    </div>

</div>
