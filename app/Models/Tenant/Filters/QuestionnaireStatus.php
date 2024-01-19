<?php

namespace App\Models\Tenant\Filters;

use App\Models\Enums\Questionnaires\QuestionnaireStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Lacodix\LaravelModelFilter\Filters\SelectFilter;

class QuestionnaireStatus extends SelectFilter
{
    protected string $field = 'submitted_at';

    /**
     * @return string
     */
    public function title(): string
    {
        return __('Status');
    }

    /**
     * @return string
     */
    public function component(): string
    {
        return 'select';
    }

    /**
     * @return string[]
     */
    public function options(): array
    {
        return QuestionnaireStatusEnum::casesArray('value', 'label');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function apply(Builder $query): Builder
    {
        return (is_countable($this->values[$this->field]) ? count($this->values[$this->field]) : 0) == 2
            ? $query
            : match ($this->values[$this->field][0]) {
                'ongoing' => $query->whereNull($this->field),
                'submitted' => $query->whereNotNull($this->field),
                default => $query,
            };
    }
}
