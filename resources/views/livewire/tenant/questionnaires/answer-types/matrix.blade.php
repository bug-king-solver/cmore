<div>
    <div wire:loading.delay.short wire:target="save" class="absolute">
        <x-loading />
    </div>
    @foreach ($question->questionOptions as $question_option)
        @php
        $xs = explode(',', $question_option->option->getTranslation('x', 'en'));
        $ys = explode(',', $question_option->option->getTranslation('y', 'en'));

        $xsLabels = explode(',', $question_option->option->x);
        $ysLabels = explode(',', $question_option->option->y);
        $xsHasCountries = array_search('{COUNTRIES}', $xs);
        $ysHasCountries = array_search('{COUNTRIES}', $ys);

        if ($xsHasCountries !== false) {
            $xs = $questionnaire->countries;
            $countries = getCountriesWhereIn($xs);
            $countries = array_column($countries, 'name');

            $xs = array_keys($countries); // Required to keep the same order
            $xsLabels = array_values($countries);
        }

        if ($ysHasCountries !== false) {
            $ys = $questionnaire->countries;
            $countries = getCountriesWhereIn($ys);
            $countries = array_column($countries, 'name');

            $ys = array_keys($countries); // Required to keep the same order
            $ysLabels = array_values($countries);
        }

        @endphp
        <table>
            <thead>
                <tr>
                    <th class="pr-2">&nbsp;</th>
                    @php
                    // Required to keep the same order
                    @endphp
                    @foreach ($xs as $key => $x)
                        <th class="pr-2">{{ $xsLabels[$key] ?? '' }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($ys as $key => $y)
                    <tr>
                        <th class="py-2 pr-2 text-right">{{ $ysLabels[$key] ?? '' }}</th>
                        @foreach ($xs as $x)
                            <td class="py-2"><input type="text" {{ $this->answer->validation ? 'disabled' : '' }}  {{ $questionnaire->isSubmitted() || auth()->user()->cannot('questionnaires.answer') ? 'disabled' : '' }} wire:change="save({{ $question_option->id }})" wire:model.lazy="value.{{ $question_option->option->id }}.{{ $y }}.{{ $x }}" class="focus:border-esg6 mr-2 rounded-lg focus:ring-0"></td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</div>
