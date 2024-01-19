<?php

namespace App\Http\Livewire\Questionnaires\AnswerTypes;

use App\Http\Livewire\Traits\AnswerCanHaveUnitsTrait;

class CheckboxObsDecimal extends CheckboxObs
{
    use AnswerCanHaveUnitsTrait;

    protected $rules = [
        'value.*' => '',
        'customData.*' => ['decimal:0,14', 'regex:/^[0-9\.\,]+$/'],
    ];

    public $showCustomDataAll = true;

    public $customDataType = 'decimal';
}
