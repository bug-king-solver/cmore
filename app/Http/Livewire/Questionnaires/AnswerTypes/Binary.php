<?php

namespace App\Http\Livewire\Questionnaires\AnswerTypes;

use App\Http\Livewire\Traits\QuestionTrait;
use App\Models\Tenant\QuestionOption;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Binary extends Component
{
    use QuestionTrait;

    /**
     * Binary values will be always yes, no, or null so we cant have required in rule, if we have null option.
     */

    protected $rules = [
        'answer.value' => 'nullable',
        'answer.comment' => 'nullable',
    ];

    public function beforeMount()
    {
        $this->dataType = 'radio';
    }

    public function save(int $optionId)
    {
        $option = QuestionOption::withTrashed()->find($optionId);

        $this->validate();

        $this->option = $option;

        $this->beforeSave();

        $this->answer->save();

        $this->afterSave();
    }
}
