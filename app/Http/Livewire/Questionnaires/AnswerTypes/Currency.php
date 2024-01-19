<?php

namespace App\Http\Livewire\Questionnaires\AnswerTypes;

use App\Http\Livewire\Traits\QuestionTrait;
use App\Models\Tenant\QuestionOption;
use Livewire\Component;

class Currency extends Component
{
    use QuestionTrait;

    public $currenciesList;

    protected $rules = [
        'value.currency' => '',
        'value.value' => 'numeric',
    ];

    public function beforeMount()
    {
        $this->currenciesList = getCurrenciesForSelect();
    }

    public function prefill()
    {
        $this->prefillJson();

        if (! $this->value) {
            $this->value = [
                'currency' => '',
                'value' => '',
            ];
        }
    }

    public function save(int $optionId)
    {
        $option = QuestionOption::withTrashed()->find($optionId);

        $this->validate();

        $this->option = $option;

        $this->beforeSave();

        $this->answer->value = collect($this->value);
        $this->answer->save();

        $this->afterSave();
    }
}
