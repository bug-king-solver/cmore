<div class="grid grid-cols-1 md:grid-cols-{{ $grid ?? '3' }} gap-9">
    @foreach ($questionnaires as $questionnaire)
        <x-cards.list>
            <x-slot:header>
                <div class="flex flex-row gap-2 items-center min-h-[50px] pb-3">
                    <a href="{{ $url ?? '#' }}" class="cursor-pointer">
                        <span class="text-esg29 font-encodesans text-sm font-bold line-clamp-2">
                            {{ $questionnaire->company->name }}
                        </span>
                    </a>
                </div>
            </x-slot:header>

            <x-slot:content>
                <div class="flex flex-row gap-2 mt-3 items-center">
                    <div class="">
                        <span data-tooltip-target="tooltip-questionnaire-email" data-tooltip-target="hover">
                            @include('icons.type', [
                                'width' => 15,
                                'height' => 16,
                                'color' => color(6),
                            ])
                        </span>
                        <div id="tooltip-questionnaire-email" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            {{ __('Type') }}
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </div>
                    <div class="text-esg16 text-sm font-encodesans font-medium flex gap-2 items-center">
                        {{ $questionnaire->type->name }}
                    </div>
                </div>

                <div class="flex flex-row gap-2 mt-3 items-center">
                    <div class="bg-esg16/10 px-2 py-1 text-esg8 rounded-md flex gap-2 items-center">
                        @include('icons.calendar', [
                            'width' => 20,
                            'height' => 20,
                            'color' => color(6),
                        ])
                        {{ $questionnaire->from->format('Y-m-d') }} > {{ $questionnaire->to->format('Y-m-d') }}
                    </div>
                </div>

                <div class="w-full flex gap-3 items-center mt-3">
                    @if ($questionnaire->hasProgress())
                        <div class="grow h-3 bg-esg7/20">
                            <div class="h-3 bg-esg6 w-[{{ $questionnaire->progress }}%]"></div>
                        </div>
                        <div class="text-sm font-extrabold text-esg8">{{ $questionnaire->progress }}%</div>
                    @endif
                </div>
            </x-slot:content>

            <x-slot:footer>
                <div class="w-full flex items-center justify-between">
                    <div class="justify-start">
                        @if ($questionnaire->submitted_at)
                            <span
                                title="{{ __('Submitted at') }} {{ $questionnaire->submitted_at->format('Y-m-d H:i') }}">{{ $questionnaire->submitted_at->format('Y-m-d') }}
                            </span>
                        @endif
                    </div>

                    <div class="flex flex-row gap-2 justify-end">
                        <x-cards.questionnaire.cards-buttons modalprefix="questionnaires"
                            routeShow="tenant.questionnaires.show" :routeParams="['questionnaire' => $questionnaire->id]" :data="json_encode(['questionnaire' => $questionnaire->id])"
                            href="{{ route('tenant.questionnaires.form', ['questionnaire' => $questionnaire->id]) }}"
                            status="{{ $questionnaire->submitted_at != null ? 'submitted' : 'ongoing' }}"
                            type="page" :questionnaire="$questionnaire" />
                    </div>
                </div>
            </x-slot:footer>
        </x-cards.list>
    @endforeach
</div>

<div class="">
    {{ $questionnaires->links() }}
</div>
