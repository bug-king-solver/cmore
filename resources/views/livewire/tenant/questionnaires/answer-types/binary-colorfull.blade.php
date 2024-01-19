@props([
    'colors' => [
        0 => 'bg-zinc-500',
        1 => 'bg-lime-500',
        2 => 'bg-lime-500',
        3 => 'bg-amber-500',
        4 => 'bg-orange-500',
        5 => 'bg-red-600',
    ],
    'questions',
])

<div>
    <div wire:loading.delay.short wire:target="save" class="absolute">
        <x-loading />
    </div>
    @php
        $breakLine =
            $question->questionOptions->count() !=
            $question->questionOptions
                ->pluck('question_option_id')
                ->intersect([1, 2, 3])
                ->count();
    @endphp
    @foreach ($question->questionOptions as $question_option)
        @php
            // keep only number on the value
            $value = preg_replace('/[^0-9]/', '', $question_option->option->value);
            // remove the numbers and the - to the label
            $label = preg_replace('/\d+ - /', '', $question_option->option->label);
        @endphp
        <div class="justify-start items-center gap-5 inline-flex mb-1">
            <input type="radio"
                {{ $questionnaire->isSubmitted() ||auth()->user()->cannot('questionnaires.answer')? 'disabled': '' }}{{ $this->answer->validation ? 'disabled' : '' }}
                wire:change="save({{ $question_option->id }})" wire:model="answer.value"
                @if ($question_option->children_action) x-on:change="enableOrDisable('{{ $question_option->children_action }}', {{ $question->id }})" @endif
                value="{{ $value }}" class="checked:bg-esg6 mr-3.5 hidden"
                id="answer.value.{{ $question_option->option->id }}">

            <label for="answer.value.{{ $question_option->option->id }}" class="cursor-pointer">
                <div
                    class="w-5 h-5 {{ $colors[$value] ?? null }} rounded flex-col mr-2 justify-center items-center gap-2.5 inline-flex {{ $value != ($answer->value ?? null) ? 'opacity-30' : null }}">
                    <span class="text-center text-white text-sm font-bold font-['Lato']">
                        {{ $value }}
                    </span>
                </div>
                <span class="{{ $value == ($answer->value ?? null) ? 'font-bold' : '' }}">
                    {{ $label }}
                </span>
            </label>
        </div>
        @if ($breakLine)
            <br>
        @endif
    @endforeach
</div>
