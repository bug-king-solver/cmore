<?php

namespace App\Http\Livewire\Traits;

use App\Services\Currency\Exchanger;
use Cartalyst\Converter\Laravel\Facades\Converter;

trait AnswerCanHaveUnitsTrait
{
    public $indicator;

    public string $unitFrom = '';

    public string $unitTo = '';

    public string $currency = '';

    public bool $unitChanged = false;

    /**
     * Set the default unit
     */
    public function defaultUnit()
    {
        $this->indicator = $this->option->indicator ?? null;

        if (! $this->indicator) {
            return;
        }

        $this->unitTo = "{$this->indicator->unit_qty}.{$this->indicator->unit_default}";
    }

    /**
     * Convert the units of the answer
     */
    public function convertUnits($optionId)
    {
        //$this->value = array_map(fn ($value) => $this->getValueInDefaultUnit($value), $this->value);
        $this->value[$optionId] = $this->getValueInDefaultUnit($this->value[$optionId]);
        $this->unitFrom = $this->unitTo;
    }

    /**
     * Convert the value to the unit selected
     * @param float $value
     */
    public function getValueInDefaultUnit($value): float
    {
        $value = $value ?: 0;

        /*
        if ($this->currency) {
            if ($this->indicator->is_financial && $this->currency) {
                $value = (new Exchanger())->convert(
                    strtoupper(substr($this->currency, -3)),
                    tenant()->get_default_currency,
                    $value
                )->format();
            }
            $this->currency = '';
        }
        */

        if ($this->unitFrom && $this->unitTo && $this->unitFrom != $this->unitTo) {
            $converter = convertUnits($this->unitFrom, $this->unitTo, $value, 6);
            $value = (float) $converter;
            $this->unitChanged = true;
        }
        return $value;
    }
}
