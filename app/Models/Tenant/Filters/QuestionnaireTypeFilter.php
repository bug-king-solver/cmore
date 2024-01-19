<?php

namespace App\Models\Tenant\Filters;

use App\Models\Tenant\Filters\vendor\SelectInFilter;
use App\Models\Tenant\QuestionnaireType;

class QuestionnaireTypeFilter extends SelectInFilter
{
    protected string $field = 'questionnaire_type_id';

    public function title(): string
    {
        return __('Type');
    }

    public function component(): string
    {
        return 'select';
    }

    public function options(): array
    {
        $questionnaires = QuestionnaireType::list()
            ->orderBy('name')
            ->get();

        $data = [];
        foreach ($questionnaires as $questionnaire) {
            $data[$questionnaire->id] = "($questionnaire->id) $questionnaire->name";
        }

        //change the key to the value
        return array_flip($data);
    }
}
