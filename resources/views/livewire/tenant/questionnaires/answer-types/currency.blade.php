<div>
    <div wire:loading.delay.short wire:target="save">
        <x-loading />
    </div>

    @foreach ($question->questionOptions as $question_option)
        <div class="flex space-x-4 mb-8">
                <div class="w-1/3 min-h-46 pr-5">
                    <x-inputs.tomselect
                        wire:model="value.currency"
                        wire:change="save({{ $question_option->id }})"
                        :options="$currenciesList"
                        plugins="['no_backspace_delete', 'remove_button']"
                        :items="$value['currency'] ?? null"
                        placeholder="{{ __('Select the currency') }}"
                        limit='1'
                    />
                </div>
        </div>
    @endforeach
    @error('value.value')
        <p class="mt-1 text-xs font-bold text-red-600">
            {{ __('This field only accepts numbers and a dot as decimal separator.') }}
        </p>
    @enderror
</div>
