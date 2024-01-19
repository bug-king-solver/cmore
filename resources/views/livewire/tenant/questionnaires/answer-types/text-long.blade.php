<div>
    <div wire:loading.delay.short wire:target="save" class="absolute">
        <x-loading />
    </div>
    @foreach ($question->questionOptions as $question_option)
        @if ($question_option->option->label)
            <p>{{ $question_option->option->label }}</p>
        @endif
        <textarea {{ $this->answer->validation ? 'disabled' : '' }}  {{ $questionnaire->isSubmitted() || auth()->user()->cannot('questionnaires.answer') ? 'disabled' : '' }} wire:change="save({{ $question_option->id }})" wire:model.lazy="value.{{ $question_option->option->id }}" rows="3" class="focus:border-esg6 w-full rounded-lg focus:ring-0"></textarea>
    @endforeach
</div>
