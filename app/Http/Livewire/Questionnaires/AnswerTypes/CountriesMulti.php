<?php

namespace App\Http\Livewire\Questionnaires\AnswerTypes;

use App\Http\Livewire\Traits\QuestionTrait;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\QuestionOption;
use Livewire\Component;

class CountriesMulti extends Component
{
    use QuestionTrait;

    public $countriesList;

    protected $rules = [
        'value.*' => '',
    ];

    public function beforeMount()
    {
        // TO DO :: Improve this
        $this->countriesList = getCountriesForSelectWhereIn(Questionnaire::find($this->questionnaire)->countries);
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
