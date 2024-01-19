<div>
    <div wire:loading.delay.short wire:target="save" class="absolute">
        <x-loading />
    </div>
    @php
        $breakLine =
            ($question->questionOptions->count() !=
            $question->questionOptions
                ->pluck('question_option_id')
                ->intersect([1, 2, 3])
                ->count()) || $question->questionOptions->pluck('comment_required')->filter()->count();
    @endphp

    @foreach ($question->questionOptions as $question_option)
        <label class="mr-11">
            <input type="radio"{{ $questionnaire->isSubmitted() || auth()->user()->cannot('questionnaires.answer') ? 'disabled' : '' }}{{ $this->answer->validation ? 'disabled' : '' }} wire:change="save({{ $question_option->id }})" wire:model="answer.value" @if ($question_option->children_action)x-on:change="enableOrDisable('{{ $question_option->children_action }}', {{ $question->id }})"@endif value="{{ $question_option->option->value ?? null }}" class="checked:bg-esg6 mr-3.5"> {{ $question_option->option->label ?? null }}
        </label>
        @if ($breakLine)
            <br>
        @endif

        @if ($question_option->comment_required && $question_option->option->value == $answer->value)
            <textarea wire:model.lazy="answer.comment" wire:change="saveComment()" rows="1" class="w-full mt-3 mb-3 border border-gray-500 rounded" rows="3" placeholder="{{ __('Add a comment to this question') }}"></textarea>
        @endif
    @endforeach
</div>
