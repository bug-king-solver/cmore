<?php

namespace App\Http\Livewire\Questionnaires\AnswerTypes;

use App\Http\Livewire\Traits\QuestionTrait;
use App\Models\Tenant\QuestionOption;
use Livewire\Component;

class TextLong extends Component
{
    use QuestionTrait;

    protected $rules = [
        'value.*' => 'present',
    ];

    public function save(int $optionId)
    {
        $option = QuestionOption::withTrashed()->find($optionId);

        $this->validate();

        $this->option = $option;

        $this->beforeSave();

        $this->answer->value = array_filter($this->value) ? collect($this->value) : null;
        $this->answer->save();

        $this->afterSave();
    }
}
