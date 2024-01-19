<div>
    <x-modals.white-form title="{{ __('Escopo da visualização') }}">
        <x-modals.white-form-row input="select" id="type" label="{{ __('Selecione o tipo de questionário') }}" :extra="['options' => $typesList]" class="h-10"/>

        <p class="mt-4"> {{ __('Período') }} </p>
        <div class="">
            <ul class="flex gap-4">
                <li>
                    <input type="radio" wire:model="period" id="year-2020" name="period" value="2020" class="hidden peer" required>
                    <label for="year-2020" class="inline-flex items-center text-center justify-between w-20 p-2 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:bg-esg6 peer-checked:text-esg27 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                        <div class="block">
                            <div class="w-full text-lg font-semibold">2020</div>
                        </div>
                    </label>
                </li>
                <li>
                    <input type="radio" wire:model="period" id="year-2021" name="period" value="2021" class="hidden peer">
                    <label for="year-2021" class="inline-flex items-center justify-between w-20 p-2 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:bg-esg6 peer-checked:text-esg27 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                        <div class="block">
                            <div class="w-full text-lg font-semibold">2021</div>
                        </div>
                    </label>
                </li>
                <li>
                    <input type="radio" wire:model="period" id="year-2022" name="period" value="2022" class="hidden peer">
                    <label for="year-2022" class="inline-flex items-center justify-between w-20 p-2 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:bg-esg6 peer-checked:text-esg27 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                        <div class="block">
                            <div class="w-full text-lg font-semibold">2022</div>
                        </div>
                    </label>
                </li>
            </ul>
        </div>
    </x-modals.white-form>
</div>
