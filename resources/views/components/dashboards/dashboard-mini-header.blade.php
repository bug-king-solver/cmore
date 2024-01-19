<div class="h-[19px] w-full flex flex-row justify-between items-center">
    <div class="grow shrink basis-0 text-esg5 text-base font-bold uppercase">{{ $title }}</div>
    <div class="self-stretch rounded justify-start items-center gap-4 flex">
        @if ($questionnaire)
            <x-chip>
                {{ __('Last update') }}: {{ $questionnaire->updated_at->diffForHumans() }}
            </x-chip>
        @endif

        @if(!empty($questionnaireList))
        <select id="questionnaireId" wire:model="questionnaireId"
            class="border-esg7 rounded py-0.5 px-2 text-sm ts-wrapper ">
            @foreach ($questionnaireList as $questionnaire)
                <option value="{{ $questionnaire->id }}"
                    {{ $questionnaire->id == $this->questionnaire->id ? 'selected' : 'null' }}>
                    {{ __('Questionnaire: :id', ['id' => $questionnaire->id]) }}
                    ({{ $questionnaire->from->format('Y-m-d') }} - {{ $questionnaire->to->format('Y-m-d') }})
                </option>
            @endforeach
        </select>
        @endif
    </div>
</div>
