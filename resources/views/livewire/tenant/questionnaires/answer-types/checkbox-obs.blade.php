<div class="grid gap-y-2">
    <div wire:loading.delay.short wire:target="save" class="absolute">
        <x-loading />
    </div>
    @foreach ($question->questionOptions as $question_option)
        <div class="grid grid-cols-3 gap-2">
            <div class="col-span-2 flex items-center">
                <label><input type="checkbox" {{ $this->answer->validation ? 'disabled' : '' }}  {{ $questionnaire->isSubmitted() || auth()->user()->cannot('questionnaires.answer') ? 'disabled' : '' }} wire:change="save({{ $question_option->id }});" wire:model="value.{{ $question_option->option->id }}" @if ($question_option->children_action) x-on:change="enableOrDisable((this.checked ? 'enable' : 'disable'), {{ $question->id }})" @endif class="checked:bg-esg6 mr-3.5"> {{ $question_option->option->label }}</label>
            </div>
            <div class="">
                @if (($showCustomDataAll === true || in_array($question_option->option->label, $showCustomData)) && isset($value[$question_option->option->id]) && $value[$question_option->option->id])
                    <input type="text" class="ml-4 rounded-lg" wire:change="save({{ $question_option->id }});" wire:model.lazy="customData.{{ $question_option->option->id }}">
                @endif
                @if (in_array($question->id, [681, 682]) && $errors->has('customData.'.$question_option->option->id))
                <p class="mt-1 text-xs font-bold text-red-600">
                    {{ $errors->first('customData.'.$question_option->option->id, 'This answer does not accept values above 100.') }}
                </p>
                @endif
            </div>
        </div>
    @endforeach
</div>
