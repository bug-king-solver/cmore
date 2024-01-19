<div class="w-full">
    <x-tables.white.table>
        <x-slot name="thead" class="!border-0 bg-esg4">
            <x-tables.white.th class="bg-esg4 text-esg6 !text-sm">{{ __('Type') }}</x-tables.white.th>
            <x-tables.white.th class="bg-esg4 text-esg6 !text-sm">{{ __('Reported period') }}</x-tables.white.th>
            <x-tables.white.th class="bg-esg4 text-esg6 !text-sm">{{ __('Status') }}</x-tables.white.th>
            <x-tables.white.th class="bg-esg4 text-esg6 !text-sm">{{ __('Actions') }}</x-tables.white.th>
        </x-slot>

        @foreach ($questionnaires as $questionnaire)
            <x-tables.white.tr class="border-b border-b-esg8/20">
                <x-tables.white.td class="!px-0 text-sm">{{ $questionnaire->type->name }}</x-tables.white.td>
                <x-tables.white.td class="!px-0 text-sm">{{ $questionnaire->from->format('Y-m-d') }} >
                    {{ $questionnaire->to->format('Y-m-d') }}</x-tables.white.td>
                <x-tables.white.td
                    class="!px-0 text-sm">{{ $questionnaire->questionnaireStatus->label() }}</x-tables.white.td>
                <x-tables.white.td class="!px-0 text-sm">
                    <x-cards.questionnaire.cards-buttons modalprefix="questionnaires"
                        routeShow="tenant.questionnaires.show" :routeParams="['questionnaire' => $questionnaire->id]" :data="json_encode(['questionnaire' => $questionnaire->id])"
                        href="{{ route('tenant.questionnaires.form', ['questionnaire' => $questionnaire->id]) }}"
                        status="{{ $questionnaire->questionnaireStatus->value }}" type="page"
                        :questionnaire="$questionnaire" />
                </x-tables.white.td>
            </x-tables.white.tr>
        @endforeach
    </x-tables.white.table>

    <div class="">
        {{ $questionnaires->links() }}
    </div>

</div>
