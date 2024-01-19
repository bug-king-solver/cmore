<div class="px-4 md:px-0">
    <x-slot name="header">
        <x-header title="{{ __('Safeguards') }}" data-test="taxonomy-header"
            click="{{ route('tenant.taxonomy.show', ['questionnaire' => $questionnaire]) }}"
            iconcolor="text-esg5" textcolor="text-esg5">
            <x-slot name="left"></x-slot>
        </x-header>
    </x-slot>

    <div class="pb-20 " x-data="{ subQuestion: false }">
        <div class="flex justify-between">
            <div class="flex gap-5 items-center">
                <div class="flex gap-3 items-center">
                    @include('icons.building', ['color' => color(5), 'width' => 20, 'height' => 19])
                    <label class="text-lg">
                        {{ __('Company :name', ['name' => $questionnaire->company->name]) }}
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <label class="font-bold text-lg "> {{ __('Progress') }} </label>
                @includeIf('icons.categories.0', ['color' => color(5)])
                <label class="font-bold text-lg "> {{ $this->questionnaireProgress }}% </label>
            </div>
        </div>

        <div class="my-10 border-t border-esg7/50"></div>

        @foreach ($this->safeguardQuestions as $questionIndex => $question)
            @php
                $disabled = true;
            @endphp
            @if (count($answeredQuestion) < 2)
                @php $disabled = false; @endphp
            @else
                @php
                    $last = $answeredQuestion[count($answeredQuestion) - 1] ?? null;
                    $penultimate = $answeredQuestion[count($answeredQuestion) - 2] ?? null;
                    if (in_array((string) $question['id'], [$last, $penultimate])) {
                        $disabled = false;
                    }
                @endphp
            @endif
            <div class="{{ !$question['enabled'] ? 'opacity-0 max-h-0 invisible' : 'opacity-100 max-h-auto visible mt-10' }} duration-300" x-data="{ open: false, text: 'Show Help', show: 'Show Help', hide: 'Hide Help' }">
                <label class="text-esg6 text-xs font-extrabold">
                    {{ __('Question :number', ['number' => $question['id']]) }}
                </label>

                <div class="flex gap-3 items-start mt-1">
                    <p class="text-sm">
                        {!! translateJson($question['text']) !!}
                    </p>
                </div>

                @if (translateJson($question['help']) !== '')
                    <div class="flex flex-row items-center gap-2 mt-1 cursor-pointer text-sm font-bold text-esg6"
                        x-on:click="open = !open; text = (open) ? hide : show"
                        x-bind:class="{ 'px-2': open  }">
                        <span >@include('icons.info', ['color' => color(6)])</span>
                        <span x-text="text" class="duration-300" x-bind:class="{ 'pr-2': open  }"></span>
                        <span x-show="!open" class="ml-1 self-center">@include('icons.arrow-menu', ['width' => 10, 'height' => 6])</span>
                        <span x-show="open" class="ml-1 self-center">@include('icons.arrow-up', ['width' => 10, 'height' => 6])</span>
                    </div>
                @endif

                <div x-ref="help"
                        x-bind:class="{ 'mt-2 opacity-100 !visible': open  }"
                        class="bg-esg7/10 px-4 py-2 rounded opacity-0 max-h-0 invisible duration-300"
                        x-bind:style="{ maxHeight: open ? $refs.help.scrollHeight + 8 + 'px' : '0px' }">
                    <p class="text-esg8/50 text-base">
                        {!! translateJson($question['help']) !!}
                    </p>

                    @foreach (collect($question['links'] ?? [])->unique('url') as $link)
                        @if (!isset($link['url']) || $link['url'] != null)
                            <div class="flex items-center mt-2">
                                <span class="cursor-pointer">
                                    @include('icons.link')
                                </span>
                                <x-buttons.a href="{{ $link['url'] }}" text="{{ translateJson($link['text']) }}"
                                    class="!bg-transparent !text-esg16 !text-sm !normal-case !font-normal underline underline-offset-4"
                                    target="_blank" />
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="flex flex-col mt-4 gap-4">
                    @foreach ($question['options'] as $i => $option)
                        <div class="flex items-center mr-4">
                            @php
                                $id = 'safeguardQuestions.' . $questionIndex . '.options.' . $i . '.selected';
                            @endphp

                            <input type="radio" value="{{ $option['text'] }}" wire:model='{{ $id }}'
                                class="w-4 h-4 text-esg8 bg-esg4 border-gray-300" name="{{ $id }}"
                                id="{{ $id }}" {{ $disabled ? 'disabled' : '' }}>

                            <label for="{{ $id }}" class="ml-2 text-sm font-medium text-esg8 cursor-pointer">
                                {{ $option['text'] }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            @if (!$loop->last)
                <div class="my-8 border-t border-esg7/50 {{ !$question['enabled'] ? 'hidden' : null }}"></div>
            @endif
        @endforeach

        @if ($this->safeguard['verified'] === 0)
            <x-alerts.alert-box color="red" title="{{ __('Does not meet the minimum safeguards') }}" />
        @elseif($this->safeguard['verified'] === 1)
            <x-alerts.alert-box color="green" title="{{ __('Complies with minimum safeguards') }}" icon="success" />
        @endif

        <div class="flex flex-row place-content-center mt-14">
            <x-buttons.btn text="{{ __('Reset') }}" class="!bg-red-800 !px-4 !py-2 mr-4"
                wire:click="resetarQuestionnaire" />

            <x-buttons.btn class="bg-esg6 !text-esg4 !border w-52 !rounded-md !p-2 !text-center duration-300 hover:!bg-[#6DB07A]"
                text="{{ __('Complete') }}" wire:click="completeQuestionnaire" />
        </div>
    </div>
</div>
