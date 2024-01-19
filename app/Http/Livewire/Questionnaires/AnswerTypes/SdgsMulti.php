<?php

namespace App\Http\Livewire\Questionnaires\AnswerTypes;

use App\Http\Livewire\Traits\QuestionTrait;
use App\Models\Tenant\QuestionOption;
use Livewire\Component;

class SdgsMulti extends Component
{
    use QuestionTrait;

    public $sdgsList;

    protected $rules = [
        'value.*' => '',
    ];

    public function beforeMount()
    {
        $this->sdgsList = getSdgsForSelect();
    }

    public function save()
    {
        $this->validate();

        $this->beforeSave();

        $this->answer->value = array_filter($this->value) ? collect($this->value) : null;
        $this->answer->save();

        $this->afterSave();
    }
}
