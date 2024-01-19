<div>
    <div wire:loading.delay.short wire:target="save" class="absolute">
        <x-loading />
    </div>
    @foreach ($question->questionOptions as $question_option)
        <p class="py-2"><label><input type="text" {{ $this->answer->validation ? 'disabled' : '' }}  {{ $questionnaire->isSubmitted() || auth()->user()->cannot('questionnaires.answer') ? 'disabled' : '' }} wire:change="save({{ $question_option->id }})" wire:model.lazy="value.{{ $question_option->option->id }}" value="{{ $question_option->option->value }}" class="focus:border-esg6 mr-2 rounded-lg focus:ring-0"> {{ $question_option->option->label }}</label></p>
    @endforeach
</div>
