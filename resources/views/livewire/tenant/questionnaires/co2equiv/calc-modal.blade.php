<div>
    <x-form.form title="{!! __('Calculadora de emissão') !!}" buttonPosition="justify-end">

        <table class="table table-auto table-bordered border-separate border border-green-900 ">
            <thead>
                <tr>
                    <th>
                        Butane
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        {{ $value }} kWh
                    </td>
                </tr>
                <tr>
                    <td>
                        12.58 kg
                    </td>
                </tr>
                <tr>
                    <td>
                        12583.00 T
                    </td>
                </tr>
                <tr>
                    <td>
                        7.232 L
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="flex flex-row w-full gap-2">
            <x-form.form-col input="text" id="emission_factor" label="{!! __('Fator de emissão (kg CO2eq / kWh)') !!}"
                class="after:content-['*'] after:text-red-500 !text-esg8 !font-normal"
                placeholder="{{ __('Inform the emission factor') }}" wire:model.defer.500="emission_factor"
                form_div_size="w-full" />

            {{-- <x-form.form-col input="text" id="value" label="{!! __('Value') !!}"
                class="after:content-['*'] after:text-red-500 !text-esg8 !font-normal" readonly
                form_div_size="w-full" /> --}}
        </div>


        <x-form.form-col input="text" id="result_emission_factor" label="{!! __('Result (kg CO2eq)') !!}"
            class="after:content-['*'] after:text-red-500 !text-esg8 !font-normal"
            placeholder="{{ __('co2eq / Emission Factor') }}" />

    </x-modals.form>
</div>
