<div>
    <x-modals.form click="save">
        <x-slot name="title">
            {{ __('Customize your chart') }}
        </x-slot>

        <x-form.form-col input="tomselect" id="chartType" label="{!! __('Chart type') !!}"
            class="after:content-['*'] after:text-red-500 !border-[#E5E7EB]" :options="$chartsList"
            items="{{ $chartType ?? '' }}" limit="1" placeholder="{{ __('Select the type of the chart') }}"
            dataTest="dynamic-chart-change-type-test" form_div_size="w-full" />

        <x-form.form-col input="text" id="chartTitlte" label="{!! __('Title') !!}" form_div_size='w-full'
            fieldClass="h-12 !border-[#d0d0d0] text-sm" wire:model="chartTitlte" />

        <div>
            <x-form.label for="chartIndicators" label="{!! __('Label and colors') !!}" />

            @foreach ($chartIndicators as $id => $name)
                <div class="flex justify-around w-full pt-5">
                    <div class="flex-col w-full pr-2">
                        <div class="flex-col w-full pr-2 readonly">
                            <x-form.form-col input="text" id="chartIndicators.{{ $id }}" form_div_size='w-full'
                                fieldClass="h-12 !border-[#d0d0d0]  text-sm"
                                wire:model="chartIndicators.{{ $id }}" />
                        </div>
                    </div>
                    <div class="flex-col">
                        <x-inputs.color input="color" id="chartColors.{{ $id }}"
                            wire:model="chartColors.{{ $id }}" label="{!! __('Indicator Color') !!}"
                            class="after:content-['*'] after:text-red-500 !border-[#d0d0d0]"
                            colorvalue="{{ $chartColors[$id] }}" type="hex" />
                    </div>
                </div>
            @endforeach
        </div>

    </x-modals.form>
</div>

<style nonce="{{ csp_nonce() }}">
    .popup.popup_right {
        top: -70%;
        left: -80%;
    }

    .picker_wrapper.popup .picker_arrow::before {
        width: 0;
        height: 0;
    }
</style>
