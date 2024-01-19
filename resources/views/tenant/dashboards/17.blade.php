@php $useExtends = $useExtends ?? true; @endphp

@if ($useExtends)
    @extends($useExtends ? customInclude('layouts.tenant') : 'layouts.base')

    <div class="bg-esg6 block h-48 pt-24 pl-10 text-esg27">
        <div class="mx-auto max-w-7xl px-2 text-esg27">
            <p class="text-xl font-bold">{{ $questionnaire->type->name }}</p>
            <p class="mt-2"><span class="font-bold">{{ __('Report period:') }}</span>
                {{ $questionnaire->from->format('Y') }}
            </p>
        </div>
    </div>
@endif

@if ($useExtends)
    @section('content')
    @endif

    <div class="w-2/3 m-auto pt-10">

        {!! tenantView('tenant.questionnaires.summary') !!}

        @if (!$questionnaire->hasResult())
            <div class="mt-20 row text-center text-red-500">
                {{ __('We are analyzing your data and creating the necessary questionnaires for you company.') }}
                <br><a href="{{ redirect()->getUrlGenerator()->current() }}"
                    class="underline">{{ __('Click here to refresh the page and check if the task is completed.') }}</a>
            </div>
        @endif

        @if ($questionnaire->children->count())
            <x-cards.card-empty class="!max-w-full mt-20">
                <x-slot name="content">
                    <div class="p-4 font-bold border-b border-b-[#E1E6EF] !-mx-[16px]">{{ __('Next Steps') }}</div>
                </x-slot>

                <div>
                    @foreach ($questionnaire->children as $generatedQuestionnaire)
                        <div class="p-4 flex items-center justify-between gap-5">
                            <div class="flex items-center gap-5">
                                <div class="text-base">
                                    <span class="font-bold">{{ $generatedQuestionnaire->type->name }}</span>
                                </div>
                            </div>

                            <div>

                                <x-cards.questionnaire.cards-buttons :only="['view', 'result']" modalprefix="questionnaires"
                                    routeShow="tenant.questionnaires.show" :routeParams="['questionnaire' => $generatedQuestionnaire->id]" :data="json_encode(['questionnaire' => $generatedQuestionnaire->id])"
                                    href="{{ route('tenant.questionnaires.form', ['questionnaire' => $generatedQuestionnaire->id]) }}"
                                    status="{{ $generatedQuestionnaire->submitted_at != null ? 'submitted' : 'ongoing' }}"
                                    type="page" :questionnaire="$generatedQuestionnaire" />
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-cards.card-empty>
        @endif
    </div>

    @if ($useExtends)
    @endsection
@endif
