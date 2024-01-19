<?php

namespace App\Http\Livewire\Questionnaires\AnswerTypes;

use App\Http\Livewire\Traits\QuestionTrait;
use App\Models\Tenant\QuestionOption;
use Livewire\Component;

class Checkbox extends Component
{
    use QuestionTrait;

    protected $rules = [
        'value.*' => '',
    ];

    public $showCustomData = [];

    public $showCustomDataAll = false;

    public $customDataType = 'short';

    public $emissionFactorComments = [];

    /**
     * before mount the component
     */
    public function beforeMount()
    {
        $this->dataType = 'checkbox';

        // This is required because some other types extends this one
        if (__CLASS__ === self::class) {
            array_push($this->showCustomData, __('Other'));
        }
    }

    /**
     * Set the values of the custom data units
     */
    public function afterMount()
    {
        $this->emissionFactorComments = $this->answer->data['comments'] ?? null;
    }

    /**
     * booted the component.
     */
    public function booted()
    {
        $answers = $this->value;
        $this->toggleOptionVsibility($this->value);
    }


    /**
     * Enable the values
     */
    public function enableValues($optionId)
    {
        $option = QuestionOption::withTrashed()->find($optionId);
        $this->value[$optionId] = true;
    }

    /**
     * Save the answer
     */
    public function save(int $optionId)
    {
        $option = QuestionOption::withTrashed()->find($optionId);

        $this->validate();

        $this->option = $option;

        $this->beforeSave();

        // If the value array contains a "false" value, means the user is unchecking a checkbox
        // This is true, because we are just saving the "true" values (below we are using the "array_filter" function)
        $valueReturnFalse = array_filter($this->value, fn ($value) => $value === false);
        if (isset($option) && $option->children_action === 'enable' && $valueReturnFalse) {
            $option->children_action = 'disable';
        }

        $this->value = array_map(fn ($v) => $v === '' ? true : $v, $this->value);
        $this->value = array_filter($this->value);
        $this->customData = array_filter($this->customData);


        foreach ($this->value as $key => &$value) {
            $value = $this->customData[$key] ?? $value;
        }

        $this->answer->value = array_filter($this->value)
            ? collect($this->value)
            : null;

        if (count($this->emissionsFactors)) {
            $this->answer->data = [
                'emissionFactors' => $this->emissionsFactors,
            ];
        }

        if (!is_null($this->emissionFactorComments) && $this->emissionFactorComments !== '') {
            $data = $this->answer->data;
            $data['comments'] = $this->emissionFactorComments;
            $this->answer->data = $data;
        }

        $this->answer->save();
        $this->toggleOptionVsibility($this->value); // This is required to update the visibility of the options
        $this->afterSave();
        $this->prefillCheckbox();
    }

    /**
     * calc the co2 equivalent
     * @param $nextOption
     * @param $currenOption
     */
    public function calcCo2Eq($nextOption, $currenOption)
    {
        $value = (float)$this->customData[$currenOption['option']['id']];
        $emission = $this->emissionsFactors[$currenOption['option']['id'] ?? null] ?? 1;

        if (!$emission) {
            $emission = $this->orignalEmissionsFactors[$currenOption['option']['id'] ?? null] ?? 1;
            if (!$emission) {
                return;
            }
            $this->emissionsFactors[$currenOption['option']['id']] = $emission; //reset the default value
        }

        $this->customData[$nextOption['option']['id']] = $value * $emission;
        $this->value = $this->customData;
    }

    /**
     * Convert the value to the unit selected
     */
    public function convert($questionOptionId, $optionId, $unity = null, $nextOption = null)
    {
        if (!isset($this->customData[$optionId])) {
            return;
        }

        $value = $this->customData[$optionId];
        $unitTo = $this->customDataUnits[$optionId];
        $questionOption = QuestionOption::withTrashed()->find($questionOptionId);

        if ($unity === null) {
            $unitFrom = $questionOption->indicator->unit_qty;
            $unitFrom .= "." . $questionOption->indicator->unit_default;
        } else {
            $unitFrom = $unity;
        }

        if (!$unitTo || !$unitFrom || !$value || !is_numeric($value)) {
            return;
        }

        $this->customData[$optionId] = convertUnits($unitTo, $unitFrom, $value, 6);
        $this->customDataUnits[$optionId] = $unitFrom;

        if ($nextOption !== null) {
            return $this->getEmissionFactor($questionOptionId, $optionId, $nextOption);
        }

        $this->customDataUnits[$optionId] = strtolower($unitFrom);
        $this->save($questionOptionId);
    }

    /**
     * Get the emission factor
     */
    public function getEmissionFactor($questionOptionId, $optionId, $nextOption)
    {
        if (!$questionOptionId || !$optionId || !$nextOption) {
            return;
        }

        if (empty($this->emissionFactorComments)) {
            return;
        }

        $questionOption = QuestionOption::withTrashed()->find($questionOptionId);
        $nextOption = QuestionOption::withTrashed()->find($nextOption);

        if (!$questionOption || !$nextOption) {
            return;
        }

        $this->calcCo2Eq($nextOption, $questionOption);
        $this->save($optionId);
    }


    /**
     * Toggle the visibility of the options
     */
    public function toggleOptionVsibility($answers)
    {
        $previousValue = null;
        $this->question->questionOptions->map(function ($item) use (&$previousValue, $answers) {
            $this->optionsConfig[$this->question->id][$item->id][$item->option->id]['shouldShow'] = 1;

            if ($previousValue == null) {
                $previousValue = $item;
                return $item;
            }

            if ($item->option->is_co2_equivalent) {
                $answersHiddenBug = [
                    "the-reporting-unit-has-ghg-emission-offset-capability-or-finances-projects-that-promote-carbon-sequestration-t-co2eq",
                    "the-reporting-unit-already-calculates-its-ghg-emissions-scope-1-t-co2eq",
                    "reporting-unit-already-calculates-its-ghg-emissions-scope-2-t-co2eq",
                    "reporting-unit-already-calculates-its-ghg-emissions-scope-3-t-co2eq"
                ];
                if (in_array($item->option->value, $answersHiddenBug)) {
                    $this->optionsConfig[$this->question->id][$item->id][$item->option->id]['shouldShow'] = 1;
                } else {
                    if (count($answers) > 0 && array_key_exists($previousValue->option->id, $answers)) {
                        $this->optionsConfig[$this->question->id][$item->id][$item->option->id]['shouldShow'] = 1;
                    } else {
                        $this->optionsConfig[$this->question->id][$item->id][$item->option->id]['shouldShow'] = 0;
                    }
                }
            }

            $previousValue = $item;
            return $item;
        });
    }

    /**
     * delete emission factor options
     */
    public function checkUncheckDecimalOption($questionOptionId, $nextOption = null)
    {
        if (!$questionOptionId) {
            return;
        }


        if ($nextOption) {
            $nextOptionObj = QuestionOption::withTrashed()->find($nextOption);
            if ($nextOptionObj) {
                if (isset($this->customData[$nextOptionObj->question_option_id])) {
                    unset($this->customData[$nextOptionObj->question_option_id]);
                }

                if (isset($this->value[$nextOptionObj->question_option_id])) {
                    unset($this->value[$nextOptionObj->question_option_id]);
                }
            }
        }
        $this->save($questionOptionId);
    }
}
