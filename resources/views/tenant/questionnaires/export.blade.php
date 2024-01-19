<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Questionnaire {{ $questionnaire->company->name }}</title>
</head>

<body>
    <h2 style="text-align: center; color:#506981;">Questionnaire {{ strtoupper($questionnaire->company->name) }}</h2>
    <h2 style="text-align: center; color:#506981;">{{ $questionnaire->from->format('Y-m-d') }} ->
        {{ $questionnaire->to->format('Y-m-d') }}</h2>
    @php
        $currentCategory = null;
        $currentSubCategory = null;
    @endphp
    @foreach ($questions as $question)
        @if ($question->enabled)
            @if ($question->category && $question->category->id !== $currentSubCategory)
                <p
                    style="color:#5F788F;font-size:18px @if (preg_match_all('/\./', $question->ref2) >= 2) {{ 'margin-left:50px;' }} @endif">
                    {{ strtoupper($question->category->name) }}
                    @php
                        $currentSubCategory = $question->category->id;
                    @endphp
                </p>
            @endif
            <h4
                style="color:#506981;font-weight: bold; @if (preg_match_all('/\./', $question->ref2) >= 2) {{ 'margin-left:50px;' }} @endif">
                Question {{ rtrim($question->ref2, '.') }}</h4>

            <p style="font-weight:normal; @if (preg_match_all('/\./', $question->ref2) >= 2) {{ 'margin-left:50px;' }} @endif">
                {{ $question->description }}</p>

            @if ($question->answer->value)
                @if ($question->answer_type == 'binary')
                    @php
                        $convertedString = str_replace('-', ' ', $question->answer->value);
                    @endphp
                    <p
                        style="word-wrap: break-word;  @if (preg_match_all('/\./', $question->ref2) >= 2) {{ 'margin-left:50px;' }} @endif">
                        {{ ucwords($convertedString) }}</p>
                @elseif($question->answer_type == 'currency')
                    @php
                        $currency = json_decode($question->answer->value, true);
                    @endphp
                    <p
                        style="word-wrap: break-word;  @if (preg_match_all('/\./', $question->ref2) >= 2) {{ 'margin-left:50px;' }} @endif">
                        {{ $currency['currency'] }}</p>
                @elseif($question->answer_type == 'integer')
                    @php
                        $all_integers = json_decode($question->answer->value, true);
                        $data = [];
                        foreach ($all_integers as $option_id => $value) {
                            foreach ($question->questionOptions as $question_option) {
                                if ($question_option->option->id == $option_id) {
                                    $data[$question_option->option->label] = $value;
                                }
                            }
                        }
                    @endphp
                    @if (count($data))
                        @foreach ($data as $option => $value)
                            <p
                                style="word-wrap: break-word; @if (preg_match_all('/\./', $question->ref2) >= 2) {{ 'margin-left:50px;' }} @endif">
                                {{ empty(ucwords($option)) ? '' : ucwords($option) . ':' }} {{ $value }}</p>
                        @endforeach
                    @endif
                @elseif($question->answer_type == 'checkbox')
                    @php
                        $all_integers = json_decode($question->answer->value, true);
                        $data = [];
                        foreach ($all_integers as $option_id => $value) {
                            foreach ($question->questionOptions as $question_option) {
                                if ($question_option->option->id == $option_id) {
                                    if ($question_option->option->label == 'Other') {
                                        $data[$option_id] = $value;
                                    } else {
                                        $data[$option_id] = $question_option->option->label;
                                    }
                                }
                            }
                        }
                    @endphp
                    @if (count($data))
                        <p
                            style="word-wrap: break-word; @if (preg_match_all('/\./', $question->ref2) >= 2) {{ 'margin-left:50px;' }} @endif">
                            {{ implode(',', $data) }}</p>
                    @endif
                @elseif($question->answer_type == 'decimal')
                    @php
                        $all_integers = json_decode($question->answer->value, true);
                        $data = [];
                        foreach ($all_integers as $option_id => $value) {
                            foreach ($question->questionOptions as $question_option) {
                                if ($question_option->option->id == $option_id) {
                                    if (empty($question_option->option->value)) {
                                        $data[$question_option->option->value] = $value;
                                    } else {
                                        if (isset($question_option->indicator->unit_default)) {
                                            $data[$question_option->indicator->unit_default] = $value;
                                        }
                                    }
                                }
                            }
                        }
                    @endphp
                    @if (count($data))
                        @foreach ($data as $option => $value)
                            <p
                                style="word-wrap: break-word; @if (preg_match_all('/\./', $question->ref2) >= 2) {{ 'margin-left:50px;' }} @endif">
                                {{ formatToCurrency($value, 'EUR') }}
                                {{ empty(ucwords($option)) ? '' : ucwords($option) }} </p>
                        @endforeach
                    @endif
                @elseif($question->answer_type == 'text-long')
                    @php
                        $text = json_decode($question->answer->value, true);
                    @endphp
                    @if (count($text))
                        <p
                            style="word-wrap: break-word;  @if (preg_match_all('/\./', $question->ref2) >= 2) {{ 'margin-left:50px;' }} @endif">
                            {{ implode(',', $text) }}</p>
                    @endif
                @elseif($question->answer_type == 'checkbox-obs')
                    @php
                        $all_obs = json_decode($question->answer->value, true);
                        $data = [];
                        foreach ($all_obs as $option_id => $value) {
                            foreach ($question->questionOptions as $question_option) {
                                if ($question_option->option->id == $option_id) {
                                    $data[$question_option->option->label] = $value;
                                }
                            }
                        }
                    @endphp
                    @if (count($data))
                        @foreach ($data as $option => $value)
                            <p
                                style="word-wrap: break-word; @if (preg_match_all('/\./', $question->ref2) >= 2) {{ 'margin-left:50px;' }} @endif">
                                {{ empty(ucwords($option)) ? '' : ucwords($option) . ': ' }} {{ $value }} </p>
                        @endforeach
                    @endif
                @elseif($question->answer_type == 'matrix')
                    @php
                        $matrix = array_values(json_decode($question->answer->value, true));
                    @endphp

                    @foreach ($matrix[0] as $mat_key => $value)
                        <p
                            style="word-wrap: break-word;  @if (preg_match_all('/\./', $question->ref2) >= 2) {{ 'margin-left:50px;' }} @endif">
                            {{ $mat_key !== 0 && !empty($mat_key) ? $mat_key . ': ' : '' }}
                            @foreach ($value as $name => $val)
                                <span>{{ $name !== 0 && !empty($name) ? $name . ': ' : '' }}
                                    {{ $val }}</span>
                            @endforeach
                        </p>
                    @endforeach
                @elseif($question->answer_type == 'sdgs-multi')
                    @php
                        $all_sdgs = json_decode($question->answer->value, true);
                        $selected_sdgs = \App\Models\Tenant\Sdg::query()
                            ->whereIn('id', $all_sdgs)
                            ->pluck('name')
                            ->toArray();
                    @endphp

                    @if (count($selected_sdgs))
                        <p
                            style="word-wrap: break-word;  @if (preg_match_all('/\./', $question->ref2) >= 2) {{ 'margin-left:50px;' }} @endif">
                            {{ implode(',', $selected_sdgs) }}</p>
                    @endif
                @elseif($question->answer_type == 'countries-multi')
                    @php
                        $countries = json_decode($question->answer->value, true);
                    @endphp

                    @if (count($countries))
                        <p
                            style="word-wrap: break-word;  @if (preg_match_all('/\./', $question->ref2) >= 2) {{ 'margin-left:50px;' }} @endif">
                            {{ implode(',', $countries) }}</p>
                    @endif
                @else
                    <p
                        style="word-wrap: break-word; @if (preg_match_all('/\./', $question->ref2) >= 2) {{ 'margin-left:50px;' }} @endif">
                        {{ $question->answer->value }} </p>
                @endif
            @endif

            <div style=" border: 1px solid rgb(231, 236, 229);"></div>
        @endif
    @endforeach
</body>

</html>
