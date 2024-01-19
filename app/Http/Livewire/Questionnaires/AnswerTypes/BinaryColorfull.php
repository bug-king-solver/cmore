<?php

namespace App\Http\Livewire\Questionnaires\AnswerTypes;

use App\Http\Livewire\Traits\QuestionTrait;
use App\Models\Tenant\QuestionOption;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class BinaryColorfull extends Component
{
    use QuestionTrait;

    protected $rules = [
        'answer.value' => 'required',
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
