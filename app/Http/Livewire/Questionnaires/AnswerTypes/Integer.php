<?php

namespace App\Http\Livewire\Questionnaires\AnswerTypes;

use App\Http\Livewire\Traits\AnswerCanHaveUnitsTrait;
use App\Http\Livewire\Traits\QuestionTrait;
use App\Models\Tenant\QuestionOption;
use Livewire\Component;

class Integer extends Component
{
    use AnswerCanHaveUnitsTrait;
    use QuestionTrait;

    protected function rules()
    {
        return array_merge($this->rules ?? [], [
            'value.*' => ['integer', 'regex:/^[0-9\.\,]+$/'],
        ]);
    }

    /**
     *
     */
    public function prefill(): void
    {
        $this->value = isset($this->answer->value)
            ? json_decode($this->answer->value, true)
            : [];
    }

    /**
     * Save the answer value to the database
     * @param int $optionId
     */
    public function save(int $optionId): void
    {
        $option = QuestionOption::withTrashed()->find($optionId);
        $this->validate();

        $this->option = $option;

        $this->beforeSave();

        $this->answer->value = array_filter($this->value, fn ($value) => is_numeric($value))
            ? collect($this->value)
            : null;

        $this->answer->save();
        $this->afterSave();
    }

    /**
     * Convert the units of the answer
     */
    public function convert(int $optionId): void
    {
        $option = QuestionOption::withTrashed()->find($optionId);
        $this->defaultUnit();
        $this->unitFrom = $this->customDataUnits[$option->option->id] ?? "";
        $this->convertUnits($option->option->id);
        $this->customDataUnits[$option->option->id] = strtolower($this->unitTo);
        $this->save($optionId);
    }
}
